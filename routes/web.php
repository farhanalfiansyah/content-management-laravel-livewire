<?php

use App\Http\Controllers\PostController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\DashboardController;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\PageController;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Session;

Route::get('/', function () {
    return Auth::check() ? redirect('/dashboard') : redirect('/login');
});

Route::get('dashboard', [DashboardController::class, 'index'])
    ->middleware(['auth', 'verified'])
    ->name('dashboard');

Route::view('profile', 'profile')
    ->middleware(['auth'])
    ->name('profile');

// Language switching routes
Route::get('/language/switch/{locale}', function ($locale) {
    $supportedLocales = ['en', 'id', 'es', 'fr', 'de', 'ar'];
    
    if (!in_array($locale, $supportedLocales)) {
        return redirect()->back();
    }
    
    App::setLocale($locale);
    Session::put('locale', $locale);
    
    return redirect()->back();
})->name('language.switch');

// Posts routes
Route::middleware(['auth', 'verified'])->prefix('posts')->name('posts.')->group(function () {
    Route::get('/', [PostController::class, 'index'])->name('index');
    Route::get('/create', [PostController::class, 'create'])->name('create');
    Route::get('/{post}/edit', [PostController::class, 'edit'])->name('edit');
});

// Pages routes
Route::middleware(['auth', 'verified'])->prefix('pages')->name('pages.')->group(function () {
    Route::get('/', [\App\Http\Controllers\PageController::class, 'index'])->name('index');
    Route::get('/create', [\App\Http\Controllers\PageController::class, 'create'])->name('create');
    Route::get('/{page}/edit', [\App\Http\Controllers\PageController::class, 'edit'])->name('edit');
});

// Categories routes
Route::middleware(['auth', 'verified'])->prefix('categories')->name('categories.')->group(function () {
    Route::get('/', [\App\Http\Controllers\CategoryController::class, 'index'])->name('index');
    Route::get('/create', [\App\Http\Controllers\CategoryController::class, 'create'])->name('create');
    Route::get('/{category}/edit', [\App\Http\Controllers\CategoryController::class, 'edit'])->name('edit');
});



require __DIR__.'/auth.php';
