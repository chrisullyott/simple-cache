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

### Testing

```bash
./vendor/bin/phpunit --configuration=./tests/phpunit.xml
```
