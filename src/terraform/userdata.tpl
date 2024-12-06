#! /bin/bash


# Save all output to a log file
exec > /var/log/userdata.log 2>&1
set -x


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
# Update package lists
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
sudo apt-get install -y php-fpm php-mysqli php-curl php-dom php-gd php-intl php-mbstring php-xml php-zip php-ldap php-imap php-json php-apcu php-cli unzip

# Start and enable Nginx and PHP-FPM services
echo '### Starting services ###'
sudo systemctl enable nginx
sudo systemctl start nginx
sudo systemctl enable php8.2-fpm  # Adjust the PHP version as necessary
sudo systemctl start php8.2-fpm


# INSTALL ResourceSpace
# Clone ResourceSpace repository from GitHub
echo '### Cloning ResourceSpace repository ###'
sudo apt-get install -y git
sudo mkdir /tmp/bcparks-dam
sudo git clone -b generic-ami ${git_url} /tmp/bcparks-dam
#git clone ${git_url} /tmp/bcparks-dam
#https://github.com/bcgov/bcparks-dam/tree/generic-ami/src/resourcespace/releases/10.4

# Copy ResourceSpace files
echo '### Copying ResourceSpace files ###'
sudo mkdir -p /var/www/resourcespace
sudo cp -R /tmp/bcparks-dam/src/resourcespace/releases/10.4/* /var/www/resourcespace
sudo chown -R www-data:www-data /var/www/resourcespace
sudo chmod -R 755 /var/www/resourcespace

echo 'Domain name value: ' ${domain_name}

# Set up Nginx server block
echo '### Configuring Nginx ###'
cat <<EOF | sudo tee /etc/nginx/sites-available/resourcespace
server {
    listen 80;
    server_name ${domain_name};

    root /var/www/resourcespace;
    index index.php index.html;

    location / {
        try_files \$uri \$uri/ /pages/home.php?\$args;
    }

    location ~ \.php\$ {
        include snippets/fastcgi-php.conf;
        fastcgi_pass unix:/var/run/php/php8.2-fpm.sock;  # Adjust PHP version if needed
        fastcgi_param SCRIPT_FILENAME \$document_root\$fastcgi_script_name;
        include fastcgi_params;
    }

    location ~ /\.ht {
        deny all;
    }
}
EOF

# Enable the Nginx configuration
sudo ln -s /etc/nginx/sites-available/resourcespace /etc/nginx/sites-enabled/
sudo nginx -t
sudo systemctl reload nginx


# INSTALL AMAZON-EFS-UTILS
# We need to build this from source for Debian Linux; it isn't available, otherwise.
#
# Update package lists and install dependencies
echo '### Installing amazon-efs-utils dependencies ###'
wait_for_dpkg_lock
sudo apt-get -y update
wait_for_dpkg_lock
sudo apt-get install -y git binutils pkg-config libssl-dev

# Switch to the www-data user and install Rust and Cargo
echo '### Installing Rust and Cargo ###'
#sudo -u www-data bash <<'EOF'
#curl --proto '=https' --tlsv1.2 -sSf https://sh.rustup.rs | sh -s -- -y
#echo '. "$HOME/.cargo/env"' >> ~/.bashrc
#source $HOME/.cargo/env
#EOF
sudo bash <<'EOF'
curl --proto '=https' --tlsv1.2 -sSf https://sh.rustup.rs | sh -s -- -y
echo '. "$HOME/.cargo/env"' >> ~/.bashrc
source $HOME/.cargo/env
EOF

# Install the build-essential packed, which includes C compiler that is required by Cargo #
echo '### Installing the build-essential package ###'
sudo apt-get install -y build-essential


# Clone and build amazon-efs-utils
echo '### Building amazon-efs-utils ###'
#sudo -u www-data bash <<'EOF'
#mkdir -p /tmp/bcparks-dam/repos
#cd /tmp/bcparks-dam/repos
#git clone https://github.com/aws/efs-utils efs-utils
#cd efs-utils
#source $HOME/.cargo/env
#./build-deb.sh
#EOF
sudo bash <<'EOF'
mkdir -p /tmp/bcparks-dam/repos
cd /tmp/bcparks-dam/repos
git clone https://github.com/aws/efs-utils efs-utils
cd efs-utils
source $HOME/.cargo/env
./build-deb.sh
EOF

# Install the built package
echo '### Installing amazon-efs-utils ###'
wait_for_dpkg_lock
# Find the built .deb package
DEB_FILE=$(ls /tmp/bcparks-dam/repos/efs-utils/build/*.deb | head -n 1)
if [ -n "$DEB_FILE" ]; then
    sudo apt-get -y install "$DEB_FILE"
else
    echo "No .deb file found to install."
    exit 1
fi


# MOUNT THE EFS PERSISTENT FILESYSTEM
# This volume contains the resourcespace filestore. We tried using S3 but it was slow and unreliable.
# EBS wouldn't work either because the autoscaling group runs in 2 availability zones.  
#
echo '### Mounting the EFS filesystem ###'
cd /var/www/resourcespace
sudo mkdir filestore
sudo chown www-data:www-data filestore
sudo chmod -R 775 filestore
#sudo cp -R filestore filestore.bitnami
wait_for_dpkg_lock
if sudo mount -t efs -o iam -o tls ${efs_dns_name}:/ ./filestore; then
  echo "EFS mounted successfully."
else
  echo "Failed to mount EFS." >&2
  exit 1
fi
#sudo chown -R bitnami:daemon filestore*
#sudo chmod -R 775 filestore*
#^^temporarily disabled. This causes the rebuild to take a very long time, with assigning ownership and permissions to 130+GB, and impacts users.

# MOUNT THE S3 BUCKET
# The S3 bucket /mnt/s3-backup is used for backups and file transfers. You can use
# the AWS web console to upload and download data into this bucket from your computer.
#
echo '### Mounting the S3 bucket ###'
sudo apt-get -y install s3fs
sudo mkdir /mnt/s3-backup
sudo s3fs bcparks-dam-${target_env}-backup /mnt/s3-backup -o iam_role=BCParks-Dam-EC2-Role -o use_cache=/tmp -o allow_other -o uid=0 -o gid=1 -o mp_umask=002  -o multireq_max=5 -o use_path_request_style -o url=https://s3-${aws_region}.amazonaws.com


# Copy the default filestore data
#sudo cp -R /opt/bitnami/resourcespace/filestore.bitnami/system /opt/bitnami/resourcespace/filestore
sudo chown -R www-data:www-data /var/www/resourcespace
sudo chmod -R 755 /var/www/resourcespace


# CUSTOMIZE THE RESOURCESPACE CONFIG
# Download all the files from our git repo to get our customized copy of config.php
# Updated 2024-03-01 11:10
echo '### Customizing the Resourcespace config ###'
sudo mkdir /tmp/bcparks-dam/repos
cd /tmp

# use values from AWS secrets manager secrets to append settings to the file
tee -a bcparks-dam/src/resourcespace/files/config.php << END

# MySQL database settings
\$mysql_server = '${rds_endpoint}:3306';
\$mysql_username = '${mysql_username}';
\$mysql_password = '${mysql_password}';
\$mysql_db = 'resourcespace';

# Email settings
\$email_notify = '${email_notify}';
\$email_from = '${email_from}';

# Secure keys
\$spider_password = '${spider_password}';
\$scramble_key = '${scramble_key}';
\$api_scramble_key = '${api_scramble_key}';

END
# SimpleSAML config
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
#sudo cp config.php config.php.bitnami
sudo cp /tmp/bcparks-dam/src/resourcespace/files/config.php .
sudo chown www-data:www-data config.php
sudo chmod 664 config.php


# copy the favicon, header image, and custom font (BC Sans)
sudo mkdir /var/www/resourcespace/filestore/system/config
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
sudo unzip /tmp/bcparks-dam/src/resourcespace/files/montala_support.zip
sudo chown -R www-data:www-data plugins
sudo chmod -R 775 plugins


# Delete cache files
sudo rm /var/www/resourcespace/filestore/tmp/querycache/*


# Clear the tmp folder
echo '### Clear the tmp folder ###'
sudo rm -rf /var/www/resourcespace/filestore/tmp/*


# Set the PHP memory_limit and other configurations (recommended by Montala)
sudo sed -i 's|^memory_limit = .*|memory_limit = 2048M|' /etc/php/8.2/fpm/php.ini
sudo sed -i 's|^post_max_size = .*|post_max_size = 2048M|' /etc/php/8.2/fpm/php.ini
sudo sed -i 's|^upload_max_filesize = .*|upload_max_filesize = 2048M|' /etc/php/8.2/fpm/php.ini
sudo sed -i 's|^max_file_uploads = .*|max_file_uploads = 40|' /etc/php/8.2/fpm/php.ini
sudo sed -i 's|^upload_tmp_dir = .*|upload_tmp_dir = /var/www/resourcespace/filestore/tmp|' /etc/php/8.2/fpm/php.ini
sudo sed -i 's|^date.timezone = .*|date.timezone = "America/Vancouver"|' /etc/php/8.2/fpm/php.ini
sudo sed -i 's|^max_execution_time = .*|max_execution_time = 150|' /etc/php/8.2/fpm/php.ini
sudo sed -i 's|^max_input_time = .*|max_input_time = 180|' /etc/php/8.2/fpm/php.ini

# Update ImageMagick policy to handle large images (over 128MP)
sudo sed -i 's|<policy domain="resource" name="memory" value="[^"]*"/>|<policy domain="resource" name="memory" value="2GiB"/>|' /etc/ImageMagick-6/policy.xml
sudo sed -i 's|<policy domain="resource" name="map" value="[^"]*"/>|<policy domain="resource" name="map" value="4GiB"/>|' /etc/ImageMagick-6/policy.xml
sudo sed -i 's|<policy domain="resource" name="area" value="[^"]*"/>|<policy domain="resource" name="area" value="200MP"/>|' /etc/ImageMagick-6/policy.xml
sudo sed -i 's|<policy domain="resource" name="disk" value="[^"]*"/>|<policy domain="resource" name="disk" value="5GiB"/>|' /etc/ImageMagick-6/policy.xml
sudo sed -i 's|<!-- <policy domain="resource" name="thread" value="[^"]*"/> -->|<policy domain="resource" name="thread" value="2"/>|' /etc/ImageMagick-6/policy.xml



# Add PHP to path
export PATH=$PATH:/usr/bin/php


# Set the cronjob for the offline job script, to generate previews in the background for improved performance
echo '### Setting up cronjob for offline jobs ###'
(crontab -l -u www-data 2>/dev/null; echo "*/2 * * * * cd /var/www/resourcespace/pages/tools && /usr/bin/php offline_jobs.php --max-jobs 5") | sudo crontab -u www-data -


# Install APC User Cache (APCu)
# https://pecl.php.net/package/APCu
# Install required build tools and PHP development packages
sudo apt-get -y install build-essential autoconf php-dev php-pear
# Download and extract the APCu source code
cd /tmp
sudo wget https://pecl.php.net/get/apcu-5.1.23.tgz  # Replace with the latest compatible version
sudo tar -xf apcu-5.1.23.tgz
cd apcu-5.1.23
# Build and install APCu
phpize
./configure
make
sudo make install
# Enable the APCu extension
echo "extension=apcu.so" | sudo tee /etc/php/8.2/mods-available/apcu.ini  # Adjust for PHP version
sudo phpenmod apcu
# Restart PHP-FPM and Nginx to apply changes
sudo systemctl restart php8.2-fpm  # Adjust for PHP version
sudo systemctl reload nginx
# Cleanup
cd /tmp
sudo rm -rf apcu-5.1.23 apcu-5.1.23.tgz
echo '### APCu installation completed ###'




# Install performance monitor utility
sudo apt-get install -y htop


# Update the slideshow directory in config.php
sudo cp /tmp/bcparks-dam/src/resourcespace/files/update_slideshow.sh /tmp/
sudo chmod +x /tmp/update_slideshow.sh
sudo /tmp/update_slideshow.sh
sudo rm /tmp/update_slideshow.sh


# Restart PHP-FPM and Nginx
echo '### Restarting services ###'
sudo systemctl restart php8.2-fpm
sudo systemctl restart nginx
#sudo /opt/bitnami/ctlscript.sh restart

# Clean up temporary files
echo '### Cleaning up ###'
#rm -rf /tmp/bcparks-dam
sudo rm /var/www/resourcespace/filestore/tmp/process_locks/*
echo '### Userdata script completed successfully ###'