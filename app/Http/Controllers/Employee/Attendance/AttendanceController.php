<?php

namespace App\Http\Controllers\Employee\Attendance;

use App\Attendance;
use App\Http\Controllers\Controller;
use App\User;
use Carbon\Carbon;
use Carbon\CarbonPeriod;
use Exception;
use Illuminate\Database\QueryException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use stdClass;
use Illuminate\Pagination\Paginator;
use Illuminate\Support\Collection;
use Illuminate\Pagination\LengthAwarePaginator;

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
    |   Getting Listing Attendance
    |
    */
    public function index(Request $request)
    {
        try {
            $user = $this->user->newQuery()->where('id', Auth::id())->first();
            if ($user) {
                $joinCurrentDiff = Carbon::parse($user->doj)->subMonth()->diffInYears(Carbon::now());
                $years = [];
                for ($i = 0; $i <= $joinCurrentDiff; $i++) {

                    if ($i == 0) {
                        $month = time();
                    } else {
                        $month = strtotime("-" . $i . " year");
                    }

                    $monthDates = getPeriod(date("Y", $month));
                    $years[$i] = date('Y', $month);
                }
                $month = CarbonPeriod::create(Carbon::now()->startOfYear()->format('Y-m-d'), '1 month', Carbon::now()->format('Y-m-d'));
            }

            return view('employees.attendance.index', compact('years', 'month'));

            // return $this->apiSuccessWithData(SUCCESS, $data);
        } catch (QueryException $e) {
            dd($e);
            return redirect()->back()->witu('error', "Something Went Wrong");
        } catch (Exception $e) {
            dd($e);
            return redirect()->back()->witu('error', "Something Went Wrong");
        }
    }
    // public function index(Request $request)
    // {
    //     try {
    //         $user = $this->user->newQuery()->where('id', Auth::id())->first();
    //         if ($user) {
    //             $joinCurrentDiff = Carbon::parse($user->doj)->subMonth()->diffInMonths(Carbon::now());
    //             $attentandance = [];
    //             for ($i = 0; $i <= $joinCurrentDiff; $i++) {

    //                 if ($i == 0) {
    //                     $month = time();
    //                 } else {
    //                     $month = strtotime("-" . $i . " month");
    //                 }

    //                 $monthDates = getPeriod(date("M Y", $month));
    //                 $monthName = date('M Y', $month);
    //                 foreach ($monthDates as $key => $value) {
    //                     $checkAvailability = $user->checkAttendance($value);
    //                     if ($value->format('Y-m-d') > date('Y-m-d')) {
    //                         continue;
    //                     }

    //                     $attentandance[$i][$key]['title'] = getTitleText($checkAvailability);
    //                     $attentandance[$i][$key]['icon'] = getAttendanceIconEmpPortal($checkAvailability);
    //                     $attentandance[$i][$key]['day'] = (int) $value->format('j');
    //                     $attentandance[$i][$key]['color'] = getTextColor($checkAvailability);
    //                     $attentandance[$i][$key]['month'] = date('M Y', $month);
    //                 }
    //             }
    //             $data['user'] = $user;
    //             $data['attendance'] = $this->paginateLengthAware($attentandance, 5);
    //         }

    //         return view('employees.attendance.index', compact('data'));

    //         // return $this->apiSuccessWithData(SUCCESS, $data);
    //     } catch (QueryException $e) {
    //         dd($e);
    //         return redirect()->back()->witu('error', "Something Went Wrong");
    //     } catch (Exception $e) {
    //         dd($e);
    //         return redirect()->back()->witu('error', "Something Went Wrong");
    //     }
    // }

    /*
    |
    |   Getting Attendance Details
    |
    */
    public function details(Request $date)
    {
        try {
            $user = $this->user->newQuery()->where('id', Auth::id())->first();
            $date = $date->all();
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
            return response()->json(['data' => json_encode($data)], 200);
        } catch (QueryException $e) {
            dd($e);
            return $this->apiError(__('default_label.something_went_wrong'), ERROR_500);
        } catch (Exception $e) {
            dd($e);
            return $this->apiError(__('default_label.something_went_wrong'), ERROR_500);
        }
    }



    public function getMonths(Request $request)
    {
        $months = array();
        $result = null;
        $doj = Carbon::parse(Auth()->user()->doj);
        if ($doj->format("Y") == $request->year) {
            $result = CarbonPeriod::create($doj->startOfMonth()->format('Y-m-d'), '1 month', $doj->endOfYear());
        } else if ($request->year == Carbon::now()->format('Y')) {
            $result = CarbonPeriod::create(Carbon::now()->startOfYear()->format('Y-m-d'), '1 month', Carbon::now()->format('Y-m-d'));
        } else {
            $result = CarbonPeriod::create($request->year . "-01-01", '1 month', $request->year . "-12-31");
        }
        // foreach ($result as $k => $m) {
        //     $months[$k] = $m->format('M');
        // }
        $html = "<option value='0'>Select Month</option>";
        foreach ($result as $k => $m) {
            $html = $html . "<option value='" . $m->format('m') . "'>" . $m->format('M') . "</option>";
        }
        return response()->json(['months' => $html], 200);
    }
}
