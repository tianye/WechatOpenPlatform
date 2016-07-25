<?php
namespace OpenOauth;

use OpenOauth\Core\Core;
use OpenOauth\Core\Tools;
use OpenOauth\Core\WechatCode\WXBizMsgCrypt;

class Decryption extends Core
{
    public function decryptionNoticeXML()
    {
        //接收微信的xml 消息
        $xml = file_get_contents("php://input");

        $msg_signature = $_GET['msg_signature'];
        $timestamp     = $_GET['timestamp'];
        $nonce         = $_GET['nonce'];

        if (!is_string($xml) || $xml == '') {
            $this->setError('xml参数错误');

            return false;
        }

        if (!is_string($msg_signature) || $xml == '' || !is_numeric($timestamp) || $timestamp <= 0 || !is_string($nonce) || $nonce == '') {
            $this->setError('签名参数错误');

            return false;
        }

        $verify_signature = Tools::verifySignature($this->configs->component_app_token);

        if (!$verify_signature) {
            $this->setError('签名验证失败');

            return false;
        }

        //解密XML
        $msgCrypt = new WXBizMsgCrypt($this->configs->component_app_token, $this->configs->component_app_key, $this->configs->component_app_id);
        $xmlInfo  = '';

        $errCode = $msgCrypt->decryptMsg($msg_signature, $timestamp, $nonce, $xml, $xmlInfo);

        if ($errCode == 0) {
            $xml_array = json_decode(json_encode(simplexml_load_string($xmlInfo, 'SimpleXMLElement', LIBXML_NOCDATA)), true);

            return $xml_array;
        } else {
            return false;
        }
    }
}