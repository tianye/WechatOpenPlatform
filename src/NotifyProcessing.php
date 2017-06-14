<?php

namespace OpenOauth;

use OpenOauth\Core\Core;

class NotifyProcessing extends Core
{
    /**
     * componentVerifyTicket
     *
     * @param $xml_array
     *
     * @return mixed
     */
    public function componentVerifyTicket($xml_array)
    {
        $key = 'component_verify_ticket:' . $xml_array['AppId'];
        self::$cacheDriver->_set($key, $xml_array['ComponentVerifyTicket'], 0);
        $component_verify_ticket = $xml_array['ComponentVerifyTicket'];

        return $component_verify_ticket;
    }

    /**
     * Authorized
     *
     * @param $xml_array
     *
     * @return mixed
     */
    public function Authorized($xml_array)
    {
        $key = 'authorized:' . $xml_array['AppId'] . ':' . $xml_array['AuthorizerAppid'];
        self::$databaseDriver->_set($key, $xml_array);

        return $xml_array;
    }

    /**
     * UpdateAuthorized
     *
     * @param $xml_array
     *
     * @return mixed
     */
    public function UpdateAuthorized($xml_array)
    {
        $key = 'authorized:' . $xml_array['AppId'] . ':' . $xml_array['AuthorizerAppid'];
        self::$databaseDriver->_set($key, $xml_array);

        return $xml_array;
    }

    /**
     * @param $xml_array
     *
     * @return mixed
     */
    public function UnAuthorized($xml_array)
    {
        $key = 'query_auth:' . $this->configs->component_app_id . ':' . $xml_array['AuthorizerAppid'];

        $query_auth_info = self::$databaseDriver->_get($key);

        if (!empty($query_auth_info)) {
            $query_auth_info['authorization_state'] = 'unauthorized';

            self::$databaseDriver->_set($key, $query_auth_info);
        }

        return $xml_array;
    }
}