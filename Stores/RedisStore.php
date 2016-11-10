<?php
/**
 * Redis 仓库
 *
 * @package     Cache
 * @subpackage  Stores
 * @author      黄邦龙
 */
class Cache_Stores_RedisStore implements Cache_Stores_Interface
{
    /**
     * Redis 实例
     * @var Redis
     */
    protected $redis;

    /**
     * key 前缀
     * @var string
     */
    protected $prefix;

    /**
     * @param Redis  $redis  Redis 实例
     * @param string $prefix key 前缀
     */
    public function __construct(Redis $redis, $prefix)
    {
        $this->redis = $redis;

        $this->setPrefix($prefix);
    }

    public function has($key)
    {
        return ! is_null($this->get($key));
    }

    public function get($key, $default = null)
    {
        $value = $this->redis->get($key);

        $value = is_numeric($value) ? $value : unserialize($value);

        return $value === false ? $default : $value;
    }

    public function set($key, $value, $expire = null)
    {
        $value = is_numeric($value) ? $value : serialize($value);

        return $this->redis->set(
            $key, $value, $expire
        );
    }

    public function add($key, $value, $expire = null)
    {
        $value = is_numeric($value) ? $value : serialize($value);

        if (! $expire) {
            return $this->redis->setNx(
                $key, $value
            );
        }

        if ($this->has($key)) {
            return false;
        } else {
            return $this->redis->set(
                $key, $value, $expire
            );
        }
    }

    public function increment($key, $step = 1)
    {
        return $this->redis->incrBy(
            $key, $step
        );
    }

    public function decrement($key, $step = 1)
    {
        return $this->redis->decrBy(
            $key, $step
        );
    }

    public function delete($key)
    {
        return (bool) $this->redis->del($key);
    }

    public function flush()
    {
        $this->redis->flushdb();
    }

    public function getPrefix()
    {
        return $this->prefix;
    }

    public function setPrefix($prefix)
    {
        $this->prefix = empty($prefix) ? '' : $prefix . ':';

        $this->redis->setOption(
            Redis::OPT_PREFIX, $this->prefix
        );
    }
}
