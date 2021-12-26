<?php

use App\App\Client\Controllers\CardCollectionsController;
use App\App\Client\Controllers\CardsController;
use App\App\Client\Controllers\CardSearchController;
use App\App\Client\Controllers\CollectionFoldersController;
use App\App\Client\Controllers\CollectionFoldersMoveController;
use App\App\Client\Controllers\CollectionFoldersTreeController;
use App\App\Client\Controllers\CollectionsController;
use App\App\Client\Controllers\CollectionsEditSearchController;
use App\App\Client\Controllers\MoveCollectionsController;
use App\App\Client\Controllers\PublicCollectionsController;
use App\App\Client\Controllers\RemoveCardsController;
use App\App\Client\Controllers\SetCollectionCardsController;
use App\App\Client\Controllers\SetCollectionsController;
use App\App\Client\Controllers\SymbolsController;
use App\App\Client\Controllers\UserPasswordController;
use App\App\Client\Controllers\UserProfileInformationController;
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

Route::get('/public-collections/{collection}', [PublicCollectionsController::class, 'show'])
    ->name('public-collections.show')
    ->middleware('isPublic');

Route::middleware(['auth', 'verified'])->group(function () {
    Route::get('/dashboard', function () {
        return Inertia::render('Dashboard');
    })->name('dashboard');

    Route::prefix('user')->group(function () {
        Route::get('/profile', function () {
            return Inertia::render('User/Profile');
        })->name('user.profile');
        Route::put('/user-profile-information', [UserProfileInformationController::class, 'update'])->name('user-profile-information.update');
        Route::put('/user-password', [UserPasswordController::class, 'update'])->name('user-password.update');

        Route::get('/settings', function () {
            return Inertia::render('User/Settings');
        })->name('user.settings');
    });

    Route::prefix('collections')->group(function () {
        Route::get('collections/index', [CollectionsController::class, 'allIndex']);
        Route::resource('collections/folders', CollectionFoldersController::class)->names([
            'create'    => 'collection-folder.create',
            'show'      => 'collection-folder.show',
            'store'     => 'collection-folder.store',
        ]);
        Route::get('collections/folders-tree', [CollectionFoldersTreeController::class, 'index'])->name('collection-folder-tree.index');
        Route::patch('collections/move', [CollectionFoldersMoveController::class, 'update'])->name('collection-folder-move.update');
        Route::resource('collections', CollectionsController::class);
        Route::post('collections/{collection}/edit/search', [CollectionsEditSearchController::class, 'store'])->name('collection-edit-search.store');
        Route::get('collections/{collection}/set/edit', [SetCollectionsController::class, 'edit'])->name('collection-set.edit');
        Route::post('collections/{collection}/set/edit', [SetCollectionsController::class, 'store'])->name('collection-set.update');
        Route::get('collections/{collection}/set/card/{card}', [SetCollectionCardsController::class, 'show'])->name('collection-card-set.edit');
        Route::post('cards/move-collection', [MoveCollectionsController::class, 'store'])->name('move-collection.store');
        Route::delete('cards/remove-card', [RemoveCardsController::class, 'destroy'])->name('remove-card.destroy');
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
