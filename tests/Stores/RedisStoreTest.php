<?php

namespace Cache\Tests\Stores;

use PHPUnit_Framework_TestCase;
use Cache_Stores_RedisStore as RedisStore;
use Cache_Connectors_RedisConnector as RedisConnector;

use Redis;

class RedisStoreTest extends TestCase
{
    protected static $redis;

    public static function setUpBeforeClass()
    {
        $config = [
            'host' => '127.0.0.1',
            'password' => null,
            'port'     => 6379,
            'database' => 9,
            'timeout'  => 5,
        ];

        $connector = new RedisConnector();
        static::$redis = $connector->connect($config);
    }

    public static function tearDownAfterClass()
    {
        static::$redis->close();
    }

    public function setUp()
    {
        $this->store = new RedisStore(
            static::$redis, static::$prefix
        );
    }

    public function tearDown()
    {
        static::$redis->setOption(
            Redis::OPT_PREFIX, static::$prefix . ':'
        );

        static::$redis->del('foo');
        static::$redis->del('bar');
    }
}
