<?php

use App\Http\Controllers\getToken;
use App\Http\Controllers\validateController;
use App\Http\Controllers\SendFormController;
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

Route::view('/', 'form');

Route::post('/validate', [ValidateController::class, 'validateFormData'])->name('validate');
Route::get('/send', [SendFormController::class, 'send'])->name('send');
Route::get('/getToken', [GetToken::class, 'get'])->name('getToken');