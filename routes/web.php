<?php

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\BillController;
use App\Http\Controllers\LeafController;
use App\Http\Controllers\MailController;
use App\Http\Controllers\MeetController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\PrintController;
use App\Http\Controllers\PaymentController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\CustomerController;
use App\Http\Controllers\DotationController;
use App\Http\Controllers\EmployeeController;
use App\Http\Controllers\ApplicantController;
use App\Http\Controllers\EquipmentController;
use App\Http\Controllers\SuspensionController;
use App\Http\Controllers\AffectationController;
use App\Http\Controllers\LicenciementController;
use App\Http\Controllers\Auth\AuthenticatedSessionController;

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
Route::get('/{locale?}', [Controller::class, 'welcome'])->defaults('locale', 'fr')->name('welcome');
Route::get('/{locale}/about', [Controller::class, 'about'])->name('about');
Route::get('/{locale}/services', [Controller::class, 'services'])->name('services');
Route::get('/{locale}/team', [Controller::class, 'team'])->name('team');
Route::get('/{locale}/shops', [Controller::class, 'shops'])->name('shops');
Route::get('/{locale}/contacts', [Controller::class, 'contacts'])->name('contacts');
Route::post('/applications', [ApplicantController::class, 'store'])->name('applications');

