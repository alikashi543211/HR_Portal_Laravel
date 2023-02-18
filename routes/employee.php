<?php
/*
|--------------------------------------------------------------------------
| Employee Routes
|--------------------------------------------------------------------------
|
| Here is where you can register admin routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "admin" middleware group. Now create something great!
|
*/

use App\Http\Controllers\Controller;
use App\Http\Controllers\Employee\Announcement\AnnouncementController;
use App\Http\Controllers\Employee\Attendance\AttendanceController;
use App\Http\Controllers\Employee\Authentication\LoginController;
use App\Http\Controllers\Employee\Dashboard\DashboardController;
use App\Http\Controllers\Employee\Inventory\InventoryController;
use App\Http\Controllers\Employee\Leave\LeaveRequestController;
use App\Http\Controllers\Employee\Loan\LoanController;
use App\Http\Controllers\Employee\Profile\ProfileController;
use App\Http\Controllers\Employee\SalaryHistory\SalaryHistoryController;
use Illuminate\Support\Facades\Route;

Route::get('test', function () {
    $token = "231";
    return view('emails.setup-password', compact('token'));
});

Route::get('/', function () {
    return redirect()->route('employee.dashboard');
});
Route::name('employee.')->prefix('employee')->group(function () {
    Route::get('login', [LoginController::class, 'loginPage'])->name('get-login');
    Route::post('save-token', [LoginController::class, 'saveToken'])->name('save-token');
    Route::post('login', [LoginController::class, 'login'])->name('login');
    Route::get('logout', [LoginController::class, 'logout'])->name('logout');
    Route::get('wiki-login', [LoginController::class, 'wikiLogin'])->name('wiki-login');
    Route::get('get-verfication-code', [LoginController::class, 'getVerificationCode'])->name('getVerficationCode');
    Route::post('send-verfication-code', [LoginController::class, 'sendVerificationCode'])->name('sendVerficationCode');
    Route::get('submit-verfication-code', [LoginController::class, 'verificationCodePage'])->name('verificationCodePage');
    Route::post('veify-code', [LoginController::class, 'verifyCode'])->name('verifyCode');
    Route::get('get-password-page/{code}', [LoginController::class, 'getPasswordPage'])->name('getPasswordPage');
    Route::post('setup-password', [LoginController::class, 'setupPassword'])->name('setupPassword');
});
Route::name('employee.')->prefix('employee')->middleware('authEmployee')->namespace('Employee')->group(function () {


    Route::get('/', [DashboardController::class, 'index'])->name('dashboard');

    // attendance routes
    Route::name('attendance.')->prefix('attendance')->namespace('Attendance')->group(function () {
        Route::get('/list', [AttendanceController::class, 'index'])->name('listing');
        Route::post('/details', [AttendanceController::class, 'details'])->name('detail');
        Route::post('get-months', [AttendanceController::class, 'getMonths'])->name('getMonths');
    });

    // leaves routes
    Route::name('leaves.')->prefix('leaves')->namespace('Leave')->group(function () {
        Route::get('listing', [LeaveRequestController::class, 'listing'])->name('listing');
        Route::get('leave-request', [LeaveRequestController::class, 'AddLeaveRequest'])->name('add-leave-Request');
        Route::post('leave-request', [LeaveRequestController::class, 'request'])->name('submit-leave-request');
        Route::get('details/{id}', [LeaveRequestController::class, 'details'])->name('details');
        Route::get('edit/{id}', [LeaveRequestController::class, 'edit'])->name('edit');
        Route::post('update', [LeaveRequestController::class, 'update'])->name('update');
        Route::get('delete/{id}', [LeaveRequestController::class, 'delete'])->name('delete');
    });

    // announcement routes
    Route::name('announcements.')->prefix('announcements')->namespace('Announcement')->group(function () {
        Route::get('listing',  [AnnouncementController::class, 'listing'])->name('listing');
        Route::get('details/{id}',  [AnnouncementController::class, 'details'])->name('detail');
    });

    // profile setting routes
    Route::name('profile.')->prefix('profile')->namespace('Profile')->group(function () {
        Route::get('details', [ProfileController::class, 'details'])->name('detail');
        Route::post('change-password', [ProfileController::class, 'changePassword'])->name('changePassword');
        Route::post('change-image', [ProfileController::class, 'changeImage'])->name('changeImage');
        Route::post('update-profile', [ProfileController::class, 'updateProfile'])->name('updateProfile');
    });

    // salary history routes
    Route::name('salary-history.')->prefix('salary-history')->namespace('SalaryHistory')->group(function () {
        Route::get('listing',  [SalaryHistoryController::class, 'listing'])->name('listing');
        Route::get('salary-slip/{id}',  [SalaryHistoryController::class, 'downloadSalarySlip'])->name('downloadSalarySlip');
    });

    //  routes
    Route::name('loans.')->prefix('loans')->namespace('Loan')->group(function () {
        Route::get('listing',  [LoanController::class, 'listing'])->name('listing');
        Route::get('create',  [LoanController::class, 'create'])->name('create');
        Route::post('store',  [LoanController::class, 'store'])->name('store');
        Route::get('edit/{id}',  [LoanController::class, 'edit'])->name('edit');
        Route::post('update',  [LoanController::class, 'update'])->name('update');
        Route::get('delete/{id}',  [LoanController::class, 'delete'])->name('delete');
        Route::get('detail/{id}',  [LoanController::class, 'detail'])->name('detail');
    });

    // Inventory routes    
    Route::name('inventories.')->prefix('inventories')->namespace('Inventory')->group(function () {
        Route::get('listing',  [InventoryController::class, 'listing'])->name('listing');
        Route::post('inventory-request',  [InventoryController::class, 'requestInventory'])->name('request');
        // Route::get('create',  [InventoryController::class, 'create'])->name('create');
        // Route::post('store',  [InventoryController::class, 'store'])->name('store');
        // Route::get('edit/{id}',  [InventoryController::class, 'edit'])->name('edit');
        // Route::post('update',  [InventoryController::class, 'update'])->name('update');
        // Route::post('delete',  [InventoryController::class, 'delete'])->name('delete');
        // Route::get('detail/{id}',  [InventoryController::class, 'detail'])->name('detail');
    });
    // notification Satus route
    Route::post('notification-status',  [Controller::class, 'NotificationStatus'])->name('NotificationStatus');
    Route::post('get-notifications',  [Controller::class, 'getNotifications'])->name('getNotifications');
    Route::get('notifications', [Controller::class, 'getAllNotificationList'])->name('notificationList');
    Route::get('read-notifications', [Controller::class, 'readNotification'])->name('readNotification');
});
