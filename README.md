# simple-cache

Quick and easy data caching in the filesystem.

Keeps previous entries for future reference and cleans up old entries on a regular basis.

### Install

```bash
$ composer require chrisullyott/simple-cache
```

### Instantiate

```php
require 'vendor/autoload.php';

use ChrisUllyott\Cache;

$cache = new Cache('my_key');
```

### Setting data

```php
$cache->set("Some data");
```

### Getting data

```php
echo $cache->get(); // "Some data"
```

### Invalidating

```php
$cache->invalidate();
```

### Clearing 

```php
$cache->clear();
```

### Usage

```php
<?php

require 'vendor/autoload.php';

use ChrisUllyott\Cache;

$cache = new Cache('my_key');
$data = $cache->get();

if (!$data) {
    $data = my_api_request();
    $cache->set($data);
}

print_r($data);
```

### Expiration

The cache will expire after a certain amount of time, and `get()` will return false. To set the expiration frequency, use the second parameter of the constructor, for example "hourly", "nightly", or "weekly":

```php
$cache = new Cache('my_key', 'hourly');
```

### Testing

```bash
$ ./vendor/bin/phpunit --configuration=./tests/phpunit.xml
```
