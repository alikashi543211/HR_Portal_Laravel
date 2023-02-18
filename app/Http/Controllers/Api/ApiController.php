<?php

namespace App\Http\Controllers\Api;

use App\Attendance;
use App\Http\Controllers\Controller;
use App\Http\Requests\Api\Attendance\StoreRequest;
use App\User;
use App\UserNotification;
use Carbon\Carbon;
use Exception;
use Illuminate\Database\QueryException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class ApiController extends Controller
{
    public function __construct()
    {
        $this->attendance = new Attendance();
        $this->user = new User();
    }

    /*
    |
    |   Creating Admin
    |
    */
    public function checkIn(StoreRequest $request)
    {
        try {
            DB::beginTransaction();
            $inputs = $request->all();
            Log::info('request: ' . json_encode($inputs));
            if ($inputs['AttStatus'] != 0 && $inputs['AttStatus'] != 1) {
                return response()->json([
                    'success' => false,
                    'message' => 'Invalid AttStatus'
                ]);
            }
            if (!$user = $this->user->newQuery()->where('finger_print_id', $inputs['EnrollNo'])->first()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Invalid EnrollNo'
                ]);
            }
            if ($inputs['AttStatus'] == 0) {
                if ($this->attendance->newQuery()->where('user_id', $user->id)->where('type',  CHECK_IN)->where('action_time', 'LIKE', '%' . date('Y-m-d') . '%')->exists()) {
                    return response()->json([
                        'success' => true,
                        'message' => 'Attendance Marked'
                    ]);
                }
            } else {
                $this->attendance->newQuery()->where('user_id', $user->id)->where('type', CHECK_OUT)->where('action_time', 'LIKE', '%' . date('Y-m-d') . '%')->delete();
            }

            $attendance = $this->attendance->newInstance();
            $attendance->user_id = $user->id;
            $attendance->action_time = Carbon::now()->format('Y-m-d H:i');
            $attendance->created_by = $user->id;
            $attendance->type = $inputs['AttStatus'] == 0 ? CHECK_IN : CHECK_OUT;
            if ($attendance->save()) {
                DB::commit();
                return response()->json([
                    'success' => true,
                    'message' => 'Attendance Marked'
                ]);
            }

            DB::rollback();
            return Redirect()->back();
        } catch (QueryException $e) {
            DB::rollBack();
            return Redirect()->back();
        } catch (Exception $e) {
            DB::rollBack();
            return Redirect()->back();
        }
    }
}
