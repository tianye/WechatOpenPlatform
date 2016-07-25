<?php
//生成 授权页面

//http://applist-test-open.weflame.com/authorized.php

include '../../../autoload.php';

use OpenOauth\Authorized;
use OpenOauth\Core\Config;

$config = new Config();
$config->init(['component_app_id' => '第三方平台appId', 'component_app_secret' => '第三方平台appSecret', 'component_app_token' => '第三方平台appToken', 'component_app_key' => '第三方平台appKey']);

$authorized = new Authorized();
$authorized->getAuthHTML('index.php');