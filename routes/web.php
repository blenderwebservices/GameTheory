<?php

use Illuminate\Support\Facades\Route;

use App\Http\Controllers\HomeController;
use App\Http\Controllers\LanguageController;
use App\Http\Controllers\SimulationController;

// Redirect default login route to filament admin login
Route::get('/login', function () {
    return redirect()->route('filament.admin.auth.login');
})->name('login');

use App\Http\Controllers\AuthController;
Route::get('/register', [AuthController::class, 'showRegistrationForm'])->name('register');
Route::post('/register', [AuthController::class, 'register'])->name('register.store');

Route::post('/logout', function () {
    auth()->logout();
    request()->session()->invalidate();
    request()->session()->regenerateToken();
    return redirect('/');
})->name('logout');

Route::get('/', function () {
    return redirect()->route('home');
});

Route::get('/home', [HomeController::class, 'index'])->name('home');

Route::group(['middleware' => 'auth'], function () {
    Route::get('/simulations', [SimulationController::class, 'index'])->name('simulation.index');
    Route::get('/simulation/{gameScenario}', [SimulationController::class, 'show'])->name('simulation.show');
    Route::post('/simulation/{gameScenario}/save', [SimulationController::class, 'update'])->name('simulation.update');
    Route::post('/simulation/{gameScenario}/reset', [SimulationController::class, 'reset'])->name('simulation.reset');
});

Route::get('/lang/{locale}', [LanguageController::class, 'switch'])->name('lang.switch');
