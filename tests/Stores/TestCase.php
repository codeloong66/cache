<?php

namespace Cache\Tests\Stores;

use PHPUnit_Framework_TestCase;
use Cache_Stores_RedisStore as RedisStore;
use Cache_Connectors_RedisConnector as RedisConnector;

use Redis;

class TestCase extends PHPUnit_Framework_TestCase
{
    protected static $prefix = 'phpunit';

    protected $store;

    public function testHas()
    {
        $this->assertFalse($this->store->has('foo'));

        $this->store->set('foo', 'xxx');
        $this->assertTrue($this->store->has('foo'));
    }

    public function testGet()
    {
        $store = $this->store;

        $this->assertNull($store->get('foo'));
        $this->assertEquals('test', $store->get('foo', 'test'));

        $store->set('foo', 'xxx');
        $this->assertEquals('xxx', $store->get('foo'));
        $this->assertEquals('xxx', $store->get('foo', 'test'));
    }

    public function testSet()
    {
        $store = $this->store;

        $store->set('foo', 'aa');
        $store->set('bar', 'bb', 2);
        $this->assertEquals('aa', $store->get('foo'));
        $this->assertEquals('bb', $store->get('bar'));

        sleep(2);
        $this->assertEquals('aa', $store->get('foo'));
        $this->assertNull($store->get('bar'));
    }

    public function testAdd()
    {
        $store = $this->store;

        $store->add('foo', 'aa');
        $store->add('foo', 'aa2');
        $this->assertEquals('aa', $store->get('foo'));

        $store->add('bar', 'bb', 2);
        $store->add('bar', 'bb2', 2);
        $this->assertEquals('bb', $store->get('bar'));
        sleep(2);
        $this->assertNull($store->get('bar'));
    }

    public function testIncrement()
    {
        $store = $this->store;

        $store->increment('foo');
        $this->assertEquals(1, $store->get('foo'));

        $store->increment('foo', 20);
        $this->assertEquals(21, $store->get('foo'));

        // $store->increment('foo', -10);
        // $this->assertEquals(11, $store->get('foo'));
    }

    public function testDecrement()
    {
        // $store = $this->store;

        // $store->decrement('foo');
        // $this->assertEquals(-1, $store->get('foo'));

        // $store->decrement('foo', 20);
        // $this->assertEquals(-21, $store->get('foo'));

        // $store->decrement('foo', -10);
        // $this->assertEquals(-11, $store->get('foo'));
    }

    public function testDelete()
    {
        $store = $this->store;

        $store->set('foo', 'test');
        $this->assertTrue($store->has('foo'));

        $store->delete('foo');
        $this->assertFalse($store->has('foo'));
    }

    public function testFlush()
    {
        $store = $this->store;

        $store->set('foo', 'test');
        $store->set('bar', 'test');

        $store->flush();

        $this->assertFalse($store->has('foo'));
        $this->assertFalse($store->has('bar'));
    }

    public function testGetPrefix()
    {
        $this->assertEquals(
            self::$prefix . ':', $this->store->getPrefix()
        );
    }
}
