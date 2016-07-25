<?php
namespace OpenOauth\Core\Exceptions;

use OpenOauth\Core\Exception;

class ConfigMistakeException extends Exception
{
    function __construct($message = '丢失参数 component_app_id || component_app_secret || component_app_token || component_app_key')
    {
        parent::__construct($message);
    }
}