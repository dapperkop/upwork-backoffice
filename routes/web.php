<?php

use App\Http\Controllers\BlockedWordController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ManagerController;
use App\Http\Controllers\LocationController;
use App\Http\Controllers\SettingController;
use App\Models\Proposal;
use App\Models\Manager;
use App\Models\Location;
use Carbon\Carbon;

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
    return redirect(route('manager.index'));
})->middleware(['auth']);

Route::get('/managers', [ManagerController::class, 'index'])->middleware(['auth'])->name('manager.index');
Route::get('/manager/{manager}', [ManagerController::class, 'show'])->middleware(['auth'])->name('manager.show');

Route::get('/locations', [LocationController::class, 'index'])->middleware(['auth'])->name('location.index');
Route::post('/location/{location}', [LocationController::class, 'update'])->middleware(['auth'])->name('location.update');

Route::get('/blocked-words', [BlockedWordController::class, 'index'])->middleware(['auth'])->name('blocked-word.index');
Route::post('/blocked-words', [BlockedWordController::class, 'create'])->middleware(['auth'])->name('blocked-word.create');
Route::delete('/blocked-words/{blockedWord}', [BlockedWordController::class, 'delete'])->middleware(['auth'])->name('blocked-word.delete');

Route::get('settings', [SettingController::class, 'index'])->middleware(['auth'])->name('setting.index');
Route::post('settings', [SettingController::class, 'update'])->middleware(['auth'])->name('setting.update');

require __DIR__ . '/auth.php';
