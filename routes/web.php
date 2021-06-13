<?php

use App\App\Client\Controllers\CardsController;
use App\App\Client\Controllers\CollectionsController;
use App\App\Client\Middleware\RedirectIfAuthenticated;
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

    Route::prefix('user')->group(function () {
        Route::get('/profile', function () {
            return Inertia::render('User/Profile');
        })->name('user.profile');

        Route::get('/settings', function () {
            return Inertia::render('User/Settings');
        })->name('user.settings');
    });

    Route::prefix('collections')->group(function () {
        Route::resource('collections', CollectionsController::class);
    });

    Route::prefix('cards')->group(function () {
//        Route::get('/cards', function () {
//        return Inertia::render('Cards/Index');
//        })->name('cards.index');
        Route::resource('cards', CardsController::class);
    });
});

require __DIR__ . '/auth.php';
