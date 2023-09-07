<?php

namespace BytePlatform\Traits;

use ReflectionClass;

trait WithHelpers
{
    public function JsonParam($param)
    {
        return json_decode(str_replace("'", '"', urldecode($param)), true);
    }
    public function JsonParam64($param)
    {
        return $this->JsonParam($this->Base64Decode($param));
    }
    public function Base64Encode($text)
    {
        return base64_encode(urlencode($text ?? ''));
    }
    public function Base64Decode($hash)
    {
        return urldecode(base64_decode($hash ?? ''));
    }
    public function getPathDirFromClass($class)
    {
        $reflector = new ReflectionClass(get_class($class));
        return dirname($reflector->getFileName());
    }
}
