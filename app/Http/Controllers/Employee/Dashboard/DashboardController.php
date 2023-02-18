<?php

namespace App\Http\Controllers\Employee\Dashboard;

use App\Announcement;
use App\Attendance;
use App\Http\Controllers\Controller;
use App\User;
use Exception;
use Illuminate\Database\QueryException;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use stdClass;

class DashboardController extends Controller
{
    public function __construct()
    {
        $this->user = new User();
        $this->attendance = new Attendance();
    }

    /*
    |
    |   Dashboard Details
    |
    */

    public function index(Request $request)
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


            $announcements = Announcement::orderBy('updated_at', 'DESC')->limit(5)->get();

            $artilces = '';
            if ($request->ajax()) {
                foreach ($announcements as $announcement) {
                    $artilces .= '<div class="row">
                    <div class="col-sm-12">
                        <div class="card">
                            <div class="card-body">
                                <div class="post-details">
                                    <h3 class="mb-2 text-black">' . $announcement->title . '</h3>
                                    <ul class="mb-4 post-meta">
                                        <li class="post-author"><i class="fa-regular fa-user"></i>' . $announcement->updatedBy->first_name . ' ' . $announcement->updatedBy->last_name . '</li>
                                        <li class="post-date"><i class="fa-light fa-calendar-days"></i>' . \Carbon\Carbon::parse($announcement->created_at)->format('d M Y') . '</li>
                                        <li class="post-date"><i class="fa-regular fa-clock"></i>' . \Carbon\Carbon::parse($announcement->created_at)->format('g:i A')  . '</li>
                                    </ul>
                                    <div class="row mt-2">
                                        ' . $announcement->description . '
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>';
                }
                return $artilces;
            }
            return view('employees.dashboard', compact('data', 'announcements'));
        } catch (QueryException $e) {
            return redirect()->back();
        } catch (Exception $e) {
            return redirect()->back();
        }
    }
}
