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
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\CustomerController;
use App\Http\Controllers\DotationController;
use App\Http\Controllers\EmployeeController;
use App\Http\Controllers\ApplicantController;
use App\Http\Controllers\EquipmentController;
use App\Http\Controllers\SuspensionController;
use App\Http\Controllers\AffectationController;
use App\Http\Controllers\LicenciementController;
use App\Http\Controllers\RecruitmentController;
use App\Http\Controllers\AppointmentController;

use App\Http\Controllers\Auth\AuthenticatedSessionController;
use App\Http\Controllers\Auth\ConfirmablePasswordController;
use App\Http\Controllers\Auth\EmailVerificationNotificationController;
use App\Http\Controllers\Auth\EmailVerificationPromptController;
use App\Http\Controllers\Auth\NewPasswordController;
use App\Http\Controllers\Auth\PasswordController;
use App\Http\Controllers\Auth\PasswordResetLinkController;
use App\Http\Controllers\Auth\RegisteredUserController;
use App\Http\Controllers\Auth\VerifyEmailController;

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


Route::get('/prints/bill/{id}/{year}/{month?}/{isReceipt?}', [PrintController::class, 'bill'])->name('prints.bill');
Route::get('/prints/bank/{month?}/{isBank?}', [PrintController::class, 'payByBankTransfer'])->name('prints.bank');
Route::get('/prints/receipt/{id}/{month?}', [PrintController::class, 'paymentReceipt'])->name('prints.receipt');
Route::get('/prints/employees/affectations', [PrintController::class, 'getEmployeesAffected'])->name('prints.affectations');
Route::get('/prints/hires/affectations', [PrintController::class, 'getHiredAffected'])->name('hires.affectations');
Route::get('/prints/applicants/report', [PrintController::class, 'getApplicantReport'])->name('applicants.report');
Route::get('/prints/partners/report', [PrintController::class, 'getPartnerReport'])->name('partners.report');
Route::get('/prints/appointments/report', [PrintController::class, 'getAppointmentReport'])->name('appointments.report');
Route::get('/prints/wifi', [PrintController::class, 'getQrcodeWifi'])->name('prints.wifi');

Route::prefix('admin')->middleware(['auth', 'verified', 'lang'])->group(function () {
    Route::get('/prints/operations/report', [PrintController::class, 'getOperationsReport'])->name('prints.operations.report');
    Route::get('/prints/categories/report', [PrintController::class, 'getCategoriesReport'])->name('prints.categories.report');
    Route::get('/prints/dotations/report', [PrintController::class, 'getDotationsReport'])->name('prints.dotations.report');
    Route::get('/prints/equipments/report', [PrintController::class, 'getEquipmentsReport'])->name('prints.equipments.report');
    Route::get('/prints/leaves/report', [PrintController::class, 'getLeavesReport'])->name('prints.leaves.report');
    Route::get('/prints/suspensions/report', [PrintController::class, 'getSuspensionsReport'])->name('prints.suspensions.report');
    Route::get('/prints/licenciements/report', [PrintController::class, 'getLicenciementsReport'])->name('prints.licenciements.report');
    Route::get('/prints/meets/report', [PrintController::class, 'getMeetsReport'])->name('prints.meets.report');
    Route::get('/prints/contracts/{id}', [PrintController::class, 'getEmployeeContract'])->name('prints.contract.employee');

    Route::get('/dashboard', [Controller::class, 'admin'])->name('dashboard')->middleware('admin');
    Route::get('/accountant', [Controller::class, 'accountant'])->name('accountant');
    Route::get('/secretary', [Controller::class, 'secretary'])->name('secretary');
    Route::get('/logistic', [Controller::class, 'logistic'])->name('logistic');
    Route::get('/humanresource', [Controller::class, 'humanresource'])->name('humanresource');
    Route::get('/groups', [Controller::class, 'groups'])->name('groups');
    Route::get('/administrators', [Controller::class, 'getAdministrators'])->name('administrators');
    Route::get('/agents', [Controller::class, 'getAgents'])->name('agents');
    Route::get('/lang/{locale}', [Controller::class, 'language'])->name('lang');
    Route::get('/employees/notAffected/{id}', [EmployeeController::class, 'employeeNotAffected'])->name('employees.notaffected');

    Route::post('/bills/monthly', [BillController::class, 'getByMonthly'])->name('bills.monthly');
    Route::post('/payments/monthly', [PaymentController::class, 'getByMonthly'])->name('payments.monthly');
    Route::post('/dash', [Controller::class, 'dash'])->name('dash');
    Route::post('/employees/search', [EmployeeController::class, 'getSearch'])->name('employees.search');

    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    Route::resource('mails', MailController::class);
    Route::resource('meets', MeetController::class);
    Route::resource('users', UserController::class);
    Route::resource('bills', BillController::class);
    Route::resource('leaves', LeafController::class);
    Route::resource('payments', PaymentController::class);
    Route::resource('employees', EmployeeController::class);
    Route::resource('dotations', DotationController::class);
    Route::resource('customers', CustomerController::class);
    Route::resource('categories', CategoryController::class);
    Route::resource('applicants', ApplicantController::class);
    Route::resource('equipments', EquipmentController::class);
    Route::resource('suspensions', SuspensionController::class);
    Route::resource('recruitments', RecruitmentController::class);
    Route::resource('affectations', AffectationController::class);
    Route::resource('licenciements', LicenciementController::class);
    Route::resource('appointments', AppointmentController::class);
    

    Route::match(['GET', 'POST'], 'logout', [AuthenticatedSessionController::class, 'destroy'])
        ->name('logout');
    Route::get('/applicants/{id}/delete', [ApplicantController::class, 'delete'])->name('applicants.delete');
    Route::post('/admin/applicants/save', [ApplicantController::class, 'store'])->name('applicants.save');
    Route::get('/admin/applicants/hires', [ApplicantController::class, 'hires'])->name('applicants.hires');
});

Route::middleware('guest')->group(function () {
    Route::get('/{locale?}', [AuthenticatedSessionController::class, 'create'])
        ->defaults('locale', 'fr')
        ->name('login');

    Route::post('{locale}/login', [AuthenticatedSessionController::class, 'store'])->name('auth');

    Route::get('{locale}/forgot', [PasswordResetLinkController::class, 'create'])
        ->name('password.request');
    Route::post('{locale}/forgot', [PasswordResetLinkController::class, 'store'])
        ->name('password.email');

    Route::get('reset/{token}', [NewPasswordController::class, 'create'])
        ->name('password.reset');
    Route::post('{locale}/resetpassword', [NewPasswordController::class, 'store'])
        ->name('password.store');
});

Route::middleware('auth')->group(function () {
    Route::get('verify-email', EmailVerificationPromptController::class)
        ->name('verification.notice');
    Route::get('confirm-password', [ConfirmablePasswordController::class, 'show'])
        ->name('password.confirm');
        
    Route::get('verify-email/{id}/{hash}', VerifyEmailController::class)
        ->middleware(['signed', 'throttle:6,1'])
        ->name('verification.verify');
    Route::post('email/verification-notification', [EmailVerificationNotificationController::class, 'store'])
        ->middleware('throttle:6,1')
        ->name('verification.send');

    Route::post('confirm-password', [ConfirmablePasswordController::class, 'store']);
    Route::put('password', [PasswordController::class, 'update'])->name('password.update');
});

