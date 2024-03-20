<?php

namespace Sokeio\Facades;

use Illuminate\Support\Facades\Facade;

/**
 * @method static string getName()
 * @method static string fileInfoJson()
 * @method static string hookFilterPath()
 * @method static string pathFolder()
 * @method static string getPath(string $path)
 * @method static string publicFolder()
 * @method static void loadApp()
 * @method static void registerApp()
 * @method static void bootApp()
 * @method static \Illuminate\Support\Collection<string, \Sokeio\Platform\DataInfo> getAll()
 * @method static \Sokeio\Platform\DataInfo find(string $name)
 * @method static bool has(string $name)
 * @method static void delete(string $name)
 * @method static void load(string $path)
 * @method static void addItem(string $path)
 * @method static string getUsed()
 * @method static void forgetUsed()
 * @method static void setUsed(string $name)
 * @method static void update(string $name)
 * @method static array getModels()
 * @see \Sokeio\Facades\Module
 */
class Module extends Facade
{
    protected static function getFacadeAccessor()
    {
        return \Sokeio\Platform\ModuleManager::class;
    }
}
