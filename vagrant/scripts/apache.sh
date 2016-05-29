#!/bin/bash

export DEBIAN_FRONTEND=noninteractive

echo "Installing / updating Apache"

apt-get install -qq apache2

service apache2 stop

a2dissite default
a2dissite default-ssl

a2enmod headers
a2enmod rewrite
a2enmod ssl

\cp -f /vagrant/templates/apache/apache2.conf /etc/apache2
\cp -f /vagrant/templates/apache/ports.conf /etc/apache2
\cp -f /vagrant/templates/apache/ssl.conf /etc/apache2/mods-available

cp /vagrant/templates/apache/monitor.videodock.com /etc/apache2/sites-available
a2ensite monitor.videodock.com

service apache2 start
