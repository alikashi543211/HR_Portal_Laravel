<?php

namespace App\Http\Controllers\Admin\LatePolicy;

use App\Http\Controllers\Controller;
use App\User;
use App\Attendance;
use App\Http\Requests\Admin\LatePolicy\StoreRequest;
use App\Http\Requests\Admin\LatePolicy\UpdateRequest;
use App\LatePolicy;
use App\LatePolicyDateException;
use App\latePolicyUser;
use App\LatePolicyUserException;
use Carbon\Carbon;
use Carbon\CarbonInterval;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Str;

class LatePolicyController extends Controller
{

    private $exceptionDate, $exceptionUser;

    /*
    |
    |   Controller Instance
    |
    */

    public function __construct()
    {
        $this->model = new LatePolicy();
        $this->user = new User();
        $this->exceptionDate = new LatePolicyDateException();
        $this->exceptionUser = new LatePolicyUserException();
        $this->defaultRedirectPath = URL_ADMIN . "late-policy/";
        $this->defaultViewPath = "admin.late-policy.";
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
            if ($latePolicy = $this->model->newQuery()->where('id', $inputs['id'])->first()) {

                $latePolicy->name = $inputs['name'];
                $latePolicy->start_time = $inputs['start_time'];
                $latePolicy->end_time = $inputs['end_time'];
                $latePolicy->type = $inputs['type'];
                $latePolicy->relax_time = $inputs['relax_time'];
                $latePolicy->per_minute = $inputs['per_minute'] > 0 ? $inputs['per_minute'] : 5;
                if (isset($inputs['variations'])) {
                    if (count($inputs['variations'])) {
                        $variations = [];
                        foreach ($inputs['variations']['time'] as $key => $var) {
                            $variations[$key]['time'] = $var;
                            $variations[$key]['type'] = $inputs['variations']['type'][$key];
                            $variations[$key]['price'] = isset($inputs['variations']['price'][$key]) ? $inputs['variations']['price'][$key] : 0;
                        }
                        $latePolicy->variations = json_encode($variations);
                    }
                }


                foreach ($latePolicy->user as $allUser) { // all old users array
                    $newUsers = isset($inputs['exception_users']) ? $inputs['exception_users'] : [];
                    if (!in_array($allUser->id, $newUsers)) { // check if user not exists in new array
                        // get user policy
                        if ($userLatePolicy = LatePolicyUser::whereUserId($allUser->id)->where('late_policy_id', $latePolicy->id)->first()) {
                            if ($userLatePolicy->created_at->format('Y-m-d') < Carbon::now()->format('Y-m-d')) { // if policy created_at not today
                                $userLatePolicy->end_date = Carbon::yesterday()->format('Y-m-d');
                                $userLatePolicy->late_policy_id = null;
                                $userLatePolicy->save();

                                $newLatePolicy = new LatePolicyUser();
                                $newLatePolicy->user_id = $allUser->id;
                                $newLatePolicy->no_policy = true;
                                $newLatePolicy->name = 'No Policy';
                                $newLatePolicy->start_date = Carbon::now()->format('Y-m-d');
                                $newLatePolicy->save();
                            } else { // if policy created_at is today then remove the policy
                                $userLatePolicy->delete();
                            }
                        }
                    }
                }

                if (isset($inputs['exception_users'])) {
                    if (count($inputs['exception_users'])) {
                        $alreadyUsers = $latePolicy->user()->pluck('users.id')->toArray();
                        foreach ($inputs['exception_users'] as $u) {
                            if (!in_array($u, $alreadyUsers)) {

                                if ($oldLatePolicy = LatePolicyUser::whereUserId($u)->orderBy('created_at', 'DESC')->first()) {
                                    if ($oldLatePolicy->created_at->format('Y-m-d') < Carbon::now()->format('Y-m-d')) {
                                        $oldLatePolicy->end_date = Carbon::yesterday()->format('Y-m-d');
                                        $oldLatePolicy->late_policy_id = null;
                                        $oldLatePolicy->save();
                                    } else {
                                        $oldLatePolicy->delete();
                                    }
                                }

                                // if (!LatePolicyUser::whereUserId($u)->where('late_policy_id', $latePolicy->id)->exists()) {

                                $latePolicyUser = new LatePolicyUser();
                                $latePolicyUser->user_id = $u;
                                $latePolicyUser->late_policy_id = $latePolicy->id;
                                if (isset($variations)) {
                                    $latePolicyUser->variations = json_encode($variations);
                                }
                                $latePolicyUser->name = $inputs['name'];
                                $latePolicyUser->start_time = $inputs['start_time'];
                                $latePolicyUser->end_time = $inputs['end_time'];
                                $latePolicyUser->type = $inputs['type'];
                                $latePolicyUser->relax_time = $inputs['relax_time'];
                                $latePolicyUser->per_minute = $inputs['per_minute'] > 0 ? $inputs['per_minute'] : 5;
                                $latePolicyUser->start_date = Carbon::now()->format('Y-m-d');
                                $latePolicyUser->save();
                                // }else{
                                //     $latestPolicy = LatePolicyUser::whereUserId($u)->where('late_policy_id', $latePolicy->id)->first();
                                //     if (isset($variations)) {
                                //         $latestPolicy->variations = json_encode($variations);
                                //     }
                                //     $latestPolicy->name = $inputs['name'];
                                //     $latestPolicy->start_time = $inputs['start_time'];
                                //     $latestPolicy->end_time = $inputs['end_time'];
                                //     $latestPolicy->type = $inputs['type'];
                                //     $latestPolicy->relax_time = $inputs['relax_time'];
                                //     $latestPolicy->per_minute = $inputs['per_minute'] > 0 ? $inputs['per_minute'] : 5;
                                //     $latestPolicy->start_date = $latePolicy->created_at->format('Y-m-d');
                                //     $latestPolicy->save();
                                // }
                            }
                        }
                    }
                } else $latePolicy->user()->sync([]);
                if ($latePolicy->save()) {
                    DB::commit();
                    return $this->redirectBack("success", __('default_label.updated'), $this->defaultRedirectPath . "listing");
                }
            } else {
                DB::rollback();
                return $this->redirectBack("error", __('default_label.update'));
            }
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

    public function store(StoreRequest $request)
    {
        try {

            DB::beginTransaction();
            $inputs = $request->all();
            $latePolicy = $this->model->newInstance();
            $latePolicy->name = $inputs['name'];
            $latePolicy->start_time = $inputs['start_time'];
            $latePolicy->end_time = $inputs['end_time'];
            $latePolicy->type = $inputs['type'];
            $latePolicy->relax_time = $inputs['relax_time'];
            $latePolicy->per_minute = $inputs['per_minute'] > 0 ? $inputs['per_minute'] : 5;
            if ($latePolicy->save()) {
                $latePolicy = $this->model->newQuery()->where('id', $latePolicy->id)->with('user')->first();
                if (isset($inputs['variations'])) {
                    if (count($inputs['variations'])) {
                        $variations = [];
                        foreach ($inputs['variations']['time'] as $key => $var) {
                            $variations[$key]['time'] = $var;
                            $variations[$key]['type'] = $inputs['variations']['type'][$key];
                            $variations[$key]['price'] = isset($inputs['variations']['price'][$key]) ? $inputs['variations']['price'][$key] : 0;
                        }
                        $latePolicy->variations = json_encode($variations);
                        $latePolicy->save();
                    }
                }
                if (isset($inputs['exception_users'])) {

                    if (isset($inputs['exception_users'])) {
                        if (count($inputs['exception_users'])) {
                            foreach ($inputs['exception_users'] as $u) {

                                if ($oldLatePolicy = LatePolicyUser::whereUserId($u)->orderBy('created_at', 'DESC')->first()) {
                                    if ($oldLatePolicy->created_at->format('Y-m-d') < Carbon::now()->format('Y-m-d')) {
                                        $oldLatePolicy->end_date = Carbon::yesterday()->format('Y-m-d');
                                        $oldLatePolicy->late_policy_id = null;
                                        $oldLatePolicy->save();
                                    } else {
                                        $oldLatePolicy->delete();
                                    }
                                }

                                $latePolicyUser = new LatePolicyUser();
                                $latePolicyUser->user_id = $u;
                                $latePolicyUser->late_policy_id = $latePolicy->id;
                                if (isset($variations)) {
                                    $latePolicyUser->variations = json_encode($variations);
                                }
                                $latePolicyUser->name = $inputs['name'];
                                $latePolicyUser->start_time = $inputs['start_time'];
                                $latePolicyUser->end_time = $inputs['end_time'];
                                $latePolicyUser->type = $inputs['type'];
                                $latePolicyUser->relax_time = $inputs['relax_time'];
                                $latePolicyUser->per_minute = $inputs['per_minute'] > 0 ? $inputs['per_minute'] : 5;
                                $latePolicyUser->start_date = Carbon::now()->format('Y-m-d');
                                $latePolicyUser->save();
                            }
                        }
                    }
                }
                DB::commit();
                return $this->redirectBack("success", __('default_label.added'), $this->defaultRedirectPath . "listing");
            }

            DB::rollback();
            return $this->redirectBack("error", __('default_label.add'));
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

    public function add(Request $request)
    {
        try {
            $allUsers = $this->user->newQuery()->whereDoesntHave('latePolicy')->get();
            $data['allUsers'] = $allUsers;
            return $this->successListView("", $this->defaultRedirectPath . "add", __('late-policy.page_heading'), $data, false, false);
        } catch (QueryException $e) {
            \Session::flash($e->getMessage());
            return Redirect()->back();
        } catch (Exception $e) {
            \Session::flash($e->getMessage());
            return Redirect()->back();
        }
    }

    public function edit($id)
    {
        try {
            if ($latePolicy = $this->model->newQuery()->where('id', $id)->first()) {
                $policyUsers = $latePolicy->user()->pluck('users.id')->toArray();
                $allUsers = $this->user->newQuery()->whereDoesntHave('latePolicy', function ($query) use ($id) {
                    $query->where('late_policy.id', '!=', $id);
                })->get();
                $data['latePolicy'] = $latePolicy;
                $data['policyUsers'] = $policyUsers;
                $data['allUsers'] = $allUsers;
                return $this->successListView("", $this->defaultRedirectPath . "edit", __('late-policy.page_heading'), $data, false, false);
            } else return $this->redirectBack("error", __('default_label.update'));
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
    |   Getting Listing admin
    |
    */
    public function listing(Request $request)
    {
        try {
            $data['policies'] = $this->model->newQuery()->withCount('user')->paginate(DATA_PER_PAGE);
            $data['exceptionDates'] = $this->exceptionDate->newQuery()->orderBy('date', 'ASC')->get();
            return $this->successListView("", $this->defaultRedirectPath . "listing", __('late-policy.page_heading'), $data, false);
        } catch (QueryException $e) {
            \Session::flash($e->getMessage());
            return Redirect()->back();
        } catch (Exception $e) {
            \Session::flash($e->getMessage());
            return Redirect()->back();
        }
    }

    public function updateDates(Request $request)
    {
        try {
            DB::beginTransaction();
            $inputs = $request->all();

            if (isset($inputs['exception_dates'])) {
                $dates = explode(',', $inputs['exception_dates']);
                if (count($dates)) {
                    foreach ($dates as $key => $date) {
                        $date = date('Y-m-d H:i', strtotime($date));
                        if (!$this->exceptionDate->newQuery()->where('date', $date)->exists()) {
                            $exceptionDate = $this->exceptionDate->newInstance();
                            $exceptionDate->date = $date;
                            if (!$exceptionDate->save()) {
                                DB::rollback();
                                return $this->redirectBack("error", __('default_label.update'));
                            }
                        }
                    }
                    DB::commit();
                    return $this->redirectBack("success", __('default_label.updated'), $this->defaultRedirectPath . "listing");
                }
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

    public function deleteDate($id)
    {
        try {
            DB::beginTransaction();
            if ($exceptionDate = $this->exceptionDate->newQuery()->where('id', $id)->first()) {
                if ($exceptionDate->delete()) {
                    DB::commit();
                    return $this->redirectBack("success", __('default_label.deleted'), $this->defaultRedirectPath . "listing");
                }
            } else return $this->redirectBack("error", __('default_label.delete'));
        } catch (QueryException $e) {
            \Session::flash($e->getMessage());
            return Redirect()->back();
        } catch (Exception $e) {
            \Session::flash($e->getMessage());
            return Redirect()->back();
        }
    }

    public function deletePolicy($id)
    {
        try {
            DB::beginTransaction();
            if ($latePolicy = $this->model->newQuery()->where('id', $id)->first()) {

                foreach ($latePolicy->user as $user) {

                    $oldLatePolicy = LatePolicyUser::whereUserId($user->id)->whereLatePolicyId($latePolicy->id)->first();
                    if ($oldLatePolicy->created_at->format('Y-m-d') < Carbon::now()->format('Y-m-d')) {
                        $oldLatePolicy->end_date = Carbon::yesterday()->format('Y-m-d');
                        $oldLatePolicy->save();
                    } else {
                        $oldLatePolicy->delete();
                    }

                    $newLatePolicy = new LatePolicyUser();
                    $newLatePolicy->user_id = $user->id;
                    $newLatePolicy->no_policy = true;
                    $newLatePolicy->name = 'No Policy';
                    $newLatePolicy->start_date = Carbon::now()->format('Y-m-d');
                    $newLatePolicy->save();
                }

                if ($latePolicy->user()->sync([])) {
                    if ($latePolicy->delete()) {
                        DB::commit();
                        return $this->redirectBack("success", __('default_label.deleted'), $this->defaultRedirectPath . "listing");
                    } else {
                        DB::rollback();
                        return $this->redirectBack("error", __('default_label.update'));
                    }
                } else {
                    DB::rollback();
                    return $this->redirectBack("error", __('default_label.update'));
                }
            } else return $this->redirectBack("error", __('default_label.delete'));
        } catch (QueryException $e) {
            \Session::flash($e->getMessage());
            return Redirect()->back();
        } catch (Exception $e) {
            \Session::flash($e->getMessage());
            return Redirect()->back();
        }
    }
}
