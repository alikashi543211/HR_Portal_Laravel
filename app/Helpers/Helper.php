<?php

use App\Allowance;
use App\Attendance;
use App\RolePermission;
use App\UserLeave;
use App\UserLeaveQuota;
use App\UserPayRollAllowance;
use Carbon\Carbon;
use App\Holiday;
use App\LatePolicy;
use App\LatePolicyUser;
use App\LoanInstallment;
use App\PayRoll;
use App\UserAttendanceException;
use App\UserPayRoll;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

function getSearchColoumns($table)
{
    $return = [];
    switch ($table) {
        case 'users':
            $return = ['first_name', 'last_name', 'email', 'personal_email', 'phone_number', 'cnic', 'doj', 'dob', 'finger_print_id', 'employee_id', 'designation', 'nationality', 'emergency_contact_name', 'emergency_contact_relation', 'base_salary', 'emergency_contact_number', 'dop'];
            break;
        case 'user_pay_rolls':
            $return = ['gross_salary', 'net_salary', 'base_salary', 'late_deduction', 'leave_deduction', 'govrt_tax', 'user_id', 'pay_roll_id', 'allowances'];
            break;
        case 'attendances':
            $return = ['first_name', 'last_name', 'email', 'personal_email', 'phone_number', 'cnic', 'doj', 'dob', 'finger_print_id', 'employee_id', 'designation', 'nationality', 'emergency_contact_name', 'emergency_contact_relation', 'base_salary', 'emergency_contact_number'];
            break;
        case 'announcements':
            $return = ['first_name', 'last_name', 'email', 'personal_email', 'phone_number', 'cnic', 'doj', 'dob', 'finger_print_id', 'employee_id', 'designation', 'nationality', 'emergency_contact_name', 'emergency_contact_relation', 'base_salary', 'emergency_contact_number'];
            break;
    }
    return $return;
}

function getAttendanceIcon($value)
{
    $return = '';
    switch ($value) {
        case EARLY_OUT:
            $return = 'fa-clock-o';
            break;
        case LATENESS:
            $return = 'fa-clock-o';
            break;
        case PUBLIC_HOLIDAY:
            $return = 'fa-calendar-minus-o';
            break;
        case PRESENT:
            $return = 'fa-user-circle';
            break;
        case ABSENT:
            $return = 'fa-warning';
            break;
        case ON_LEAVE:
            $return = 'fa-home';
            break;
        case WEEKEND:
            $return = 'fa-calendar-check-o';
            break;
        case HALF_DAY:
            $return = 'fa-adjust';
            break;
        case NO_LATE_POLICY:
            $return = 'fa-user-minus';
            break;
        case EXCEPTION_ATTENDANCE:
            $return = 'fa-exclamation-circle';
            break;
    }
    return $return;
}

function getAttendanceIconEmpPortal($value)
{
    $return = '';
    switch ($value) {
        case EARLY_OUT:
            $return = 'early_out';
            break;
        case LATENESS:
            $return = 'late In';
            break;
        case PUBLIC_HOLIDAY:
            $return = 'holiday';
            break;
        case PRESENT:
            $return = 'present';
            break;
        case ABSENT:
            $return = 'absent';
            break;
        case ON_LEAVE:
            $return = 'on_leave';
            break;
        case WEEKEND:
            $return = 'weekend';
            break;
        case HALF_DAY:
            $return = 'half_day';
            break;
        case NO_LATE_POLICY:
            $return = 'no_attendence_policy';
            break;
        case EXCEPTION_ATTENDANCE:
            $return = 'attendence_exception';
            break;
    }
    return $return;
}

function getTextColor($value)
{
    $return = '';
    switch ($value) {
        case EARLY_OUT:
            $return = 'text-secondary';
            break;
        case LATENESS:
            $return = 'text-primary';
            break;
        case PUBLIC_HOLIDAY:
            $return = 'text-default';
            break;
        case PRESENT:
            $return = 'text-success';
            break;
        case ABSENT:
            $return = 'text-danger';
            break;
        case ON_LEAVE:
            $return = 'text-info';
            break;
        case WEEKEND:
            $return = 'text-default';
            break;
        case HALF_DAY:
            $return = 'text-danger';
            break;
        case NO_LATE_POLICY:
            $return = 'text-secondary';
            break;
        case EXCEPTION_ATTENDANCE:
            $return = 'text-secondary';
            break;
    }
    return $return;
}
function getTitleText($value)
{
    $return = '';
    switch ($value) {
        case EARLY_OUT:
            $return = 'Early out';
            break;
        case LATENESS:
            $return = 'Late In';
            break;
        case PUBLIC_HOLIDAY:
            $return = 'Holiday';
            break;
        case PRESENT:
            $return = 'Present';
            break;
        case ABSENT:
            $return = 'Absent';
            break;
        case ON_LEAVE:
            $return = 'On leave';
            break;
        case WEEKEND:
            $return = 'Weekend';
            break;
        case HALF_DAY:
            $return = 'Half Day';
            break;
        case NO_LATE_POLICY:
            $return = 'No Attendance Policy';
            break;
        case EXCEPTION_ATTENDANCE:
            $return = 'Attendance Exception';
            break;
    }
    return $return;
}
function getLeaveTitleText($value)
{
    $return = '';
    switch ($value) {
        case SICK_LEAVE_APPROVED:
            $return = 'Sick Leave Approved';
            break;
        case SICK_LEAVE_NOT_APPROVED:
            $return = 'Sick Leave Not Approved';
            break;
        case CASUAL_LEAVE_APPROVED:
            $return = 'Casual Leave Approved';
            break;
        case CASUAL_LEAVE_NOT_APPROVED:
            $return = 'Casual Leave Not Approved';
            break;
    }
    return $return;
}

function getLeaveIcon($value)
{
    $icon = '';
    switch ($value) {
        case SICK_LEAVE_APPROVED:
            $icon = "fa-plus-circle icon-green";
            break;
        case SICK_LEAVE_NOT_APPROVED:
            $icon = "fa-plus-circle icon-red";
            break;
        case CASUAL_LEAVE_APPROVED:
            $icon = "fa-suitcase icon-green";
            break;
        case CASUAL_LEAVE_NOT_APPROVED:
            $icon = "fa-suitcase icon-red";
            break;
    }
    return $icon;
}
function getModalClass($value)
{
    $return = '';
    switch ($value) {
        case EARLY_OUT:
            $return = 'view-attendance-popup';
            break;
        case LATENESS:
            $return = 'view-attendance-popup';
            break;
        case PUBLIC_HOLIDAY:
            $return = '';
            break;
        case PRESENT:
            $return = 'view-attendance-popup';
            break;
        case ABSENT:
            $return = 'view-leave-popup';
            break;
        case ON_LEAVE:
            $return = '';
            break;
        case WEEKEND:
            $return = '';
            break;
        case HALF_DAY:
            $return = 'view-attendance-popup';
            break;
        case NO_LATE_POLICY:
            $return = 'view-attendance-popup';
            break;
        case EXCEPTION_ATTENDANCE:
            $return = 'delete-exception-popup';
            break;
    }
    if (!checkPermission(ATTENDANCES, READ) && $return == 'view-attendance-popup') {
        $return = '';
    }
    if (!checkPermission(LEAVES, WRITE) && $return == 'view-leave-popup') {
        $return = '';
    }
    return $return;
}

function getPeriod($month = NULL)
{
    if (!$month) {
        $month = request('date') ? request('date') : (request('month') ? request('month') : '');
    }
    $start    = new DateTime('first day of ' . ($month ? str_replace(',', '', $month) : 'this month'));
    $end      = new DateTime('last day of ' . ($month ? str_replace(',', '', $month) : 'this month'));
    $interval = DateInterval::createFromDateString('1 day');
    return new DatePeriod($start, $interval, $end->modify('1 day'));
}

function getUserHalfDaySalary($user, $date)
{
    $end = new DateTime('last day of ' . $date);
    return round(($user->base_salary / $end->format('d')) / 2, 4);
}

function getUserFullDaySalary($user, $date)
{
    $end = new DateTime('last day of ' . $date);
    return round(($user->base_salary / $end->format('d')), 4);
}

function addUserQuota($user)
{
    if ($user->dop) {

        if (!$user->leaveQuota) {
            $quota = new UserLeaveQuota();
            $quota->user_id = $user->id;
            $quota->remaining_casual_leaves = 0;
            $quota->remaining_sick_leaves = 0;
            $quota->save();
        }
    }
}

function checkPermission($permission, $type)
{
    if ($permission > 0) {
        return RolePermission::whereRoleId(Auth::user()->role_id)->wherePermissionId($permission)->whereActionId($type)->exists();
    } else return true; // for dashboard
}

function getAllowances($user, $userPayRoll)
{
    $totalAmount = 0;
    $allowances = Allowance::whereForAll(true)->get();
    foreach ($allowances as $key => $allow) {
        UserPayRollAllowance::where('user_id', $user->id)
            ->where('user_pay_roll_id', $userPayRoll->id)
            ->where('allowance_id', $allow->id)->delete();
        $new = new UserPayRollAllowance();
        $new->user_id = $user->id;
        $new->user_pay_roll_id = $userPayRoll->id;
        $new->allowance_id = $allow->id;
        $amount = 0;
        if ($allow->type == ALLOWANCES_FIXED) {
            $amount = $allow->value;
        } else {
            $amount = ($allow->value / 100) * $user->base_salary;
        }
        $totalAmount += $amount;
        $new->amount = $amount;
        $new->save();
    }
    return $totalAmount;
}

function getMonthDeductions($user, $month)
{
    $loan_deduction = getUserLoan($user, $month);
    $daysCounter = 0;
    $start    = new DateTime('first day of ' . $month);
    $end      = new DateTime('last day of ' . $month);
    $interval = DateInterval::createFromDateString('1 day');
    $period = new DatePeriod($start, $interval, $end->modify('1 day'));
    $totalLateDeductions = 0;
    $totalLeaveDeductions = 0;
    $totalLeaveCount = 0;
    $fullDaySalary = getUserFullDaySalary($user, $month);
    foreach ($period as $key => $date) {
        if ($user->dot && ($date->format('Y-m-d') > $user->dot) || ($date->format('Y-m-d') < $user->doj)) {
            $totalLeaveDeductions += $fullDaySalary;
            continue;
        }
        if (Holiday::where('date', $date->format('Y-m-d'))->whereType(HOLIDAY)->exists()) {
            $daysCounter++;
            \Log::info("{$user->full_name} {$date->format('Y-m-d')} {$daysCounter} Type: holiday");
            continue;
        }
        if ($date->format('D') == 'Sun' || $date->format('D') == 'Sat') { // only for weekends
            if (!Holiday::where('date', $date->format('Y-m-d'))->whereType(WORKING_DAY)->exists()) { // check if weekend is on for working
                $daysCounter++;
                \Log::info("{$user->full_name} {$date->format('Y-m-d')} {$daysCounter} Type: Weekend");
                continue;
            }
        }
        if (!LatePolicyUser::where('user_id', $user->id)->where('no_policy', false)->where('start_date', '<=', $date->format('Y-m-d'))->where(function ($query) use ($date) {
            $query->where('end_date', '>=', $date->format('Y-m-d'))
                ->orWhere('end_date', NULL);
        })->first()) {
            $daysCounter++;
            \Log::info("{$user->full_name} {$date->format('Y-m-d')} {$daysCounter} Type: no policy");
            continue;
        }
        if (checkAttendanceException($user->id, $date->format('Y-m-d'))) {
            $daysCounter++;
            \Log::info("{$user->full_name} {$date->format('Y-m-d')} {$daysCounter} Type: Exception");
            continue;
        }
        $res = $user->getLateMinutes($date);
        if (is_array($res)) {
            $totalLateDeductions += $res['deduction'];
            $daysCounter++;
            \Log::info("{$user->full_name} {$date->format('Y-m-d')} {$daysCounter} Type: deduction");
        } else {
            $checkIn = Attendance::whereDate('action_time', $date->format('Y-m-d'))->whereUserId($user->id)->whereType(CHECK_IN)->first();
            $checkOut = Attendance::whereDate('action_time', $date->format('Y-m-d'))->whereUserId($user->id)->whereType(CHECK_OUT)->first();
            $leave = UserLeave::whereUserId($user->id)->where('date', $date->format('Y-m-d'))->first();
            // if ($date->format('D') != 'Sun' && $date->format('D') != 'Sat') {
            if ($leave) {
                if ($leave->leave_adjust == LEAVE_NOT_ADJUST) {
                    if ($leave->period == HALF_DAY_LEAVE) {
                        $totalLeaveDeductions += ($fullDaySalary / 2);
                        $daysCounter += 0.5;
                    } else {
                        $totalLeaveDeductions += $fullDaySalary;
                    }
                } else {
                    $daysCounter++;
                }

                if ($leave->period == HALF_DAY_LEAVE) {
                    $totalLeaveCount += 0.5;
                } else {
                    $totalLeaveCount++;
                }
            } else if (!$checkIn && !$checkOut) {
                $totalLeaveDeductions += $fullDaySalary;
            } else if (!$checkIn) {
                $totalLeaveDeductions += ($fullDaySalary / 2);
                $daysCounter += 0.5;
                \Log::info("{$user->full_name} {$date->format('Y-m-d')} {$daysCounter} Type: check in miss");
            } else if ($checkIn && !$checkOut) {
                $daysCounter++;
                \Log::info("{$user->full_name} {$date->format('Y-m-d')} {$daysCounter} Type: present but checkout missing");
            } else if ($checkIn && $checkOut) {
                $daysCounter++;
                \Log::info("{$user->full_name} {$date->format('Y-m-d')} {$daysCounter} Type: present");
            }
            // }
        }
    }


    return ['late_deduction' => $totalLateDeductions, 'leave_deduction' => $totalLeaveDeductions, 'paid_days' => $daysCounter, 'leave_count' => $totalLeaveCount, 'loan_deduction' => $loan_deduction];
}

function convertValueToCommaSeparated($value)
{
    return number_format($value);
}

function checkAttendanceException($user_id, $date)
{
    if (UserAttendanceException::where('user_id', $user_id)->where('date', $date)->exists()) {
        return true;
    }

    return false;
}

function getCheckInUpdatedBy($date, $user_id)
{
    $name = "--";
    $info = Attendance::where('type', CHECK_IN)->where('action_time', 'LIKE', $date . "%")->where('user_id', $user_id)->first();
    if ($info) {
        if (!empty($info->updatedBy)) {
            $name = $info->updatedBy->fullname;
        } else {
            $name = 'System';
        }
    }

    return $name;
}

function getCheckOutUpdatedBy($date, $user_id)
{
    $name = "--";
    $info = Attendance::where('type', CHECK_OUT)->where('action_time', 'LIKE', $date . "%")->where('user_id', $user_id)->first();
    if ($info) {
        if (!empty($info->updatedBy)) {
            $name = $info->updatedBy->fullname;
        } else {
            $name = 'System';
        }
    }

    return $name;
}

function getExceptionDetailsUpdatedBy($date, $user_id)
{
    $name = "--";
    $info = UserAttendanceException::where('date', $date)->where('user_id', $user_id)->first();

    if ($info) {
        if (!empty($info->updatedBy)) {
            $name = $info->updatedBy->fullname;
        }
    }

    return $name;
}


function getLeavePeriodType($type)
{
    switch ($type) {
        case FULL_DAY:
            return 'Full Day';
            break;

        case FIRST_HALF:
            return 'First Half';
            break;

        default:
            return 'Second Half';
            break;
    }
}

function getLeavePeriod($type)
{
    switch ($type) {
        case HALF_DAY_LEAVE:
            return 'Half Day';
            break;

        case FULL_DAY_LEAVE:
            return 'Full Day';
            break;

        default:
            return 'Full Day';
            break;
    }
}

function getLeaveType($type)
{
    switch ($type) {
        case CASUAL_LEAVE:
            return 'Casual';
            break;

        case SICK_LEAVE:
            return 'Sick';
            break;

        default:
            return 'Casual';
            break;
    }
}

function getLeaveAdjust($type)
{
    switch ($type) {
        case LEAVE_ADJUST:
            return 'Adjust';
            break;

        case LEAVE_NOT_ADJUST:
            return 'Not Adjust';
            break;

        default:
            return 'Not Adjust';
            break;
    }
}


function getPayrollLeaveSymbol($type, $model = NULL)
{
    if ($type == LEAVES && $model) {
        if ($model->type == CASUAL_LEAVE) {
            if ($model->period == HALF_DAY_LEAVE) {
                if ($model->leave_adjust == LEAVE_ADJUST) {
                    return 'C/H';
                } else return 'A/H';
            } else {
                if ($model->leave_adjust == LEAVE_ADJUST) {
                    return 'C';
                } else return '--';
            }
        } else {
            if ($model->period == HALF_DAY_LEAVE) {
                if ($model->leave_adjust == LEAVE_ADJUST) {
                    return 'S/H';
                } else return 'A/H';
            } else {
                if ($model->leave_adjust == LEAVE_ADJUST) {
                    return 'S';
                } else return '--';
            }
        }
    } else if ($type == EXCEPTION_ATTENDANCE) {
        return 'E';
    } else if ($type == PUBLIC_HOLIDAY) {
        return 'H';
    } else if ($type == ABSENT) {
        if ($model) {
            if ($model->period == HALF_DAY_LEAVE && $model->leave_adjust == LEAVE_NOT_ADJUST) {
                return 'A/H';
            } else return 'A';
        } else return 'A';
    } else return '--';
}

function getUserLoan($user, $month)
{
    $return = 0;
    $month = Carbon::parse($month)->format('Y-m');
    $installment = LoanInstallment::where('month', 'LIKE', '%' . $month . '%')->whereHas('loan', function ($q) use ($user) {
        $q->whereUserId($user->id);
    })->first();
    if ($installment) {
        $loan = $installment->loan;
        $installment->status = PAID;
        $installment->save();
        if ($loan->installments()->count() == $loan->installments()->where('status', PAID)->count()) {
            $loan->status = PAID;
            $loan->save();
        }
        $return = $installment->amount;
    }
    return $return;
}


function incYear($year)
{
    $year = $year + 1;
    return $year;
}
function decYear($year)
{
    $year = $year - 1;
    return $year;
}


function getUserLeaveTypeStatus($date, $user_id)
{
    $data = UserLeave::whereDate('date', $date)->where('user_id', $user_id)->first();
    // dump($data);
    if ($data) {
        $status = "";
        if ($data->type == SICK_LEAVE) {
            if ($data->leave_adjust == LEAVE_ADJUST) {
                $status = "fa-plus-circle icon-green";
                // asset('assets/image/Sick_Approved.png');
            } else {
                $status = "fa-plus-circle icon-red";
                // asset('assets/image/Sick_Unapproved.png');
            }
        } else {
            if ($data->leave_adjust == LEAVE_ADJUST) {
                $status = "fa-suitcase icon-green";
                // asset('assets/image/Casual_Approved.png');
            } else {
                $status = "fa-suitcase icon-red";
                // asset('assets/image/Casual_Unapproved.png');
            }
            // $status = $status  . "Casual leave ";
        };

        return $status;
    } else {
        return "-";
    }
    // dd($data->type . " " . $data->leave_adjust);
}

function getPeriodType($date, $user_id)
{
    $data = UserLeave::whereDate('date', $date)->where('user_id', $user_id)->first();
    // dump($data);
    if ($data) {
        $status = "";
        if ($data->period_type == FULL_DAY) {
            return "FULL DAY";
        }
        if ($data->period_type == FIRST_HALF) {
            return "FIRST HALF";
        }
        if ($data->period_type == SECOND_HALF) {
            return "SECOND HALF";
        }
    } else {
        return "-";
    }
}

function getUserTaxPaid($date, $user_id)
{
    if ($payRoll = PayRoll::where('date', $date)->first()) {
        if ($userPayRoll = UserPayRoll::where('user_id', $user_id)->where('pay_roll_id', $payRoll->id)->first()) {
            return $userPayRoll->govrt_tax;
        } else {
            return 0;
        }
    } else {
        return 0;
    }
}

function getUniqueKey()
{
    $user_key = "";
    do {
        $user_key = mt_rand(1000000000, 9999999999);
    } while (DB::table('users')->where('random_key', $user_key)->exists());
    return $user_key;
}
