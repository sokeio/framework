<?php

namespace Sokeio\Core\Concerns;

use ReflectionClass;

trait WithHelpers
{
    public function jsonParam($param)
    {
        return json_decode(str_replace("'", '"', urldecode($param)), true);
    }
    public function jsonParam64($param)
    {
        return $this->jsonParam($this->base64Decode($param));
    }
    public function base64Encode($text)
    {
        return base64_encode(urlencode($text ?? ''));
    }
    public function base64Decode($hash)
    {
        return urldecode(base64_decode($hash ?? ''));
    }
    public function getPathDirFromClass($class)
    {
        $reflector = new ReflectionClass(get_class($class));
        return dirname($reflector->getFileName());
    }
}
