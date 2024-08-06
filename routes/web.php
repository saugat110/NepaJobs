<?php

use App\Http\Controllers\AccountController;
use App\Http\Controllers\HomeController;
use Illuminate\Support\Facades\Auth;
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
Route::get('/', [HomeController::class, 'index'])->name('home');

Route::get('/routes', function () {
    //returns registration page
    // Route::get('/account/register', [AccountController::class, 'registration']) -> name('account.registration');

    //handles registration data
    // Route::post('/account/process-register', [AccountController::class, 'processRegistration']) -> name('account.processRegistration');

    //returns login page
    // Route::get('/account/login', [AccountController::class, 'login']) -> name('account.login');

    //handle login data and authenticate user
    // Route::post('/account/process-login', [AccountController::class, 'processLogin']) -> name('account.processLogin');

    //return user profile page
    // Route::get('/account/profile', [AccountController::class, 'profile'])->name('account.profile');

    //logout user and redirect to login page
    // Route::get('/account/logout', [AccountController::class, 'logout'])->name('account.logout');
});

//routes with prefix account
Route::prefix('/account')->group(function () {

    //Guest routes, prevent authenticated users from accessing these routes
    Route::middleware('guest')->group(function () {

        //returns registration page
        Route::get('/register', [AccountController::class, 'registration'])->name('account.registration');

        //handles registration data
        Route::post('/process-register', [AccountController::class, 'processRegistration'])->name('account.processRegistration');

        //returns login page
        Route::get('/login', [AccountController::class, 'login'])->name('account.login');

        //handle login data and authenticate user
        Route::post('/process-login', [AccountController::class, 'processLogin'])->name('account.processLogin');
    });


    //authenticated routes
    Route::middleware('auth')->group(function () {
        //return user profile page
        Route::get('/profile', [AccountController::class, 'profile'])->name('account.profile');

        //logout user and redirect to login page
        Route::get('/logout', [AccountController::class, 'logout'])->name('account.logout');
    });

});



