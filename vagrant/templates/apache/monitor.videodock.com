<VirtualHost *:80>
  ServerName monitor.videodock.com

  ServerAdmin developer@videodock.com

  DocumentRoot /var/www/Monitor/public

  <Directory /var/www/Monitor/public>
    Options FollowSymLinks
    AllowOverride All
    Order allow,deny
    allow from all
  </Directory>

  ErrorLog ${APACHE_LOG_DIR}/monitor.videodock.com.error.log
  CustomLog ${APACHE_LOG_DIR}/monitor.videodock.com.access.log combined

  # Redirect to https
  <IfModule mod_rewrite.c>
    RewriteEngine On
    RewriteCond %{HTTPS} !=on
    RewriteRule ^(.*)$ https://%{HTTP_HOST}$1 [R=301,L]
  </IfModule>
</VirtualHost>

<VirtualHost *:443>
  ServerName monitor.videodock.com

  ServerAdmin developer@monitor.videodock.com

  DocumentRoot /var/www/Monitor/public

  <Directory /var/www/Monitor/public>
    Options FollowSymLinks
    AllowOverride All
    Order allow,deny
    allow from all
  </Directory>

  ErrorLog ${APACHE_LOG_DIR}/monitor.videodock.com.error.log
  CustomLog ${APACHE_LOG_DIR}/monitor.videodock.com.access.log combined

  SSLEngine on
  SSLCertificateFile /etc/ssl/certs/monitor.videodock.com.crt
  SSLCertificateKeyFile /etc/ssl/private/monitor.videodock.com.key
</VirtualHost>
