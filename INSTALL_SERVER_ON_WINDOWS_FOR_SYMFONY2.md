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
Go to getcomposer.org
Click on download and use the windows installer.
Done.
You need composer to get packages like the symfony2 packages.
Eventually you need other symfony2 bundles which can be obtained via composer.

Symfony2 setup
--------
Open cmd. (command line tool)
Go to your www map in wamp by typing cd /wamp/www.

Go to http://symfony.com/doc/current/book/installation.html
Copy: composer create-project symfony/framework-standard-edition /wamp/www/blog 2.4.*
Notice that I filled in the path to /wamp/www/blog and that I didn't type php in front and that I didn't use composer.phar but just composer.
It is going to get and configure symfony2 now and put it in the folder you have given.

A list with answers to the questions asked by your command line.
(if the answer already stands between brackets you can click enter to use that answer as it is a default value):
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
InsertVeryUniqueHashHere

Open httpd-vhosts.conf in your apache extra folder.
On my pc that is /wamp/bin/apache2/apache2.4.4/conf/extra/httpd-vhosts.conf

Go to http://symfony.com/doc/current/cookbook/configuration/web_server_configuration.html
And copy:
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

Change it like this, but with your own name in it ofcourse:

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

We are going to add the servername to your hosts file, so your browser knows where to look ;)

Change your hosts file in /windows/system32/drivers/etc/hosts.
Don't forget to open your notepad as administrator.
Add your tld to the localhost like matthijs.blog
localhost    127.0.0.1 matthijs.blog
is what it says in my hosts file.

Restart all wamp services by clicking on the bottom right corner icon of wamp and select restart all.

Go to your browser and navigate to matthijs.blog/app_dev.php
Your symfony2 start page is ready! w00t!

app_dev.php stands in your web directory (/wamp/www/blog). This is where you work with as long you are developing.
All generated urls will be to app_dev.php like: matthijs.blog/app_dev.php/create/blog-item/1.
If you're going in production mode, you use app.php. For a test environment you use app_test.php... etc.
The whole idea is that the application can behave differently based on its environment.
Like extra debug information in the dev enviroment.