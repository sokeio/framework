<?php

namespace Sokeio\Platform;

use Illuminate\Support\Facades\Http;

class PlatformUpdater
{
    const VERSION_URL = 'repos/version';
    const DOWNLOAD_URL = 'repos/download';
    private $token = null;
    private DataInfo $item;
    private $lastVersion = null;
    public function __construct(DataInfo $item)
    {
        $this->$item = $item;
    }
    public function getId()
    {
        return $this->item->getId();
    }
    public function getBaseType()
    {
        return $this->item->getBaseType();
    }
    public function getName()
    {
        return $this->item->getName();
    }
    public function getVersion()
    {
        return $this->item->getVersion();
    }
    public function checkVersion()
    {
        $this->lastVersion = $this->getLastVersion();
        if ($this->lastVersion != $this->getVersion()) {
            return true;
        }
        return false;
    }
    public function getLastVersion()
    {
        Http::post(self::VERSION_URL, [
            'id' => $this->getId(),
            ''
        ]);
    }
}
