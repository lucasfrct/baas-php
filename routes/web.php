<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UserController;
use App\Http\Controllers\BankAccountController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\TransactionCheckController;

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

Route::get('/login', [UserController::class, 'loginCreate'])->name('login');
Route::post('/login', [UserController::class, 'loginValid'])->name('login');
Route::get('/logout', [UserController::class, 'logout'])->name('logout');

Route::get('/signin', [UserController::class, 'signinCreate'])->name('signin');
Route::post('/signin', [UserController::class, 'signinStore'])->name('signin');

Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard')->middleware('auth');
Route::post('/dashboard/transaction/check', [DashboardController::class, 'transactionCheck'])->name('transactionCheck')->middleware('auth');
Route::post('/dashboard/transaction/resume', [DashboardController::class, 'transactionResume'])->name('transactionResume')->middleware('auth');
Route::post('/dashboard/transaction/apply', [DashboardController::class, 'transactionApply'])->name('transactionApply')->middleware('auth');

Route::get('/dashboard/bankstatement', [DashboardController::class, 'bankStatement'])->name('bankStatement')->middleware('auth');

Route::resource('/bank-account', BankAccountController::class);