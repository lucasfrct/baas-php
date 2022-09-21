<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UserController;
use App\Http\Controllers\BankAccountController;
use App\Http\Controllers\DashboardController;

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

Route::get('/login', [UserController::class, 'loginCreate'])->name('user.login');
Route::post('/login', [UserController::class, 'loginValid'])->name('user.login');

Route::get('/signin', [UserController::class, 'signinCreate'])->name('user.signin');
Route::post('/signin', [UserController::class, 'signinStore'])->name('user.signin');

Route::get('/dashboard', [DashboardController::class, 'index'])->name('user.dashboard');

Route::resource('/bank-account', BankAccountController::class);