WAMP
======================
Install Wamp.
Go to http://www.wampserver.com/en/#download-wrapper
Take wampserver 64 bit php 5.4

PHP54
--------
Enable openssl by enabling extension=php_openssl.dll in php.ini
At my pc that is /wamp/bin/php/php5.4.12/php.ini
Remove the ; of ;extension=php_openssl.dll
You need this later on to use ssl for getting symfony2.

Apache2
--------
Enable httpd-vhosts.conf in advance in httpd.conf in the apache folder.
At my pc that is /wamp/bin/apache/apache2.2.4/conf/httpd.conf at the bottom of the file.
Remove the # from #Include conf/extra/httpd-vhosts.conf
note: your localhost page will not work anymore.
This is where you put your virtual host configuratie later on to redirect to your application.

Mysql
--------
Go to PHPMYADMIN by typing in your browser localhost/phpmyadmin.
Log in with Root and password is empty
Make a new user called symfony with password symfony
Change the password from to root user to something different then nothing ;)
Create a database called blog.

Composer
--------
ga to getcomposer.org
klik op download. doe de windows installer.
klaar.
composer heb je nodig om packages binnen te halen zoals symfony2.
later eventueel andere symfony2 bundles

Symfony2 setup
--------
Open cmd. (je command line tool)
ga naar je www map van wamp door cd /wamp/www te doen.

ga naar http://symfony.com/doc/current/book/installation.html
kopieer composer create-project symfony/framework-standard-edition /wamp/www/blog 2.4.*
zie dat ik de path al heb ingevuld naar /wamp/www/blog en dat ik geen php ervoor heb en dat ik geen composer.phar heb maar composer.
Hij gaat nu symfony2 binnen halen en configureren en in die map zetten.

lijstje met antwoorden waar om gevraagd wordt tijdens de installatie,
als het al tussen de blokhaken staat kun je op enter klikken want dan is dat de default waarde:
pdo_mysql
127.0.0.1
3306
blog
symfony
symfony
gmail
~
Your email address
Your password
en
*Ram met uw voorhoofd uw toetsenbord voor een generieke secret*

open httpd-vhosts.conf in je apache extra folder.
bij mij is dat /wamp/bin/apache2/apache2.4.4/conf/extra/httpd-vhosts.conf

Ga naar http://symfony.com/doc/current/cookbook/configuration/web_server_configuration.html
kopieer:
    <VirtualHost *:80>
        ServerName domain.tld
        ServerAlias www.domain.tld

        DocumentRoot /var/www/project/web
        <Directory /var/www/project/web>
            # enable the .htaccess rewrites
            AllowOverride All
            Order allow,deny
            Allow from All
        </Directory>

        ErrorLog /var/log/apache2/project_error.log
        CustomLog /var/log/apache2/project_access.log combined
    </VirtualHost>

Verander het zoals deze is, maar dan je eigen naam:

    <VirtualHost *:80>
        ServerName matthijs.blog

        DocumentRoot /wamp/www/blog/web
        <Directory /wamp/www/project/web>
            # enable the .htaccess rewrites
            AllowOverride All
            Order allow,deny
            Allow from All
        </Directory>

        ErrorLog /wamp/logs/matthijs_blog_error.log
        CustomLog /wamp/logs/matthijs_blog_access.log combined
    </VirtualHost>

De servername gaan we toevoegen aan je hosts file zodat je er naartoe kan gaan in je browser.

Verander uw hosts file in /windows/system32/drivers/etc/hosts vergeet niet om dit als administator te doen (open kladblok als admin)
voeg achter localhost uw eigen tld toe zoals matthijs.blog dat bij mij is.
localhost    127.0.0.1 matthijs.blog
staat er bij mij.

restart al uw wamp services(apache vooral) bij het icoontje rechts onderin

ga vervolgens in uw browser naar matthijs.blog/app_dev.php
tada uw symfony2 start pagina staat voor u klaar!

app_dev.php staat in de web map van je blog applicatie(/wamp/www/blog). hier werk je mee zolang je aan het developen bent.
alle url's zullen app_dev.php zometeen zoals: matthijs.blog/app_dev.php/create/blog-item/1.
Wanneer je ooit in productie gaat, gebruik je de app.php.
je zou zelfs een test environment kunnen toevoegen en dan naar app_test.php kunnen gaan ;)
maar dat is een beetje overdreven voor ons :P
Het idee is dat de applicatie zichzelf anders kan gedragen in develop modu, in productie modus en in test modus.