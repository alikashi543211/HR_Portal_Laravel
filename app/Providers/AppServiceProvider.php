<?php

namespace App\Providers;

use App\Allowance;
use App\Announcement;
use App\Attendance;
use App\Holiday;
use App\Increment;
use App\LatePolicy;
use App\LatePolicyDateException;
use App\LatePolicyUser;
use App\LatePolicyUserException;
use App\Observers\AllowanceObserver;
use App\Observers\AnnouncementObserver;
use App\Observers\AttendanceObserver;
use App\Observers\HolidayObserver;
use App\Observers\IncrementObserver;
use App\Observers\LatePolicyDateExceptionObserver;
use App\Observers\LatePolicyObserver;
use App\Observers\LatePolicyUserExceptionObserver;
use App\Observers\LatePolicyUserObserver;
use App\Observers\PayRollObserver;
use App\Observers\RoleObserver;
use App\Observers\RolePermissionObserver;
use App\Observers\UserLeaveObserver;
use App\Observers\UserLeaveQuotaObserver;
use App\Observers\UserObserver;
use App\Observers\UserPayRollAllowanceObserver;
use App\Observers\UserPayRollExtraObserver;
use App\Observers\UserPayRollObserver;
use App\PayRoll;
use App\Role;
use App\RolePermission;
use App\User;
use App\UserLeave;
use App\UserLeaveQuota;
use App\UserPayRoll;
use App\UserPayRollAllowance;
use App\UserPayRollExtra;
use Illuminate\Pagination\Paginator;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        User::observe(UserObserver::class);
        Allowance::observe(AllowanceObserver::class);
        Attendance::observe(AttendanceObserver::class);
        Holiday::observe(HolidayObserver::class);
        Increment::observe(IncrementObserver::class);
        LatePolicyDateException::observe(LatePolicyDateExceptionObserver::class);
        LatePolicyUserException::observe(LatePolicyUserExceptionObserver::class);
        LatePolicy::observe(LatePolicyObserver::class);
        PayRoll::observe(PayRollObserver::class);
        Role::observe(RoleObserver::class);
        RolePermission::observe(RolePermissionObserver::class);
        UserLeave::observe(UserLeaveObserver::class);
        UserLeaveQuota::observe(UserLeaveQuotaObserver::class);
        UserPayRollAllowance::observe(UserPayRollAllowanceObserver::class);
        UserPayRollExtra::observe(UserPayRollExtraObserver::class);
        UserPayRoll::observe(UserPayRollObserver::class);
        LatePolicyUser::observe(LatePolicyUserObserver::class);
        Announcement::observe(AnnouncementObserver::class);
        Paginator::useBootstrap();
    }
}
