#!/bin/bash

export DEBIAN_FRONTEND=noninteractive

echo "Installing / updating SSL certificate"

if [ ! -f /vagrant/ssl/monitor.videodock.com.crt ]
then
  mkdir -p /vagrant/ssl
  echo "Generating self-signed SSL certificate"
  openssl req -x509 -nodes -days 365 -newkey rsa:2048 -keyout /vagrant/ssl/monitor.videodock.com.key -out /vagrant/ssl/monitor.videodock.com.crt -subj "/CN=monitor.videodock.com" 2>/dev/null
fi

cmp --silent /vagrant/ssl/monitor.videodock.com.crt /etc/ssl/certs/monitor.videodock.com.crt
if [ $? -ne 0 ]
then
  echo "Installing SSL certificate"
  \cp -f /vagrant/ssl/monitor.videodock.com.crt /etc/ssl/certs
  \cp -f /vagrant/ssl/monitor.videodock.com.key /etc/ssl/private
fi
