<?php

use App\Http\Controllers\RecruitmentController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ApplicantController;

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

// Public Routes
Route::get('/', [RecruitmentController::class, 'create'])->name('home');
Route::resource('applicants', ApplicantController::class)->only('store');



