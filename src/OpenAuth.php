<?php
namespace OpenOauth;

use OpenOauth\Core\Core;
use OpenOauth\Core\Http\Http;
use OpenOauth\Core\CacheDriver\BaseDriver as CacheBaseDriver;
use OpenOauth\Core\DatabaseDriver\BaseDriver as DatabaseBaseDriver;

/**
 * 微信Auth相关接口.
 *
 * @author Tian.
 */
class OpenAuth extends Core
{
    const API_URL       = 'https://open.weixin.qq.com/connect/oauth2/authorize';
    const CODE_GET_USER = 'https://api.weixin.qq.com/sns/oauth2/component/access_token';
    const API_USER_INFO = 'https://api.weixin.qq.com/sns/userinfo';

    protected $authorizedUser;
    private   $authorized_app_id;

    /**
     * AuthApi constructor.
     *
     * @param $authorized_app_id
     */
    public function __construct($authorized_app_id)
    {
        parent::__construct();

        $this->authorized_app_id = $authorized_app_id;
    }

    /**
     * 获取当前URL
     *
     * @return string
     */
    public static function current()
    {
        $protocol = (!empty($_SERVER['HTTPS'])
            && $_SERVER['HTTPS'] !== 'off'
            || $_SERVER['SERVER_PORT'] === 443) ? 'https://' : 'http://';

        if (isset($_SERVER['HTTP_X_FORWARDED_HOST'])) {
            $host = $_SERVER['HTTP_X_FORWARDED_HOST'];
        } else {
            $host = $_SERVER['HTTP_HOST'];
        }

        return $protocol . $host . $_SERVER['REQUEST_URI'];
    }

    /**
     * 生成outh URL
     *
     * @param string $to
     * @param string $scope
     * @param string $state
     *
     * @return string
     */
    public function url($to = null, $scope = 'snsapi_userinfo', $state = 'STATE')
    {
        $to !== null || $to = $this->current();

        $queryStr = [
            'appid'           => $this->authorized_app_id,
            'redirect_uri'    => $to,
            'response_type'   => 'code',
            'scope'           => $scope,
            'state'           => $state,
            'component_appid' => $this->configs->component_app_id,
        ];

        return self::API_URL . '?' . http_build_query($queryStr) . '#wechat_redirect';
    }

    /**
     * 直接跳转
     *
     * @param string $to
     * @param string $scope
     * @param string $state
     */
    public function redirect($to = null, $scope = 'snsapi_userinfo', $state = 'STATE')
    {
        header('Location:' . $this->url($to, $scope, $state));

        exit;
    }

    /**
     * 获取已授权用户
     *
     * @return array $user
     */
    public function user()
    {
        if ($this->authorizedUser || !isset($_GET['state']) || (!$code = isset($_GET['code']) ? $_GET['code'] : false) && isset($_GET['state'])) {
            return $this->authorizedUser;
        }

        $user = $this->getUser($code);

        return $this->authorizedUser = $user;
    }

    /**
     * 通过授权获取用户
     *
     * @param null   $to
     * @param string $scope
     * @param string $state
     *
     * @return array
     */
    public function authorize($to = null, $scope = 'snsapi_userinfo', $state = 'STATE')
    {
        if (!isset($_GET['state']) && (!$code = isset($_GET['code']) ? $_GET['code'] : false)) {
            $this->redirect($to, $scope, $state);
        }

        return $this->user();
    }

    /**
     * 获取用户信息
     *
     * @param string $code
     *
     * @return array
     */
    public function getUser($code)
    {

        $queryStr = [
            'appid'                  => $this->authorized_app_id,
            'grant_type'             => 'authorization_code',
            'component_appid'        => $this->configs->component_app_id,
            'code'                   => $code,
            'component_access_token' => $this->getComponentAccessToken(),
        ];

        $query_data = http_build_query($queryStr);

        $res = Http::_get(self::CODE_GET_USER . '?' . $query_data);

        return $res;
    }

    /**
     * 获取大授权 用户信息
     *
     * @param        $access_token
     * @param        $openid
     * @param string $lang
     *
     * @return array
     */
    public function getUserInfo($access_token, $openid, $lang = 'zh_CN')
    {
        $queryStr = [
            'access_token' => $access_token,
            'openid'       => $openid,
            'lang'         => $lang,
        ];

        $query_data = http_build_query($queryStr);

        $res = Http::_get(self::API_USER_INFO . '?' . $query_data);

        return $res;
    }
}
