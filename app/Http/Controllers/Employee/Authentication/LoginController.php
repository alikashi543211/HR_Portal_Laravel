<?php

namespace App\Http\Controllers\Employee\Authentication;

use App\Http\Controllers\Controller;
use App\Mail\SendMail;
use App\User;
use Carbon\Carbon;
use Exception;
use Illuminate\Database\QueryException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;
use Tymon\JWTAuth\JWTAuth;
use GuzzleHttp\Client;
use Illuminate\Support\Facades\Hash;

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
        // $this->jwt = $jwt;
        $this->user = new User();
    }
    /**
     * User Login
     */
    public function login(Request $request)
    {
        try {
            DB::beginTransaction();
            $credentials = $request->only('email', 'password');

            if (Auth::attempt($credentials)) {
                $random_key = getUniqueKey();
                $user = User::find(Auth::id());
                $user->random_key = $random_key;
                $user->update();
                DB::connection('mysql2')->table('users')->where('email', $user->email)->update(['random_key' => $random_key]);

                DB::commit();
                return redirect()->route('employee.dashboard');
            }
            Session::flash('error', 'Invalid Credentials');
            return redirect()->back()->withInput();
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

    public function loginPage()
    {
        return view('employees.auth.login');
    }

    public function logout(Request $request)
    {
        try {
            if ($user = User::find(Auth::id())) {
                $user->random_key = null;
                $user->update();
                DB::connection('mysql2')->table('users')->where('email', $user->email)->update(['random_key' => null]);
                Auth::logout();
                Session::flush();
            }
            return redirect()->route('employee.get-login');
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


    public function saveToken(Request $request)
    {
        $user = User::find(auth()->user()->id);
        $user->device_token = $request->token;
        $user->save();
        return response()->json(['token saved successfully.']);
    }

    public function wikiLogin()
    {
        return redirect(env("WIKI_URL") . "/user-login?random_key=" . Auth::user()->random_key);
    }

    public function getVerificationCode()
    {
        return view('employees.auth.forget-email');
    }

    public function sendVerificationCode(Request $request)
    {
        try {
            DB::beginTransaction();
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
                        'user_name' => $user->first_name . " " . $user->last_name
                    ]
                );
            }
            DB::commit();
            return redirect()->route('employee.verificationCodePage')->with('success', 'Verification code has been sent to your email');
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

    public function verificationCodePage()
    {
        return view('employees.auth.verify-code');
    }

    public function verifyCode(Request $request)
    {
        try {
            $inputs = $request->all();
            if (!empty($inputs['code'])) {
                if ($user = $this->user->newQuery()->where('verification_code', $request->code)->first()) {
                    if (Carbon::now()->diffInMinutes($user->updated_at) <= 5) {
                        if ($user->verification_code == $request->code) {
                            $user_id = $user->id;
                            return redirect()->route('employee.getPasswordPage', $request->code)->with('user_id', $user_id);
                        }
                    } else {
                        return redirect()->back()->with('error', "Your code expires try another one");
                    }
                } else return  redirect()->back()->with('error', "Incorrect Code Try again");
            } else return  redirect()->back()->with('error', "Something went wrong");
        } catch (QueryException $e) {
            Session::flash(ERROR, $e->getMessage());
            return Redirect()->back()->withInput();
        } catch (Exception $e) {
            Session::flash(ERROR, $e->getMessage());
            return Redirect()->back()->withInput();
        }
    }

    public function setupPassword(Request $request)
    {
        try {
            DB::beginTransaction();
            $inputs = $request->all();
            if ($user = $this->user->newQuery()->where('id', $inputs['user_id'])->first()) {
                if ($inputs['password'] === $inputs['password_confirmation']) {
                    $user->password = Hash::make($inputs['password']);
                    $user->verification_code = NULL;
                    if ($user->save()) {
                        DB::connection('mysql2')->table('users')->where('email', $user->email)->update(['password' => Hash::make($inputs['password'])]);
                        // dd($request->all());
                        DB::commit();
                        return redirect()->route('employee.login')->with('success', "Password set successfully");
                    } else return redirect()->back()->with('error', "Something went wrong");
                } else {
                    return redirect()->back()->with('error', "New Password and Confirm Password Field do not match !!");
                }
            } else {
                return  redirect()->back()->with('error', "Something went wrong");
            }
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

    public function getPasswordPage($code)
    {
        if ($user = $this->user->newQuery()->where('verification_code', $code)->first()) {
            // if (Carbon::now()->diffInMinutes($user->updated_at) <= 5) {
            $user_id = $user->id;
            return view('employees.auth.new-pasword', compact('user_id'));
            // } else {
            //     return redirect()->back()->with('error', "Your code expires try another one");
            // }
        } else return  redirect()->back()->with('error', "Incorrect Code Try again");
    }
}
