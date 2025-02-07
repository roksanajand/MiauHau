<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\CommentController;
use App\Http\Controllers\BookController;
use App\Http\Controllers\AnimalController;
use App\Http\Controllers\SearchController;
use App\Http\Controllers\GuestAndLoggedPhotoCarouselController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\LikeController;

Route::get('/', [GuestAndLoggedPhotoCarouselController::class, 'index']);

// Trasa dla dashboardu z zabezpieczeniem middlewarem auth i verified
Route::get('/dashboard', [DashboardController::class, 'index'])
    ->middleware(['auth', 'verified'])
    ->name('dashboard');

// Trasa dla stopki - dostępna na każdej stronie
//Route::get('/footer', [FooterController::class, 'show'])->name('footer.show');

// Trasy tylko dla zalogowanych użytkowników
Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    Route::resource('books', BookController::class);

    Route::get('/add-animal', [AnimalController::class, 'create'])->name('add-animal');
    Route::post('/add-animal', [AnimalController::class, 'store'])->name('add-animal-post');
    Route::resource('animals', AnimalController::class)->except(['create','post']);

    // Trasy dla usuwania zwierząt
    Route::get('animals/{id}/delete', [AnimalController::class, 'confirmDelete'])->name('animals.confirmDelete');
    Route::delete('animals/{id}', [AnimalController::class, 'destroy'])->name('animals.delete');

    Route::get('animals/{id}/edit', [AnimalController::class, 'edit'])->name('animals.edit');

    Route::get('/admin/animals/{id}/history', [AdminController::class, 'showChangeHistory'])->name('admin.animals.history');

    Route::get('/search', [SearchController::class, 'index'])->name('search');
});

Route::middleware(['auth'])->group(function () {
    Route::get('/admin/animals', [AdminController::class, 'index'])->name('admin.animals');
    Route::post('/admin/animals/{id}/approve', [AdminController::class, 'approveAnimal'])->name('admin.animals.approve');
    Route::post('/admin/animals/{id}/reject', [AdminController::class, 'rejectAnimal'])->name('admin.animals.reject');
    Route::post('/animals/{animal}/like', [LikeController::class, 'like'])->name('animals.like');
    Route::post('/animals/{animal}/unlike', [LikeController::class, 'unlike'])->name('animals.unlike');
    Route::post('/animals/{id}/like', [LikeController::class, 'like'])->name('animals.like');
    Route::post('/animals/{id}/unlike', [LikeController::class, 'unlike'])->name('animals.unlike');

});

// Dodatkowe trasy dla komentarzy
Route::resource('/comments', CommentController::class);

// Załadowanie pliku z dodatkowymi trasami auth
require __DIR__ . '/auth.php';
