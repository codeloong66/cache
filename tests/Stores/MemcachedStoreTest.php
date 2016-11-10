<?php

namespace Cache\Tests\Stores;

use PHPUnit_Framework_TestCase;
use Cache_Stores_MemcachedStore as MemcachedStore;
use Cache_Connectors_MemcachedConnector as MemcachedConnector;

use Memcached;

class MemcachedStoreTest extends TestCase
{
    protected static $memcached;

    public static function setUpBeforeClass()
    {
        $config = [
            [
                'host' => '127.0.0.1',
                'port' => 11211,
                'weight'=> 1,
            ],
        ];

        $connector = new MemcachedConnector();
        static::$memcached = $connector->connect($config);
    }

    public static function tearDownAfterClass()
    {
        static::$memcached->quit();
    }

    public function setUp()
    {
        $this->store = new MemcachedStore(
            static::$memcached, static::$prefix
        );
    }

    public function tearDown()
    {
        static::$memcached->delete(static::$prefix . ':foo');
        static::$memcached->delete(static::$prefix . ':bar');
    }
}
