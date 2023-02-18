<?php

namespace App\Http\Controllers\Admin\Dashboard;

use App\Http\Controllers\Controller;
use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use stdClass;

class DashboardController extends Controller
{
    /*
    |
    |   Controller Instance
    |
    */

    public function __construct()
    {
        $this->user = new User();
    }

    /*
    |
    |   Dashboard Details
    |
    */

    public function details()
    {
        try {

            $data = new stdClass;
            // Permanent
            $data->widget['permanent']['count'] = $this->user->newQuery()->where('status', USER_STATUS_PERMANENT)->where('role_id', '>', SUPER_ADMIN)->count();
            $data->widget['permanent']['text-color'] = 'text-success';
            $data->widget['permanent']['icon'] = "fa-user-lock";

            // 1M Probation
            $data->widget['1M Probation']['count'] = $this->user->newQuery()->where('status', USER_STATUS_1M_PROBATION)->where('role_id', '>', SUPER_ADMIN)->count();
            $data->widget['1M Probation']['text-color'] = 'text-warning';
            $data->widget['1M Probation']['icon'] = "fa-user-clock";

            // 2M Probation
            $data->widget['2M Probation']['count'] = $this->user->newQuery()->where('status', USER_STATUS_2M_PROBATION)->where('role_id', '>', SUPER_ADMIN)->count();
            $data->widget['2M Probation']['text-color'] = 'text-warning';
            $data->widget['2M Probation']['icon'] = "fa-user-clock";

            // 3M Probation
            $data->widget['3M Probation']['count'] = $this->user->newQuery()->where('status', USER_STATUS_3M_PROBATION)->where('role_id', '>', SUPER_ADMIN)->count();
            $data->widget['3M Probation']['text-color'] = 'text-warning';
            $data->widget['3M Probation']['icon'] = "fa-user-clock";

            // Terminate'
            $data->widget['terminated']['count'] = $this->user->newQuery()->where('status', USER_STATUS_TERMINATE)->where('role_id', '>', SUPER_ADMIN)->count();
            $data->widget['terminated']['text-color'] = 'text-danger';
            $data->widget['terminated']['icon'] = "fa-user-slash";

            // Other'
            $data->widget['other']['count'] = $this->user->newQuery()->where('status', 'Other')->where('role_id', '>', SUPER_ADMIN)->count();
            $data->widget['other']['text-color'] = 'text-muted';
            $data->widget['other']['icon'] = "fa-user";

            $data->upcomingDob = $this->user->newQuery()->whereMonth('dob', date('m'))->where('status', '!=', USER_STATUS_TERMINATE)->where('role_id', '>', SUPER_ADMIN)->get();
            // $data->birthdays = $this->user->newQuery()->select('first_name', 'last_name', 'dob')->where('status', '!=', USER_STATUS_TERMINATE)->where('role_id', '>', SUPER_ADMIN)->get()->toArray();
            return $this->successView("", "admin.dashboard", __('dashboard.title'), $data);
        } catch (QueryException $e) {
            \Session::flash(ERROR, $e->getMessage());
            return Redirect()->back();
        } catch (Exception $e) {
            \Session::flash(ERROR, $e->getMessage());
            return Redirect()->back();
        }
    }

    public function getProfile()
    {
        try {

            return $this->successView("", "admin.profile", __('dashboard.profile'));
        } catch (QueryException $e) {
            \Session::flash(ERROR, $e->getMessage());
            return Redirect()->back();
        } catch (Exception $e) {
            \Session::flash(ERROR, $e->getMessage());
            return Redirect()->back();
        }
    }

    public function updateProfile(Request $request)
    {
        try {
            DB::beginTransaction();
            $inputs = $request->all();
            $user = Auth::User();
            if (!Hash::check($inputs['old_password'], $user->password)) {
                return $this->redirectBack("error", 'Invalid old password');
            }
            if ($inputs['password'] != $inputs['password_confirmation']) {
                return $this->redirectBack("error", 'Password doesn\'t match');
            }

            $user->password = Hash::make($inputs['password']);

            if ($user->save()) {
                DB::commit();
                return $this->redirectBack("success", __('default_label.updated'), "admin/profile");
            }

            DB::rollback();
            return $this->redirectBack("error", __('default_label.update'));
        } catch (QueryException $e) {
            DB::rollBack();
            \Session::flash($e->getMessage());
            return Redirect()->back();
        } catch (Exception $e) {
            DB::rollBack();
            \Session::flash($e->getMessage());
            return Redirect()->back();
        }
    }

    public function getBirthdays(Request $request)
    {
        $birthdays = $this->user->newQuery()->select('first_name', 'last_name', 'dob')->where('status', '!=', USER_STATUS_TERMINATE)->where('role_id', '>', SUPER_ADMIN)->get();
        return response()->json(json_encode($birthdays->toArray()), 200);
    }
}
