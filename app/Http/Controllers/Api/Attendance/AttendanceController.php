<?php

namespace App\Http\Controllers\Api\Attendance;

use App\Attendance;
use App\Http\Controllers\Controller;
use App\Http\Requests\Api\Attendance\AttandanceDetailRequest;
use App\User;
use Carbon\Carbon;
use Exception;
use Illuminate\Database\QueryException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use stdClass;

class AttendanceController extends Controller
{
    /*
    |
    |   Controller Instance
    |
    */
    public function __construct()
    {
        $this->model = new Attendance();
        $this->user = new User();
        $this->numbers = [1, 2, 3, 4, 5, 6, 7, 8, 9, 10];
    }

    /*
    |
    |   Getting Attendance Details
    |
    */
    public function details(AttandanceDetailRequest $request)
    {
        try {
            $user = $this->user->newQuery()->where('id', Auth::id())->first();
            $date = $request->all();
            $date = Carbon::parse($date['year'] . '-' . $date['month'] . '-01')->format("M Y");
            $data = [];
            if ($user) {
                $totalDeduction = 0;
                $totalMinutes = 0;
                $monthDates = getPeriod($date);
                foreach ($monthDates as $key => $date) {
                    $data['attendance'][$key]['date'] = $date->format('Y-m-d');
                    $data['attendance'][$key]['check_in_time'] = $user->getCheckInTime($date);
                    $data['attendance'][$key]['check_out_time'] = $user->getCheckOutTime($date);
                    $data['attendance'][$key]['title'] = getTitleText($user->checkAttendance($date));
                    $data['attendance'][$key]['icon'] = getAttendanceIconEmpPortal($user->checkAttendance($date));
                    $late = $user->getLateMinutes($date);
                    if (is_array($late)) {
                        $data['attendance'][$key]['minutes'] = $late['read_able'];
                        $data['attendance'][$key]['deduction'] = $late['deduction'];
                        $totalMinutes = $totalMinutes + explode(" ", $late['read_able'])[0];
                        $totalDeduction = $totalDeduction + explode(" ", $late['deduction'])[0];
                    } else {
                        $data['attendance'][$key]['minutes'] = "-";
                        $data['attendance'][$key]['deduction'] = "-";
                    }
                    $data['attendance'][$key]['attendance_title'] = getTitleText($user->checkAttendance($date));
                }
                $data['total']['deduction'] = round($totalDeduction * 100) / 100;
                $data['total']['minutes'] = $totalMinutes;
            }
            $json = array();
            return $this->apiSuccessWithData(SUCCESS, $data);
        } catch (QueryException $e) {
            return $this->apiError(__('default_label.something_went_wrong'), ERROR_500);
        } catch (Exception $e) {
            return $this->apiError(__('default_label.something_went_wrong'), ERROR_500);
        }
    }
}
