<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\DiagnosaController;
use App\Http\Controllers\MedisController;
use App\Http\Controllers\MateriController;

Route::get('/', [HomeController::class, 'index'])->name('home');
Route::get('/about', [HomeController::class, 'about'])->name('about');
Route::get('/gallery', [HomeController::class, 'gallery'])->name('gallery');
Route::get('/contact', [HomeController::class, 'contact'])->name('contact');

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/user', [UserController::class, 'index'])->name('users.index');
    Route::get('/user/pengajar', [UserController::class, 'pengajar'])->name('users.pengajar');
    Route::get('/user/create', [UserController::class, 'create'])->name('users.create');
    Route::get('/user/create-pengajar', [UserController::class, 'createPengajar'])->name('users.create-pengajar');
    Route::get('/user/data', [UserController::class, 'data'])->name('users.data');
    Route::get('/user/data-pengajar', [UserController::class, 'dataPengajar'])->name('users.dataPengajar');
    Route::get('/users/edit/{id}', [UserController::class, 'edit'])->name('users.edit');
    Route::delete('/users/hapus/{id}', [UserController::class, 'destroy'])->name('users.destroy');
    Route::post('/user/add', [UserController::class, 'store'])->name('users.store');
    Route::post('/user/add-pengajar', [UserController::class, 'storePengajar'])->name('users.storePengajar');

    Route::get('/diagnosa', [DiagnosaController::class, 'index'])->name('diagnosa');
    Route::post('/diagnosa/add', [DiagnosaController::class, 'store'])->name('diagnosa.store');
    Route::get('/diagnosa/data', [DiagnosaController::class, 'data'])->name('diagnosa.data');

    Route::get('/medis', [MedisController::class, 'index'])->name('medis');
    Route::get('/medis/create', [MedisController::class, 'create'])->name('medis.create');
    Route::get('/medis/data', [MedisController::class, 'data'])->name('medis.data');
    Route::get('/autocomplete-user', [MedisController::class, 'autocomplete'])->name('user.autocomplete');
    Route::get('/history', [MedisController::class, 'history'])->name('medis.history');
    Route::post('/medis/add', [MedisController::class, 'store'])->name('medis.store');

    Route::get('/materi', [MateriController::class, 'index'])->name('materi');
    Route::get('/materi/user', [MateriController::class, 'materiUser'])->name('materi.user');
    Route::get('/medis/create', [MedisController::class, 'create'])->name('medis.create');
    Route::get('/materi/data', [MateriController::class, 'data'])->name('materi.data');
    Route::get('/materi/dataMateri', [MateriController::class, 'dataMateri'])->name('materi.dataMateri');
    Route::post('/materi/add', [MateriController::class, 'store'])->name('materi.store');

    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__ . '/auth.php';
