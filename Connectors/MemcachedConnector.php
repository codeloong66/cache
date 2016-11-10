<?php
/**
 * Memached 仓库
 *
 * @package     Cache
 * @subpackage  Connectors
 * @author      黄邦龙
 */
class Cache_Connectors_MemcachedConnector
{
    public function connect(array $servers)
    {
        $memcached = new Memcached;

        foreach ($servers as $server) {
            $memcached->addServer(
                $server['host'], $server['port'], $server['weight']
            );
        }

        if ($memcached->getVersion() === false) {
            throw new RuntimeException('No Memcached servers added.');
        }

        return $memcached;
    }
}
