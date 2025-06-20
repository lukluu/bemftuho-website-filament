<?php

use App\Models\Event;
use App\Models\Category;
use App\Models\Marchandise;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\EventController;
use App\Http\Controllers\BeritaController;
use App\Http\Controllers\WelcomeController;
use App\Http\Controllers\AspirasiController;
use App\Http\Controllers\Page\AboutController;
use App\Http\Controllers\PengumumanController;
use App\Http\Controllers\MarchandiseController;
use App\Http\Controllers\CategoryPostController;

Route::get('/', [WelcomeController::class, 'index'])->name('welcome');
Route::get('/profile', [AboutController::class, 'index'])->name('profile');
Route::get('/struktur-organisasi', [AboutController::class, 'struktur'])->name('struktur-organisasi');
Route::get('/kabinet/{kabinet}', [AboutController::class, 'show'])->name('kabinet.show');
Route::get('/pengumuman', [PengumumanController::class, 'index'])->name('pengumuman');
Route::get('/pengumuman/{pengumuman}', [PengumumanController::class, 'show'])->name('pengumuman.show');
Route::get('/event', [EventController::class, 'index'])->name('event');
Route::get('/event/{event}', [EventController::class, 'show'])->name('event.show');
Route::get('/aspirasi', [AspirasiController::class, 'index'])->name('aspirasi');
Route::get('/marchandise', [MarchandiseController::class, 'index'])->name('marchandise');
Route::get('/marchandise/{marchandise}', [MarchandiseController::class, 'show'])->name('marchandise.show');
Route::get('/berita', [BeritaController::class, 'index'])->name('berita');
Route::get('/berita/{category}/{post}', [BeritaController::class, 'show'])->name('berita.show');
Route::get('/category-post/{category}', [CategoryPostController::class, 'show'])->name('categoryPost.show');


Route::get('/{any}', function () {
    return view('page.404');
})->where('any', '.*');
