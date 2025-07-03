#!/bin/bash
# This script installs the APCu extension for PHP 8.2 on a Debian-based system.

# Install APC User Cache (APCu)
# https://pecl.php.net/package/APCu
sudo apt-get -y install build-essential autoconf php-dev php-pear
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
echo "extension=apcu.so" | sudo tee /etc/php/8.2/mods-available/apcu.ini
sudo phpenmod apcu
sudo systemctl restart php8.2-fpm
sudo systemctl reload nginx
cd /tmp
sudo rm -rf apcu-5.1.23 apcu-5.1.23.tgz
echo '### APCu installation completed ###'