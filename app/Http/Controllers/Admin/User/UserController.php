<?php

namespace App\Http\Controllers\Admin\User;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\User\StoreRequest;
use App\Http\Requests\Admin\User\UpdateRequest;
use App\LatePolicy;
use App\LatePolicyUser;
use App\Mail\SendMail;
use App\Role;
use App\User;
use App\UserLeaveQuota;
use App\UserNotification;
use Carbon\Carbon;
use Exception;
use Illuminate\Database\QueryException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class UserController extends Controller
{
    private $user;
    /*
    |
    |   Controller Instance
    |
    */

    public function __construct()
    {

        $this->user = new User();
        $this->userLeaveQuota = new UserLeaveQuota();
        $this->latePolicyUser = new LatePolicyUser();
        $this->latePolicy = new LatePolicy();
        $this->role = new Role();
    }

    /*
    |
    |   Creating Admin
    |
    */
    public function store(StoreRequest $request)
    {
        try {
            // dd($request->all());
            $random_key = getUniqueKey();
            DB::beginTransaction();
            $inputs = $request->all();
            $user = $this->user->newInstance();
            $user->fill($inputs);
            $user->password = Hash::make($inputs['password']);
            $user->random_key = $random_key;

            if ($request->hasFile('picture')) {

                $filename = 'picture_' . Str::random(40) . '.' . $request->file('picture')->getClientOriginalExtension();

                $request->file('picture')->move('uploads/employee/', $filename);
                $user->picture = $filename;
            }

            // adding user to wiki database
            DB::connection('mysql2')->table('users')->insert([
                'name' => $inputs['first_name'] . ' ' . $inputs['last_name'],
                'email' => $inputs['email'],
                'password' => bcrypt($inputs['password']),
                'role_idFk' => $inputs['role_id'],
                'random_key' => $random_key
            ]);

            if ($user->save()) {

                addUserQuota($user);

                $latePolicy = LatePolicy::whereId($request->policy_id)->first();
                $latePolicyUser = new LatePolicyUser();
                $latePolicyUser->user_id = $user->id;
                $latePolicyUser->late_policy_id = $latePolicy->id;
                $latePolicyUser->variations = json_encode($latePolicy->variations);
                $latePolicyUser->name = $latePolicy->name;
                $latePolicyUser->start_time = $latePolicy->start_time;
                $latePolicyUser->end_time = $latePolicy->end_time;
                $latePolicyUser->type = $latePolicy->type;
                $latePolicyUser->relax_time = $latePolicy->relax_time;
                $latePolicyUser->per_minute = $latePolicy->per_minute;
                $latePolicyUser->start_date = $request->doj;
                $latePolicyUser->save();



                DB::commit();
                return $this->redirectBack(SUCCESS, __('default_label.saved'), URL_ADMIN . "users/listing", "url");
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

    /*
    |
    |   Updating admin
    |
    */
    public function update(UpdateRequest $request)
    {
        try {
            $random_key = getUniqueKey();
            DB::beginTransaction();
            $inputs = $request->all();
            $wikiUser = array(
                'email' => $inputs['email'],
                'name' => $inputs['first_name'] . ' ' . $inputs['last_name'],
                'role_idFk' => $inputs['role_id'],
                'random_key' => getUniqueKey()
            );

            $user = $this->user->newQuery()->whereId($inputs['id'])->first();

            $user->fill($inputs);
            $user->random_key = $random_key;
            if (!empty($inputs["password"])) {
                $user->password = Hash::make($inputs['password']);
                $wikiUser['password'] = bcrypt($inputs['password']);
            } else {
                $wikiUser['password'] = bcrypt($user->password);
            }

            if ($request->hasFile('picture')) {

                $filename = 'picture_' . Str::random(40) . '.' . $request->file('picture')->getClientOriginalExtension();
                $request->file('picture')->move('uploads/employee/', $filename);
                $user->picture = $filename;
            }
            // adding user to wiki database
            DB::connection('mysql2')->table('users')->updateOrInsert(['email' => $wikiUser['email']], $wikiUser);


            if ($user->save()) {
                addUserQuota($user);
                DB::commit();
                return $this->redirectBack("success", __('default_label.updated'), "admin/users/listing");
            }

            DB::rollback();
            return $this->redirectBack("error", __('default_label.update'));
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
    |   Getting delete admin
    |
    */
    public function delete($id)
    {
        try {
            DB::beginTransaction();
            $user = $this->user->newQuery()->whereId($id)->where('role_id', '>', SUPER_ADMIN)->first();
            if ($user && $user->delete()) {
                DB::connection('mysql2')->table('users')->where('email', $user->email)->delete();
                DB::commit();
                return $this->redirectBack("", __('default_label.deleted'), "admin/users/listing");
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

    /*
    |
    |   Getting Listing admin
    |
    */
    public function listing(Request $request)
    {
        try {
            $user = $this->user->newQuery();
            $inputs = $request->all();
            $user->where("role_id", ">", SUPER_ADMIN)->orderBy('first_name', 'ASC');

            if (!empty($inputs['search'])) {
                $user->where(function ($q) use ($inputs) {
                    $this->search($q, $inputs['search'], getSearchColoumns('users'));
                });
            }


            return $this->successListView("", "admin.user.listing", __('user.page_heading'), $user->paginate(DATA_PER_PAGE));
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
            $data = [];
            $data['users'] = $this->user->newQuery()->where('status', '!=', 'Terminate')->get();
            $data['policies'] = $this->latePolicy->newQuery()->get();
            $data['roles'] = $this->role->newQuery()->get();
            return $this->successView("", "admin.user.add", __('user.add_page_heading'), $data);
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
    |   EDIT view to add User
    |
    */
    public function edit(Request $req, $id)
    {
        try {
            if ($this->user->newQuery()->whereId($id)->where('role_id', SUPER_ADMIN)->first() && Auth::user()->role_id !== SUPER_ADMIN) {
                return $this->redirectBack("error", "Unauthorized");
            }

            DB::beginTransaction();
            $inputs = $req->all();
            $users = $this->user->newQuery()->where('status', '!=', 'Terminate')->where('id', '!=', $id)->get();
            $user = $this->user->newQuery()->with('latePolicies')->whereId($id)->first();
            $policies = $this->latePolicy->newQuery()->get();

            if ($user) {
                unset($user->password);
                $roles = $this->role->newQuery()->get();
                DB::commit();
                return $this->successView("", "admin.user.edit", __('user.edit_page_heading'), ['data' => $user, 'users' => $users, 'roles' => $roles, 'policies' => $policies]);
            }

            DB::rollback();
            return $this->redirectBack("", __('default_label.fetch'));
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
    |   User Details View
    |
    */
    public function details(Request $req, $id)
    {
        try {
            if ($this->user->newQuery()->whereId($id)->where('role_id', SUPER_ADMIN)->first() && Auth::user()->role_id !== SUPER_ADMIN) {
                return $this->redirectBack("error", "Unauthorized");
            }

            DB::beginTransaction();
            $user = $this->user->newQuery()->whereId($id)->first();
            if ($user) {
                unset($user->password);

                $latePolicies = [];
                $latePolicies[] = 'No Policy';
                foreach ($user->latePolicies as $policy) {
                    if (!in_array($policy->name, $latePolicies) && $policy->name) {
                        $latePolicies[] = $policy->name;
                    }
                }
                $policies = LatePolicy::all();
                foreach ($policies as $p) {
                    if (!in_array($p->name, $latePolicies)) {
                        $latePolicies[] = $p->name;
                    }
                }

                $user['late_policies'] = $latePolicies;
                DB::commit();
                return $this->successView("", "user-details", __('default_label.details'), $user);
            }

            DB::rollback();
            return $this->redirectBack("", __('default_label.fetch'));
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
    |   Update Attendance Policy of user
    |
    */
    public function changePolicy(Request $request)
    {
        try {
            if ($this->user->newQuery()->whereHas('latePolicy', function ($query) {
                $query->where('user_id', request('user_policy_id'));
            })->where('role_id', SUPER_ADMIN)->first() && Auth::user()->role_id !== SUPER_ADMIN) {
                return $this->redirectBack("error", "Unauthorized");
            }

            DB::beginTransaction();
            if (!$latePolicy = $this->latePolicyUser->newQuery()->where('name', $request->policy_name)->first()) {
                $latePolicy = $this->latePolicy->newQuery()->where('name', $request->policy_name)->first();
            }
            $latePolicyUser = $this->latePolicyUser->newQuery()->whereId($request->user_policy_id)->first();
            if ($latePolicyUser) {
                $latePolicyUser->late_policy_id = $latePolicy ? $latePolicy->late_policy_id : NULL;
                $latePolicyUser->variations = $latePolicy ? $latePolicy->getOriginal('variations') : NULL;
                $latePolicyUser->name = $latePolicy ? $latePolicy->name : 'No Policy';
                $latePolicyUser->start_time = $latePolicy ? $latePolicy->start_time : '';
                $latePolicyUser->end_time = $latePolicy ? $latePolicy->end_time : '';
                $latePolicyUser->type = $latePolicy ? $latePolicy->type : 0;
                $latePolicyUser->relax_time = $latePolicy ? $latePolicy->relax_time : '';
                $latePolicyUser->per_minute = $latePolicy ? $latePolicy->per_minute : 0;
                $latePolicyUser->no_policy = $request->policy_name == 'No Policy' ? true : false;
                $latePolicyUser->save();
                DB::commit();
                return $this->redirectBack("", __('default_label.updated'));
            }
            DB::rollback();
            return $this->redirectBack("error", __('default_label.update'));
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

    public function sendJoinEmail($id)
    {
        try {
            if ($user = $this->user->newQuery()->where('id', $id)->first()) {
                $user->verification_code = Str::random(20);
                if ($user->save()) {
                    new SendMail($user->email, 'Setup Password', 'emails.setup-password', [
                        'token' => $user->verification_code,
                        'user_name' => $user->first_name . ' ' . $user->last_name
                    ]);
                    return $this->redirectBack("success", __('default_label.email_sent'));
                }
                DB::rollback();
                return $this->redirectBack("error", __('default_label.something_went_wrong'));
            }
            DB::rollback();
            return $this->redirectBack("error", __('default_label.something_went_wrong'));
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

    public function sendJoinEmailToAll()
    {
        try {
            $users = $this->user->newQuery()->where('status', '!=', USER_STATUS_TERMINATE)->where('verification_code', NULL)->get();
            foreach ($users as $key => $user) {
                $user->verification_code = Str::random(20);
                if ($user->save()) {
                    new SendMail($user->email, 'Setup Password', 'emails.setup-password', ['token' => $user->verification_code]);
                } else return $this->redirectBack("error", __('default_label.something_went_wrong'));
            }
            return $this->redirectBack("success", __('default_label.email_sent'));
        } catch (QueryException $e) {
            \Session::flash(ERROR, $e->getMessage());
            return Redirect()->back();
        } catch (Exception $e) {
            \Session::flash(ERROR, $e->getMessage());
            return Redirect()->back();
        }
    }

    public function markAllNotificationsAsRead(Request $request)
    {
        try {
            DB::beginTransaction();
            $readNotification = DB::table('user_notifications')->where('user_id', Auth::user()->id)->where('read', '=', 0)->update(array('read' => 1));
            DB::commit();
            return redirect()->back()->with('success', "All Notifications Marked as read");
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

    public function notificaitionListing(Request $request)
    {
        $notifications = UserNotification::where('user_id', Auth::id())->orderBy('created_at', 'DESC')->paginate(5);
        $html = '';
        if ($request->ajax()) {

            foreach ($notifications as $notification) {
                $html .= '<div class="row">
                    <div class="col-sm-12">
                        <div class="card">
                            <div class="card-body">
                                <div class="post-details">
                                    <h3 class="mb-2 text-black font-size">' . $notification->title . '</h3>
                                    <ul class="mb-4 post-meta">
                                      <li class="post-author"><strong> Status: </strong>  ' . (($notification->read == 1) ? "Read" : 'Unread') . '</li>
                                      <li class="post-date"><i class="fa fa-calender"></i>' . $notification->created_at->diffForHumans() . '</li>
                                  </ul>
                                  <div class="row mt-2 ml-1 d-flex-notification ">
                                        <div class="row mt-2 ml-1">
                                        ' . $notification->message . '
                                        </div>
                                        <a href="' . url($notification->type) . '">View</a>
                                  </div>
                                   
                                </div>
                            </div>
                        </div>
                    </div>
                </div>';
            }

            // <ul class="mb-4 post-meta">
            //                             <li class="post-author">' . $announcement->updatedBy->first_name . ' ' . $announcement->updatedBy->last_name . '</li>
            //                             <li class="post-date"><i class="fa fa-calender"></i>' . \Carbon\Carbon::parse($announcement->created_at)->format('d M Y') . '</li>
            //                         </ul>
            return $html;
        }



        return view('partials.admin.user.notification-listing', compact('notifications'));
    }
}
