<?php

namespace BytePlatform\Facades;

use Illuminate\Support\Facades\Facade;

/**
 * 
 * @method static void setTestTimestamp(int $timestamp = null)
 * @method static mix registerKeys(array $name)
 * @method static string encode(array $payload, array $header = [])
 * @method static array decode(string $token, bool $verify = true)
 * @method static bool verify(string $token)
 * 
 *
 * @see \BytePlatform\Facades\Jwt
 */
class Jwt extends Facade
{
    protected static function getFacadeAccessor()
    {
        return \BytePlatform\Support\JWT\JwtManager::class;
    }
}
