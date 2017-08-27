<?php

namespace OU;

class ClientIPAddressFinder
{
    /**
     * @param array $serverParams
     * @return string
     */
    public static function find(array $serverParams)
    {
        if (isset($serverParams['HTTP_X_FORWARDED_FOR']) && empty($serverParams['HTTP_X_FORWARDED_FOR']) == false) {
            return $serverParams['HTTP_X_FORWARDED_FOR'];
        } elseif (isset($serverParams['HTTP_CLIENT_IP']) && empty($serverParams['HTTP_CLIENT_IP']) == false) {
            return $serverParams['HTTP_CLIENT_IP'];
        } else {
            return $serverParams['REMOTE_ADDR'] ?? '';
        }
    }
}
