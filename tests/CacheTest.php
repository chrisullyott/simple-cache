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
        $cache = new Cache('my_key');

        $set = $cache->set(['data' => 'Some data']);
        $this->assertTrue($set);

        $get = $cache->get();
        $this->assertNotEmpty($get);
    }
}
