![dschini.org](htdocs/img/dschini_medium.png)

http://games.dschini.org is public now. feel free to fork it. The code is based on https://github.com/crafics/mvc-php

### Install

    apt-get update
    apt-get install apache2
    apt-get install php5
    apt-get install php5-sqlite
    service apache2 restart
    apt-get install git
    apt-get install phpunit
    apt-get install mysql-server
    apt-get install php5-mysql
    apt-get install phpmyadmin
    apt-get install php5-memcache
    apt-get install memcached

### Configure
    
    Apache
    vi /etc/apache2/sites-available/default
    AllowOverride All
    DocumentRoot /var/www/htdocs
    a2enmod rewrite
    service apache2 restart

    MySQL
    CREATE USER 'John'@'localhost' IDENTIFIED BY 'secret-pwd-pls-change';
    GRANT ALL PRIVILEGES ON * . * TO 'John'@'localhost';
    FLUSH PRIVILEGES;
    CREATE database games_dschini_org;
    mysql -uroot -secret-pwd-pls-change games_dschini_org < docs/games_dschini_org.sql

### Then

    Register a user
    Login
    Recompile the .fla Files and send Highscore Requests to your host
    At least once a month run-cron.sh or look into cron folder
 
