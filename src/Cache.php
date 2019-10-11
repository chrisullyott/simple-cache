<?php

/**
 * Quick and easily data cache using the filesystem.
 */

namespace ChrisUllyott;

use ChrisUllyott\Log;
use ChrisUllyott\Utility\File;

class Cache
{
    /**
     * A string that identifies this cache.
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
            $filename = sha1($this->id);
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
            $this->cache->set('id', $this->id);
        }

        return $this->cache;
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
        return !file_exists($this->getCachePath()) || unlink($this->getCachePath());
    }
}
