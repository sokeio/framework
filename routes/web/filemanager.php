<?php

use Illuminate\Support\Facades\Route;

//, 'auth'
Route::group([
    'middleware' => ['web'],
    'prefix' => 'file-manager',
    'as' => 'filemanager.',
    'namespace' => 'Sokeio\Http\Controllers'
], function () {
    Route::post('/', 'FileManangerController@postIndex')->name('index');
    Route::post('upload', 'FileManangerController@postUpload')->name('upload');
});
