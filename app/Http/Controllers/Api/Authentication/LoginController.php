<?php

namespace App\Http\Controllers\Api\Authentication;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\Authentication\LoginRequest;
use App\Http\Requests\Api\Authentication\SetupPasswordRequest;
use App\Mail\SendMail;
use App\User;
use Carbon\Carbon;
use Exception;
use Illuminate\Database\QueryException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Tymon\JWTAuth\Exceptions\JWTException;
use Tymon\JWTAuth\Facades\JWTAuth;
use Str;
// use Tymon\JWTAuth\Exceptions\JWTException;
// use Tymon\JWTAuth\JWTAuth;

class LoginController extends Controller
{
    protected $jwt;
    private $user;
    /**
     * @param \Tymon\JWTAuth\JWTAuth $jwt
     * @param \Illuminate\Http\Request $request
     */
    public function __construct(JWTAuth $jwt)
    {
        $this->jwt = $jwt;
        $this->user = new User();
    }

    /**
     * User Login
     */
    public function login(LoginRequest $request)
    {
        $credentials = $request->only('email', 'password');
        try {
            // attempt to verify the credentials and create a token for the user
            if (!$token = JWTAuth::attempt($credentials)) {
                return response()->json([
                    'success' => false,
                    'message' => "Invalid Username or Password "
                ], 401);
            }
            // $user = Auth::user();
            $random_key = getUniqueKey();
            $user = User::find(Auth::id());
            $user->random_key = $random_key;
            $user->update();
            DB::connection('mysql2')->table('users')->where('email', $user->email)->update(['random_key' => $random_key]);
            DB::commit();


            if ($user->picture == null) {
                $user->picture = asset('employeesAsset/images/profile/profile.png');
            } else {
                $user->picture = asset('uploads/employee/' . $user->picture);
            }
            $toReturn['user'] = $user;
            $toReturn['token'] = $token;
            return $this->apiSuccessWithData("Successfully Logged in", $toReturn);
        } catch (JWTException $e) {
            // something went wrong whilst attempting to encode the token
            // return response()->json(['error' => 'could_not_create_token'], 500);
            return $this->apiError(__('general.could_not_authenticate_user'), ERROR_400);
        }
    }

    public function sendVerificationCode(Request $request)
    {
        try {
            if ($user = $this->user->newQuery()->where('email', $request->email)->first()) {

                do {
                    $rand_num = rand(1000, 9999);
                    $user->verification_code = $rand_num;
                } while (DB::table('users')->where('verification_code', $rand_num)->exists());

                $user->save();
                new SendMail(
                    $user->email,
                    'Verification Code',
                    'emails.setup-password',
                    [
                        'token' => $user->verification_code,
                        'user_name' => $user->first_name . ' ' . $user->last_name
                    ]
                );
            }
            return $this->apiSuccess("Verification code send to your email successfully", 200);
        } catch (QueryException $e) {
            return $this->apiError($e->getMessage(), ERROR_500);
        } catch (Exception $e) {
            return $this->apiError($e->getMessage(), ERROR_500);
        } catch (JWTException $e) {
            // something went wrong whilst attempting to encode the token
            return $this->apiError($e->getMessage(), ERROR_500);
        }
    }

    public function logout(Request $request)
    {
        try {
            $inputs = $request->all();
            if ($this->jwt->invalidate()) {
                return $this->apiSuccess(__('general.logout_success'));
            } else return $this->apiError(__('general.something_went_wrong'), 400);
        } catch (QueryException $e) {
            return $this->apiError($e->getMessage(), ERROR_500);
        } catch (Exception $e) {
            return $this->apiError($e->getMessage(), ERROR_500);
        } catch (JWTException $e) {
            // something went wrong whilst attempting to encode the token
            return $this->apiError($e->getMessage(), ERROR_500);
        }
    }

    public function checkVerificationCode(Request $request)
    {
        try {
            $inputs = $request->all();
            if (!empty($inputs['code'])) {
                if ($user = $this->user->newQuery()->where('verification_code', $request->code)->first()) {
                    if (Carbon::now()->diffInMinutes($user->updated_at) <= 5) {
                        if ($user->verification_code == $request->code) {
                            return $this->apiSuccessWithData(SUCCESS, ['id' => $user->id]);
                        }
                    } else {
                        return $this->apiError("Your code expires try another one", 401);
                    }
                } else return $this->apiError(__('default_label.something_went_wrong'), 400);
            } else return $this->apiError(__('default_label.parameter_missing'), 401);
        } catch (QueryException $e) {
            return $this->apiError($e->getMessage(), ERROR_500);
        } catch (Exception $e) {
            return $this->apiError($e->getMessage(), ERROR_500);
        } catch (JWTException $e) {
            // something went wrong whilst attempting to encode the token
            return $this->apiError($e->getMessage(), ERROR_500);
        }
    }


    public function setupPassword(SetupPasswordRequest $request)
    {
        try {
            $inputs = $request->all();
            if (!empty($inputs['code'])) {
                if ($user = $this->user->newQuery()->where('verification_code', $inputs['code'])->where('id', $inputs['user_id'])->first()) {
                    if ($inputs['password'] === $inputs['password_confirmation']) {
                        $user->password = Hash::make($inputs['password']);
                        $user->verification_code = NULL;
                        if ($user->save()) {
                            DB::connection('mysql2')->table('users')->where('email', $user->email)->update(['password' => Hash::make($inputs['password'])]);
                            // Auth::login($user);
                            // $this->user = Auth::user();
                            // $this->user->jwt_sign = null;

                            // $toReturn['user'] = $this->user;
                            // $toReturn['token'] = $this->user->generateJWTToken();
                            return $this->apiSuccess("Password set successfully", 200);
                        } else return $this->apiError(__('default_label.something_went_wrong'), 400);
                    } else {
                        return $this->apiError("New Password and Confirm Password Field do not match !! ", 400);
                    }
                } else {
                    return $this->apiError(__('default_label.something_went_wrong'), 400);
                }
            } else return $this->apiError(__('default_label.parameter_missing'), 400);
        } catch (QueryException $e) {
            return $this->apiError($e->getMessage(), ERROR_500);
        } catch (Exception $e) {
            return $this->apiError($e->getMessage(), ERROR_500);
        } catch (JWTException $e) {
            // something went wrong whilst attempting to encode the token
            return $this->apiError($e->getMessage(), ERROR_500);
        }
    }
}
