# Aggie88 (Link Tree)

## Installation

1. Make sure you  have the following:   
github command line utility https://cli.github.com/   
composer command line utility https://getcomposer.org/
using php 8.2.7 or above
having basic php extensions installed

2. Clone this repository and `cd` into it
   ```
   git clone git@github.com:klynicol/aggie88.git
   cd aggie88
   ```

3. Run composer to install dependencies
   ```
   composer install
   ```

4. Create a new file .env at the root level of the directory
   ```
   vi .env
   ```
   Paste the following into the new file and change the variables to match your environment. Note: you may want to start a new database on your local mysql server.
   ```
   # development / production
   CI_ENVIRONMENT = development

   app.baseURL = 'http://test.aggie88'
   app_baseURL = 'http://test_aggie88'

   #--------------------------------------------------------------------
   # DATABASE
   #--------------------------------------------------------------------

   database.default.hostname = localhost
   database.default.database = aggie
   database.default.username = root
   database.default.password = root
   database.default.DBDriver = MySQLi
   database.default.DBPrefix =
   database.default.port = 3306
   ```

5. Run the migrations to create the schema on your database.
   ```
   php spark migrate
   ```

6. You can either point a webhost (nginx or apache) at the public folder, the following is my example apache config (which includes a re-write for index.php)
   ```
   <VirtualHost *:80>
         ServerName test.aggie88
         ServerAlias test.aggie88
         DocumentRoot "/home/markwickline/fiverr/aggie88/public"
         <Directory /home/markwickline/fiverr/aggie88/public>
                  Require all granted
                  Allow from all
                  RewriteEngine On
                  RewriteCond %{REQUEST_FILENAME} !-f
                  RewriteCond %{REQUEST_FILENAME} !-d
                  RewriteRule ^(.*)$ index.php/$1 [L]
         </Directory>
         # Run things on php 8.2
         <FilesMatch ".+\.ph(ar|p|tml)$">
                  SetHandler "proxy:unix:/run/php-fpm/www.sock|fcgi://php.localhost"
         </FilesMatch>
   </VirtualHost>
   ```
   Or you can run a temporary php server by running the following in the root directory
   ```
   php spark serve
   ```

