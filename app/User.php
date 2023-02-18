<?php

namespace App;

use Carbon\Carbon;
use Carbon\CarbonInterval;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Tymon\JWTAuth\Contracts\JWTSubject;
use Tymon\JWTAuth\Facades\JWTAuth;

class User extends Authenticatable implements JWTSubject
{
    use Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'first_name', 'email', 'last_name', 'gender', 'phone_number', 'personal_email', 'cnic', 'designation',
        'doj', 'dob', 'dop', 'dot', 'employee_id', 'finger_print_id', 'nationality', 'base_salary', 'emergency_contact_name',
        'emergency_contact_relation', 'emergency_contact_number', 'picture', 'role_id', 'status', 'account_no', 'manager_id', 'govrt_tax'
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $appends = [
        'full_name',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    public function checkIn()
    {
        return $this->hasOne('App\Attendance', 'user_id')->whereType(CHECK_IN);
    }

    public function checkOut()
    {
        return $this->hasOne('App\Attendance', 'user_id')->whereType(CHECK_OUT);
    }

    public function latePolicies()
    {
        return $this->hasOne('App\LatePolicyUser', 'user_id');
    }
    public function manager()
    {
        return $this->hasOne($this, 'manager_id');
    }

    public function leaveQuota()
    {
        return $this->hasOne('App\UserLeaveQuota', 'user_id');
    }

    // public function setPictureAttribute()
    // {
    // if ($this->picture == null) {
    //     // $this->picture = asset('employeesAsset/images/profile/profile.png');
    //     dd($this->setAttribute('picture', asset('employeesAsset/images/profile/profile.png')));
    //     return $this->picture;
    // } else {
    //     dd($this->picture);
    //     dd($this->setAttribute('picture', asset('employeesAsset/images/profile/profile.png')));
    //     dd($this->picture);
    //     $this->picture = asset('uploads/employee/' . $this->picture);
    //     return $this->picture;
    // }
    // }

    public function getFullNameAttribute()
    {
        return $this->first_name . ' ' . $this->last_name;
    }

    public function checkAttendance($date)
    {
        if ($this->dot && $date->format('Y-m-d') > $this->dot) {
            // dd($date, $this->dot);
            return NOTHING;
        }

        if ($date->format('D') == 'Sun' || $date->format('D') == 'Sat') {
            if (!Holiday::where('date', $date->format('Y-m-d'))->whereType(WORKING_DAY)->exists()) {
                return WEEKEND;
            }
        }

        if (Holiday::where('date', $date->format('Y-m-d'))->whereType(HOLIDAY)->exists()) {
            return PUBLIC_HOLIDAY;
        }

        if (UserAttendanceException::where('date', $date->format('Y-m-d'))->where('user_id', $this->id)->exists()) {
            return EXCEPTION_ATTENDANCE;
        }

        if ($date <= now()) {
            $latePolicy = LatePolicyUser::where('user_id', $this->id)->where('start_date', '<=', $date->format('Y-m-d'))->where(function ($query) use ($date) {
                $query->where('end_date', '>=', $date->format('Y-m-d'))
                    ->orWhere('end_date', NULL);
            })->first();
            if ($leave = UserLeave::whereUserId($this->id)->where('date', $date->format('Y-m-d'))->first()) {
                return $leave->period == HALF_DAY_LEAVE ? HALF_DAY : ON_LEAVE;
            }

            $checkIn = Attendance::whereDate('action_time', $date)->whereUserId($this->id)->whereType(CHECK_IN)->first();
            $checkOut = Attendance::whereDate('action_time', $date)->whereUserId($this->id)->whereType(CHECK_OUT)->first();

            if ($checkIn && LatePolicyDateException::where('date', '>=', $checkIn->action_time)->where('date', 'LIKE', '%' . $date->format('Y-m-d') . '%')->exists()) {
                return PRESENT;
            }
            if (!$checkIn && !$checkOut) {
                if (!$latePolicy) {
                    return NO_LATE_POLICY;
                } else if ($latePolicy->no_policy) {
                    return NO_LATE_POLICY;
                } else return ABSENT;
            } else if ($checkIn && !$checkOut && $latePolicy) {
                if (strtotime($checkIn->action_time) <= strtotime($date->format('Y-m-d') . ' ' . $latePolicy->start_time)) {
                    return PRESENT;
                } else return LATENESS;
            } else if (($checkIn || $checkOut) && !$latePolicy) {
                return PRESENT;
            } else if ($checkIn && $checkOut && $latePolicy) {

                $date1 = Carbon::createFromFormat('Y-m-d H:i', $checkIn->action_time);
                $date2 = Carbon::createFromFormat('Y-m-d H:i', $checkOut->action_time);
                $diff = $date1->diffInSeconds($date2);

                if (strtotime(date('Y-m-d H:i', strtotime($checkIn->action_time))) > strtotime($date->format('Y-m-d') . ' ' . $latePolicy->start_time)) {
                    return LATENESS;
                } else if (strtotime(date('Y-m-d H:i', strtotime($checkOut->action_time))) < strtotime($date->format('Y-m-d') . ' ' . $latePolicy->end_time)) {
                    // if ($date->format('Y-m-d') == '2020-11-03') {
                    //     dd($date->format('Y-m-d') . ' ' . $latePolicy->relax_time);
                    // }
                    return EARLY_OUT;
                } else return PRESENT;
            } else if (!$checkIn && $checkOut && $latePolicy) {
                return LATENESS;
            }
        } else return NOTHING;
    }

    public function getCheckInTime($date)
    {
        $checkIn = Attendance::whereDate('action_time', $date)->whereUserId($this->id)->whereType(CHECK_IN)->first();
        if ($checkIn) {
            return date('H:i', strtotime($checkIn->action_time));
        } else return '--';
    }

    public function getCheckOutTime($date)
    {
        $checkOut = Attendance::whereDate('action_time', $date)->whereUserId($this->id)->whereType(CHECK_OUT)->first();
        if ($checkOut) {
            return date('H:i', strtotime($checkOut->action_time));
        } else return '--';
    }

    public function getLateMinutes($date)
    {
        if (!Holiday::where('date', $date->format('Y-m-d'))->whereType(HOLIDAY)->exists()) {
            $leave = UserLeave::whereUserId($this->id)->where('date', $date->format('Y-m-d'))->first();
            if ($leave) {
                if ($leave->period == FULL_DAY_LEAVE) {
                    return '--';
                } else if ($leave->period_type == FIRST_HALF) {
                    return '--';
                }
            }

            $checkIn = Attendance::whereDate('action_time', $date)->whereUserId($this->id)->whereType(CHECK_IN)->first();
            $latePolicy = LatePolicyUser::where('user_id', $this->id)->where('no_policy', false)->where('start_date', '<=', $date->format('Y-m-d'))->where(function ($query) use ($date) {
                $query->where('end_date', '>=', $date->format('Y-m-d'))
                    ->orWhere('end_date', NULL);
            })->first();
            if ($latePolicy && $checkIn) {
                $relaxTime = Carbon::createFromFormat('Y-m-d H:i', $date->format('Y-m-d') . ' ' . $latePolicy->relax_time);

                if ($dateException = LatePolicyDateException::where('date', 'LIKE', '%' . date('Y-m-d', strtotime($checkIn->action_time)) . '%')->first()) {
                    $relaxTime = Carbon::createFromFormat('Y-m-d H:i', date('Y-m-d H:i', strtotime($dateException->date)));
                }
                if ($checkIn->action_time > $relaxTime) {
                    $actionTime = Carbon::createFromFormat('Y-m-d H:i', $checkIn->action_time);
                    $halfSalary = getUserHalfDaySalary($this, $date->format('Y-m-d'));
                    if ($latePolicy->type == PER_MINUTE_POLICY) {
                        $deduction = 0;
                        if (count($latePolicy->variations)) {
                            foreach ($latePolicy->variations as $key => $variation) {
                                $variationTime = Carbon::createFromFormat('Y-m-d H:i', $date->format('Y-m-d') . ' ' . $variation->time);
                                if ($variation->type == '0') {
                                    if ($checkIn->action_time < $variationTime) {
                                        if ($key == 0) {
                                            $diff = $relaxTime->diffInSeconds($actionTime);
                                            $deduction += ($diff / 60) * $latePolicy->per_minute;
                                        }
                                    } else {
                                        if ($key == 0) { // only for first time
                                            // first calculate between relax and variation time
                                            if ($relaxTime < $variationTime) {
                                                $diffBetweenRelaxAndVariation = $relaxTime->diffInSeconds($variationTime);
                                                $deduction += ($diffBetweenRelaxAndVariation / 60) * $latePolicy->per_minute;
                                            }
                                        }
                                        // now check if next variation exists
                                        if (isset($latePolicy->variations[$key + 1])) {
                                            // now calculate between variation time and next variation time
                                            if ($relaxTime < $variationTime) {
                                                $nextVariationTime = Carbon::createFromFormat('Y-m-d H:i', $date->format('Y-m-d') . ' ' . $latePolicy->variations[$key + 1]->time);
                                                if ($actionTime > $nextVariationTime) {
                                                    $diffBetweenVariationAndNextVariation = $variationTime->diffInSeconds($nextVariationTime);
                                                } else {
                                                    $diffBetweenVariationAndNextVariation = $variationTime->diffInSeconds($actionTime);
                                                }
                                                $deduction += ($diffBetweenVariationAndNextVariation / 60) * $variation->price;
                                            }
                                        } else {
                                            // just calculate between variation time and user action time
                                            if ($relaxTime < $variationTime) {
                                                $diffBetweenVariationAndActionTime = $variationTime->diffInSeconds($actionTime);
                                                $deduction += ($diffBetweenVariationAndActionTime / 60) * $variation->price;
                                            } else {
                                                $diffBetweenVariationAndActionTime = $relaxTime->diffInSeconds($actionTime);
                                                $deduction += ($diffBetweenVariationAndActionTime / 60) * $variation->price;
                                            }
                                        }
                                    }
                                } else {
                                    if ($checkIn->action_time > $variationTime) {
                                        $deduction = $halfSalary;
                                    }
                                }
                            }
                        } else {

                            $diff = $relaxTime->diffInSeconds($actionTime);
                            $deduction += ($diff / 60) * $latePolicy->per_minute;
                        }
                        $diff = $relaxTime->diffInSeconds($actionTime);

                        if ($deduction > $halfSalary) {
                            $deduction = $halfSalary;
                        }
                    } else {
                        $deduction = $halfSalary;
                        $diff = $actionTime->diffInSeconds($date->format('Y-m-d') . ' ' . $latePolicy->start_time);
                    }
                    return ['read_able' => CarbonInterval::seconds($diff)->cascade()->forHumans(), 'minutes' => $diff / 60, 'deduction' => round($deduction, 2)];
                } else return '--';
            } else return '--';
        } else return '--';
    }

    public function latePolicy()
    {
        return $this->belongsToMany('App\LatePolicy', 'late_policy_users', 'user_id', 'late_policy_id');
    }

    public function netWorkingDays($month)
    {
        $dates = getPeriod($month);
    }

    public function role()
    {
        return $this->belongsTo('App\Role', 'role_id', 'id');
    }

    /**
     * Get the identifier that will be stored in the subject claim of the JWT.
     *
     * @return mixed
     */
    public function getJWTIdentifier()
    {
        return $this->getKey();
    }

    /**
     * Return a key value array, containing any custom claims to be added to the JWT.
     *
     * @return array
     */
    public function getJWTCustomClaims()
    {
        return [];
    }

    /*
     * generate Token
     * */
    public function generateJWTToken($ttl = 0)
    {
        if ($ttl) {
            JWTAuth::factory()->setTTL($ttl);
        }

        return JWTAuth::fromUser($this);
    }

    public function getLeaveDetails($date)
    {
        return UserLeave::where('user_id', $this->id)->where('date', $date->format('Y-m-d'))->first();
    }

    /**
     * Get all of the payrolls for the User
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function payrolls()
    {
        return $this->hasMany(UserPayRoll::class, 'user_id', 'id');
    }

    public function userLeaves()
    {
        return $this->hasMany(UserLeave::class, 'user_id', 'id');
    }

    public function loans()
    {
        return $this->hasMany(Loan::class, 'user_id', 'id');
    }
    public function inventory()
    {
        return $this->hasOne(Inventory::class, 'user_id', 'id');
    }

    public function notificaitons()
    {
        return $this->hasMany(UserNotification::class, 'user_id', 'id');
    }
}
