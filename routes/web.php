<?php

use Illuminate\Support\Facades\Route;

use App\Http\Controllers\HomeController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\DiagnosaController;
use App\Http\Controllers\MedisController;
use App\Http\Controllers\MateriController;
use App\Http\Controllers\BeritaController;

/*
|--------------------------------------------------------------------------
| Public pages (tanpa login)
|--------------------------------------------------------------------------
*/

Route::get('/',                [HomeController::class, 'index'])->name('home');
Route::get('/about',           [HomeController::class, 'about'])->name('about');
Route::get('/news',            [HomeController::class, 'berita'])->name('news');
Route::get('/gallery',         [HomeController::class, 'gallery'])->name('gallery');
Route::get('/struktur',         [HomeController::class, 'struktur'])->name('struktur');
Route::get('/contact',         [HomeController::class, 'contact'])->name('contact');
Route::get('/detail-berita/{id}', [BeritaController::class, 'show'])->name('berita.show');

/*
|--------------------------------------------------------------------------
| Dashboard (wajib login + verified)
|--------------------------------------------------------------------------
*/
Route::get('/dashboard', fn() => view('dashboard'))
    ->middleware(['auth', 'verified'])
    ->name('dashboard');

/*
|--------------------------------------------------------------------------
| Authenticated area
|--------------------------------------------------------------------------
*/
Route::middleware('auth')->group(function () {

    /*
    |----------------------------------------------------------------------
    | ROUTES BERSAMA (semua role boleh mengakses)
    |----------------------------------------------------------------------
    | Print PDF medis: admin boleh semua; user hanya miliknya (cek di controller).
    */
    Route::get('/medis/{medis}/print/preview',  [MedisController::class, 'printPreview'])->name('medis.print.preview');
    Route::get('/medis/{medis}/print/stream',   [MedisController::class, 'printStream'])->name('medis.print.stream');
    Route::get('/medis/{medis}/print/download', [MedisController::class, 'printDownload'])->name('medis.print.download');

    // Profile (default dari Breeze/Jetstream)
    Route::get('/profile',  [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    /*
    |----------------------------------------------------------------------
    | ADMIN ONLY
    |----------------------------------------------------------------------
    */
    Route::get('/medis/data',       [MedisController::class, 'data'])->name('medis.data');
    Route::middleware('role:admin')->group(function () {

        // -------- Data Medis (kelola semua)
        Route::get('/medis',            [MedisController::class, 'index'])->name('medis');
        Route::get('/medis/create',     [MedisController::class, 'create'])->name('medis.create');
        Route::post('/medis/add',       [MedisController::class, 'store'])->name('medis.store');
        Route::get('/medis/{medis}/edit', [MedisController::class, 'edit'])->name('medis.edit');
        Route::put('/medis/{medis}',    [MedisController::class, 'update'])->name('medis.update');
        Route::delete('/medis/{medis}', [MedisController::class, 'destroy'])->name('medis.destroy');

        // Autocomplete user saat input medis (khusus admin)
        Route::get('/autocomplete-user', [MedisController::class, 'autocomplete'])->name('user.autocomplete');

        // -------- Data User (lansia & pengajar)
        Route::get('/user',                    [UserController::class, 'index'])->name('users.index');
        Route::get('/user/data',               [UserController::class, 'data'])->name('users.data');

        Route::get('/user/pengajar',           [UserController::class, 'pengajar'])->name('users.pengajar');
        Route::get('/user/data-pengajar',      [UserController::class, 'dataPengajar'])->name('users.dataPengajar');

        Route::get('/user/create',             [UserController::class, 'create'])->name('users.create');
        Route::post('/user/add',               [UserController::class, 'store'])->name('users.store');
        Route::get('/users/{user}/edit',       [UserController::class, 'edit'])->name('users.edit');
        Route::put('/users/{user}',            [UserController::class, 'update'])->name('users.update');

        Route::get('/user/create-pengajar',    [UserController::class, 'createPengajar'])->name('users.create-pengajar');
        Route::post('/user/add-pengajar',      [UserController::class, 'storePengajar'])->name('users.storePengajar');
        Route::get('/users/pengajar/{user}/edit', [UserController::class, 'editPengajar'])->name('users.editPengajar');
        Route::put('/users/pengajar/{user}',      [UserController::class, 'updatePengajar'])->name('users.updatePengajar');

        Route::delete('/users/hapus/{id}',     [UserController::class, 'destroy'])->name('users.destroy');

        // -------- Diagnosa
        Route::get('/diagnosa',           [DiagnosaController::class, 'index'])->name('diagnosa');
        Route::get('/diagnosa/data',      [DiagnosaController::class, 'data'])->name('diagnosa.data');
        Route::post('/diagnosa/add',      [DiagnosaController::class, 'store'])->name('diagnosa.store');
        Route::put('/diagnosa/{id}',      [DiagnosaController::class, 'update'])->name('diagnosa.update');
        Route::delete('/diagnosa/{id}',   [DiagnosaController::class, 'destroy'])->name('diagnosa.destroy');

        // -------- Berita
        Route::get('/berita',                 [BeritaController::class, 'index'])->name('berita');
        Route::get('/berita/data',            [BeritaController::class, 'data'])->name('berita.data');
        Route::post('/berita',                [BeritaController::class, 'store'])->name('berita.store');
        Route::delete('/berita/{berita}',     [BeritaController::class, 'destroy'])->name('berita.destroy');
    });

    /*
    |----------------------------------------------------------------------
    | PENGAJAR ONLY (upload materi)
    |----------------------------------------------------------------------
    */
    Route::get('/materi',        [MateriController::class, 'index'])->name('materi');        // list + modal upload
    Route::middleware('role:pengajar')->group(function () {
        Route::get('/materi/data',   [MateriController::class, 'data'])->name('materi.data');    // DataTables (materi saya)
        Route::post('/materi/add',   [MateriController::class, 'store'])->name('materi.store');  // upload
        Route::delete('/materi/{material}', [MateriController::class, 'destroy'])->name('materi.destroy');
    });

    /*
    |----------------------------------------------------------------------
    | USER ONLY (lihat history & materi)
    |----------------------------------------------------------------------
    */
    Route::get('/materi/dataMateri',   [MateriController::class, 'dataMateri'])->name('materi.dataMateri'); // DataTables materi user
    Route::middleware('role:user')->group(function () {
        Route::get('/history',             [MedisController::class, 'history'])->name('medis.history'); // rekam medis milik user
        Route::get('/materi/user',         [MateriController::class, 'materiUser'])->name('materi.user'); // list materi untuk user
    });
});

require __DIR__ . '/auth.php';
