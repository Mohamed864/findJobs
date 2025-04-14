<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\JobController;
use App\Http\Middleware\LogRequest;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\LoginController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\RegisterController;


Route::get('/', [HomeController::class, 'index'])->name('home');
//applying auth middleware
Route::resource('jobs',JobController::class)->middleware('auth')->only(['create','edit','update','destroy']);

Route::resource('jobs',JobController::class)->except(['create','edit','update','destroy']);

Route::middleware('guest')->group(function(){
    Route::get('/register', [RegisterController::class, 'register'])->name('register');

    Route::post('/register', [RegisterController::class, 'store'])->name('register.store');

    Route::get('/login', [LoginController::class, 'login'])->name('login');

    Route::post('/login', [LoginController::class, 'authenticate'])->name('login.authenticate');

});

Route::post('/logout', [LoginController::class, 'logout'])->name('logout');

Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard')->middleware('auth');

Route::put('/profile', [ProfileController::class, 'update'])->name('profile.update')->middleware('auth');

//in guest middleware it prevent me if i loggedin  to go to login or register page
