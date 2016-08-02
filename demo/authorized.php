<?php
//生成 授权页面

//http://applist-test-open.weflame.com/authorized.php

include '../../../autoload.php';

use OpenOauth\Authorized;
use OpenOauth\Core\Config;
use OpenOauth\Core\Core;

$config = new Config();
$config->init(['component_app_id' => '第三方平台appId', 'component_app_secret' => '第三方平台appSecret', 'component_app_token' => '第三方平台appToken', 'component_app_key' => '第三方平台appKey']);

$cacheDriver    = new \OpenOauth\Core\CacheDriver\RedisDriver(['host' => '127.0.0.1', 'port' => '6379', 'database' => '1']);
$databaseDriver = new \OpenOauth\Core\DatabaseDriver\RedisDriver(['host' => '127.0.0.1', 'port' => '6379', 'database' => '1']);
Core::init($cacheDriver, $databaseDriver);

$authorized = new Authorized();

$authorized->getAuthHTML('index.php');