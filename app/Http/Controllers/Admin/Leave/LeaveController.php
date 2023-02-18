<?php

namespace App\Http\Controllers\Admin\Leave;

use App\Http\Controllers\Controller;
use App\User;
use App\Attendance;
use App\Http\Requests\Admin\Leave\UpdateRequest;
use App\Http\Requests\Leave\SummaryRequest;
use App\LatePolicy;
use App\UserLeave;
use App\UserLeaveQuota;
use Carbon\Carbon;
use Carbon\CarbonInterval;
use Carbon\CarbonPeriod;
use Exception;
use Illuminate\Database\QueryException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Session;
use Str;

class LeaveController extends Controller
{
    private $userLeave;
    /*
    |
    |   Controller Instance
    |
    */
    public function __construct()
    {
        $this->model = new User();
        $this->userLeave = new UserLeave();
        $this->defaultRedirectPath = URL_ADMIN . "leaves/";
        $this->defaultViewPath = "admin.leaves.";
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

            foreach ($inputs['id'] as $key => $value) {
                $user = User::where('id', $value)->first();
                if ($user) {
                    $user->leaveQuota->remaining_casual_leaves = $inputs['remaining_casual_leaves'][$key];
                    $user->leaveQuota->remaining_sick_leaves = $inputs['remaining_sick_leaves'][$key];
                    if (!$user->leaveQuota->save()) {
                        DB::rollback();
                        return $this->redirectBack("error", __('default_label.update'));
                    }
                }
            }
            DB::commit();
            return $this->redirectBack("success", __('default_label.updated'), $this->defaultRedirectPath . "listing");
        } catch (QueryException $e) {
            DB::rollBack();
            Session::flash($e->getMessage());
            return Redirect()->back();
        } catch (Exception $e) {
            DB::rollBack();
            Session::flash($e->getMessage());
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
            $user = $this->model->newQuery()->whereHas('leaveQuota')->whereNotIn('status', ['Terminate', 'Other'])->orderBy('first_name', 'ASC');
            $inputs = $request->all();
            $user->where("role_id", ">", SUPER_ADMIN);

            if (!empty($inputs['search'])) {
                $user->where(function ($q) use ($inputs) {
                    $this->search($q, $inputs['search'], ['first_name', 'last_name', 'email', 'personal_email', 'phone_number', 'cnic', 'doj', 'dob', 'finger_print_id', 'employee_id', 'designation', 'nationality', 'emergency_contact_name', 'emergency_contact_relation', 'base_salary', 'emergency_contact_number', 'dop']);
                });
            }

            return $this->successListView("", "admin.leaves.listing", __('leave.page_heading'), $user->paginate(DATA_PER_PAGE), true, false);
        } catch (QueryException $e) {
            Session::flash($e->getMessage());
            return Redirect()->back();
        } catch (Exception $e) {
            Session::flash($e->getMessage());
            return Redirect()->back();
        }
    }

    public function history(Request $req, $id)
    {
        $user = $this->model->newQuery()->where('id', $id)->first();
        $doj = date('Y', strtotime($user->doj));
        $currentYear = date('Y');
        if (isset($req->year)) {
            $currentYear = $req->year;
        }

        $userLeaves = UserLeave::where('user_id', $id)->with(['user']);
        return $this->successListView("", $this->defaultViewPath . "history", __('leave_history.page_heading'), $userLeaves->paginate(DATA_PER_PAGE), false, false);
    }

    public function summary(SummaryRequest $request)
    {
        try {
            $inputs = $request->all();
            $startDate = Carbon::now()->subDays(30);
            $endDate = Carbon::now();

            $user = $this->model->newQuery()->where('role_id', '>', SUPER_ADMIN)->orderBy('first_name', 'ASC');
            $userLeaveQuery = $this->userLeave->newQuery()->orderBy('date', 'ASC');

            if (isset($inputs['user_id'])) {
                $user->whereIn('id', $inputs['user_id']);
                $userLeaveQuery->whereIn('user_id', $inputs['user_id']);
            }
            if (isset($inputs['from_date'])) {
                $startDate = Carbon::createFromFormat('Y-m-d', $inputs['from_date']);
                $userLeaveQuery->where('date', '>=', $inputs['from_date']);
            }
            if (isset($inputs['to_date'])) {
                $endDate = Carbon::createFromFormat('Y-m-d', $inputs['to_date']);
                $userLeaveQuery->where('date', '<=', $inputs['to_date']);
            }
            // $date_list = $userLeaveQuery->distinct('date')->get()->toArray();
            // dd($date_list);
            // $date_list = $userLeaveQuery->pluck('date')->toArray();
            // // $date_list = array_values(array_unique($date_list));
            // $date_list = array_unique(array_map(function ($elem) {
            //     return $elem['date'];
            // }, $date_list));


            $date_list  = CarbonPeriod::create($startDate, $endDate);

            $inputs = $request->all();
            if (!empty($inputs['search'])) {

                $user->where(function ($q) use ($inputs) {
                    $this->search($q, $inputs['search'], getSearchColoumns('attendances'));
                });
            }
            $users = $this->model->newQuery()->where('role_id', '>', SUPER_ADMIN)->orderBy('first_name', 'ASC')->get();
            $data = array('date_list' => $date_list, 'users' => $users, 'user' => $user->paginate(DATA_PER_PAGE));
            return $this->successListView("", $this->defaultRedirectPath . "summary", __('leave.summary_page_heading'), $data, false, false);
        } catch (QueryException $e) {
            Session::flash($e->getMessage());
            return Redirect()->back();
        } catch (Exception $e) {
            Session::flash($e->getMessage());
            return Redirect()->back();
        }
    }
}
