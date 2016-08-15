# WechatOpenPlatform
微信开放平台
<p align="center">
<a href="https://travis-ci.org/tianye/WechatOpenPlatform"><img src="https://travis-ci.org/tianye/WechatOpenPlatform.svg?branch=master" alt="Build Status"></a>
<a href="https://packagist.org/packages/tianye/open-oauth"><img src="https://poser.pugx.org/tianye/open-oauth/v/stable.svg" alt="Latest Stable Version"></a>
<a href="https://packagist.org/packages/tianye/open-oauth"><img src="https://poser.pugx.org/tianye/open-oauth/v/unstable.svg" alt="Latest Unstable Version"></a>
<a href="https://scrutinizer-ci.com/g/tianye/WechatOpenPlatform/build-status/master"><img src="https://scrutinizer-ci.com/g/tianye/WechatOpenPlatform/badges/build.png?b=master" alt="Build Status"></a>
<a href="https://scrutinizer-ci.com/g/tianye/WechatOpenPlatform/?branch=master"><img src="https://scrutinizer-ci.com/g/tianye/WechatOpenPlatform/badges/quality-score.png?b=master" alt="Scrutinizer Code Quality"></a>
<a href="https://scrutinizer-ci.com/g/tianye/WechatOpenPlatform/?branch=master"><img src="https://scrutinizer-ci.com/g/tianye/WechatOpenPlatform/badges/coverage.png?b=master" alt="Code Coverage"></a>
<a href="https://packagist.org/packages/tianye/open-oauth"><img src="https://poser.pugx.org/tianye/open-oauth/downloads" alt="Total Downloads"></a>
<a href="https://packagist.org/packages/tianye/open-oauth"><img src="https://poser.pugx.org/tianye/open-oauth/license" alt="License"></a>
</p>

####一句话安装:
`composer require tianye/open-oauth dev-master -vvv`

接入其他 第三方微信SDK包方案:
```
$config = new Config();
$config->init(['component_app_id' => '第三方平台appId', 'component_app_secret' => '第三方平台appSecret', 'component_app_token' => '第三方平台appToken', 'component_app_key' => '第三方平台appKey']);

$cacheDriver    = new \OpenOauth\Core\CacheDriver\RedisDriver(['host' => '127.0.0.1', 'port' => '6379', 'database' => '1']);
$databaseDriver = new \OpenOauth\Core\DatabaseDriver\RedisDriver(['host' => '127.0.0.1', 'port' => '6379', 'database' => '1']);
Core::init($cacheDriver, $databaseDriver);

初始化 配置 和 选择缓存驱动 和 数据存储 是 必须的~
```


`src/Authorized.php` 中 `getAuthorizerAccessToken($authorizer_app_id)` 方法 传入 要获取 access_token 的 服务号 APP_ID 即可获取 到 acess_token 已经加入到了缓存中

注意 走 授权的 方法有改变 可以参照:
`src/OpenAuth` 中 的 方案 使用方式 `demo/oauth_demo` 中

[开放平台-资源中心-授权流程技术说明](https://open.weixin.qq.com/cgi-bin/showdocument?action=dir_list&t=resource/res_list&verify=1&id=open1453779503&token=&lang=zh_CN)


