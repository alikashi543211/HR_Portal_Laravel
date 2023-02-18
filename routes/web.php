<?php

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

// Route::any('employee/{any}', function () { })->where('any', '^(?!api).*$');
/* Route::get('angular', function () {
    View::addExtension('html', 'php');
    return View::make('index');
}); */

use App\Http\Controllers\Employee\Angular\AngularController;
use Illuminate\Support\Facades\Route;

// Route::any('/', [AngularController::class, 'index'])->where('any', '^(?!api).*$');

/* Route::get('/', function () {
    return view('login');
}); */

Route::get('login', function () {
    return view('login');
});


Route::post('login', 'LoginController@login');
Route::get('logout', 'LoginController@logout');

Route::group(['namespace' => 'Admin', 'prefix' => 'admin', 'middleware' => 'adminAuth'], function () {
    Route::group(['namespace' => 'Dashboard'], function () {
        Route::get('dashboard', 'DashboardController@details');
        Route::get('profile', 'DashboardController@getProfile');
        Route::post('profile/update', 'DashboardController@updateProfile');
        Route::post('get-birthdays', 'DashboardController@getBirthdays');
    });

    Route::group(['namespace' => 'Helper', 'prefix' => 'helper'], function () {
        Route::get('search-employee', 'HelperController@searchEmployee');
    });

    Route::group(['namespace' => 'User', 'prefix' => 'users'], function () {
        Route::get('listing', 'UserController@listing');
        Route::get('add', 'UserController@create');
        Route::get('edit/{id}', 'UserController@edit');
        Route::get('details/{id}', 'UserController@details');
        Route::get('delete/{id}', 'UserController@delete');
        Route::post('save', 'UserController@store');
        Route::post('update', 'UserController@update');
        Route::post('change-policy', 'UserController@changePolicy');
        Route::get('send-join-mail/{id}', 'UserController@sendJoinEmail');
        Route::get('join-mail-to-all', 'UserController@sendJoinEmailToAll');
        Route::get('mark-all-as-read', 'UserController@markAllNotificationsAsRead');
        Route::get('notifications', 'UserController@notificaitionListing');
    });

    Route::group(['namespace' => 'Attendance', 'prefix' => 'attendances'], function () {
        Route::get('listing', 'AttendanceController@listing');
        Route::get('add', 'AttendanceController@create');
        Route::get('edit/{id}', 'AttendanceController@edit');
        Route::get('delete/{id}', 'AttendanceController@delete');
        Route::post('save', 'AttendanceController@store');
        Route::get('summary/{id}', 'AttendanceController@summaryDetails');
        Route::post('bulk-update', 'AttendanceController@updateAttendanceBulk');
        Route::post('update', 'AttendanceController@update');
        Route::post('ajax/update', 'AttendanceController@ajaxUpdate');
        Route::post('ajax/store', 'AttendanceController@ajaxStore');
        Route::get('get/date-details', 'AttendanceController@getDateDetails');
        Route::get('get/user/summary', 'AttendanceController@getUserSummary');
        Route::post('adjust/leave', 'AttendanceController@adjustLeave');
        Route::post('adjust/half/leave', 'AttendanceController@adjustHalfLeave');
        Route::post('upload/excel', 'AttendanceController@uploadExcelData');
        Route::get('export/excel', 'AttendanceController@ExportExcel');
        Route::post('ajax/add-exception', 'AttendanceController@ajaxAddAttendaceException');
        Route::get('delete-exception/{id}', 'AttendanceController@deleteAttendaceException');
    });

    Route::group(['namespace' => 'LatePolicy', 'prefix' => 'late-policy'], function () {
        Route::get('listing', 'LatePolicyController@listing');
        Route::get('add', 'LatePolicyController@add');
        Route::post('save', 'LatePolicyController@store');
        Route::get('edit/{id}', 'LatePolicyController@edit');
        Route::post('update', 'LatePolicyController@update');
        Route::get('delete/{id}', 'LatePolicyController@deletePolicy');
        Route::post('update-dates', 'LatePolicyController@updateDates');
        Route::get('delete-date/{id}', 'LatePolicyController@deleteDate');
    });

    Route::group(['namespace' => 'Holiday', 'prefix' => 'holidays'], function () {
        Route::get('listing', 'HolidayController@listing');
        Route::get('add', 'HolidayController@create');
        Route::get('edit/{id}', 'HolidayController@edit');
        Route::get('delete/{id}', 'HolidayController@delete');
        Route::post('save', 'HolidayController@store');
        Route::post('update', 'HolidayController@update');
    });

    Route::group(['namespace' => 'Leave', 'prefix' => 'leaves'], function () {
        Route::get('listing', 'LeaveController@listing');
        Route::post('update', 'LeaveController@update');
        Route::get('history/{id}', 'LeaveController@history');
        Route::get('summary', 'LeaveController@summary');
    });

    Route::group(['namespace' => 'Leave', 'prefix' => 'leave-requests'], function () {
        Route::get('listing', 'LeaveRequestController@listing');
        Route::post('update', 'LeaveRequestController@update');
        Route::get('details/{id}', 'LeaveRequestController@details');
        Route::get('reject/{id}', 'LeaveRequestController@reject');
        Route::get('delete/{id}', 'LeaveRequestController@delete');
    });

    Route::group(['namespace' => 'Permission', 'prefix' => 'permissions'], function () {
        Route::get('listing', 'PermissionController@listing');
        Route::post('update', 'PermissionController@update');
        Route::get('add', 'PermissionController@create');
        Route::post('save', 'PermissionController@store');
        Route::get('delete/{id}', 'PermissionController@delete');
    });

    Route::group(['namespace' => 'Allowance', 'prefix' => 'allowances'], function () {
        Route::get('listing', 'AllowanceController@listing');
        Route::get('edit/{id}', 'AllowanceController@edit');
        Route::post('update', 'AllowanceController@update');
        Route::get('add', 'AllowanceController@create');
        Route::post('save', 'AllowanceController@store');
        Route::get('delete/{id}', 'AllowanceController@delete');
    });
    Route::group(['namespace' => 'Increment', 'prefix' => 'increments'], function () {
        Route::get('listing', 'IncrementController@listing');
        Route::get('edit/{id}', 'IncrementController@edit');
        Route::post('update', 'IncrementController@update');
        Route::get('add', 'IncrementController@add');
        Route::post('save', 'IncrementController@store');
        Route::get('delete/{id}', 'IncrementController@delete');
    });


    Route::group(['namespace' => 'Loans', 'prefix' => 'loans'], function () {
        Route::get('listing', 'LoanController@listing');
        Route::get('edit/{id}', 'LoanController@edit');
        Route::post('update', 'LoanController@update');
        Route::get('add', 'LoanController@add');
        Route::post('save', 'LoanController@store');
        Route::post('status-update', 'LoanController@statusUpdate');
        Route::get('delete/{id}', 'LoanController@delete');
    });

    Route::group(['namespace' => 'PayRoll', 'prefix' => 'pay-rolls'], function () {
        Route::get('listing', 'PayRollsController@listing');
        Route::get('detail/{id}', 'PayRollsController@detail');
        Route::get('detail/add/{id}', 'PayRollsController@userPayRollAddPage');
        Route::post('update', 'PayRollsController@update');
        Route::get('add', 'PayRollsController@create');
        Route::post('save', 'PayRollsController@store');
        Route::post('save/user', 'PayRollsController@storeUser');
        Route::get('delete/{id}', 'PayRollsController@delete');
        Route::get('user/delete/{id}', 'PayRollsController@userPayRollDelete');
        Route::get('user/allowance/delete/{id}', 'PayRollsController@userPayRollAllowanceDelete');
        Route::post('user/allowance/save', 'PayRollsController@userPayRollAllowanceSave');
        Route::get('user/extra/delete/{id}', 'PayRollsController@userPayRollExtraDelete');
        Route::post('user/extra/save', 'PayRollsController@userPayRollExtraSave');
        Route::post('user/govrt/update', 'PayRollsController@userGovrtTaxUpdate');
        Route::get('salary-slip-pdf', 'PayRollsController@exportSalaryPDF');
        Route::get('payroll-pdf', 'PayRollsController@exportPayrollPDF');
        Route::get('generate-pdf', 'PayRollsController@generatePDF');
        Route::get('salary-slip-excel', 'PayRollsController@exportSalaryExcel');
        Route::get('details/salary-slip', 'PayRollsController@exportSalaryPDF');
        Route::get('recalculate/{id}', 'PayRollsController@recalculatePayRoll');
        Route::get('lock/{id}', 'PayRollsController@lockPayRoll');
        Route::get('attendance-pdf', 'PayRollsController@attendancePDF');
        Route::get('summary', 'PayRollsController@payRollTaxSummary');

        //payrollTaxes
        Route::get('tax-listing/{id}', 'PayRollTaxController@listing');
        Route::get('tax-add/{id}', 'PayRollTaxController@add');
        Route::post('tax-save', 'PayRollTaxController@save');
        Route::get('tax-edit/{id}', 'PayRollTaxController@edit');
        Route::get('tax-delete/{id}', 'PayRollTaxController@delete');
        Route::post('tax-update', 'PayRollTaxController@update');
    });

    // inventory routes
    Route::group(['namespace' => 'Inventory', 'prefix' => 'inventories'], function () {
        Route::get('listing', 'InventoryController@listing');
        Route::get('add', 'InventoryController@create');
        Route::post('save', 'InventoryController@save');
        Route::get('edit/{id}', 'InventoryController@edit');
        Route::get('delete/{id}', 'InventoryController@delete');
        Route::post('update', 'InventoryController@update');
        Route::post('request-inventory', 'InventoryController@inventoryRequest');
    });

    // payRollTaxes routes
    Route::group(['namespace' => 'PayRollTax', 'prefix' => 'pay-roll-taxes'], function () {
    });

    Route::group(['namespace' => 'Announcement', 'prefix' => 'announcements'], function () {
        Route::get('listing', 'AnnouncementController@listing');
        Route::get('add', 'AnnouncementController@add');
        Route::get('edit/{id}', 'AnnouncementController@edit');
        Route::post('update', 'AnnouncementController@update');
        Route::post('save', 'AnnouncementController@store');
        Route::get('delete/{id}', 'AnnouncementController@delete');
        Route::get('send-email/{id}', 'AnnouncementController@sendEmail');
    });

    Route::group(['namespace' => 'Mail', 'prefix' => 'emails'], function () {
        Route::get('email', 'EmailController@emailPage');
        Route::post('send-mails', 'EmailController@sendEmails');
    });

    Route::group(['namespace' => 'Letters', 'prefix' => 'letters'], function () {
        Route::get('listing', 'LetterController@listing');
        Route::get('add', 'LetterController@create');
        Route::post('store', 'LetterController@store');
        Route::post('update', 'LetterController@update');
        Route::get('edit/{id}', 'LetterController@edit');
        Route::get('delete/{id}', 'LetterController@delete');
        Route::get('mail/{id}', 'LetterController@mail');
        Route::post('mail', 'LetterController@sendMail');
    });

    Route::group(['namespace' => 'CompanyProfile', 'prefix' => 'company'], function () {
        Route::get('details', 'CompanyProfileController@details');
        Route::get('edit', 'CompanyProfileController@edit');
        Route::post('update', 'CompanyProfileController@update');
    });
});
Route::post('notification-status', 'Controller@NotificationStatus')->name('adminNotificationStatus');
Route::post('get-notifications', 'Controller@getNotifications')->name('admniGetNotifications');
