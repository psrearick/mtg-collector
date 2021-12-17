<?php

use App\App\Client\Controllers\CardCollectionsController;
use App\App\Client\Controllers\CardsController;
use App\App\Client\Controllers\CardSearchController;
use App\App\Client\Controllers\CollectionsController;
use App\App\Client\Controllers\CollectionsEditSearchController;
use App\App\Client\Controllers\SetCollectionCardsController;
use App\App\Client\Controllers\SetCollectionsController;
use App\App\Client\Controllers\SymbolsController;
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
        Route::post('collections/{collection}/edit/search', [CollectionsEditSearchController::class, 'store'])->name('collection-edit-search.store');
        Route::get('collections/{collection}/set/edit', [SetCollectionsController::class, 'edit'])->name('collection-set.edit');
        Route::post('collections/{collection}/set/edit', [SetCollectionsController::class, 'store'])->name('collection-set.update');
        Route::get('collections/{collection}/set/card/{card}', [SetCollectionCardsController::class, 'show'])->name('collection-card-set.edit');
    });

    Route::prefix('cards')->group(function () {
        Route::get('search', [CardSearchController::class, 'index'])->name('cards.search');
        Route::resource('cards', CardsController::class);
    });

    Route::prefix('card-collections')->group(function () {
        Route::resource('card-collections', CardCollectionsController::class);
    });

    Route::prefix('api')->group(function () {
        Route::post('brace-content', [SymbolsController::class, 'index'])->name('braces.content');
    });
});

require __DIR__ . '/auth.php';
