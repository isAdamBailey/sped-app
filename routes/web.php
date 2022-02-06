<?php

use App\Http\Controllers\ChapterController;
use App\Http\Controllers\DocumentController;
use App\Http\Controllers\NewTeamController;
use App\Http\Controllers\SectionController;
use App\Http\Controllers\UserController;
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
    return Inertia::render('Welcome');
})->name('home');

Route::get('/laws', [SectionController::class, 'index'])->name('laws.index');
Route::get('/laws/{section}', [SectionController::class, 'show'])->name('laws.show');

Route::middleware(['auth:sanctum', 'verified'])->group(function () {
    Route::get('/create-first-team', NewTeamController::class)
        ->middleware('ensure-no-team')
        ->name('create-first-team');

    Route::middleware('ensure-team')->group(function () {
        Route::get('/dashboard', function () {
            return Inertia::render('Dashboard/Show');
        })->name('dashboard');

        Route::get('/documents', [DocumentController::class, 'index'])->name('documents.index');
        Route::get('/documents/{document}', [DocumentController::class, 'show'])->name('documents.show');
        Route::post('/documents', [DocumentController::class, 'store'])->name('documents.store');
        Route::put('/documents/{document}', [DocumentController::class, 'update'])->name('documents.update');
        Route::delete('/documents/{document}', [DocumentController::class, 'destroy'])->name('documents.destroy');

        Route::group(['middleware' => ['can:edit chapters']], function () {
            Route::get('/chapters', [ChapterController::class, 'index'])->name('chapters.index');
            Route::put('chapters/{chapter}', [ChapterController::class, 'update'])->name('chapters.update');
        });

        Route::group(['middleware' => ['can:edit users']], function () {
            Route::get('/users', [UserController::class, 'index'])->name('users.index');
            Route::get('/users/{user}', [UserController::class, 'show'])->name('users.show');
            Route::put('users/{user}', [UserController::class, 'update'])->name('users.update');
        });
    });
});

Route::fallback(function () {
    return Inertia::render('Error', ['status' => 404]);
})->name('404.show');
