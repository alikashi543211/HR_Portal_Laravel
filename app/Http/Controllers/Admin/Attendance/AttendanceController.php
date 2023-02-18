<?php

namespace App\Http\Controllers\Admin\Attendance;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\Attendance\StoreRequest;
use App\Http\Requests\Admin\Attendance\UpdateRequest;
use App\User;
use App\Attendance;
use App\Exports\AttendanceExport;
use App\UserLeave;
use App\UserLeaveQuota;
use Carbon\Carbon;
use Carbon\CarbonInterval;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use App\Imports\AttendanceImport;
use App\UserAttendanceException;
use Exception;
use Illuminate\Database\QueryException;
use Maatwebsite\Excel\Facades\Excel;
use Str;

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
        $this->userLeaveQuota = new UserLeaveQuota();
        $this->userLeave = new UserLeave();
        $this->userAttException = new UserAttendanceException();
        $this->defaultRedirectPath = URL_ADMIN . "attendances/";
        $this->defaultViewPath = "admin.attendances.";
    }

    /*
    |
    |   Creating Admin
    |
    */
    public function store(StoreRequest $request)
    {
        try {
            DB::beginTransaction();
            $inputs = $request->all();
            if ($inputs['type'] == CHECK_IN) {
                if ($this->model->newQuery()->where('user_id', $inputs['user_id'])->where('type', $inputs['type'])->where('action_time', 'LIKE', '%' . date('Y-m-d', strtotime($inputs['action_time'])) . '%')->exists()) {
                    return $this->redirectBack(SUCCESS, __('default_label.saved'), $this->defaultRedirectPath . "listing", "url");
                }
                $this->model->newQuery()->where('user_id', $inputs['user_id'])->where('type', CHECK_OUT)->where('action_time', 'LIKE', '%' . date('Y-m-d', strtotime($inputs['action_time'])) . '%')->delete();
            }
            $model = $this->model->newInstance();
            $inputs['created_by'] = Auth::id();
            $model->fill($inputs);

            if ($model->save()) {
                DB::commit();
                return $this->redirectBack(SUCCESS, __('default_label.saved'), $this->defaultRedirectPath . "listing", "url");
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

    /*
    |
    |   Updating admin
    |
    */
    public function update(UpdateRequest $request)
    {
        try {

            DB::beginTransaction();
            $inputs = $request->all();
            $model = $this->model->newQuery()->whereId($inputs['id'])->first();

            $inputs['updated_by'] = Auth::id();
            $model->fill($inputs);

            if ($model->save()) {
                DB::commit();
                return $this->redirectBack("success", __('default_label.updated'), $this->defaultRedirectPath . "listing");
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

    /*
    |
    |   Store admin
    |
    */
    public function ajaxStore(Request $request)
    {
        try {

            DB::beginTransaction();
            $inputs = $request->all();

            $model = $this->model->newInstance();
            $inputs['created_by'] = Auth::id();
            $model->fill($inputs);

            if ($model->save()) {
                $model->time = date('H:i', strtotime($model->action_time));
                DB::commit();
                return ['success' => true, 'message' => __('default_label.saved'), 'model' => $model];
            }

            DB::rollback();
            return ['success' => false, 'message' => __('default_label.save')];
        } catch (QueryException $e) {
            DB::rollBack();
            return ['success' => false, 'message' => $e->getMessage()];
        } catch (Exception $e) {
            DB::rollBack();
            return ['success' => false, 'message' => $e->getMessage()];
        }
    }

    /*
    |
    |   Updating admin
    |
    */
    public function ajaxUpdate(Request $request)
    {
        try {

            DB::beginTransaction();
            $inputs = $request->all();
            $model = $this->model->newQuery()->whereId($inputs['id'])->first();

            $inputs['updated_by'] = Auth::id();
            $model->fill($inputs);

            if ($model->save()) {
                $model->time = date('H:i', strtotime($model->action_time));
                DB::commit();
                return ['success' => true, 'message' => __('default_label.updated'), 'model' => $model];
            }

            DB::rollback();
            return ['success' => false, 'message' => __('default_label.update')];
        } catch (QueryException $e) {
            DB::rollBack();
            return ['success' => false, 'message' => $e->getMessage()];
        } catch (Exception $e) {
            DB::rollBack();
            return ['success' => false, 'message' => $e->getMessage()];
        }
    }

    /*
    |
    |   Getting delete admin
    |
    */
    public function delete($id)
    {
        try {
            DB::beginTransaction();
            $user = $this->model->newQuery()->whereId($id)->first();

            if ($user && $user->delete()) {
                DB::commit();
                return $this->redirectBack("", __('default_label.deleted'), $this->defaultRedirectPath . "listing");
            }

            DB::rollback();
            return $this->redirectBack("", __('default_label.delete'));
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

    /*
    |
    |   Getting Listing admin
    |
    */
    public function listing(Request $request)
    {
        try {
            $user = $this->user->newQuery()->where('role_id', '>', SUPER_ADMIN)->orderBy('first_name', 'ASC');
            $month = date('Y-m', request('date') ? strtotime(str_replace(',', '', request('date'))) : strtotime('now'));

            $user->where(function ($q) use ($month) {
                $q->where(function ($q) use ($month) {
                    $q->where('dot', '>=', $month . '-01')
                        ->where('doj', '<=', $month . '-31');
                })
                    ->orWhereNull('dot');
            });


            $inputs = $request->all();
            if (!empty($inputs['search'])) {

                $user->where(function ($q) use ($inputs) {
                    $this->search($q, $inputs['search'], getSearchColoumns('attendances'));
                });
            }
            return $this->successListView("", $this->defaultRedirectPath . "listing", __('attendance.page_heading'), $user->paginate(DATA_PER_PAGE));
        } catch (QueryException $e) {
            \Session::flash($e->getMessage());
            return Redirect()->back();
        } catch (Exception $e) {
            \Session::flash($e->getMessage());
            return Redirect()->back();
        }
    }

    /*
    |
    |   Creating view to add User
    |
    */
    public function create(Request $req)
    {
        try {
            return $this->successView("", $this->defaultViewPath . "add", __('attendance.add_page_heading'), "");
        } catch (QueryException $e) {
            \Session::flash($e->getMessage());
            return Redirect()->back();
        } catch (Exception $e) {
            \Session::flash($e->getMessage());
            return Redirect()->back();
        }
    }

    /*
    |
    |   EDIT view to add User
    |
    */
    public function edit(Request $req, $id)
    {
        try {

            DB::beginTransaction();
            $inputs = $req->all();
            $model = $this->model->newQuery()->whereId($id)->first();
            if ($model) {

                return $this->successView("", $this->defaultViewPath . "edit", __('attendance.edit_page_heading'), $model);
            }

            DB::rollback();
            return $this->redirectBack("", __('default_label.fetch'));
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

    public function getDateDetails(Request $request)
    {
        $date1 = '';
        $date2 = '';
        $checkIn = $this->model->newQuery()->whereDate('action_time', $request->date)->whereUserId($request->user_id)->whereType(CHECK_IN)->first();
        if ($checkIn) {
            $checkIn->time = date('H:i', strtotime($checkIn->action_time));
            $date1 = $checkIn->action_time;
        } else {
            $date1 = date('Y-m-d H:i', strtotime("+14 hours", strtotime($request->date)));
        }
        $checkOut = $this->model->newQuery()->whereDate('action_time', $request->date)->whereUserId($request->user_id)->whereType(CHECK_OUT)->first();
        if ($checkOut) {
            $checkOut->time = date('H:i', strtotime($checkOut->action_time));
            $date2 = $checkOut->action_time;
        } else {
            $date2 = date('Y-m-d H:i', strtotime("+18 hours 15 minutes", strtotime($request->date)));
        }
        $date1 = Carbon::createFromFormat('Y-m-d H:i', $date1);
        $date2 = Carbon::createFromFormat('Y-m-d H:i', $date2);
        $diff = $date1->diffInSeconds($date2);

        $adjustHalfLeave = false;
        if ($checkIn && $checkOut && checkPermission(LEAVES, WRITE)) {
            if (strtotime($checkIn->action_time) > strtotime('+13 hours', strtotime(date('Y-m-d', strtotime($checkIn->action_time))))) {
                $adjustHalfLeave = true;
            } else if (strtotime($checkOut->action_time) < strtotime('+14 hours', strtotime(date('Y-m-d', strtotime($checkOut->action_time))))) {
                $adjustHalfLeave = true;
            }
        }

        $diff = gmdate('H:i', $diff);
        $checkinUpdatedBy = !empty($checkIn->updatedBy) ? $checkIn->updatedBy->full_name : 'System';
        $checkoutUpdatedBy = !empty($checkOut->updatedBy) ? $checkOut->updatedBy->full_name : 'System';
        return ['check_in' => $checkIn, 'check_out' => $checkOut, 'total_time' => $diff, 'adjust_half_leave' => $adjustHalfLeave, 'checkin_updated_by' => $checkinUpdatedBy, 'checkout_updated_by' => $checkoutUpdatedBy];
    }

    public function summaryDetails($id)
    {
        $user = $this->user->newQuery()->whereId($id)->first();
        return $this->successView("", $this->defaultViewPath . 'userSummary', 'Attendance Summary (' . $user->full_name . ')', $user);
    }

    public function adjustLeave(Request $request)
    {
        try {
            DB::beginTransaction();
            $inputs = $request->all();
            $quota = $this->userLeaveQuota->newQuery()->whereUserId($inputs['user_id'])->first();
            $oldLeave = $this->userLeave->newQuery()->whereUserId($inputs['user_id'])->where('date', $inputs['date'])->first();

            if ($oldLeave) {
                if ($oldLeave->leave_adjust == LEAVE_ADJUST) {
                    $dayPoints = $oldLeave->period == HALF_DAY_LEAVE ? 0.5 : 1;
                    if ($oldLeave->type == SICK_LEAVE) {
                        $quota->remaining_sick_leaves = $quota->remaining_sick_leaves + $dayPoints;
                        $quota->used_sick_leaves = $quota->used_sick_leaves - $dayPoints;
                    } else {
                        $quota->remaining_casual_leaves = $quota->remaining_casual_leaves + $dayPoints;
                        $quota->used_casual_leaves = $quota->used_casual_leaves - $dayPoints;
                    }
                    if (!$quota->save()) {
                        DB::rollBack();
                    }
                }
                if (!$oldLeave->delete()) {
                    DB::rollBack();
                }
            }

            if ($inputs['leave_adjust'] == LEAVE_ADJUST) {
                $error = $request->summary ? 'summary_error' : 'leave_error';
                if (!$quota) {
                    return Redirect()->back()->with($error, 'User doesn\'t have any Leaves')->withInput();
                }
                if ($inputs['type'] == SICK_LEAVE) {
                    if ($quota->remaining_sick_leaves < 1) {
                        return Redirect()->back()->with($error, 'User doesn\'t have any sick leave')->withInput();
                    }
                } else {
                    if ($quota->remaining_casual_leaves < 1) {
                        return Redirect()->back()->with($error, 'User doesn\'t have any casual leave')->withInput();
                    }
                }
            }

            $leave = $this->userLeave->newInstance();

            $leave->user_id = $inputs['user_id'];
            $leave->type = $inputs['type'];
            $leave->date = $inputs['date'];
            $leave->reason = $inputs['reason'];
            $leave->leave_adjust = $inputs['leave_adjust'];
            $leave->period = FULL_DAY_LEAVE;
            $leave->period_type = FULL_DAY;

            if ($leave->save()) {

                if ($inputs['leave_adjust'] == LEAVE_ADJUST) {
                    if ($inputs['type'] == SICK_LEAVE) {
                        $quota->remaining_sick_leaves = $quota->remaining_sick_leaves - 1;
                        $quota->used_sick_leaves = $quota->used_sick_leaves + 1;
                    } else {
                        $quota->remaining_casual_leaves = $quota->remaining_casual_leaves - 1;
                        $quota->used_casual_leaves = $quota->used_casual_leaves + 1;
                    }
                    if (!$quota->save()) {
                        DB::rollBack();
                    }
                }

                DB::commit();
                return redirect()->back()->with('summaryModal', $request->summary)->with('userId', $request->user_id)->with(SUCCESS, __('default_label.saved'));
                // return $this->redirectBack(SUCCESS, __('default_label.saved'), $this->defaultRedirectPath . "listing", "url");
            }

            DB::rollback();
            return Redirect()->back()->with('leave_error', __('default_label.save'));
        } catch (QueryException $e) {
            DB::rollBack();
            return Redirect()->back()->with('leave_error', $e->getMessage());
        } catch (Exception $e) {
            DB::rollBack();
            return Redirect()->back()->with('leave_error', $e->getMessage());
        }
    }

    public function adjustHalfLeave(Request $request)
    {
        try {
            DB::beginTransaction();
            $inputs = $request->all();

            $quota = $this->userLeaveQuota->newQuery()->whereUserId($inputs['user_id'])->first();
            $oldLeave = $this->userLeave->newQuery()->whereUserId($inputs['user_id'])->where('date', $inputs['date'])->first();

            if ($oldLeave) {
                if ($oldLeave->leave_adjust == LEAVE_ADJUST) {
                    $dayPoints = $oldLeave->period == HALF_DAY_LEAVE ? 0.5 : 1;
                    if ($oldLeave->type == SICK_LEAVE) {
                        $quota->remaining_sick_leaves = $quota->remaining_sick_leaves + $dayPoints;
                        $quota->used_sick_leaves = $quota->used_sick_leaves - $dayPoints;
                    } else {
                        $quota->remaining_casual_leaves = $quota->remaining_casual_leaves + $dayPoints;
                        $quota->used_casual_leaves = $quota->used_casual_leaves - $dayPoints;
                    }
                    if (!$quota->save()) {
                        DB::rollBack();
                    }
                }
                if (!$oldLeave->delete()) {
                    DB::rollBack();
                }
            }

            if ($inputs['leave_adjust'] == LEAVE_ADJUST) {
                if (!$quota) {
                    return Redirect()->back()->with('half_leave_error', 'User doesn\'t have any Leaves')->withInput();
                }

                if ($inputs['type'] == SICK_LEAVE) {
                    if ($quota->remaining_sick_leaves < 0.5) {
                        return Redirect()->back()->with('half_leave_error', 'User doesn\'t have any sick leave')->withInput();
                    }
                } else {
                    if ($quota->remaining_casual_leaves < 0.5) {
                        return Redirect()->back()->with('half_leave_error', 'User doesn\'t have any casual leave')->withInput();
                    }
                }
            }

            $leave = $this->userLeave->newInstance();

            $leave->user_id = $inputs['user_id'];
            $leave->type = $inputs['type'];
            $leave->date = $inputs['date'];
            $leave->reason = $inputs['reason'];
            $leave->period = HALF_DAY_LEAVE;
            $leave->period_type = $inputs['period_type'];
            $leave->leave_adjust = $inputs['leave_adjust'];

            if ($leave->save()) {
                if ($inputs['leave_adjust'] == LEAVE_ADJUST) {
                    if ($inputs['type'] == SICK_LEAVE) {
                        $quota->remaining_sick_leaves = $quota->remaining_sick_leaves - 0.5;
                        $quota->used_sick_leaves = $quota->used_sick_leaves + 0.5;
                    } else {
                        $quota->remaining_casual_leaves = $quota->remaining_casual_leaves - 0.5;
                        $quota->used_casual_leaves = $quota->used_casual_leaves + 0.5;
                    }
                    if (!$quota->save()) {
                        DB::rollBack();
                    }
                }

                DB::commit();

                return redirect()->back()->with('summaryModal', $request->summary)->with('userId', $request->user_id)->with(SUCCESS, __('default_label.saved'));
                // return $this->redirectBack(SUCCESS, __('default_label.saved'), $this->defaultRedirectPath . "listing", "url");
            }

            DB::rollback();
            return Redirect()->back()->with('half_leave_error', __('default_label.save'));
        } catch (QueryException $e) {
            DB::rollBack();
            return Redirect()->back()->with('half_leave_error', $e->getMessage());
        } catch (Exception $e) {
            DB::rollBack();
            return Redirect()->back()->with('half_leave_error', $e->getMessage());
        }
    }

    public function uploadExcelData(Request $request)
    {
        try {
            DB::beginTransaction();
            if ($request->hasFile('excel')) {
                $filename = 'excel_' . Str::random(40) . '.' . $request->excel->getClientOriginalExtension();
                $request->file('excel')->move(public_path('/uploads/attendance/'), $filename);
                ini_set('memory_limit', '1G');
                $rows = array_map('str_getcsv', file(public_path('/uploads/attendance/' . $filename)));
                foreach ($rows as $key => $row) {
                    if ($key > 0) {
                        if ($row[3] != 'C/In' && $row[3] != 'C/Out') {
                            continue;
                        }
                        $isDeleted = false;
                        $user = $this->user->newQuery()->where('finger_print_id', $row[1])->first();
                        if ($user) {
                            if ($row[3] == 'C/In') {
                                if ($checkIn = $this->model->newQuery()->where('user_id', $user->id)->where('type', CHECK_IN)->where('action_time', 'LIKE', '%' . date('Y-m-d', strtotime($row[2])) . '%')->first()) {
                                    if (strtotime($checkIn->action_time) > strtotime($row[2])) {
                                        $isDeleted = true;
                                        $this->model->newQuery()->where('user_id', $user->id)->where('type', CHECK_IN)->where('action_time', 'LIKE', '%' . date('Y-m-d', strtotime($row[2])) . '%')->delete();
                                    }
                                } else $isDeleted = true;
                            } else {
                                if ($checkOut = $this->model->newQuery()->where('user_id', $user->id)->where('type', CHECK_OUT)->where('action_time', 'LIKE', '%' . date('Y-m-d', strtotime($row[2])) . '%')->first()) {
                                    if (strtotime($checkOut->action_time) < strtotime($row[2])) {
                                        $isDeleted = true;
                                        $this->model->newQuery()->where('user_id', $user->id)->where('type', CHECK_OUT)->where('action_time', 'LIKE', '%' . date('Y-m-d', strtotime($row[2])) . '%')->delete();
                                    }
                                } else $isDeleted = true;
                            }
                            if ($isDeleted) {
                                $model = $this->model->newInstance();
                                $model->type = $row[3] == 'C/In' ? CHECK_IN : CHECK_OUT;
                                $model->user_id = $user->id;
                                $model->created_by = Auth::id();
                                $model->updated_by = Auth::id();
                                $model->action_time = date('Y-m-d H:i', strtotime($row[2]));
                                if (!$model->save()) {
                                    DB::rollback();
                                    return Redirect()->back()->with('half_leave_error', __('default_label.save'));
                                }
                            }
                        }
                    }
                }
            }
            DB::commit();
            return $this->redirectBack(SUCCESS, __('default_label.saved'), $this->defaultRedirectPath . "listing", "url");
        } catch (QueryException $e) {
            DB::rollBack();
            return Redirect()->back()->with('half_leave_error', $e->getMessage());
        } catch (Exception $e) {
            DB::rollBack();
            return Redirect()->back()->with('half_leave_error', $e->getMessage());
        }
    }

    public function updateAttendanceBulk(Request $request)
    {
        try {
            DB::beginTransaction();
            $inputs = $request->all();
            foreach ($inputs['checkIn'] as $date => $time) {
                if ($time) {
                    if (!$attendance = $this->model->newQuery()->where('user_id', $inputs['user_id'])->where('type', CHECK_IN)->where('action_time', 'LIKE', $date . '%')->first()) {
                        $attendance = $this->model->newInstance();
                        $attendance->type = CHECK_IN;
                        $attendance->user_id = $inputs['user_id'];
                        $attendance->created_by = Auth::id();
                        $attendance->updated_by = Auth::id();
                    }
                    $attendance->action_time = $date . ' ' . $time;
                    $attendance->save();
                } else $this->model->newQuery()->where('user_id', $inputs['user_id'])->where('type', CHECK_IN)->where('action_time', 'LIKE', $date . '%')->delete();
            }
            foreach ($inputs['checkOut'] as $date => $time) {

                if ($time) {
                    if (!$attendance = $this->model->newQuery()->where('user_id', $inputs['user_id'])->where('type', CHECK_OUT)->where('action_time', 'LIKE', $date . '%')->first()) {
                        $attendance = $this->model->newInstance();
                        $attendance->user_id = $inputs['user_id'];
                        $attendance->type = CHECK_OUT;
                        $attendance->created_by = Auth::id();
                        $attendance->updated_by = Auth::id();
                    }
                    $attendance->action_time = $date . ' ' . $time;
                    $attendance->save();
                } else $this->model->newQuery()->where('user_id', $inputs['user_id'])->where('type', CHECK_OUT)->where('action_time', 'LIKE', $date . '%')->delete();
            }
            DB::commit();
            return Redirect()->back()->with('success', 'Attendance Updated');
        } catch (QueryException $e) {
            DB::rollBack();
            return $this->error($e->getMessage(), ERROR_500);
        } catch (Exception $e) {
            DB::rollBack();
            return $this->error($e->getMessage(), ERROR_500);
        }
    }

    public function ExportExcel(Request $request)
    {
        try {
            return Excel::download(new AttendanceExport, 'attendance-' . date('F-Y', !empty(request('date')) ? strtotime(request('date')) : time()) . '.xlsx');
        } catch (QueryException $e) {
            \Session::flash($e->getMessage());
            return Redirect()->back();
        } catch (Exception $e) {
            \Session::flash($e->getMessage());
            return Redirect()->back();
        }
    }

    /*
    |
    |   Mark Attendance Exception
    |
    */
    public function ajaxAddAttendaceException(Request $request)
    {
        try {
            DB::beginTransaction();
            $inputs = $request->all();
            $model = $this->userAttException->newInstance();
            $model->date = $inputs['date'];
            $model->updated_by = Auth::id();
            $model->user_id = $inputs['user_id'];

            if ($model->save()) {
                DB::commit();
                \Session::flash(SUCCESS, __('default_label.updated'));
                return ['success' => true, 'message' => __('default_label.updated'), 'model' => $model];
            }

            DB::rollback();
            \Session::flash(SUCCESS, __('default_label.save'));
            return ['success' => false, 'message' => __('default_label.save')];
        } catch (QueryException $e) {
            DB::rollBack();
            return ['success' => false, 'message' => $e->getMessage()];
        } catch (Exception $e) {
            DB::rollBack();
            return ['success' => false, 'message' => $e->getMessage()];
        }
    }

    /*
    |
    |   Mark Attendance Exception
    |
    */
    public function deleteAttendaceException(Request $request, $id)
    {
        try {
            DB::beginTransaction();
            $model = $this->userAttException->newQuery()->where('user_id', $id)->where('date', $request['date'])->first();

            if ($model && $model->delete()) {
                DB::commit();
                return Redirect()->back()->with('success', __('default_label.deleted'));
            }

            DB::rollBack();
            return Redirect()->back()->with('error', __('default_label.fetch'));
        } catch (QueryException $e) {
            DB::rollBack();
            return Redirect()->back()->with('error', 'Something went wrong');
        } catch (Exception $e) {
            DB::rollBack();
            return Redirect()->back()->with('error', 'Something went wrong');
        }
    }
}
