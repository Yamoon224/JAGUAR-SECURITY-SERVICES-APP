<?php

use App\Http\Controllers\RecruitmentController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ApplicantController;

/*
|--------------------------------------------------------------------------
| Sous-domaine recrutement (recruitment.jss-gn.com)
|--------------------------------------------------------------------------
*/

Route::get('/', [RecruitmentController::class, 'create'])->name('home');
Route::resource('applicants', ApplicantController::class)->only('store');
Route::get('/merci', [ApplicantController::class, 'done'])->name('applicants.done');
