<?php
/**
 * memached 仓库
 *
 * @package     Cache
 * @subpackage  Stores
 * @author      黄邦龙
 */
class Cache_Stores_MemcachedStore implements Cache_Stores_Interface
{
    protected $memcached;

    protected $prefix;

    public function __construct($memcached, $prefix)
    {
        $this->memcached = $memcached;

        $this->setPrefix($prefix);
    }

    public function has($key)
    {
        return ! is_null($this->get($key));
    }

    public function get($key, $default = null)
    {
        $value = $this->memcached->get($this->prefix . $key);

        if ($this->memcached->getResultCode() == 0) {
            return $value;
        }

        return $default;
    }

    public function set($key, $value, $expire = null)
    {
        $this->memcached->set(
            $this->prefix . $key, $value, $expire
        );
    }

    public function add($key, $value, $expire = null)
    {
        return $this->memcached->add(
            $this->prefix . $key, $value, $expire
        );
    }

    public function increment($key, $step = 1)
    {
        $result = $this->memcached->increment(
            $this->prefix . $key, $step
        );

        if ($this->memcached->getResultCode() === Memcached::RES_NOTFOUND) {
            $this->memcached->set($this->prefix . $key, $step);

            return $step;
        }

        return $result;
    }

    public function decrement($key, $step = 1)
    {
        $result = $this->memcached->decrement(
            $this->prefix . $key, $step
        );

        if ($this->memcached->getResultCode() === Memcached::RES_NOTFOUND) {
            $this->memcached->set($this->prefix . $key, $step);

            return $step;
        }

        return $result;
    }

    public function delete($key)
    {
        return $this->memcached->delete($this->prefix . $key);
    }

    public function flush()
    {
        $this->memcached->flush();
    }

    public function getPrefix()
    {
        return $this->prefix;
    }

    public function setPrefix($prefix)
    {
        $this->prefix = empty($prefix) ? '' : $prefix . ':';
    }
}
