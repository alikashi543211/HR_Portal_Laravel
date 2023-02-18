<?php

namespace App\Http\Controllers\Admin\PayRoll;

use App\Http\Controllers\Controller;
use App\PayRoll;
use App\PayRollTax;
use App\User;
use Exception;
use Illuminate\Database\QueryException;
use Illuminate\Http\Request;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;

class PayRollTaxController extends Controller
{

    /*
    |
    |   Controller Instance
    |
    */

    public function __construct()
    {
        $this->parentModel = new PayRoll();
        $this->model = new PayRollTax();
        $this->user = new User();
        $this->defaultRedirectPath = URL_ADMIN . "pay-rolls/";
        $this->defaultViewPath = "admin.pay-rolls.";
    }

    public function listing(Request $request, $id = null)
    {
        try {
            $model = $this->model->newQuery()->orderBy('created_at', 'DESC');
            $inputs = $request->all();

            if (!empty($inputs['search'])) {
                $model->where(function ($q) use ($inputs) {
                    $this->search($q, $inputs['search'], ['name', 'amount', 'date'], 'payRoll');
                });
            }

            if (isset($id)) {
                $model = $model->where('pay_roll_id', $id);
            }

            return $this->successListView("", $this->defaultViewPath . "tax-listing", __('payroll.page_tax_heading'), $model->paginate(DATA_PER_PAGE));
        } catch (QueryException $e) {
            Session::flash(ERROR, $e->getMessage());
            return Redirect()->back();
        } catch (Exception $e) {
            Session::flash(ERROR, $e->getMessage());
            return Redirect()->back();
        }
    }

    public function add($id)
    {
        try {
            $payRoll = $this->parentModel->newQuery()->where('id', $id)->first();

            return $this->successView("", $this->defaultViewPath . "tax-add", __('payroll.add_page_heading'), $payRoll);
        } catch (QueryException $e) {
            Session::flash(ERROR, $e->getMessage());
            return Redirect()->back();
        } catch (Exception $e) {
            Session::flash(ERROR, $e->getMessage());
            return Redirect()->back();
        }
    }
    public function save(Request $request)
    {
        try {
            DB::beginTransaction();
            $inputs = $request->all();
            $model = $this->model->newInstance();
            $model->name = $inputs['name'];
            $model->amount = $inputs['amount'];
            $model->pay_roll_id = $inputs['pay_roll_id'];
            $model->image = $this->uploadFile('payroll-tax', 'image');
            if ($model->save()) {
                DB::commit();
                return $this->redirectBack(SUCCESS, __('default_label.saved'), $this->defaultRedirectPath . "tax-listing" . "/" . $inputs['pay_roll_id']);
            }

            DB::rollback();
            return Redirect()->back();
        } catch (QueryException $e) {
            DB::rollBack();
            dd($e->getMessage(), 'QueryException');
            return Redirect()->back();
        } catch (Exception $e) {
            DB::rollBack();
            dd($e, 'Exception');
            return Redirect()->back();
        }
    }
    public function edit($id)
    {
        try {
            $payRollTax = $this->model->newQuery()->where('id', $id)->first();

            return $this->successView("", $this->defaultViewPath . "tax-edit", __('payroll.edit_tax_page_heading'), $payRollTax);
        } catch (QueryException $e) {
            Session::flash(ERROR, $e->getMessage());
            return Redirect()->back();
        } catch (Exception $e) {
            Session::flash(ERROR, $e->getMessage());
            return Redirect()->back();
        }
    }
    public function update(Request $request)
    {
        try {
            DB::beginTransaction();
            $inputs = $request->all();
            $model  = $this->model->newQuery()->where('id', $request->pay_roll_tax_id)->first();
            $model->name = $inputs['name'];
            $model->amount = $inputs['amount'];
            $model->pay_roll_id = $inputs['pay_roll_id'];
            if ($request->hasFile('image')) {
                $model->image = $this->uploadFile('payroll-tax', 'image');
            }
            if ($model->save()) {
                DB::commit();
                return $this->redirectBack(SUCCESS, __('default_label.saved'), $this->defaultRedirectPath . "tax-listing" . "/" . $inputs['pay_roll_id']);
            }

            DB::rollback();
            return Redirect()->back();
        } catch (QueryException $e) {
            DB::rollBack();
            dd($e->getMessage(), 'QueryException');
            return Redirect()->back();
        } catch (Exception $e) {
            DB::rollBack();
            dd($e, 'Exception');
            return Redirect()->back();
        }
    }
    public function delete($id)
    {

        try {
            DB::beginTransaction();
            $model = $this->model->newQuery()->where('id', $id)->first();
            $url = $model->pay_roll_id;
            $image =  $model->image;
            if ($model && $model->delete()) {
                $this->deleteFile($image);
                DB::commit();
                return $this->redirectBack("success", __('default_label.deleted'), $this->defaultRedirectPath . "tax-listing" . "/" . $url);
            }

            DB::rollback();
            return $this->redirectBack("", __('default_label.delete'));
        } catch (QueryException $e) {
            DB::rollBack();
            Session::flash(ERROR, $e->getMessage());
            return Redirect()->back();
        } catch (Exception $e) {
            DB::rollBack();
            Session::flash(ERROR, $e->getMessage());
            return Redirect()->back();
        }
    }
}
