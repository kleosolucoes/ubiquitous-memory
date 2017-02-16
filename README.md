Configurando

sudo apt-get update
sudo apt-get install -y apache2 git curl php5-cli php5 php5-intl libapache2-mod-php5

<VirtualHost *:80>
    ServerName skeleton-zf.local
    DocumentRoot $DOCUMENT_ROOT_ZEND
    <Directory $DOCUMENT_ROOT_ZEND>
        DirectoryIndex index.php
        AllowOverride All
        Order allow,deny
        Allow from all
    </Directory>
</VirtualHost>
/etc/apache2/sites-available/skeleton-zf.conf

a2enmod rewrite
a2dissite 000-default
a2ensite skeleton-zf
service apache2 restart
cd /var/www/zf
curl -Ss https://getcomposer.org/installer | php
php composer.phar install --no-progress