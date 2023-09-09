<?php

namespace BytePlatform;

use BytePlatform\Livewire\FormPage;
use BytePlatform\Livewire\TablePage;
use Illuminate\Routing\RouteAction;
use Illuminate\Support\Facades\Route;

class CrudManager
{
    public function __construct()
    {
        $this->SetupFormCustom();
    }
    public function FormPage()
    {
        return null;
    }
    public function TablePage()
    {
        return null;
    }
    private $formCustom = [];

    public function FormCustom($key, $formSetup)
    {
        $this->formCustom[$key] = $formSetup;
        return $this;
    }
    public function SetupFormCustom()
    {
    }
    public function getFormCustom($key)
    {
        return $this->formCustom[$key]();
    }
    private function getFormCustomKey()
    {
        return array_keys($this->formCustom);
    }
    public static function RoutePage($url, $isForm = true, $name = null, $crudClass = null)
    {
        if (!$crudClass)  $crudClass = get_called_class();
        if (!$name)  $name = $url;
        /**
         * @var \BytePlatform\CrudManager $crud The class instance.
         */
        $crud = app($crudClass);
        if (!$crud) return;
        Route::get($url . 's', function () use ($crudClass) {
            $route = Route::current();
            $route->setParameter('manager', $crudClass);
            $route->setAction(array_merge($route->getAction(), RouteAction::parse($route->uri(), ['uses' => TablePage::class])));
            return $route->run();
        })->name($name . '-list');
        if ($isForm) {
            Route::post($url . '-form/{dataId?}', function () use ($crudClass) {
                $route = Route::current();
                $route->setParameter('manager', $crudClass);
                $route->setAction(array_merge($route->getAction(), RouteAction::parse($route->uri(), ['uses' => FormPage::class])));
                return $route->run();
            })->name($name . '-form');
            if ($crudClass && $crud = app($crudClass)) {
                foreach ($crud->getFormCustomKey() as $key) {
                    Route::post($url . '-' . $key . '-form/{dataId?}', function () use ($crudClass, $key, $crud) {
                        $route = Route::current();
                        $route->setParameter('manager', $crudClass);
                        $route->setParameter('formCustom', $key);
                        if ($crud->getFormCustom($key)->IsTable()) {
                            $route->setAction(array_merge($route->getAction(), RouteAction::parse($route->uri(), ['uses' => TablePage::class])));
                        } else {
                            $route->setAction(array_merge($route->getAction(), RouteAction::parse($route->uri(), ['uses' => FormPage::class])));
                        }
                        return $route->run();
                    })->name($name  . '-' . $key . '-form');
                }
            }
        }
    }
}
