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
        $data = ['items' => [1, 2, 3]];

        $cache->set($data);
        $this->assertSame($cache->get(), $data);
    }
}
