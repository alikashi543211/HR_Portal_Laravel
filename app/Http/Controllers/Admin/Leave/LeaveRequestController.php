<?php

namespace App\Http\Controllers\Admin\Leave;

use App\Drivers\FirebaseDriver;
use App\Http\Controllers\Controller;
use App\LeaveRequest;
use App\UserLeave;
use App\UserLeaveQuota;
use Carbon\Carbon;
use Carbon\CarbonPeriod;
use Exception;
use Illuminate\Database\QueryException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;

class LeaveRequestController extends Controller
{
    private $model, $defaultRedirectPath, $defaultViewPath;
    /*
    |
    |   Controller Instance
    |
    */

    public function __construct()
    {
        $this->model = new LeaveRequest();
        $this->defaultRedirectPath = URL_ADMIN . "leave-requests/";
        $this->defaultViewPath = "admin.leave-request.";
        $this->userLeaveQuota = new UserLeaveQuota();
        $this->userLeave = new UserLeave();
    }

    /*
    |
    |   Getting Listing admin
    |
    */
    public function listing(Request $request)
    {
        try {
            $query = $this->model->newQuery()->orderBy('created_at', 'DESC');
            $inputs = $request->all();
            if (!empty($inputs['search'])) {
                $query->where(function ($q) use ($inputs) {
                    $this->search($q, $inputs['search'], ['first_name', 'last_name', 'email', 'personal_email', 'phone_number', 'cnic', 'doj', 'dob', 'finger_print_id', 'employee_id', 'designation', 'nationality', 'emergency_contact_name', 'emergency_contact_relation', 'base_salary', 'emergency_contact_number', 'dop'], 'user');
                });
            }
            $data = $query->paginate(DATA_PER_PAGE);
            return $this->successListView("", $this->defaultViewPath . "listing", __('leave.page_heading'), $data, true, false);
        } catch (QueryException $e) {
            Session::flash($e->getMessage());
            return Redirect()->back();
        } catch (Exception $e) {
            Session::flash($e->getMessage());
            return Redirect()->back();
        }
    }

    public function details($id)
    {
        $leaveRequest = $this->model->newQuery()->where('id', $id)->first();
        return $this->successView(NULL, $this->defaultViewPath . 'details', __('leave.page_heading'), $leaveRequest);
    }

    /*
    |
    |   Accept Leave Request
    |
    */
    public function update(Request $request)
    {
        $fromDate = Carbon::parse($request->from_date);
        $toDate = Carbon::parse($request->to_date)->addDay();
        $total_off_days_req = $fromDate->diffInDays($toDate);
        try {
            $inputs = $request->all();
            if ($leaveRequest = $this->model->newQuery()->where('id', $inputs['id'])->first()) {
                DB::beginTransaction();
                $leaveRequest->status = ACCEPTED;
                if ($leaveRequest->save()) {
                    $quota = $this->userLeaveQuota->newQuery()->whereUserId($leaveRequest->user_id)->first();
                    $oldLeaves = $this->userLeave->newQuery()->whereUserId($leaveRequest->user_id)->whereBetween('date', [date('Y-m-d', strtotime($leaveRequest->from_date)), date('Y-m-d', strtotime($leaveRequest->to_date . ' 1 days'))])->get();
                    foreach ($oldLeaves as $key => $oldLeave) {
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
                    }


                    if ($inputs['leave_adjust'] == LEAVE_ADJUST) {
                        if (!$quota) {
                            return $this->redirectBack(ERROR, 'User doesn\'t have any Leaves');
                        }
                        if ($leaveRequest->type == SICK_LEAVE) {
                            if ($quota->remaining_sick_leaves < 0.5) {
                                return $this->redirectBack(ERROR, 'User doesn\'t have any sick leave');
                            }
                        } else {
                            if ($quota->remaining_casual_leaves < 0.5) {
                                return $this->redirectBack(ERROR, 'User doesn\'t have any casual leave');
                            }
                        }
                    }
                    $leaveDates = CarbonPeriod::create($fromDate, $toDate->subDay());

                    foreach ($leaveDates  as $key => $value) {
                        $leave = $this->userLeave->newInstance();
                        $leave->user_id = $leaveRequest->user_id;
                        $leave->type = $leaveRequest->type;
                        $leave->date = $value;
                        $leave->reason = $leaveRequest->reason;
                        $leave->leave_adjust = $inputs['leave_adjust'];
                        $leave->period = $leaveRequest->period;
                        $leave->period_type = $leaveRequest->period_type;
                        if ($leave->save()) {

                            if ($inputs['leave_adjust'] == LEAVE_ADJUST) {
                                if ($leaveRequest->type == SICK_LEAVE) {
                                    $quota->remaining_sick_leaves = $quota->remaining_sick_leaves - ($leaveRequest->period == HALF_DAY_LEAVE ? 0.5 : 1);
                                    $quota->used_sick_leaves = $quota->used_sick_leaves + ($leaveRequest->period == HALF_DAY_LEAVE ? 0.5 : 1);
                                } else {
                                    $quota->remaining_casual_leaves = $quota->remaining_casual_leaves - ($leaveRequest->period == HALF_DAY_LEAVE ? 0.5 : 1);
                                    $quota->used_casual_leaves = $quota->used_casual_leaves + ($leaveRequest->period == HALF_DAY_LEAVE ? 0.5 : 1);
                                }
                                if (!$quota->save()) {
                                    DB::rollBack();
                                }
                            }
                        }
                    }

                    $notify = new FirebaseDriver();
                    $notify->setMessageBody("Leave Request Approval", "Your leave request has been approved", NOTIFICATION_LEAVE, $request->id);
                    $notify->sendNotificationToUser("Employees", $leaveRequest->user->id);

                    DB::commit();
                    return $this->redirectBack(SUCCESS, __('default_label.updated'), $this->defaultRedirectPath . "listing");
                }
                DB::rollback();
                return $this->redirectBack(ERROR, __('default_label.something_went_wrong'));
            } else return $this->redirectBack(ERROR, __('default_label.something_went_wrong'));
        } catch (QueryException $e) {
            dd($e->getMessage());
            Session::flash($e->getMessage());
            return Redirect()->back();
        } catch (Exception $e) {
            dd($e->getMessage());
            Session::flash($e->getMessage());
            return Redirect()->back();
        }
    }

    /*
    |
    |   Reject Leave Request
    |
    */
    public function reject($id)
    {
        try {
            if ($leaveRequest = $this->model->newQuery()->where('id', $id)->first()) {
                DB::beginTransaction();
                $leaveRequest->status = REJECTED;
                if ($leaveRequest->save()) {
                    DB::commit();

                    $notify = new FirebaseDriver();
                    $notify->setMessageBody("Leave Request Rejection", "Your leave request has been rejected", NOTIFICATION_LEAVE, $id);
                    $notify->sendNotificationToUser("Employees", $leaveRequest->user->id);

                    return $this->redirectBack(SUCCESS, __('default_label.updated'), $this->defaultRedirectPath . "listing");
                }
                DB::rollback();
                return $this->redirectBack(ERROR, __('default_label.something_went_wrong'));
            } else return $this->redirectBack(ERROR, __('default_label.something_went_wrong'));
        } catch (QueryException $e) {
            Session::flash($e->getMessage());
            return Redirect()->back();
        } catch (Exception $e) {
            Session::flash($e->getMessage());
            return Redirect()->back();
        }
    }

    public function delete($id)
    {
        try {
            if ($leaveRequest = $this->model->newQuery()->where('id', $id)->first()) {
                DB::beginTransaction();
                if ($leaveRequest->delete()) {
                    DB::commit();
                    return $this->redirectBack(SUCCESS, __('default_label.delete'), $this->defaultRedirectPath . "listing");
                }
                DB::rollback();
                return $this->redirectBack(ERROR, __('default_label.something_went_wrong'));
            } else return $this->redirectBack(ERROR, __('default_label.something_went_wrong'));
        } catch (QueryException $e) {
            Session::flash($e->getMessage());
            return Redirect()->back();
        } catch (Exception $e) {
            Session::flash($e->getMessage());
            return Redirect()->back();
        }
    }
}
