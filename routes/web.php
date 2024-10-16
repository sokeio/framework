<?php

use Sokeio\Facades\Platform;
use Sokeio\Livewire\Setup;
use Sokeio\Support\Svg\EasySVG;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Route;
use Sokeio\Components\UI;
use Sokeio\Livewire\ViewUILab;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::group(['prefix' => '__sokeio__'], function () {
    if (env('SOKEIO_DEPLOYMENT_AUTO', false)) { //deployment
        Route::get('git-pull/{key}', function ($key) {
            if (env('SOKEIO_DEPLOYMENT_KEY') == $key) {
                runCmd(base_path(''), 'git reset --hard HEAD');
                runCmd(base_path(''), 'git pull');
                runCmd(base_path(''), 'rm -rf bootstrap/cache/*.php');
                runCmd(base_path(''), 'composer dump-autoload');
                if (env('SOKEIO_DEPLOYMENT_MIGRATE', false)) {
                    runCmd(base_path(''), 'php artisan migrate');
                }
                Artisan::call('cache:clear');
                Artisan::call('config:clear');
                Artisan::call('view:clear');
                Artisan::call('event:clear');
                Artisan::call('route:clear');
                //composer dump-autoload
                \Sokeio\Facades\Platform::makeLink();
            }
            return $key;
        })->name('sokeio.git-pull');
    }

    Route::get('{types}/screenshot/{id}', function ($types, $id) {

        $svg = EasySVG::create(true);
        //1280 × 853 px
        $svg->addAttribute("width", "1280");
        $svg->addAttribute("height", "653");
        $svg->addAttribute("style", "background:#0054a6");
        $sokeio = platformBy($types);
        if ($sokeio && ($item = $sokeio->find($id))) {
            if (File::exists($item->getPath('screenshot.png'))) {
                return response(file_get_contents($item->getPath('screenshot.png')))
                    ->header('Content-Type', 'image/png');
            }
            $svg->addText(str($item->getTitle())->upper(), 'center', 200, ['fill' => '#fff']);
            $svg->addText('----- ' . $types . ' -----', 'center', 340, ['fill' => '#f59f00']);
        } else {
            $svg->addText('Not found', 'center', 'center');
        }
        return response($svg->asXML())->header('Content-Type', 'image/svg+xml');
    })->name('sokeio.screenshot');

    Route::post('events', [Sokeio\Http\Controllers\PlatformController::class, 'doEvents']);
    Route::post('webhook', [Sokeio\Http\Controllers\PlatformController::class, 'doWebhooks']);
    Route::get('/', function () {
        return 'hello, now is ' . now();
    })->name('__sokeio__');
});
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
Route::get('labss', function () {
    return UI::getTagUI();
});
Route::get('test-layout', ViewUILab::class);
if (Platform::checkSetupUI()) {
    Route::get('/setup', Setup::class)->name('sokeio.setup');
}
