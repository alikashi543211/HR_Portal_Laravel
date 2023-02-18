<?php

use App\Http\Controllers\Api\ApiController;
use App\Http\Controllers\Api\NotificationController;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

// Route::group(['namespace' => 'Api'], function () {
//     Route::post('attendance/add', 'ApiController@checkIn');
// });

Route::group(['prefix' => 'employee', 'namespace' => 'Api'], function () {

    Route::group(['prefix' => 'auth', 'namespace' => 'Authentication'], function () {
        // Login Process
        Route::post('login', 'LoginController@login');
        Route::post('get-verfication-code', 'LoginController@sendVerificationCode');
        Route::post('check-verification-code', 'LoginController@checkVerificationCode');
        Route::post('setup-password', 'LoginController@setupPassword');
    });

    Route::group(['middleware' => ['jwtAuth']], function () {

        Route::group(['prefix' => 'dashboard', 'namespace' => 'Dashboard'], function () {
            Route::post('details', 'DashboardController@dashboard');
        });

        Route::group(['prefix' => 'attendance', 'namespace' => 'Attendance'], function () {
            // Route::post('listing', 'AttendanceController@listing');
            Route::post('details', 'AttendanceController@details');
        });

        Route::group(['prefix' => 'inventory', 'namespace' => 'Inventory'], function () {
            Route::get('listing', 'InventoryController@listing');
            Route::post('request', 'InventoryController@requestInventory');
            // Route::post('details', 'AttendanceController@details');
        });

        Route::group(['prefix' => 'salary-history', 'namespace' => 'SalaryHistory'], function () {
            Route::get('listing', 'SalaryHistoryController@listing');
        });

        Route::group(['prefix' => 'announcements', 'namespace' => 'Announcement'], function () {
            Route::get('listing', 'AnnouncementController@listing');
            // Route::post('details', 'AnnouncementController@details');
        });
        Route::group(['prefix' => 'loans', 'namespace' => 'Loan'], function () {
            Route::get('listing', 'LoanController@listing');
            Route::post('store', 'LoanController@store');
            Route::post('update', 'LoanController@update');
            Route::post('delete', 'LoanController@delete');
            Route::get('detail/{id}', 'LoanController@detail');
        });

        Route::group(['prefix' => 'leaves', 'namespace' => 'LeaveRequest'], function () {
            Route::get('listing', 'LeaveRequestController@listing');
            Route::get('next-date-check', 'LeaveRequestController@AddLeaveRequest');
            Route::post('store', 'LeaveRequestController@request');
            Route::get('edit/{id}', 'LeaveRequestController@edit');
            Route::get('details/{id}', 'LeaveRequestController@details');
            Route::post('update', 'LeaveRequestController@update');
        });

        Route::group(['prefix' => 'profile', 'namespace' => 'Profile'], function () {
            // Route::post('details', 'ProfileController@details');
            Route::post('change-password', 'ProfileController@changePassword');
            Route::post('change-emergency-details', 'ProfileController@changeEmergencyDetails');
            Route::post('user-info-details', 'ProfileController@updateProfile');
            Route::post('change-user-image', 'ProfileController@changeImage');
        });

        Route::post('auth/logout', 'Authentication\LoginController@logout');
        Route::get('get-notifications',  [NotificationController::class, 'index'])->name('getNotifications');
        Route::get('read-notifications/{id}',  [NotificationController::class, 'read'])->name('readNotifications');
        Route::get('read-announcement',  [NotificationController::class, 'readAllAnnouncement'])->name('readNotifications');
        Route::get('unread-notification',  [NotificationController::class, 'unreadAnnouncementAndNotificationCount'])->name('announcementCount');
        Route::get('read-all-notification',  [NotificationController::class, 'readAllnotification'])->name('readAllNotification');
    });

    // Route::post('notification-status',  [Controller::class, 'NotificationStatus'])->name('NotificationStatus');

    // Route::get('notifications', [Controller::class, 'getAllNotificationList'])->name('notificationList');
    // Route::get('read-notifications', [Controller::class, 'readNotification'])->name('readNotification');
});
