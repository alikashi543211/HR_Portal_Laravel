<?php

namespace App\Http\Controllers\Api\Dashboard;

use App\Announcement;
use App\Attendance;
use App\Http\Controllers\Controller;
use App\User;
use Exception;
use Illuminate\Database\QueryException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use stdClass;
use Tymon\JWTAuth\Exceptions\JWTException;

class DashboardController extends Controller
{
    public function __construct()
    {
        $this->user = new User();
        $this->attendance = new Attendance();
    }
    public function dashboard()
    {

        try {
            $data = new stdClass;
            // $announcements = Announcement::orderBy('id', 'DESC')->limit(5)->get();
            $user = $this->user->where('id', Auth::id())->with('leaveQuota')->first();
            $data = $user;
            $data->totalUsers = $this->user->newQuery()->where('status', USER_STATUS_PERMANENT)->where('role_id', '>', SUPER_ADMIN)->count();
            $todayCheckIn = $this->attendance->newQuery()->whereUserId(Auth::id())->where('action_time', 'LIKE', '%' . date('Y-m-d') . '%')->where('type', 'Check In')->first();
            $todayCheckOut = $this->attendance->newQuery()->whereUserId(Auth::id())->where('action_time', 'LIKE', '%' . date('Y-m-d') . '%')->where('type', 'Check Out')->first();
            if (!empty($todayCheckIn)) {
                $data->todayCheckIn =  date('H:i', strtotime($todayCheckIn->action_time));
            } else {
                $data->todayCheckIn = "--";
            }
            if (!empty($todayCheckOut)) {
                $data->todayCheckOut =  date('H:i', strtotime($todayCheckOut->action_time));
            } else {
                $data->todayCheckOut = "--";
            }
            return $this->apiSuccessWithData(SUCCESS, $data);
        } catch (QueryException $e) {
            return $this->apiError($e->getMessage(), ERROR_500);
        } catch (Exception $e) {
            return $this->apiError($e->getMessage(), ERROR_500);
        } catch (JWTException $e) {
            // something went wrong whilst attempting to encode the token
            return $this->apiError($e->getMessage(), ERROR_500);
        }
    }
}
