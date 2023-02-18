<?php

namespace App\Http\Controllers\Api\LeaveRequest;

use App\Drivers\FirebaseDriver;
use App\Http\Controllers\Controller;
use App\Http\Requests\Api\LeaveRequest\NewLeaveRequest;
use App\Http\Requests\Api\LeaveRequest\UpdateLeaveRequest;
use App\LeaveRequest;
use App\LeaveRequestAttachment;
use App\User;
use App\UserLeave;
use App\UserLeaveQuota;
use Carbon\Carbon;
use Exception;
use Illuminate\Database\QueryException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class LeaveRequestController extends Controller
{
    public function __construct()
    {
        $this->model = new LeaveRequest();
        $this->defaultRedirectPath = URL_ADMIN . "leave-requests/";
        $this->defaultViewPath = "admin.leave-request.";
        $this->userLeaveQuota = new UserLeaveQuota();
        $this->userLeave = new UserLeave();
    }

    public function listing(Request $request)
    {
        $query = LeaveRequest::query()->where('user_id', Auth::id());
        if (isset($request->status)) {
            $query->where('status', $request->status);
        }
        if (isset($request->type)) {
            $query->where('type', $request->type);
        }
        if (isset($request->period_type)) {
            $query->where('period_type', $request->period_type);
        }

        $leaves = $query->orderBy('id', 'DESC')->paginate(DATA_PER_PAGE);
        $toResturn['leaves'] = $leaves;
        return $this->apiSuccessWithData('', $toResturn);
    }


    public function AddLeaveRequest()
    {
        $nextDateCheck = "enable";
        $nextDate = '';
        $startDate = '';
        if ($startTime = User::find(Auth::id())->latePolicy()->first()->start_time) {
            $currentDate = Carbon::now();
            $nextDate = Carbon::now()->addHours(24)->setTime($startTime, 0);
            $subDateTime =  $nextDate->copy()->subHours(17);
            if ($currentDate < $subDateTime) {
                $nextDateCheck = "enable";
                $startDate = $nextDate;
            } else {
                $nextDateCheck = "disable";
                $startDate = Carbon::now()->addHours(48)->format('Y-m-d');
            }
        } else {
            $nextDateCheck = "enable";
        }
        $toReturn['nextDateCheck'] = $nextDateCheck;
        $toReturn['startDate'] = $startDate;
        return $this->apiSuccessWithData('', $toReturn);
    }

    public function edit($id)
    {
        try {
            $leaveRequest = $this->model->newQuery()->with('leaveRequestAttachments')->where('id', $id)->first();
            if ($leaveRequest->status == 0) {
                $toResturn['leaveRequest'] = $leaveRequest;
                return $this->apiSuccessWithData('', $toResturn);
            } else {
                return $this->apiSuccess("You are not allowed to do this operation");
            }
        } catch (QueryException $e) {
            DB::rollBack();
            return $this->apiError('Something went wrong', ERROR_400);
        } catch (Exception $e) {
            DB::rollBack();
            return $this->apiError('Something went wrong', ERROR_400);
        }
    }
    public function details($id)
    {
        try {
            $leaveRequest = $this->model->newQuery()->with('leaveRequestAttachments')->where('id', $id)->first();
            $toResturn['leaveRequest'] = $leaveRequest;
            return $this->apiSuccessWithData('', $toResturn);
        } catch (QueryException $e) {
            DB::rollBack();
            return $this->apiError('Something went wrong', ERROR_400);
        } catch (Exception $e) {
            DB::rollBack();
            return $this->apiError('Something went wrong', ERROR_400);
        }
    }


    public function request(NewLeaveRequest $request)
    {
        try {
            DB::beginTransaction();
            $inputs = '';
            if ($request->hasFile('attachments')) {
                $inputs = $request->except('attachments');
            } else {
                $inputs = $request->all();
            }
            $model = $this->model->newInstance();
            $model->fill($inputs);
            $model->user_id = Auth::id();
            if (!$model->save()) {
                DB::rollback();
                return $this->apiError('Some fields are missing', ERROR_400);
            }
            if ($request->hasFile('attachments')) {
                $inputFiles = $request->file('attachments');
                foreach ($inputFiles as $key => $attachment) {
                    $leaveRequestAttactment = new LeaveRequestAttachment();
                    $leaveRequestAttactment->file = $this->uploadFiles('leave-request-attachments', $attachment, 'Document_' . $key);
                    $leaveRequestAttactment->leave_request_id = $model->id;
                    $leaveRequestAttactment->save();
                }
            }
            $notify = new FirebaseDriver();
            $notify->setMessageBody("Leave Request", "You Have New Leave Request", NOTIFICATION_LEAVE, $model->id);
            $notify->sendNotificationToUser("Admins", $model->id);

            DB::commit();
            return $this->apiSuccess("Your Request Submitted Successfully");
        } catch (QueryException $e) {
            DB::rollBack();
            return $this->apiError("Something went wrong", ERROR_400);
        } catch (Exception $e) {
            DB::rollBack();
            return $this->apiError("Something went wrong", ERROR_400);
        }
    }

    public function update(UpdateLeaveRequest $request)
    {
        try {
            DB::beginTransaction();
            $inputs = '';
            if ($request->hasFile('attachments')) {
                $inputs = $request->except('attachments');
            } else {
                $inputs = $request->all();
            }
            $model = $this->model->newQuery()->where('id', $inputs['id'])->first();
            $model->fill($inputs);
            if (!$model->save()) {
                DB::rollback();
                return $this->apiError('Some fields are missing', ERROR_400);
            }

            if (isset($inputs['files_exits'])) {
                foreach ($model->leaveRequestAttachments()->whereNotIn('id', $inputs['files_exits'])->get() as $key => $value) {
                    $this->deleteFile('uploads/leave-request-attachments/' . $value->file);
                }
                $model->leaveRequestAttachments()->whereNotIn('id', $inputs['files_exits'])->delete();
            } else if ($model->leaveRequestAttachments->count() > 0 && $inputs['type'] == 0) {
                foreach ($model->leaveRequestAttachments()->get() as $key => $value) {
                    $this->deleteFile('uploads/leave-request-attachments/' . $value->file);
                }
                $model->leaveRequestAttachments()->delete();
            }

            if ($request->hasFile('attachments')) {
                foreach ($request->file('attachments') as $key => $attachment) {
                    $leaveRequestAttactment = new LeaveRequestAttachment();
                    $leaveRequestAttactment->file = $this->uploadFiles('leave-request-attachments', $attachment, $key);
                    $leaveRequestAttactment->leave_request_id = $model->id;
                    $leaveRequestAttactment->save();
                }
            }
            DB::commit();
            return $this->apiSuccess('Your Request Updated Successfully');
        } catch (QueryException $e) {
            DB::rollBack();
            dd($e->getMessage());
            return $this->apiError('Some fields are missing', ERROR_400);
        } catch (Exception $e) {
            dd($e->getMessage());
            DB::rollBack();
            return $this->apiError('Some fields are missing', ERROR_400);
        }
    }
}
