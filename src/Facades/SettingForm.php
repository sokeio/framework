<?php

namespace BytePlatform\Facades;

use Illuminate\Support\Facades\Facade;

/**
 * 
 * @method static void Register($callback, $form = 'overview')
 * @method static mix getFormByKey($key= 'overview')
 * @method static array getForms()
 * @method static array getFormWithTitles()
 * 
 * 
 *
 * @see \BytePlatform\Facades\SettingForm
 * 
 */
/*
        use BytePlatform\Facades\SettingForm;

        SettingForm::Register(function (\BytePlatform\ItemManager $item) {
            return $item;
        });

 * */
class SettingForm extends Facade
{
    protected static function getFacadeAccessor()
    {
        return \BytePlatform\FormCollection::class;
    }
}
