#!/bin/bash

export DEBIAN_FRONTEND=noninteractive

echo "Installing / updating PHP"

apt-get install -qq build-essential php5 php5-intl php5-mcrypt php5-curl php-pear php5-dev

\cp -f /vagrant/templates/php/php.ini /etc/php5/apache2

service apache2 restart

hash composer 2>/dev/null
if [ $? -ne 0 ]
then
  echo 'Installing Composer'
  cd /usr/local/bin
  curl -sS https://getcomposer.org/installer | php
  mv composer.phar composer
  composer --version
fi
