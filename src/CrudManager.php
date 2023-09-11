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
    /**
     * Get config for form page
     *
     * @return \BytePlatform\ItemManager
     */
    public function FormPage()
    {
        return null;
    }
    /**
     * Get config for table page
     *
     * @return \BytePlatform\ItemManager
     */
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
    public static function RoutePage($url, $name = null, $crudClass = null)
    {
        if (!$crudClass)  $crudClass = get_called_class();
        if (!$name) $name = $url;
        /**
         * @var \BytePlatform\CrudManager $crud The class instance.
         */
        $crud = app($crudClass);
        if (!$crud) return;
        if ($tablePage = $crud->TablePage()) {
            Route::match($tablePage->getMethodType(), $url . 's', function () use ($crudClass) {
                $route = Route::current();
                $route->setParameter('manager', $crudClass);
                $route->setAction(array_merge($route->getAction(), RouteAction::parse($route->uri(), ['uses' => TablePage::class])));
                return $route->run();
            })->name($name . '-list');
        }

        if ($formPage = $crud->FormPage()) {
            Route::match($formPage->getMethodType(), $url . '-form/{dataId?}', function () use ($crudClass, $formPage) {
                $route = Route::current();
                $route->setParameter('manager', $crudClass);
                if ($formPage->getMethodType() == 'post') {
                    $route->setAction(array_merge($route->getAction(), RouteAction::parse($route->uri(), ['uses' => Form::class])));
                } else {
                    $route->setAction(array_merge($route->getAction(), RouteAction::parse($route->uri(), ['uses' => FormPage::class])));
                }
                return $route->run();
            })->name($name . '-form');
        }
        if ($formCustomKeys = $crud->getFormCustomKey()) {
            foreach ($formCustomKeys as $key) {
                $configCustomPage = $crud->getFormCustom($key);
                if ($configCustomPage) {
                    Route::match($configCustomPage->getMethodType(), $url . '-' . $key . '-form/{dataId?}', function () use ($crudClass, $key, $configCustomPage) {
                        $route = Route::current();
                        $route->setParameter('manager', $crudClass);
                        $route->setParameter('formCustom', $key);
                        if ($configCustomPage->IsTable()) {
                            $route->setAction(array_merge($route->getAction(), RouteAction::parse($route->uri(), ['uses' => TablePage::class])));
                        } else {
                            if ($configCustomPage->getMethodType() == 'post') {
                                $route->setAction(array_merge($route->getAction(), RouteAction::parse($route->uri(), ['uses' => Form::class])));
                            } else {
                                $route->setAction(array_merge($route->getAction(), RouteAction::parse($route->uri(), ['uses' => FormPage::class])));
                            }
                        }
                        return $route->run();
                    })->name($name  . '-' . $key . '-form');
                }
            }
        }
    }
}
