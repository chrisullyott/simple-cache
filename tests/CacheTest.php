<?php
/**
 * Tests for Cache.
 */

use ChrisUllyott\Cache;

class CacheTest extends \PHPUnit_Framework_TestCase
{
    /**
     * Test whether we can cache data.
     */
    public function testSetAndGet()
    {
        $cache = new Cache('test_cache_key');

        $set = $cache->set('Some content to store');
        $this->assertTrue($set);

        $get = $cache->get();
        $this->assertNotEmpty($get);
    }
}
