<?php

namespace Lz\Cache\Stores;

/**
 * 缓存仓库接口
 *
 * @package     Cache
 * @subpackage  Stores
 * @author      Longzhi
 */
interface StoreInterface
{
    /**
     * 判断 key 是否存在
     * @param  string  $key 缓存 key
     * @return boolean      如果 key 存在，则返回 true，否则返回 false
     */
    public function has($key);

    /**
     * 获取 key 的值
     * @param  string $key     缓存 key
     * @param  string $default 默认值，如果 key 的值不存在，返回该值
     * @return mixed           如果 key 值存在，则返回该值，否则返回默认值
     */
    public function get($key, $default = null);

    /**
     * 设置 key 的值
     * @param string    $key    缓存 key
     * @param mixed     $value  缓存值
     * @param integer   $expire 有效期
     */
    public function set($key, $value, $expire = null);

    /**
     * 添加 key 的值
     *
     * 如果 key 存在则不修改，否则添加 key
     *
     * @param string    $key    缓存 key
     * @param mixed     $value  缓存值
     * @param integer   $expire 有效期
     */
    public function add($key, $value, $expire = null);

    /**
     * 删除 key
     * @param  string   $key 缓存 key
     * @return boolean       是否删除成功，返回 true 则删除成功，否则删除失败
     */
    public function delete($key);

    /**
     * 递增 key 的值
     * @param  string  $key   缓存 key
     * @param  integer $step  递增的步进值，默认为 1
     */
    public function increment($key, $step = 1);

    /**
     * 递减 key 的值
     * @param  string  $key   缓存 key
     * @param  integer $step  递减的步进值，默认为 1
     */
    public function decrement($key, $step = 1);

    /**
     * 清空缓存
     */
    public function flush();

    /**
     * 获取缓存 key 的前缀
     * @return string   缓存 key 的前缀
     */
    public function getPrefix();
}
