<?php
//接受第三方平台信息

include '../../../autoload.php';

use OpenOauth\Core\Config;
use OpenOauth\Decryption;
use OpenOauth\Core\Tools;
use OpenOauth\NotifyProcessing;

$config = new Config();
$config->init(['component_app_id' => '第三方平台appId', 'component_app_secret' => '第三方平台appSecret', 'component_app_token' => '第三方平台appToken', 'component_app_key' => '第三方平台appKey']);

$cacheDriver    = new \OpenOauth\Core\CacheDriver\RedisDriver(['host' => '127.0.0.1', 'port' => '6379', 'database' => '1']);
$databaseDriver = new \OpenOauth\Core\DatabaseDriver\RedisDriver(['host' => '127.0.0.1', 'port' => '6379', 'database' => '1']);

$decryption = new Decryption($cacheDriver, $databaseDriver);
$xml_array  = $decryption->decryptionNoticeXML();

Tools::dataRecodes('xml_array', $xml_array, 'notice');

$notify_processing = new NotifyProcessing($cacheDriver, $databaseDriver);
switch ($xml_array['InfoType']) {
    case 'component_verify_ticket':
        //每10分钟 接收一次微信推送过来 当前 第三方平台的 ticket 并且缓存
        $notify_processing->componentVerifyTicket($xml_array);
        exit('SUCCESS');
        break;
    case 'authorized':
        //服务号授权
        $notify_processing->Authorized($xml_array);
        exit('SUCCESS');
        break;
    case 'unauthorized':
        //服务号取消授权
        $notify_processing->UnAuthorized($xml_array);
        exit('SUCCESS');
        break;
    case 'updateauthorized':
        //服务号更新授权
        $notify_processing->UpdateAuthorized($xml_array);
        exit('SUCCESS');
        break;
}

exit('FAIL');
