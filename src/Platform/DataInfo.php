<?php

namespace Sokeio\Platform;

use Illuminate\Support\Facades\File;
use Sokeio\Laravel\JsonData;
use Illuminate\Support\Str;
use Sokeio\Facades\Platform;
use Illuminate\Support\Traits\Macroable;
use Sokeio\Platform\Concerns\WithDataInfoProperty;
use Sokeio\Platform\Concerns\WithDataInfoStatus;
use Sokeio\RouteEx;

class DataInfo extends JsonData
{
    use Macroable;
    use WithDataInfoProperty;
    use WithDataInfoStatus;
    private $updater = null;
    
    public function __construct($path, $parent)
    {
        parent::__construct([], $parent);
        $this['path'] = $path;
        $this['fileInfo'] =  $parent->fileInfoJson();
        $this['public'] =  $parent->publicFolder();
        $this['baseType'] = $parent->getName();
        $this->ReLoad();
        $this->updater = new PlatformUpdater($this);
    }
    public function ReLoad()
    {
        $temp = $this->CloneData();
        $this->loadJsonFromFile($temp['path'] . '/' .  $temp['fileInfo']);
        $this['path'] = $temp['path'];
        $this['fileInfo'] = $temp['fileInfo'];
        $this['public'] = $temp['public'];
        $this['baseType'] = $temp['baseType'];
        $this['key'] = basename($temp['path'], ".php");
    }
    public function __toString()
    {
        return $this->getName();
    }
    public function checkKeyValue($key, $value)
    {
        return $this[$key] == $value;
    }

    public function TriggerEvent($func)
    {
        $classOptionOperation = '\\' . $this->getNamespaceInfo() . '\\Option';
        if (!class_exists($classOptionOperation) && File::exists($this->getPath('src/Option.php'))) {
            includeFile($this->getPath('src/Option.php'));
        }
        if (class_exists($classOptionOperation) && method_exists($classOptionOperation, $func)) {
            call_user_func([$classOptionOperation, $func], $this);
        }
    }
    private $manifestData = null;
    public function getManifestData()
    {
        if (File::exists($this->getPath('public/build/manifest.json'))) {
            if (!$this->manifestData) {
                $this->manifestData = self::getJsonFromFile($this->getPath('public/build/manifest.json'));
            }
            return $this->manifestData;
        }
        return null;
    }
    public function getBase()
    {
        return  PlatformStatus::key($this->getBaseType());
    }
    public function getModels()
    {
        $arr = [];
        if ($files =  glob($this->getPath('src/Models') . '/*.*')) {
            foreach ($files as $itemFile) {
                $fileName =  preg_replace("/\.[^.]+$/", "", basename($itemFile));
                $arr[$this->getNamespaceInfo() . '\\Models\\' . $fileName] = $fileName;
            }
        }
        return $arr;
    }
    public function getLayouts()
    {
        $arr = [];
        if ($files =  glob($this->getPath('resources/views/layouts') . '/*.*')) {
            foreach ($files as $itemFile) {
                $fileName =  str_replace(".blade.php", "", basename($itemFile));
                $arr[] = $fileName;
            }
        }
        return $arr;
    }

    public function checkComposer()
    {
        return file_exists($this->getPath('composer.json'));
    }
    public function getRequired()
    {
        return data_get($this->getJsonFromFile($this->getPath('composer.json')), 'require',[]);
    }
    public function checkDump()
    {
        return file_exists($this->getPath('vendor/autoload.php'));
    }
    public function Dump()
    {
        runCmd($this->getPath(), 'composer dump -o -n -q');
    }
    public function checkName($name)
    {
        return Str::lower($this->getId())  ==  Str::lower($name) || Str::lower($this->name) == Str::lower($name);
    }
    public function delete()
    {
        File::deleteDirectory($this->getPath());
    }
    public function loadRoute()
    {
        RouteEx::Load($this->getPath('routes'));
    }
    public function doRegister()
    {
        $this->providers = Platform::registerComposer($this->getPath(), true);
    }
    public function doBoot()
    {
        foreach ($this->providers as $item) {
            $item->boot();
        }
    }

    public function getUpdater(): PlatformUpdater
    {
        return $this->updater;
    }
}
