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
$cache->set("Some data");
```

### Setting data

```php
$cache = new Cache('my_key');
$cache->set("Some data");
```

### Getting data

```php
$cache = new Cache('my_key');
echo $cache->get(); // "Some data"
```

### Clearing 

```php
$cache = new Cache('my_key');
echo $cache->clear();
```

### Usage

```php
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
