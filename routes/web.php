<?php

use App\Http\Controllers\ChapterController;
use App\Http\Controllers\SectionController;
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
});

Route::middleware(['auth:sanctum', 'verified'])->group(function () {
    Route::get('/dashboard', function () {
        return Inertia::render('Dashboard/Show');
    })->name('dashboard');

    Route::get('/sections', [SectionController::class, 'index'])->name('sections.index');
    Route::get('/sections/{section}', [SectionController::class, 'show'])->name('sections.show');

    Route::group(['middleware' => ['can:edit chapters']], function () {
        Route::get('/chapters', [ChapterController::class, 'index'])->name('chapters.index');
        Route::put('chapters/{chapter}', [ChapterController::class, 'update'])->name('chapters.update');
    });
});
