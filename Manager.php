<?php
/**
 * 缓存管理者
 *
 * @package     Cache
 * @author      黄邦龙
 */
class Cache_Manager
{
    /**
     * 配置
     * @var array
     */
    protected $config;

    /**
     * 已解析的 store
     * @var array
     */
    protected $stores = array();

    /**
     * @param array $config 配置
     */
    public function __construct(array $config)
    {
        $this->config = $config;
    }

    /**
     * 获取 store
     * @param  string $name store 名称
     * @return Cache_Stores_Interface 
     */
    public function store($name = null)
    {
        $name = $name ? $name : $this->getDefaultStore();

        if (isset($this->stores[$name])) {
            return $this->stores[$name];
        }

        return $this->stores[$name] = $this->resolve($name);
    }

    /**
     * 解析 store
     * @param  string $name store 名称
     * @return Cache_Stores_Interface
     */
    protected function resolve($name)
    {
        $config = $this->getConfig($name);

        if (is_null($config)) {
            throw new InvalidArgumentException("Cache store [{$name}] is not defined.");
        }

        $driverMethod = 'create'.ucfirst($config['driver']).'Driver';

        if (method_exists($this, $driverMethod)) {
            return $this->{$driverMethod}($config);
        } else {
            throw new InvalidArgumentException("Driver [{$config['driver']}] not supported.");
        }
    }

    /**
     * 创建 Memcached 驱动
     * @param  array $config 配置项
     * @return Cache_Stores_RedisStore
     */
    protected function createMemcachedDriver($config)
    {
        $connector = new Cache_Connectors_MemcachedConnector();
        $memcached = $connector->connect($config['servers']);

        return new Cache_Stores_MemcachedStore(
            $memcached, $this->getPrefix($config)
        );
    }

    /**
     * 创建 Redis 驱动
     * @param  array $config 配置项
     * @return Cache_Stores_RedisStore
     */
    protected function createRedisDriver($config)
    {
        $connector = new Cache_Connectors_RedisConnector();
        $redis = $connector->connect($config['connection']);

        return new Cache_Stores_RedisStore(
            $redis, $this->getPrefix($config)
        );
    }

    /**
     * 获取缓存前缀
     * @param  array    $config 配置项
     * @return string
     */
    protected function getPrefix($config)
    {
        return isset($config['prefix'])
            ? $config['prefix'] : $this->config['prefix'];
    }

    /**
     * 获取 store 的配置
     * @param  string $name store 名称
     * @return array
     */
    protected function getConfig($name)
    {
        if (isset($this->config['stores'][$name])) {
            return $this->config['stores'][$name];
        }

        return null;
    }

    /**
     * 获取默认的 store 名称
     * @return string
     */
    protected function getDefaultStore()
    {
        return $this->config['default'];
    }

    public function __call($method, $parameters)
    {
        return call_user_func_array(
            array($this->store(), $method), $parameters
        );
    }
}
