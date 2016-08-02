<?php

include '../../../autoload.php';

use OpenOauth\Core\Config;
use OpenOauth\OpenAuth;

$config = new Config();
$config->init(['component_app_id' => '第三方平台appId', 'component_app_secret' => '第三方平台appSecret', 'component_app_token' => '第三方平台appToken', 'component_app_key' => '第三方平台appKey']);

$cacheDriver    = new \OpenOauth\Core\CacheDriver\RedisDriver(['host' => '127.0.0.1', 'port' => '6379', 'database' => '1']);
$databaseDriver = new \OpenOauth\Core\DatabaseDriver\RedisDriver(['host' => '127.0.0.1', 'port' => '6379', 'database' => '1']);

$open_auth = new OpenAuth($cacheDriver, $databaseDriver, '授权的服务号appId');

//snsapi_base 小授权    snsapi_userinfo 大授权
$user = $open_auth->authorize(null, 'snsapi_userinfo');

var_dump($user);

//获取大授权 用户信息
$user_info = $open_auth->getUserInfo($user['access_token'], $user['openid']);

var_dump($user_info);