AutoVM
VMware ESXI Web Control  Panel

* Monitoring Bandwidth Per virtual Machine
* Install And Config static IP Address From VM

for buy licence, please visit:
http://my.autovm.net/




INSTALLATION
------------

### Install from an Archive File

Extract the archive file downloaded from github to
a directory named `autovm` that is directly under the Web root.

Set cookie validation key in `config/web.php` file to some random secret string:

```php
'request' => [
    // !!! insert a secret key in the following (if it is empty) - this is required by cookie validation
    'cookieValidationKey' => '<secret random string goes here>',
],
```

You can then access the application through the following URL:

~~~
http://localhost/autovm/web/
~~~

CONFIGURATION
-------------

### Database

Edit the file `config/db.php` with real data, for example:

```php
return [
    'class' => 'yii\db\Connection',
    'dsn' => 'mysql:host=localhost;dbname=yii2basic',
    'username' => 'root',
    'password' => '1234',
    'charset' => 'utf8',
];
```

Then go to PhpMyAdmin and import autovm.sql from your carted database.
