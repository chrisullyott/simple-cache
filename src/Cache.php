<?php

/**
 * Quick and easily data cache using the filesystem.
 */

namespace ChrisUllyott;

use ChrisUllyott\Utility\Log;
use ChrisUllyott\Utility\Time;
use ChrisUllyott\Utility\File;

class Cache
{
    /**
     * The key (essentially, the ID) of this cache.
     *
     * @var string
     */
    public $key;

    /**
     * The expiration frequency of this cache.
     *
     * @var string
     */
    private $expire = 'weekly';

    /**
     * The time this class began to run.
     *
     * @var integer
     */
    private $runTime;

    /**
     * The time this cache was created.
     *
     * @var integer
     */
    private $createdTime;

    /**
     * The time this cache expires.
     *
     * @var integer
     */
    private $expireTime;

    /**
     * The cache directory path.
     *
     * @var string
     */
    private $cacheDir;

    /**
     * This cache's path.
     *
     * @var string
     */
    private $cachePath;

    /**
     * The cache data object.
     *
     * @var Log
     */
    private $cache;

    /**
     * The names of the properties that will be saved to disk.
     *
     * @var array
     */
    private static $storedProperties = [
        'key',
        'expire',
        'createdTime',
        'expireTime'
    ];

    /**
     * Constructor.
     *
     * @param string $key This cache key
     */
    public function __construct($key)
    {
        $this->runTime = time();
        $this->key = $key;

        $this->cacheDir = File::path('cache');
        File::createDir($this->cacheDir);
    }

    /**
     * Get the path to the cache file.
     *
     * @return string
     */
    private function getCachePath()
    {
        if (!$this->cachePath) {
            $filename = File::slugify($this->key) . '.json';
            $this->cachePath = File::path($this->cacheDir, $filename);
        }

        return $this->cachePath;
    }

    /**
     * Get the cache data object.
     *
     * @return Log
     */
    private function getCache()
    {
        if (!$this->cache) {
            $this->cache = new Log($this->getCachePath());
            $this->cache->merge($this->getStoredProperties());
        }

        return $this->cache;
    }

    /**
     * Get the properties that will be saved to disk.
     *
     * @return array
     */
    private function getStoredProperties()
    {
        $props = get_object_vars($this);
        $props_stored = array_flip(static::$storedProperties);

        return array_intersect_key($props, $props_stored);
    }

    /**
     * Store some data in this cache.
     *
     * @param mixed $value The data to store
     */
    public function set($value)
    {
        $data = base64_encode(serialize($value));
        $this->getCache()->set('data', $data);

        return $this;
    }

    /**
     * Get the data out of this cache.
     *
     * @return mixed
     */
    public function get()
    {
        $data = $this->getCache()->get('data');

        return unserialize(base64_decode($data));
    }

    /**
     * Manually expire this cache.
     *
     * @return boolean Whether the cache was expired
     */
    public function expire()
    {
        return $this->getCache()->set('expireTime', 0);
    }

    /**
     * Find whether this cache's content is expired.
     *
     * @return boolean Whether expired
     */
    public function isExpired()
    {
        return $this->getCache()->get('expireTime') <= $this->runTime();
    }

    /**
     * Clear this cache.
     *
     * @return boolean Whether the cache was cleared
     */
    public function clear()
    {
        return File::deleteDir($this->getCacheDir());
    }
}
