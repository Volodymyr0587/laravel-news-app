<?php

use App\Http\Controllers\AdminArticleController;
use App\Http\Controllers\ProfileController;
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

Route::get('/', function () {
    return view('welcome');
})->name('welcome');

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

});

Route::middleware('admin')->group(function () {
    Route::get('/articles', [AdminArticleController::class, 'index'])->name('admin.index');
    Route::get('/article/create', [AdminArticleController::class, 'create'])->name('admin.create');
    Route::get('article/edit/{article}', [AdminArticleController::class, 'edit'])->name('admin.edit');
    Route::post('/articles', [AdminArticleController::class, 'store'])->name('admin.store');
    Route::put('/article/{article}', [AdminArticleController::class, 'update'])->name('admin.update');
    Route::delete('/article/{article}', [AdminArticleController::class, 'destroy'])->name('admin.destroy');
});

require __DIR__.'/auth.php';
