<?php

namespace OpenOauth\Core\DatabaseDriver;

/**
 * 数据库基类.
 *
 */
abstract class BaseDriver
{
    protected $databaseDir; // 数据路径

    /**
     * 初始化时设置路径.
     *
     * @param string $dir 路径信息
     */
    public function __construct($dir)
    {
        $this->databaseDir = $dir;
    }

    /**
     * 根据key名获取内容.
     *
     * @param string $name 缓存名
     */
    abstract public function _get($name);

    /**
     * 根据key名设置值.
     *
     * @param string       $name  缓存名
     * @param string|array $value 缓存值
     *
     * @return boolean;
     */
    abstract public function _set($name, $value);
}
