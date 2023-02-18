<?php

namespace App\Http\Controllers\Admin\Helper;

use App\Http\Controllers\Controller;
use App\User;
use Illuminate\Http\Request;

class HelperController extends Controller
{
    /*
    |
    |   Controller Instance
    |
    */

    public function __construct()
    { }

    /*
    |
    |   Dashboard Details
    |
    */

    public function searchEmployee(Request $request)
    {
        $inputs = $request->all();
        $query = User::where('role_id', EMPLOYEE);
        if(!empty($inputs['search'])){
            $query->where(function($q) use ($inputs){
                $q->orWhere('first_name', 'LIKE', '%' .$inputs['search']. '%')
                ->orWhere('last_name', 'LIKE', '%' .$inputs['search']. '%')
                ->orWhere('email', 'LIKE', '%' .$inputs['search']. '%')
                ->orWhere('personal_email', 'LIKE', '%' .$inputs['search']. '%')
                ->orWhere('phone_number', 'LIKE', '%' .$inputs['search']. '%')
                ->orWhere('cnic', 'LIKE', '%' .$inputs['search']. '%')
                ->orWhere('doj', 'LIKE', '%' .$inputs['search']. '%')
                ->orWhere('dob', 'LIKE', '%' .$inputs['search']. '%')
                ->orWhere('finger_print_id', 'LIKE', '%' .$inputs['search']. '%')
                ->orWhere('employee_id', 'LIKE', '%' .$inputs['search']. '%')
                ->orWhere('designation', 'LIKE', '%' .$inputs['search']. '%')
                ->orWhere('nationality', 'LIKE', '%' .$inputs['search']. '%')
                ->orWhere('emergency_contact_name', 'LIKE', '%' .$inputs['search']. '%')
                ->orWhere('emergency_contact_relation', 'LIKE', '%' .$inputs['search']. '%')
                ->orWhere('base_salary', 'LIKE', '%' .$inputs['search']. '%')
                ->orWhere('emergency_contact_number', 'LIKE', '%' .$inputs['search']. '%');
            });
        }
        $data = $query->limit(10)->get(['id', 'email as text']);
        return ['result' => $data];
    }
}
