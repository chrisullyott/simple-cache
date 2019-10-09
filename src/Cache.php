<?php

/**
 * Quick and easily data cache using the filesystem.
 */

namespace ChrisUllyott;

use ChrisUllyott\Utility\Log;
use ChrisUllyott\Utility\File;

class Cache
{
    /**
     * The ID of this cache.
     *
     * @var string
     */
    public $id;

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
        'id'
    ];

    /**
     * Constructor.
     *
     * @param string $id This cache ID
     */
    public function __construct($id)
    {
        $this->id = $id;
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
            $filename = File::slugify($this->id) . '.json';
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
     * Clear this cache.
     *
     * @return boolean Whether the cache was cleared
     */
    public function clear()
    {
        return unlink($this->getCachePath());
    }
}
