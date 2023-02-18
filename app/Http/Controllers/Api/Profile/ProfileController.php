<?php

namespace App\Http\Controllers\Api\Profile;

use App\Announcement;
use App\Attendance;
use App\Http\Controllers\Controller;
use App\Http\Requests\Api\Profile\ChangeEmergencyDetailsApiRequest;
use App\Http\Requests\Api\Profile\ChangeImageRequest;
use App\Http\Requests\Api\Profile\ChangePasswordApiRequest;
use App\Http\Requests\Api\Profile\ChangeUserInfoApiRequest;
use App\Http\Requests\Employee\Profile\ChangePasswordRequest;
use App\User;
use Exception;
use Illuminate\Database\QueryException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use stdClass;
use Tymon\JWTAuth\Exceptions\JWTException;

class ProfileController extends Controller
{
    public function __construct()
    {
    }
    public function changePassword(ChangePasswordApiRequest $request)
    {
        try {
            $inputs = $request->all();
            if (!Hash::check($inputs['old_password'], Auth::user()->password)) {
                return $this->apiError('Incorrect Old Password', ERROR_400);
            }
            $user = User::find(Auth::id());
            $user->password = Hash::make($inputs['password']);
            if ($user->save()) {
                DB::connection('mysql2')->table('users')->where('email', Auth::user()->email)->update(
                    ["password" => Hash::make($inputs['password'])]
                );
                $toReturn['user_id'] = Auth::user()->id;
                return $this->apiSuccessWithData("Password Updated Successfully", $toReturn);
            } else {
                return $this->apiError('Something went wrong', ERROR_400);
            }
        } catch (QueryException $e) {
            return $this->apiError('Something went wrong', ERROR_400);
        } catch (Exception $e) {
            return $this->apiError("Something went wrong", ERROR_400);
        }
    }

    public function changeEmergencyDetails(ChangeEmergencyDetailsApiRequest $request)
    {
        try {
            $inputs = $request->all();
            $user = User::find(Auth::id());
            $user->emergency_contact_name  = $inputs['emergency_contact_name'];
            $user->emergency_contact_relation = $inputs['emergency_contact_relation'];
            $user->emergency_contact_number  = $inputs['emergency_contact_number'];
            if ($user->save()) {
                $toReturn['user_id'] = Auth::user()->id;
                return $this->apiSuccessWithData("User Updated Successfully", $toReturn);
            } else {
                return $this->apiError('Something went wrong', ERROR_400);
            }
        } catch (QueryException $e) {
            return $this->apiError('Something went wrong', ERROR_400);
        } catch (Exception $e) {
            return $this->apiError("Something went wrong", ERROR_400);
        }
    }

    public function changeImage(ChangeImageRequest $request)
    {
        // dd($request->all());

        try {
            $inputs = $request->all();
            $user = User::find(Auth::user()->id);
            $nameImg = time() . '.' . $request->format;
            $path  = public_path('uploads/employee/')  . $nameImg;
            $image = base64_decode($request->base64String);
            $success = file_put_contents($path, $image);
            $user->picture = $nameImg;
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


    public function updateProfile(ChangeUserInfoApiRequest $request)
    {
        try {
            $inputs = $request->all();
            $user = User::find(Auth::user()->id);
            $user->personal_email = $request->personal_email;
            $user->phone_number = $request->phone_number;
            $user->save();
            if ($user->save()) {
                $toReturn['user_id'] = Auth::user()->id;
                return $this->apiSuccess("Profile Updated Successfully", $toReturn);
            }
            return $this->apiError("Something went wrong", ERROR_400);
        } catch (QueryException $e) {
            return $this->apiError("Something went wrong", ERROR_400);
        } catch (Exception $e) {
            return $this->apiError("Something went wrong", ERROR_400);
        }
    }
}
