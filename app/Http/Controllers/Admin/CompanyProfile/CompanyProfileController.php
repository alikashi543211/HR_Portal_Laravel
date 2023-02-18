<?php

namespace App\Http\Controllers\Admin\CompanyProfile;

use App\CompanyProfile;
use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\CompanyProfile\UpdateRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class CompanyProfileController extends Controller
{
    private $companyProfile;

    /*
    |
    |   Controller Instance
    |
    */
    public function __construct()
    {
        $this->companyProfile = new CompanyProfile();
    }

    /*
    |
    |   Description: Following will fetch the details of a company
    |
    */
    public function details()
    {
        try {
            DB::beginTransaction();
            $companyProfile = $this->companyProfile->newQuery()->first();
            DB::commit();
            return $this->successView("", "admin.company-profile.details", __('default_label.details'), $companyProfile);
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
    |   Description: Following will edit the details of a company
    |
    */
    public function edit()
    {
        try {

            DB::beginTransaction();
            $companyProfile = $this->companyProfile->newQuery()->first();
            DB::commit();
            return $this->successView("", "admin.company-profile.edit", __('default_label.edit'), $companyProfile);
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
    |   Description: Following will update the details of a company
    |
    */
    public function update(UpdateRequest $request)
    {
        try {

            $inputs = $request->all();
            DB::beginTransaction();

            $companyProfile = $this->companyProfile->newQuery()->first();
            if (!$companyProfile) {
                $companyProfile = $this->companyProfile->newInstance();
            }

            $companyProfile->fill($inputs);
            $companyProfile->updated_by = Auth::id();

            if ($request->hasFile('logo')) {

                $filename = 'logo_' . Str::random(40) . '.' . $request->file('logo')->getClientOriginalExtension();

                $request->file('logo')->move(public_path('uploads/logo/'), $filename);
                $companyProfile->logo = $filename;
            }

            if (isset($inputs['salary_details']) && !empty($inputs['salary_details'])) {
                $companyProfile->cheque_bank_name = $inputs['cheque_bank_name'];
                $companyProfile->respective_title = $inputs['respective_title'];
                $companyProfile->respective_first_name = $inputs['respective_first_name'];
                $companyProfile->respective_last_name = $inputs['respective_last_name'];
                $companyProfile->respective_designation = $inputs['respective_designation'];
                $companyProfile->respective_bank_name = $inputs['respective_bank_name'];
                $companyProfile->respective_address_1 = $inputs['respective_address_1'];
                $companyProfile->respective_address_2 = $inputs['respective_address_2'];
            } else {
                $companyProfile->cheque_bank_name = NULL;
                $companyProfile->respective_title = NULL;
                $companyProfile->respective_first_name = NULL;
                $companyProfile->respective_last_name = NULL;
                $companyProfile->respective_designation = NULL;
                $companyProfile->respective_bank_name = NULL;
                $companyProfile->respective_address_1 = NULL;
                $companyProfile->respective_address_2 = NULL;
            }

            if ($companyProfile->save()) {
                DB::commit();
                return $this->redirectBack(SUCCESS, __('default_label.updated'));
            }

            DB::rollback();
            return $this->redirectBack(ERROR, __('default_label.update'));
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
