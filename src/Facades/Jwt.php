<?php

namespace Sokeio\Facades;

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
 * @see \Sokeio\Facades\Jwt
 */
class Jwt extends Facade
{
    protected static function getFacadeAccessor()
    {
        return \Sokeio\Support\JWT\JwtManager::class;
    }
}
