<?php

namespace App\Http\Controllers\Employee\Profile;

use App\Http\Controllers\Controller;
use App\Http\Requests\Employee\Profile\ChangePasswordRequest;
use App\User;
use Exception;
use Illuminate\Database\QueryException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class ProfileController extends Controller
{
    public function __construct()
    {
        $this->user = new User();
    }

    /*
    |
    |   Profile Details
    |
    */

    public function details()
    {
        try {

            $data = [];

            $user = $this->user->where('id', Auth::id())->first();
            if ($user) {
                $data = $user;
            }
            return view('employees.profile.detail', compact('data'));
        } catch (QueryException $e) {
            return redirect()->back()->with('error', 'Something went wrong');
        } catch (Exception $e) {
            return redirect()->back()->with('error', 'Something went wrong');
        }
    }

    /*
    |
    |   Update Password
    |
    */

    public function changePassword(Request $request)
    {
        if ($request->password !== $request->password_confirmation) {
            return redirect()->back()->with('error', 'Your new password and confirm password are not same');
        }
        try {
            DB::beginTransaction();
            $inputs = $request->all();
            if (!Hash::check($inputs['old_password'], Auth::user()->password)) {
                return redirect()->back()->with('error', 'Incorrect Old Password');
            }
            $user = User::find(Auth::id());
            $user->password = Hash::make($inputs['password']);
            if ($user->save()) {
                DB::connection('mysql2')->table('users')->where('email', Auth::user()->email)->update([
                    "password" => Hash::make($inputs['password'])
                ]);
                return redirect()->back()->with('success', 'Password Updated Successfully');
            } else {
                return redirect()->back()->with('error', 'Something went wrong');
            }
            DB::commit();
        } catch (QueryException $e) {
            DB::rollBack();
            return redirect()->back()->with('error', 'Something went wrong');
        } catch (Exception $e) {
            DB::rollBack();
            return redirect()->back()->with('error', 'Something went wrong');
        }
    }

    public function changeImage(Request $request)
    {
        try {
            $inputs = $request->all();
            $user = User::find(Auth::user()->id);
            $user->picture = $this->uploadFile('employee', 'profileImage');
            $user->save();

            if ($user->save()) {
                return  response()->json(['success' => "Image Updated Successfully"]);
            }

            return response()->json(['error' => 'Something went wrong']);
        } catch (QueryException $e) {
            return response()->json(['error' => 'Something went wrong']);
        } catch (Exception $e) {
            return response()->json(['error' => 'Something went wrong']);
        }
    }

    public function updateProfile(Request $request)
    {
        try {

            $inputs = $request->all();
            $user = User::find(Auth::user()->id);
            $user->fill($inputs);
            $user->save();

            if ($user->save()) {
                return  redirect()->back()->with('success', "Profile Updated Successfully");
            }

            return redirect()->back()->with('error', 'Something went wrong');
        } catch (QueryException $e) {
            return redirect()->back()->with('error', 'Something went wrong');
        } catch (Exception $e) {
            return redirect()->back()->with('error', 'Something went wrong');
        }
    }
}
