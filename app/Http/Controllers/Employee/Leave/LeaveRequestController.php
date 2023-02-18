<?php

namespace App\Http\Controllers\Employee\Leave;

use App\Drivers\FirebaseDriver;
use App\Drivers\FirebaseDriver1;
use App\Http\Controllers\Controller;
use App\Http\Requests\Api\LeaveRequest\NewLeaveRequest;
use App\Http\Requests\Api\LeaveRequest\UpdateLeaveRequest;
use App\LeaveRequest;
use App\LeaveRequestAttachment;
use App\User;
use Carbon\Carbon;
use Exception;
use Illuminate\Database\QueryException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Kreait\Firebase\Contract\Messaging;
use Kreait\Firebase\Messaging\CloudMessage;




class LeaveRequestController extends Controller
{
    private $model, $user, $firbase;


    public function __construct()
    {
        $this->model = new LeaveRequest();
        $this->user = new User();;
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
        $html = view('employees.leaves.row', compact('leaves'))->render();


        if ($request->ajax()) {
            return response()->json(['html' => $html]);
        } else {
            return view('employees.leaves.index', compact('leaves'));
        }
    }


    public function AddLeaveRequest()
    {
        $nextDateCheck = "enable";
        if ($startTime = Auth::user()->latePolicy()->first()->start_time) {
            $currentDate = Carbon::now();
            $nextDate = Carbon::now()->addHours(24)->setTime($startTime, 0);
            $subDateTime =  $nextDate->copy()->subHours(17);


            if ($currentDate < $subDateTime) {
                $nextDateCheck = "enable";
            } else {
                $nextDateCheck = "disable";
            }
        } else {
            $nextDateCheck = "enable";
        }
        return view('employees.leaves.add', compact('nextDateCheck'));
    }

    public function request(Request $request)
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
                return redirect()->back()->with('error', 'Some fields are missing');
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
            $notify->setMessageBody("Leave Request", "You Have New Leave Request", LEAVE_REQUEST, $model->id);
            $notify->sendNotificationToUser("Admins", $model->id);

            DB::commit();
            return redirect()->route('employee.leaves.listing')->with('success', 'Your Request Submitted Successfully');
        } catch (QueryException $e) {
            DB::rollBack();
            return redirect()->back()->with('error', 'Some fields are missing');
        } catch (Exception $e) {
            DB::rollBack();
            dd($e);
            return redirect()->back()->with('error', 'Some fields are missing');
        }
    }

    public function edit($id)
    {
        try {
            $leaveRequest = $this->model->newQuery()->with('leaveRequestAttachments')->where('id', $id)->first();
            return view('employees.leaves.edit', compact('leaveRequest'));
        } catch (QueryException $e) {
            DB::rollBack();
            return redirect()->back()->with('error', 'Something went wrong');
        } catch (Exception $e) {
            DB::rollBack();
            return redirect()->back()->with('error', 'Something went wrong');
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
                return redirect()->back()->with('error', 'Some fields are missing');
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
            return redirect()->route('employee.leaves.listing')->with('success', 'Your Request Updated Successfully');
        } catch (QueryException $e) {
            DB::rollBack();
            dd($e->getMessage());
            return redirect()->back()->with('error', 'Some fields are missing');
        } catch (Exception $e) {
            dd($e->getMessage());
            DB::rollBack();
            return redirect()->back()->with('error', 'Some fields are missing');
        }
    }

    public function details($id)
    {
        try {
            $leaveRequest = $this->model->newQuery()->where('id', $id)->first();
            return view('employees.leaves.detail', compact('leaveRequest'));
        } catch (QueryException $e) {
            DB::rollBack();
            return redirect()->back()->with('error', 'Something went wrong');
        } catch (Exception $e) {
            DB::rollBack();
            return redirect()->back()->with('error', 'Something went wrong');
        }
    }
    public function delete($id)
    {
        try {
            $leaveRequest = $this->model->newQuery()->where('id', $id)->first();
            foreach ($leaveRequest->leaveRequestAttachments as $key => $value) {
                $this->deleteFile('uploads/leave-request-attachments/' . $value->file);
            }
            $leaveRequest->leaveRequestAttachments()->delete();
            $leaveRequest->delete();

            return redirect()->route('employee.leaves.listing')->with('success', 'Your Request Deleted Successfully');
        } catch (QueryException $e) {
            DB::rollBack();
            return redirect()->back()->with('error', 'Something went wrong');
        } catch (Exception $e) {
            DB::rollBack();
            return redirect()->back()->with('error', 'Something went wrong');
        }
    }
}
