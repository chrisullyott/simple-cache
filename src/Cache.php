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
    private $key;

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
     * The path for all caches.
     *
     * @var string
     */
    private $container = 'cache';

    /**
     * The path for this cache directory.
     *
     * @var string
     */
    private $cachePath;

    /**
     * This cache's catalog.
     *
     * @var Catalog
     */
    private $catalog;

    /**
     * Constructor.
     */
    public function __construct()
    {
        $a = func_get_args();

        // Set properties.
        if (is_array($a[0])) {
            $this->setProperties($a[0]);
        } else {
            if (isset($a[0])) {
                $this->key = $a[0];
            }

            if (isset($a[1])) {
                $this->expire = $a[1];
            }
        }

        $this->init();
    }

    /**
     * Set multiple properties of this object via an associative array.
     *
     * @param array $properties An associative array of property names and values
     * @return self
     */
    private function setProperties(array $properties)
    {
        foreach ($properties as $name => $value) {
            if (property_exists($this, $name)) {
                $this->{$name} = $value;
            } else {
                throw new Exception("{$name} is not a valid property");
            }
        }

        return $this;
    }

    /**
     * What time is it?  |(• ◡•)|/ \(❍ᴥ❍ʋ)
     *
     * @return integer
     */
    private function getRunTime()
    {
        if (!$this->runTime) {
            $this->runTime = time();
        }

        return $this->runTime;
    }

    /**
     * Get this cache's key.
     *
     * @return string
     */
    private function getKey()
    {
        if (!$this->key) {
            throw new Exception('Cache key is missing');
        }

        return $this->key;
    }

    /**
     * Get the path to this cache.
     *
     * @return string
     */
    private function getCachePath()
    {
        if (!$this->cachePath) {
            $this->cachePath = File::path($this->container, $this->getKey());
        }

        return $this->cachePath;
    }

    /**
     * Get the catalog path.
     *
     * @return string
     */
    private function getCatalogPath()
    {
        return File::path($this->getCachePath(), '.catalog');
    }

    /**
     * Get the Catalog object belonging to this cache.
     *
     * @return Catalog
     */
    private function getCatalog()
    {
        if (!$this->catalog) {
            $this->catalog = new Log($this->getCatalogPath());
        }

        return $this->catalog;
    }

    /**
     * Initialize a new cache by clearing its directory and building a new catalog.
     *
     * @return boolean Whether the cache was set up
     */
    private function init()
    {
        // Create the directory if it doesn't exist
        File::createDir($this->getCachePath());

        // Build and save a new catalog file
        $props = [
            'key'          => $this->getKey(),
            'expire'       => $this->expire,
            'createdTime'  => $this->getRunTime(),
            'expireTime'   => Time::nextExpire($this->expire)
        ];

        return $this->getCatalog()->setAll($props);
    }

    /**
     * Find whether this cache's content is expired.
     *
     * @return boolean Whether expired
     */
    public function isExpired()
    {
        return $this->getCatalog()->get('expireTime') <= $this->getRunTime();
    }

    /**
     * Get the latest data from the cache. Return false if expired or unreadable.
     *
     * @return string
     */
    public function get()
    {
        // get

        return false;
    }

    /**
     * Invalidate this cache.
     *
     * @return boolean Whether the cache was invalidated
     */
    public function invalidate()
    {
        return $this->getCatalog()->set('expireTime', 0);
    }

    /**
     * Clear the cache. If a key is not specified, the container is cleared.
     *
     * @return boolean Whether the cache was cleared
     */
    public function clear()
    {
        return File::deleteDir($this->getCachePath());
    }
}
