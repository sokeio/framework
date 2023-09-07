<?php

use Illuminate\Support\Facades\Artisan;
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

Route::get('/hello', function () {
    return view('welcome');
});


Route::get('/inilink', function () {
   return file_get_contents('https://cdn.postsrc.com/posts/98/aADlJJlUEz1B5vXPm9tJTcmvSbTwTHs9aUhflx6I.webp');
});