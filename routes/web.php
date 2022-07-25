<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\LinkController;
use App\Http\Controllers\LinkCurtoController;

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

Route::get('/', function () {
    return redirect('/links');
});

Route::resource('links', LinkController::class);

Route::get('/redirect/{codigo}', [LinkCurtoController::class, 'redirecionamento']);
Route::resource('links-curtos', LinkCurtoController::class);
