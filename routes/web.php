<?php

use App\Http\Controllers\TalentMapController;
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


Route::get('/', [TalentMapController::class, 'index'])->name('talent.map');
Route::post('/talent/match', [TalentMapController::class, 'match'])->name('talent.match');
Route::get('/talent/nearby', [TalentMapController::class, 'getNearbyTalent'])->name('talent.nearby');
