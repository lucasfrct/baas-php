<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UserController;
use App\Http\Controllers\BankAccountController;

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

Route::get('/', function () { return view('home'); });

Route::get('/home', function () { return view('home'); });

Route::get('/login', [UserController::class, 'loginForm']);
Route::post('/login', [UserController::class, 'loginValid']);

Route::get('/signin', [UserController::class, 'signinForm']);
Route::post('/signin', [UserController::class, 'signinStore']);

Route::resource('/bank-account', BankAccountController::class);