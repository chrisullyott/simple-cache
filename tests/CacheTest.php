<?php
/**
 * Tests for Cache.
 */

use ChrisUllyott\Cache;

class CacheTest extends \PHPUnit_Framework_TestCase
{
    private $cache;

    /**
     * Get a Cache object.
     */
    public function getCache()
    {
        if (is_null($this->cache)) {
            $this->cache = new Cache('my_key');
        }

        return $this->cache;
    }

    /**
     * Test whether data can be stored.
     */
    public function testSet()
    {
        $set = $this->getCache()->set("Some data");

        $this->assertTrue($set);
    }

    /**
     * Test whether data can be stored.
     */
    public function testGet()
    {
        $data = $this->getCache()->get();

        $this->assertNotEmpty($data);
    }
}
