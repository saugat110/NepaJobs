<?php

use App\Http\Controllers\AccountController;
use App\Http\Controllers\HomeController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

// Route::get('/', function () {
//     return view('welcome');
// });


//returns home page
Route::get('/', [HomeController::class, 'index']) -> name('home');


//returns registration page
Route::get('/account/register', [AccountController::class, 'registration']) -> name('account.registration');
//handles registration data
Route::post('/account/process-register', [AccountController::class, 'processRegistration']) -> name('account.processRegistration');

//returns login page
Route::get('/account/login', [AccountController::class, 'login']) -> name('account.login');
//
