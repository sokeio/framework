<?php

namespace Sokeio\Support\Platform\Concerns;

trait WithPlatformInfo
{
    public function getSystemName(): string
    {
        return  setting('SOKEIO_SYSTEM_NAME') ?? 'Sokeio Technology';
    }

    public function getSystemLogo(): string
    {
        return  setting('SOKEIO_SYSTEM_LOGO') ?? asset('platform/module/sokeio/sokeio.webp');
    }
    public function getVersion(): string
    {
        return  'v1.0.0';
    }
}
