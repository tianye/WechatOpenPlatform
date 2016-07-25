<?php

namespace OpenOauth\Core;

class Config
{
    /** @var array ['component_app_id'=>'','component_app_secret'=>'', 'component_app_token'=>'', 'component_app_key'=>''] */
    static $configs = [];

    static function init($configs)
    {
        self::$configs = $configs;
    }

}