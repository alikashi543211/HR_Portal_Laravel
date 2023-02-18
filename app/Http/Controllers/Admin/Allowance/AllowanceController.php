<?php

namespace App\Http\Controllers\Admin\Allowance;

use App\Allowance;
use App\Http\Controllers\Controller;
use App\User;
use App\Attendance;
use App\Http\Requests\Admin\Allowance\StoreRequest;
use App\Http\Requests\Admin\Allowance\UpdateRequest;
use App\LatePolicy;
use App\Permission;
use App\Role;
use App\RolePermission;
use App\UserLeaveQuota;
use Carbon\Carbon;
use Carbon\CarbonInterval;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Str;

class AllowanceController extends Controller
{
    /*
    |
    |   Controller Instance
    |
    */

    public function __construct()
    {
        $this->model = new Allowance();
        $this->defaultRedirectPath = URL_ADMIN . "allowances/";
        $this->defaultViewPath = "admin.allowances.";
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
            if($inputs['type'] == ALLOWANCES_PERCENTAGE){
                if($inputs['value'] > 100){
                    return redirect()->back()->with('error', 'Value should be less than and equal to 100')->withInput();
                }
            }
            $model = $this->model->newQuery()->whereId($inputs['id'])->first();

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
    |   Getting Listing admin
    |
    */
    public function listing(Request $request)
    {
        try {
            $model = $this->model->newQuery();
            $inputs = $request->all();

            if(!empty($inputs['search'])){
                $model->where(function($q) use ($inputs){
                    $this->search($q, $inputs['search'], ['name', 'value']);
                });
            }

            return $this->successListView("", $this->defaultViewPath . "listing", __('allowance.page_heading'), $model->paginate(DATA_PER_PAGE));
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
            return $this->successView("", $this->defaultViewPath . "add", __('allowance.add_page_heading'), "");
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
    |   Creating Admin
    |
    */
    public function store(StoreRequest $request)
    {
        try {
            DB::beginTransaction();
            $inputs = $request->all();

            if($inputs['type'] == ALLOWANCES_PERCENTAGE){
                if($inputs['value'] > 100){
                    return redirect()->back()->with('error', 'Value should be less than and equal to 100')->withInput();
                }
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
    |   Getting delete admin
    |
    */
    public function delete($id)
    {
        try {
            DB::beginTransaction();
            $model = $this->model->newQuery()->whereId($id)->first();

            if ($model && $model->delete()) {
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

                DB::commit();
                return $this->successView("", $this->defaultViewPath . "edit", __('allowance.edit_page_heading'), $model);
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

}
