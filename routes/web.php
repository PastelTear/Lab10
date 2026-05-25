<?php

declare(strict_types=1);

use App\Http\Controllers\AuthController;
use App\Http\Controllers\BookingController;
use App\Http\Controllers\CabinetController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\MasterclassController;
use Illuminate\Support\Facades\Route;

Route::get('/', [HomeController::class, 'index'])->name('home');

Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
Route::post('/login', [AuthController::class, 'login']);
Route::get('/register', [AuthController::class, 'showRegister'])->name('register');
Route::post('/register', [AuthController::class, 'register']);
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

Route::get('/category/{category}', [CategoryController::class, 'show'])->name('category.show');

Route::middleware(['auth', 'visitor'])->group(function (): void {
    Route::get('/book/{masterclass}/confirm', [BookingController::class, 'confirm'])->name('book.confirm');
    Route::post('/book/{masterclass}', [BookingController::class, 'store'])->name('book.store');
});

Route::middleware(['auth', 'leader'])->group(function (): void {
    Route::get('/cabinet', [CabinetController::class, 'index'])->name('cabinet');
    Route::get('/masterclass/create', [MasterclassController::class, 'create'])->name('masterclass.create');
    Route::post('/masterclass', [MasterclassController::class, 'store'])->name('masterclass.store');
    Route::get('/masterclass/{masterclass}/edit', [MasterclassController::class, 'edit'])->name('masterclass.edit');
    Route::put('/masterclass/{masterclass}', [MasterclassController::class, 'update'])->name('masterclass.update');
    Route::get('/busy-slots', [MasterclassController::class, 'busySlots'])->name('busy-slots');
});
