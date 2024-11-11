<?php

use Illuminate\Support\Facades\Route;
use Livewire\Livewire;
use Sokeio\Http\Controllers\FileManagerController;
use Sokeio\Http\Controllers\PlatformController;
use Sokeio\Platform;
use Sokeio\Theme;

Route::get('routes', function () {
    $routeCollection = Route::getRoutes();

    echo "<table style='width:100%'>";
    echo "<tr>";
    echo "<td width='10%'><h4>HTTP Method</h4></td>";
    echo "<td width='10%'><h4>Route</h4></td>";
    echo "<td width='10%'><h4>Name</h4></td>";
    echo "<td width='70%'><h4>Corresponding Action</h4></td>";
    echo "</tr>";
    foreach ($routeCollection as $value) {
        echo "<tr>";
        echo "<td>" . $value->methods()[0] . "</td>";
        echo "<td>" . $value->uri() . "</td>";
        echo "<td>" . $value->getName() . "</td>";
        echo "<td>" . $value->getActionName() . "</td>";
        echo "</tr>";
    }
    echo "</table>";
});
Platform::routeAdmin(function () {
    Route::get('/test', function () {
        return   Theme::getTheme()->getLayouts();
    });
    Route::get('/test2', function () {
        return   Platform::getLivewireComponents();
    });
}, true);
//id is slug
// id= abc/xyz/abc

Route::get('platform/{type}/screenshot/{id}', [PlatformController::class, 'bannerScreenshot'])
    ->where('id', '.*')
    ->name('platform.screenshot');

Route::get('models', function () {
    return Platform::getAllModel();
});

Route::get('livewires', function () {
    return Platform::getLivewireComponents();
});
Route::group([
    'prefix' => 'platform/file-manager',
    'as' => 'platform.file-manager.',
    'controller' => FileManagerController::class
], function () {
    Route::post('/', 'index')->name('index');
});
