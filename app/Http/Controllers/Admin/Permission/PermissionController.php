<?php

namespace App\Http\Controllers\Admin\Permission;

use App\Http\Controllers\Controller;
use App\User;
use App\Attendance;
use App\Http\Requests\Admin\Permission\StoreRequest;
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

class PermissionController extends Controller
{
    /*
    |
    |   Controller Instance
    |
    */

    public function __construct()
    {
        $this->model = new Role();
        $this->permission = new Permission();
        $this->rolePermission = new RolePermission();
        $this->defaultRedirectPath = URL_ADMIN . "permissions/";
        $this->defaultViewPath = "admin.permissions.";
    }

    /*
    |
    |   Updating admin
    |
    */
    public function update(Request $request)
    {
        try {

            DB::beginTransaction();
            $inputs = $request->all();

            if ($rolePermission = $this->rolePermission->newQuery()->where('permission_id', $inputs['permission_id'])->where('action_id', $inputs['action_id'])->where('role_id', $inputs['role_id'])->first()) {
                if ($rolePermission->delete()) {
                    DB::commit();
                    return ['success' => true, 'permission' => false];
                }
            } else {
                $rolePermission = $this->rolePermission->newInstance();
                $rolePermission->fill($inputs);
                if ($rolePermission->save()) {
                    DB::commit();
                    return ['success' => true, 'permission' => true];
                }
            }
            DB::rollBack();
            return ['success' => false];
        } catch (QueryException $e) {
            DB::rollBack();
            \Session::flash(ERROR, $e->getMessage());
            return Redirect()->back();
        } catch (Exception $e) {
            DB::rollBack();
            \Session::flash(ERROR, $e->getMessage());
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
            $role = $this->model->newQuery()->where('id', '>', SUPER_ADMIN);
            $inputs = $request->all();

            if (!empty($inputs['search'])) {
                $role->where(function ($q) use ($inputs) {
                    $this->search($q, $inputs['search'], ['slug', 'title']);
                });
            }
            $permissions = $this->permission->newQuery()->get();
            $data['roles'] = $role->paginate(DATA_PER_PAGE);
            $data['permissions'] = $permissions;
            return $this->successListView("", $this->defaultViewPath . "listing", __('permission.page_heading'), $data);
        } catch (QueryException $e) {
            \Session::flash(ERROR, $e->getMessage());
            return Redirect()->back();
        } catch (Exception $e) {
            \Session::flash(ERROR, $e->getMessage());
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
            return $this->successView("", $this->defaultViewPath . "add", __('permission.add_page_heading'), "");
        } catch (QueryException $e) {
            \Session::flash(ERROR, $e->getMessage());
            return Redirect()->back();
        } catch (Exception $e) {
            \Session::flash(ERROR, $e->getMessage());
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
            $model = $this->model->newInstance();
            $inputs['slug'] = strtolower(str_replace(' ', '_', $inputs['title']));
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

            if ($model) {
                $this->rolePermission->newQuery()->whereRoleId($id)->delete();
                if ($model->delete()) {
                    DB::commit();
                    return $this->redirectBack(SUCCESS, __('default_label.deleted'));
                }
            }
            DB::rollback();
            return $this->redirectBack("", __('default_label.delete'));
        } catch (QueryException $e) {
            DB::rollBack();
            \Session::flash(ERROR, $e->getMessage());
            return Redirect()->back();
        } catch (Exception $e) {
            DB::rollBack();
            \Session::flash(ERROR, $e->getMessage());
            return Redirect()->back();
        }
    }
}
