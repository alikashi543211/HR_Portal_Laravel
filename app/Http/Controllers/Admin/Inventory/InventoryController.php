<?php

namespace App\Http\Controllers\Admin\Inventory;

use App\Drivers\FirebaseDriver;
use App\Http\Controllers\Controller;
use App\Inventory;
use App\User;
use Exception;
use Illuminate\Database\QueryException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;

class InventoryController extends Controller
{

    public function __construct()
    {
        $this->model = new Inventory();
        $this->user = new User();
        $this->defaultRedirectPath = URL_ADMIN . "inventories/";
        $this->defaultViewPath = "admin.inventories.";
    }
    public function listing(Request $request)
    {
        $inventories = $this->model->newQuery();
        $inputs = $request->all();
        if (!empty($inputs['status'])) {
            if ($inputs['status'] ==  ASSIGNED) {
                $inventories =  $inventories->where('user_id', '!=', 'null');
            }
            if ($inputs['status'] ==  UNASSIGNED) {
                $inventories =  $inventories->whereNull('user_id');
            }
        }
        if (!empty($inputs['user_id'])) {

            $inventories =  $inventories->where(function ($q) use ($inputs) {
                $this->search($q, $inputs['user_id'], ['user_id']);
            });
        }
        if (!empty($inputs['search'])) {

            $inventories =  $inventories->where(function ($q) use ($inputs) {
                $this->search($q, $inputs['search'], ['name', 'code', 'first_name', 'last_name'], 'user');
            });
        }
        // dd($inventories->get());

        $inventories = $inventories->orderBy('created_at', 'DESC');
        $inventories = $inventories->paginate(DATA_PER_PAGE);

        return $this->successListView(NULL, $this->defaultRedirectPath . 'listing', 'Inventory', $inventories);
    }
    public function create()
    {
        try {
            $users = User::where('role_id', '!=', 1)->get();
            return $this->successView("", $this->defaultViewPath . "add", __('inventory.add_page_heading'), $users);
        } catch (QueryException $e) {
            Session::flash($e->getMessage());
            return Redirect()->back();
        } catch (Exception $e) {
            Session::flash($e->getMessage());
            return Redirect()->back();
        }
    }
    public function save(Request $request)
    {
        try {
            DB::beginTransaction();
            $inputs = $request->all();
            $model = $this->model->newInstance();
            $model->fill($inputs);
            if ($model->save()) {
                DB::commit();
                return $this->redirectBack(SUCCESS, __('default_label.saved'), $this->defaultRedirectPath . "listing");
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
    public function edit($id)
    {
        try {
            $data = array();
            $data['inventory'] = Inventory::with('user')->where('id', $id)->first();
            $data['users'] = User::where('role_id', '!=', 1)->get();
            return $this->successView("", $this->defaultViewPath . "edit", __('inventory.edit_page_heading'), $data);
        } catch (QueryException $e) {
            Session::flash($e->getMessage());
            return Redirect()->back();
        } catch (Exception $e) {
            Session::flash($e->getMessage());
            return Redirect()->back();
        }
    }
    public function update(Request $request)
    {
        try {
            DB::beginTransaction();
            $inputs = $request->all();
            $model = $this->model->newQuery()->where('id', $inputs['id'])->first();
            $model->fill($inputs);
            if ($model->save()) {
                DB::commit();
                return $this->redirectBack(SUCCESS, __('default_label.updated'), $this->defaultRedirectPath . "listing");
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

    public function inventoryRequest(Request $request)
    {
        $notify = null;

        try {
            DB::beginTransaction();
            $inputs = $request->all();
            $model = $this->model->newQuery()->where('id', $request->id)->first();
            $model->request = $request->get('request');
            if ($model->status == INVENTORY_OFFICE && $request->get('request') == INVT_REQ_APPROVED) {
                $model->status = INVENTORY_HOME;
            } elseif ($model->status == INVENTORY_HOME && $request->get('request') == INVT_REQ_APPROVED) {
                $model->status = INVENTORY_OFFICE;
            }

            if ($model->save()) {
                DB::commit();
                $notify = new FirebaseDriver();
                if ($model->status == INVENTORY_OFFICE) {
                    if ($request->get('request') == INVT_REQ_REJECTED) {
                        $notify->setMessageBody("Inventory Request Rejection", "Your inventory checkin request has been rejected", NOTIFICATION_DEVICE);
                    } elseif ($request->get('request') == INVT_REQ_APPROVED) {
                        $notify->setMessageBody("Inventory Request Approval", "Your inventory check-in request has been approved", NOTIFICATION_DEVICE);
                    }
                    $notify->sendNotificationToUser("Employees", $model->user->id);
                    return $this->redirectBack(SUCCESS, __('inventory.request_error'), $this->defaultRedirectPath . "listing");
                } elseif ($model->status == INVENTORY_HOME) {

                    if ($request->get('request') == INVT_REQ_REJECTED) {
                        $notify->setMessageBody("Inventory Request Rejection", "Your inventory checkout request has been rejected", NOTIFICATION_DEVICE);
                    } elseif ($request->get('request') == INVT_REQ_APPROVED) {
                        $notify->setMessageBody("Inventory Request Approval", "Your inventory checkout request to home has been approved", NOTIFICATION_DEVICE);
                    }
                    $notify->sendNotificationToUser("Employees", $model->user->id);
                    return $this->redirectBack(SUCCESS, __('inventory.request_success'), $this->defaultRedirectPath . "listing");
                }
            }

            DB::rollback();
            return Redirect()->back();
        } catch (QueryException $e) {
            dd($e);
            DB::rollBack();
            return Redirect()->back();
        } catch (Exception $e) {
            dd($e);
            DB::rollBack();
            return Redirect()->back();
        }
    }

    public function delete($id)
    {
        try {
            DB::beginTransaction();
            $model = $this->model->newQuery()->where('id', $id)->first();
            if ($model->delete()) {
                DB::commit();
                return $this->redirectBack(SUCCESS, __('default_label.deleted'), $this->defaultRedirectPath . "listing");
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
