<?php
namespace OpenOauth\Core\Http;

class Http
{
    public static $error;

    public static function _post($apiUrl, $data)
    {
        $apiUrl = urldecode($apiUrl);
        $ch     = curl_init();

        if (is_array($data)) {
            if (!defined('JSON_UNESCAPED_UNICODE')) {
                // 解决php 5.3版本 json转码时 中文编码问题.
                $data = json_encode($data);
                $data = preg_replace("#\\\u([0-9a-f]{4})#ie", "iconv('UCS-2BE', 'UTF-8', pack('H4', '\\1'))", $data);
            } else {
                $data = json_encode($data, JSON_UNESCAPED_UNICODE);
            }
        }

        curl_setopt($ch, CURLOPT_URL, $apiUrl);
        curl_setopt($ch, CURLOPT_TIMEOUT, 60);
        curl_setopt($ch, CURLOPT_HEADER, true);
        curl_setopt($ch, CURLOPT_NOBODY, false);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);

        $res       = trim(curl_exec($ch));
        $http_code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        curl_close($ch);

        $header = '';
        $body   = $res;
        if ($http_code == 200) {
            list($header, $body) = explode("\r\n\r\n", $res, 2);
            $header = http_parse_headers($header);
        }

        $result           = [];
        $result['info']   = $body;
        $result['header'] = $header;
        $result['status'] = $http_code;

        return self::packData($result);
    }

    public static function _get($apiUrl)
    {
        $apiUrl = urldecode($apiUrl);
        $ch     = curl_init($apiUrl);
        curl_setopt($ch, CURLOPT_URL, $apiUrl);
        curl_setopt($ch, CURLOPT_TIMEOUT, 60);
        curl_setopt($ch, CURLOPT_HEADER, true);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);

        $res       = curl_exec($ch);
        $http_code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        curl_close($ch);

        $header = '';
        $body   = $res;
        if ($http_code == 200) {
            list($header, $body) = explode("\r\n\r\n", $res, 2);
            $header = http_parse_headers($header);
        }

        $result           = [];
        $result['info']   = $body;
        $result['header'] = $header;
        $result['status'] = $http_code;

        return self::packData($result);
    }

    /**
     * 对接口返回的数据进行验证和组装.
     *
     * @author Tian
     *
     * @date   2015-12-08
     *
     * @param array $apiReturnData 由_post|| _get方法返回的数据.
     *
     * @return array
     */
    private static function packData($apiReturnData)
    {
        $status     = $apiReturnData['status'];
        $returnData = $apiReturnData['info'];

        if ($status != 200 && empty($returnData)) {
            self::$error = '接口服务器连接失败.';

            return false;
        }

        $apiReturnData = json_decode($returnData, true);

        if ($status != 200 && !$apiReturnData) {
            self::$error = $returnData;

            return false;
        }

        if (isset($apiReturnData['errcode']) && $apiReturnData['errcode'] != 0) {
            $error = '错误码:' . $apiReturnData['errcode'] . ', 错误信息:' . $apiReturnData['errmsg'];

            self::$error = $error;

            return false;
        }

        return $apiReturnData;
    }

    //post 发送xml
    public static function _post_xml($url, $xml = null)
    {
        $ch = curl_init($url);
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $xml);
        $response = curl_exec($ch);
        if (curl_errno($ch)) {
            print curl_error($ch);
        }
        curl_close($ch);

        return $response;
    }
}