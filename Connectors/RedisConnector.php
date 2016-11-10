<?php

namespace Lz\Cache\Connectors;

/**
 * Redis 连接器
 *
 * @package     Cache
 * @subpackage  Connectors
 * @author      Longzhi
 */
class RedisConnector
{
    public function connect(array $server)
    {
        $redis = new Redis;

        $host = $server['host'];
        $port = $server['port'];
        $timeout = 5;
        if (isset($config['timeout'])) {
            $timeout = (int) $config['timeout'];
        }

        if (! $redis->connect($host, $port, $timeout)) {
            throw new RuntimeException('Redis connect failure.');
        }

        if (isset($server['password'])
            && ! $redis->auth($server['password'])) {
            throw new RuntimeException('Redis auth failure.');
        }

        if (isset($server['database'])
            && ! $redis->select($server['database'])) {
            throw new RuntimeException('Redis select database failure.');
        }

        return $redis;
    }
}
