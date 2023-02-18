<?php

namespace App\Http\Controllers;

use App\Http\Requests\Login\LoginRequest;
use Exception;
use Illuminate\Database\QueryException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;

class LoginController extends Controller
{
    /*
    |
    |   Controller Instance
    |
    */

    public function __construct()
    {
    }

    public function login(Request $req)
    {
        try {
            DB::beginTransaction();
            $credentials = $req->only('email', 'password');
            if (!Auth::attempt($credentials)) {
                Session::flash('error', 'Invalid Credentials');
                return redirect()->back()->withInput();
            }
            if ((in_array(Auth::user()->role_id, [SUPER_ADMIN, ADMIN, ACCOUNTANT, MANAGER, HUMAN_RESOURCE]) !== false)) {
                return $this->redirectBack('success', "login", 'admin/dashboard');
            }

            DB::rollback();
            return Redirect()->back();
        } catch (QueryException $e) {
            DB::rollBack();
            Session::flash(ERROR, $e->getMessage());
            return Redirect()->back()->withInput();
        } catch (Exception $e) {
            DB::rollBack();
            Session::flash(ERROR, $e->getMessage());
            return Redirect()->back()->withInput();
        }
    }

    public function logout()
    {
        Auth::logout();
        Session::flush();

        return redirect('login');
    }
}
