#!/bin/bash

export DEBIAN_FRONTEND=noninteractive

if [[ ! -d '/.provisioning' ]]; then
    mkdir '/.provisioning'
    echo 'Created directory /.provisioning'
fi

if [[ -f '/.provisioning/setup' ]]; then
    exit 0
fi

cp /vagrant/templates/add-apt-repository /usr/sbin
chmod +x /usr/sbin/add-apt-repository

add-apt-repository ppa:git-core/ppa 2>/dev/null

apt-get update -qq
apt-get install -qq git htop vim 2>/dev/null

# Add dutch locales
echo "nl_NL.UTF-8 UTF-8" >> /etc/locale.gen
echo "nl_NL ISO-8859-1" >> /etc/locale.gen
locale-gen

# Set timezone
\cp -f /usr/share/zoneinfo/Europe/Amsterdam /etc/localtime

touch '/.provisioning/initial-setup'

mkdir -p /var/www/Monitor
usermod -a -G www-data vagrant
