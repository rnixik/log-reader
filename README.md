LogReader
=========

This is error log parser written in PHP. It reads nginx errors and errors of PHP module for Apache.
You can use it if you need to read and analyze web server's error log.


Usage
=========
### Apache

```php
$logReader = new LogReader_ApachePhp("/var/log/apache2/error.log", new LogReader_Storage_Array());
$logReader->read();

$logs = $logReader->getStorage()->load();
//or $logs = $logReader->getStorage()->loadUnique();
$logs = array_reverse($logs);
var_dump($logs);
```

Each item of $logs contains: timestamp, type, message.
See example_apache.php.

### Nginx

```php
$logReader = new LogReader_Nginx("/var/log/nginx/error.log", new LogReader_Storage_Array());
$logReader->read();

$logs = $logReader->getStorage()->load();
//or $logs = $logReader->getStorage()->loadUnique();
$logs = array_reverse($logs);
var_dump($logs);
```

Each item of $logs contains: timestamp, type, message, host, request
See example_nginx.php.