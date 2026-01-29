#! /bin/bash

# Save all output to a log file
exec > /var/log/userdata.log 2>&1
set -ex  # Changed from -x to -ex to exit on any error

# INSTALL SSM AGENT
# This allows SSH access into the VM from the Session Manager web interface.
# This take a while to start up, so be patient. You can use the EC2 serial console
# to monitor progress before the Session Manager is ready.
#
echo '### Installing the SSM Agent ###'
mkdir /tmp/ssm
cd /tmp/ssm
wget -q https://s3.amazonaws.com/ec2-downloads-windows/SSMAgent/latest/debian_amd64/amazon-ssm-agent.deb
sudo dpkg -i amazon-ssm-agent.deb

# Function to wait for dpkg lock
wait_for_dpkg_lock() {
  while sudo fuser /var/lib/dpkg/lock-frontend >/dev/null 2>&1 || sudo fuser /var/lib/apt/lists/lock >/dev/null 2>&1; do
    echo "Waiting for dpkg lock to be released..."
    sleep 5
  done
}

# INSTALL NGINX AND PHP
echo '### Installing Nginx and php'
echo '### Updating package lists ###'
wait_for_dpkg_lock
sudo apt-get update -y

# Install Nginx
echo '### Installing Nginx ###'
wait_for_dpkg_lock
sudo apt-get install -y nginx

# Install PHP and required extensions
echo '### Installing PHP and extensions ###'
wait_for_dpkg_lock
sudo apt-get install -y php-fpm php-mysqli php-curl php-dom php-gd php-intl php-mbstring php-xml php-zip php-ldap php-json php-apcu php-cli unzip

# Start and enable Nginx and PHP-FPM services
echo '### Starting services ###'
sudo systemctl enable nginx
sudo systemctl start nginx

# Detect PHP-FPM version and enable it
PHP_FPM_SERVICE=$(systemctl list-units --type=service --all | grep -oP 'php[0-9.]*-fpm' | head -1)
if [ -n "$PHP_FPM_SERVICE" ]; then
  echo "### Enabling $PHP_FPM_SERVICE ###"
  sudo systemctl enable $PHP_FPM_SERVICE
  sudo systemctl start $PHP_FPM_SERVICE
else
  echo "ERROR: Could not find PHP-FPM service"
  exit 1
fi

# INSTALL ResourceSpace
echo '### Cloning ResourceSpace repository ###'
sudo apt-get install -y git
sudo mkdir /tmp/bcparks-dam
sudo git clone ${git_url} /tmp/bcparks-dam
#sudo git clone -b generic-ami ${git_url} /tmp/bcparks-dam

# Copy ResourceSpace files
echo '### Copying ResourceSpace files ###'
sudo mkdir -p /var/www/resourcespace
sudo cp -R /tmp/bcparks-dam/src/resourcespace/releases/10.7/* /var/www/resourcespace
sudo chown -R www-data:www-data /var/www/resourcespace
sudo chmod -R 755 /var/www/resourcespace

# Configure Nginx
echo '### Configuring Nginx ###'
sudo cp /tmp/bcparks-dam/src/resourcespace/files/configure-nginx.sh /tmp/
sudo chmod +x /tmp/configure-nginx.sh
sudo /tmp/configure-nginx.sh
#sudo rm /tmp/configure-nginx.sh

echo '### Installing amazon-efs-utils ###'
wait_for_dpkg_lock
sudo apt-get install -y nfs-common

# MOUNT THE EFS PERSISTENT FILESYSTEM
# This volume contains the resourcespace filestore. We tried using S3, but it was slow and unreliable.
# EBS wouldn't work either because the autoscaling group runs in 2 availability zones.
echo '### Mounting the EFS filesystem ###'
cd /var/www/resourcespace
sudo mkdir filestore
sudo chown www-data:www-data filestore
sudo chmod -R 775 filestore
wait_for_dpkg_lock
if sudo mount -t nfs4 -o nfsvers=4.1,rsize=1048576,wsize=1048576,hard,timeo=600,retrans=2,noresvport ${efs_dns_name}:/ ./filestore; then
  echo "EFS mounted successfully as NFS4."
else
  echo "Failed to mount EFS." >&2
  exit 1
fi

# Fix EFS ownership after mounting
echo '### Fixing EFS ownership ###'
sudo chown -R www-data:www-data /var/www/resourcespace/filestore
sudo chmod -R 775 /var/www/resourcespace/filestore

# MOUNT THE S3 BUCKET
# The S3 bucket /mnt/s3-backup is used for backups and file transfers.
echo '### Mounting the S3 bucket ###'
sudo apt-get -y install s3fs
sudo mkdir /mnt/s3-backup
sudo s3fs bcparks-dam-backup-${target_env} /mnt/s3-backup -o iam_role=BCParks-Dam-EC2-Role -o use_cache=/tmp -o allow_other -o uid=0 -o gid=1 -o mp_umask=002  -o multireq_max=5 -o use_path_request_style -o url=https://s3-${aws_region}.amazonaws.com

echo '### Customizing the Resourcespace config ###'
sudo mkdir /tmp/bcparks-dam/repos
cd /tmp

# use values from AWS secrets manager secrets to append settings to the file
tee -a bcparks-dam/src/resourcespace/files/config.php << END
\$mysql_server = '${rds_endpoint}:3306';
\$mysql_db = 'resourcespace';
\$mysql_username = '${mysql_username}';
\$mysql_password = '${mysql_password}';

# Email settings
\$email_notify = '${email_notify}';
\$email_from = '${email_from}';

# Secure keys
\$spider_password = '${spider_password}';
\$scramble_key = '${scramble_key}';
\$api_scramble_key = '${api_scramble_key}';

END
sudo cat bcparks-dam/src/resourcespace/files/simplesaml-config-1.php | tee -a bcparks-dam/src/resourcespace/files/config.php
tee -a bcparks-dam/src/resourcespace/files/config.php << END
    'technicalcontact_name' => '${technical_contact_name}',
    'technicalcontact_email' => '${technical_contact_email}',
    'secretsalt' => '${secret_salt}',
    'auth.adminpassword' => '${auth_admin_password}',
    'database.username' => '${saml_database_username}',
    'database.password' => '${saml_database_password}',
END
sudo cat bcparks-dam/src/resourcespace/files/simplesaml-config-2.php | tee -a bcparks-dam/src/resourcespace/files/config.php
sudo cat bcparks-dam/src/resourcespace/files/simplesaml-authsources-1.php | tee -a bcparks-dam/src/resourcespace/files/config.php
tee -a bcparks-dam/src/resourcespace/files/config.php << END
        'entityID' => '${sp_entity_id}',
        'idp' => '${idp_entity_id}',
END
sudo cat bcparks-dam/src/resourcespace/files/simplesaml-authsources-2.php | tee -a bcparks-dam/src/resourcespace/files/config.php
tee -a bcparks-dam/src/resourcespace/files/config.php << END
\$simplesamlconfig['metadata']['${idp_entity_id}'] = [
    'entityID' => '${idp_entity_id}',
END
sudo cat bcparks-dam/src/resourcespace/files/simplesaml-metadata-1.php | tee -a bcparks-dam/src/resourcespace/files/config.php
tee -a bcparks-dam/src/resourcespace/files/config.php << END
        'Location' => '${single_signon_service_url}',
END
sudo cat bcparks-dam/src/resourcespace/files/simplesaml-metadata-2.php | tee -a bcparks-dam/src/resourcespace/files/config.php
tee -a bcparks-dam/src/resourcespace/files/config.php << END
        'Location' => '${single_logout_service_url}',
END
sudo cat bcparks-dam/src/resourcespace/files/simplesaml-metadata-3.php | tee -a bcparks-dam/src/resourcespace/files/config.php
tee -a bcparks-dam/src/resourcespace/files/config.php << END
        'X509Certificate' => '${x509_certificate}',
END
sudo cat bcparks-dam/src/resourcespace/files/simplesaml-metadata-4.php | tee -a bcparks-dam/src/resourcespace/files/config.php

# copy the customized config.php file to overwrite the resourcespace config
cd /var/www/resourcespace/include
sudo cp /tmp/bcparks-dam/src/resourcespace/files/config.php .
sudo chown www-data:www-data config.php
sudo chmod 664 config.php

# copy the health check
cd /var/www/resourcespace
sudo cp /tmp/bcparks-dam/src/resourcespace/files/health-check.php .
sudo chown www-data:www-data health-check.php
sudo chmod 664 health-check.php

# copy the favicon, header image, and custom font (BC Sans)
sudo mkdir -p /var/www/resourcespace/filestore/system/config
sudo chown www-data:www-data /var/www/resourcespace/filestore/system/config
sudo chmod 775 /var/www/resourcespace/filestore/system/config
cd /var/www/resourcespace/filestore/system/config
sudo cp /tmp/bcparks-dam/src/resourcespace/files/header_favicon.png .
sudo cp /tmp/bcparks-dam/src/resourcespace/files/linkedheaderimgsrc.png .
sudo cp /tmp/bcparks-dam/src/resourcespace/files/custom_font.woff2 .
sudo chown www-data:www-data *.*
sudo chmod 664 *.*

# extract the Montala Support plugin
cd /var/www/resourcespace/filestore/system
sudo unzip -o /tmp/bcparks-dam/src/resourcespace/files/montala-support.zip
sudo chown -R www-data:www-data plugins
sudo chmod -R 775 plugins

cd ..

# Delete cache files
sudo rm -f tmp/querycache/* 2>/dev/null || true

# Clear the tmp folder
echo '### Clear the tmp folder ###'
sudo rm -rf tmp/*

# SimpleSAML setup
sudo mkdir -p simplesaml/data
sudo mkdir -p simplesaml/log
sudo chown www-data:www-data -R simplesaml
sudo chmod 775 -R simplesaml

# Set the PHP memory_limit and other configurations
# Detect PHP version
PHP_VERSION=$(php -r 'echo implode(".", array_slice(explode(".", phpversion()), 0, 2));')
PHP_INI="/etc/php/$PHP_VERSION/fpm/php.ini"
PHP_POOL="/etc/php/$PHP_VERSION/fpm/pool.d/www.conf"

if [ -f "$PHP_INI" ]; then
  sudo sed -i 's|^memory_limit = .*|memory_limit = 4096M|' "$PHP_INI"
  sudo sed -i 's|^post_max_size = .*|post_max_size = 2048M|' "$PHP_INI"
  sudo sed -i 's|^upload_max_filesize = .*|upload_max_filesize = 2048M|' "$PHP_INI"
  sudo sed -i 's|^max_file_uploads = .*|max_file_uploads = 100|' "$PHP_INI"
  sudo sed -i 's|^upload_tmp_dir = .*|upload_tmp_dir = /var/www/resourcespace/filestore/tmp|' "$PHP_INI"
  sudo sed -i 's|^date.timezone = .*|date.timezone = "America/Vancouver"|' "$PHP_INI"
  sudo sed -i 's|^max_execution_time = .*|max_execution_time = 1200|' "$PHP_INI"
  sudo sed -i 's|^max_input_time = .*|max_input_time = 1200|' "$PHP_INI"
  sudo sed -i 's|^max_input_vars = .*|max_input_vars = 2000|' "$PHP_INI"
fi

if [ -f "$PHP_POOL" ]; then
  sudo sed -i 's|^[;[:space:]]*pm.max_children\s*=.*|pm.max_children = 50|' "$PHP_POOL"
  sudo sed -i 's|^[;[:space:]]*pm.start_servers\s*=.*|pm.start_servers = 15|' "$PHP_POOL"
  sudo sed -i 's|^[;[:space:]]*pm.min_spare_servers\s*=.*|pm.min_spare_servers = 10|' "$PHP_POOL"
  sudo sed -i 's|^[;[:space:]]*pm.max_spare_servers\s*=.*|pm.max_spare_servers = 25|' "$PHP_POOL"
  sudo sed -i 's|^[;[:space:]]*pm.max_requests\s*=.*|pm.max_requests = 500|' "$PHP_POOL"
  sudo sed -i 's|^[;[:space:]]*request_terminate_timeout\s*=.*|request_terminate_timeout = 1200|' "$PHP_POOL"
fi

# Install ImageMagick
sudo apt-get install -y imagemagick php-imagick
sudo sed -i 's|<policy domain="resource" name="memory" value="[^"]*"/>|<policy domain="resource" name="memory" value="2GiB"/>|' /etc/ImageMagick-6/policy.xml
sudo sed -i 's|<policy domain="resource" name="map" value="[^"]*"/>|<policy domain="resource" name="map" value="4GiB"/>|' /etc/ImageMagick-6/policy.xml
sudo sed -i 's|<policy domain="resource" name="area" value="[^"]*"/>|<policy domain="resource" name="area" value="200MP"/>|' /etc/ImageMagick-6/policy.xml
sudo sed -i 's|<policy domain="resource" name="disk" value="[^"]*"/>|<policy domain="resource" name="disk" value="5GiB"/>|' /etc/ImageMagick-6/policy.xml
sudo sed -i 's|<!-- <policy domain="resource" name="thread" value="[^"]*"/> -->|<policy domain="resource" name="thread" value="2"/>|' /etc/ImageMagick-6/policy.xml

sudo apt-get install -y ghostscript
sudo apt-get install -y ffmpeg
sudo apt-get install -y libimage-exiftool-perl
sudo apt install -y mariadb-client
sudo apt install -y net-tools
sudo apt install -y clamav clamav-daemon
sudo systemctl enable clamav-freshclam
sudo systemctl enable clamav-daemon
sudo systemctl start clamav-freshclam
sudo systemctl start clamav-daemon

echo '### Setting up cronjob for offline jobs ###'
sudo apt-get install -y cron
(
  crontab -l -u www-data 2>/dev/null
  echo "*/4 * * * * cd /var/www/resourcespace/pages/tools && /usr/bin/php offline_jobs.php --max-jobs 1"
  echo "*/10 * * * * cd /var/www/resourcespace/pages/tools && /usr/bin/php staticsync.php >> /var/www/resourcespace/filestore/staticsync.log 2>&1"
  echo "0 10 * * * rm -r /var/www/resourcespace/filestore/tmp/*"
) | sudo crontab -u www-data -

# Install SQLite
echo '### Installing sqlite3 ###'
sudo apt-get install -y php-sqlite3

# Install APC User Cache (APCu)
echo '### Installing APCu ###'
sudo cp /tmp/bcparks-dam/src/resourcespace/files/install-apcu.sh /tmp/
sudo chmod +x /tmp/install-apcu.sh
sudo /tmp/install-apcu.sh
#sudo rm /tmp/install-apcu.sh

# Install performance monitor utility
sudo apt-get install -y htop

# Update the slideshow directory in config.php
sudo cp /tmp/bcparks-dam/src/resourcespace/files/update-slideshow.sh /tmp/
sudo chmod +x /tmp/update-slideshow.sh
sudo /tmp/update-slideshow.sh
sudo rm /tmp/update-slideshow.sh

# Clean up temporary files
echo '### Cleaning up ###'
#rm -rf /tmp/bcparks-dam
sudo rm -r /var/www/resourcespace/filestore/tmp/*

echo '### Restarting services ###'
# Detect and restart the PHP-FPM service
PHP_FPM_SERVICE=$(systemctl list-units --type=service --all | grep -oP 'php[0-9.]*-fpm' | head -1)
if [ -n "$PHP_FPM_SERVICE" ]; then
  sudo systemctl restart $PHP_FPM_SERVICE
fi
sudo systemctl restart nginx

echo '### Userdata script completed successfully ###'