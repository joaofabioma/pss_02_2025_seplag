<?php

namespace PSS0225Seplag\Config;

class JwtConfig
{
    public static function getConfig()
    {
        return [
            'secret' => 'secret_secret_secret',
            'algorithm' => 'HS256',
            'expiration' => 3600 / 12
        ];
    }
}