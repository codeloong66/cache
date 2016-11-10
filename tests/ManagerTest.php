<?php

namespace Cache\Tests\Stores;

use PHPUnit_Framework_TestCase;
use Cache_Manager;
use Cache_Stores_RedisStore as RedisStore;
use Cache_Stores_MemcachedStore as MemcachedStore;

class ManagerTest extends PHPUnit_Framework_TestCase
{
    protected $config =  [
        'prefix' => 'test',
        'default' => 'redis',
        'stores' => [
            'redis' => [
                'driver' => 'redis',
                'connection' => [
                    'host' => '127.0.0.1',
                    'password' => null,
                    'port'     => 6379,
                    'database' => 0,
                    'timeout'  => 5,
                ]
            ],
            'memcached' => [
                'driver' => 'memcached',
                'servers' => [
                    [
                        'host' => '127.0.0.1',
                        'port' => 11211,
                        'weight'=> 1,
                    ],
                ]
            ]
        ]
    ];

    public function testStore()
    {
        $manager = new Cache_Manager($this->config);

        $this->assertInstanceOf(RedisStore::class, $manager->store());
        $this->assertInstanceOf(RedisStore::class, $manager->store('redis'));
        $this->assertInstanceOf(MemcachedStore::class, $manager->store('memcached'));

    }

    public function testShareStore()
    {
        $manager = new Cache_Manager($this->config);

        $this->assertEquals($manager->store(), $manager->store());
        $this->assertEquals($manager->store('redis'), $manager->store());
        $this->assertEquals($manager->store('redis'), $manager->store('redis'));
    }

    /**
     * @expectedException \InvalidArgumentException
     * @expectedExceptionMessage Cache store [foo] is not defined.
     */
    public function testNotExistsStore()
    {
        $manager = new Cache_Manager($this->config);
        $manager->store('foo');
    }

    /**
     * @expectedException \InvalidArgumentException
     * @expectedExceptionMessage Driver [bar] not supported.
     */
    public function testNotExistsDriver()
    {
        $config = $this->config;
        $config['stores']['redis']['driver'] = 'bar';

        $manager = new Cache_Manager($config);
        $manager->store('redis');
    }

}
