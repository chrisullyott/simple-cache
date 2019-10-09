# simple-cache

Quick and easy data caching in the filesystem.

### Install

```bash
$ composer require chrisullyott/simple-cache
```

### Instantiate

```php
require 'vendor/autoload.php';

use ChrisUllyott\Cache;
$cache = new Cache('cache_id');
```

### Setting data

```php
$cache->set("Some data");
```

### Getting data

```php
echo $cache->get(); // "Some data"
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

### Testing

```bash
$ ./vendor/bin/phpunit --configuration=./tests/phpunit.xml
```
