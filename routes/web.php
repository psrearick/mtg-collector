<?php

use App\Http\Middleware\RedirectIfAuthenticated;
use Illuminate\Foundation\Application;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;

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
    return Inertia::render('Welcome', [
        'canLogin'       => Route::has('login'),
        'canRegister'    => Route::has('register'),
        'laravelVersion' => Application::VERSION,
        'phpVersion'     => PHP_VERSION,
    ]);
})->middleware(RedirectIfAuthenticated::class);

Route::middleware(['auth', 'verified'])->group(function () {
    Route::get('/dashboard', function () {
        return Inertia::render('Dashboard');
    })->name('dashboard');

    Route::get('/profile', function () {
//        return Inertia::render('Profile');
    })->name('profile');

    Route::get('/settings', function () {
//        return Inertia::render('Settings');
    })->name('settings');

    Route::get('/collections', function () {
//        return Inertia::render('Collections');
    })->name('collections');

    Route::get('/cards', function () {
//        return Inertia::render('Cards');
    })->name('cards');
});

require __DIR__ . '/auth.php';
