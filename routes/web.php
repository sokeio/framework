<?php

use BytePlatform\Livewire\ShortcodeSetting;
use BytePlatform\Support\Svg\EasySVG;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Route;
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

Route::group(['prefix' => '__byte__'], function () {
    if (env('BYTE_DEPLOYMENT_AUTO', false)) { //deployment
        Route::get('git-pull/{key}', function ($key) {
            if (env('BYTE_DEPLOYMENT_KEY') == $key) {
                run_cmd(base_path(''), 'git reset --hard HEAD');
                run_cmd(base_path(''), 'git pull');
                run_cmd(base_path(''), 'rm -rf bootstrap/cache/*.php');
                run_cmd(base_path(''), 'composer dump-autoload');
                if (env('BYTE_DEPLOYMENT_MIGRATE', false)) {
                    run_cmd(base_path(''), 'php artisan migrate');
                }
                Artisan::call('cache:clear');
                Artisan::call('config:clear');
                Artisan::call('view:clear');
                Artisan::call('event:clear');
                Artisan::call('route:clear');
                //composer dump-autoload
                \BytePlatform\Facades\Platform::makeLink();
            }
            return $key;
        })->name('byte.git-pull');
    }

    Route::get('{types}/screenshot/{id}', function ($types, $id) {

        $svg = EasySVG::Create(true);
        //1280 × 853 px
        $svg->addAttribute("width", "1280");
        $svg->addAttribute("height", "653");
        $svg->addAttribute("style", "background:#0054a6");
        $byteplatform = platform_by($types);
        if ($byteplatform && ($item = $byteplatform->find($id))) {
            if (File::exists($item->getPath('screenshot.png')))
                return response(file_get_contents($item->getPath('screenshot.png')))->header('Content-Type', 'image/png');
            $svg->addText(str($item->getTitle())->upper(), 'center', 200, ['fill' => '#fff']);
            $svg->addText('----- ' . $types . ' -----', 'center', 340, ['fill' => '#f59f00']);
        } else {
            $svg->addText('Not found', 'center', 'center');
        }
        return response($svg->asXML())->header('Content-Type', 'image/svg+xml');
    })->name('byte.screenshot');
    Route::post('component', [BytePlatform\Http\Controllers\PlatformController::class, 'getComponent']);
    Route::post('events', [BytePlatform\Http\Controllers\PlatformController::class, 'doEvents']);
    Route::post('webhook', [BytePlatform\Http\Controllers\PlatformController::class, 'doWebhooks']);
    Route::get('/', function () {
        return 'hello, now is ' . now();
    })->name('__byte__');
    Route::group(['prefix' => 'filemanager', 'middleware' => ['web', 'auth']], function () {
        \UniSharp\LaravelFilemanager\Lfm::routes();
    });
    Route::group(['middleware' => ['web', 'auth']], function () {
        Route::post('shortcode-setting/', ShortcodeSetting::class)->name('shortcode-setting');
    });
});
Route::get('/', route_theme(function () {
    return view_scope(apply_filters(PLATFORM_HOMEPAGE, 'byte::homepage'));
}))->name('homepage');
Route::get('tesst', function () {
    return view('byte::test-about')->withShortcodes();
});
