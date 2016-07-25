<?php

namespace OpenOauth\Core\DatabaseDriver;

use OpenOauth\Core\DatabaseDriver\BaseDriver;

/**
 * 文件缓存驱动.
 *
 */
class FileDriver extends BaseDriver
{
    /**
     * @param string $name
     *
     * @return bool|null|string|void
     */
    public function _get($name)
    {
        $name = $this->createFileName($name);
        $file = $this->databaseDir . '/' . $name;
        $data = @file_get_contents($file);
        if (!$data) {
            return false;
        }

        $data = $this->unpackData($data);

        return $data;
    }

    /**
     * 根据缓存名 设置缓存值和超时时间.
     *
     * @param string $name  缓存名
     * @param mixed  $value 缓存值
     *
     * @return boolean;
     */
    public function _set($name, $value)
    {
        $name = $this->createFileName($name);
        $data = $this->packData($value);

        return file_put_contents($this->databaseDir . '/' . $name, $data);
    }

    /**
     * 数据打包.
     *
     * @param void $data 缓存值
     *
     * @return string
     */
    private function packData($data)
    {
        $str         = [];
        $str['data'] = $data;
        $str         = serialize($str);

        return $str;
    }

    /**
     * @param $data
     *
     * @return bool
     */
    private function unpackData($data)
    {
        $arr = unserialize($data);

        return $arr['data'];
    }

    /**
     * 创建缓存文件名.
     *
     * @param string $name 缓存名
     *
     * @return string
     */
    private function createFileName($name)
    {
        return md5($name);
    }
}
