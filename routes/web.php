<?php

use App\Http\Controllers\AdminArticleController;
use App\Http\Controllers\ArticleIndexController;
use App\Http\Controllers\ArticleShowController;
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
});


Route::get('/home', ArticleIndexController::class)->name('welcome');
Route::get('/article/{id}', ArticleShowController::class)->name('articleShow');

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

});

Route::middleware('admin')->group(function () {
    Route::get('/articles/create', [AdminArticleController::class, 'create'])->name('admin.create');
    Route::get('/articles', [AdminArticleController::class, 'index'])->name('admin.index');
    Route::post('/article', [AdminArticleController::class, 'store'])->name('admin.store');
    Route::get('/article/edit/{article}', [AdminArticleController::class, 'edit'])->name('admin.edit');
    Route::put('/article/{article}', [AdminArticleController::class, 'update'])->name('admin.update');
    Route::delete('/article/{article}', [AdminArticleController::class, 'destroy'])->name('admin.destroy');
});

require __DIR__.'/auth.php';
