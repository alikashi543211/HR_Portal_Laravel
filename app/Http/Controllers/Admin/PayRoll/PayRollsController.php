<?php

namespace App\Http\Controllers\Admin\PayRoll;

use App\Allowance;
use App\CompanyProfile;
use App\Exports\ExportMultipleSheets;
use App\Exports\PayRollExport;
use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\PayRoll\AddAllowanceRequest;
use App\Http\Requests\Admin\PayRoll\AddExtraRequest;
use App\Http\Requests\Admin\PayRoll\StoreRequest;
use App\Http\Requests\Admin\PayRoll\UserGovrtTaxRequest;
use App\Increment;
use App\PayRoll;
use App\Permission;
use App\RolePermission;
use App\User;
use App\UserPayRoll;
use App\UserPayRollAllowance;
use App\UserPayRollExtra;
use Carbon\Carbon;
use Carbon\CarbonPeriod;
use Exception;
use Illuminate\Database\QueryException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;
use Str;
use DomPDF;
use MPDF;
use Excel;
use Meneses\LaravelMpdf\LaravelMpdf;

class PayRollsController extends Controller
{
    /*
    |
    |   Controller Instance
    |
    */

    public function __construct()
    {
        $this->model = new PayRoll();
        $this->user = new User();
        $this->userPayRoll = new UserPayRoll();
        $this->userPayRollAllowance = new UserPayRollAllowance();
        $this->userPayRollExtra = new UserPayRollExtra();
        $this->allowance = new Allowance();
        $this->defaultRedirectPath = URL_ADMIN . "pay-rolls/";
        $this->defaultViewPath = "admin.pay-rolls.";
    }

    /*
    |
    |   Getting Listing admin
    |
    */
    public function listing(Request $request)
    {

        try {
            $model = $this->model->newQuery()->orderBy('created_at', 'DESC');
            $inputs = $request->all();

            if (!empty($inputs['search'])) {
                $model->where(function ($q) use ($inputs) {
                    $this->search($q, $inputs['search'], ['date']);
                });
            }

            return $this->successListView("", $this->defaultViewPath . "listing", __('payroll.page_heading'), $model->paginate(DATA_PER_PAGE));
        } catch (QueryException $e) {
            Session::flash(ERROR, $e->getMessage());
            return Redirect()->back();
        } catch (Exception $e) {
            Session::flash(ERROR, $e->getMessage());
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

            // dd(PayRoll::where('date', Carbon::now()->subMonth(2)->format('F, Y'))->first()->payRollTaxes);
            if (PayRoll::where('date', Carbon::now()->subMonth(2)->format('F, Y'))->first()) {
                if (!count(PayRoll::where('date', Carbon::now()->subMonth(2)->format('F, Y'))->first()->payRollTaxes)) {
                    return $this->redirectBack('error', 'Please upload the taxes for the month of ##last month name## first.');
                }
            }
            $users = $this->user->newQuery()->where(function ($query) {
                $query->whereMonth('dot', Carbon::now()->subMonth()->format('m'))
                    ->orWhere('dot', NULL);
            })->where('role_id', '!=', 1)->get();
            return $this->successView("", $this->defaultViewPath . "add", __('payroll.add_page_heading'), $users);
        } catch (QueryException $e) {
            Session::flash(ERROR, $e->getMessage());
            return Redirect()->back();
        } catch (Exception $e) {
            Session::flash(ERROR, $e->getMessage());
            return Redirect()->back();
        }
    }

    /*
    |
    |   Creating Admin
    |
    */
    public function store(StoreRequest $request)
    {
        try {
            DB::beginTransaction();
            $inputs = $request->all();
            $model = $this->model->newInstance();
            $inputs['created_by'] = Auth::id();
            $model->fill($inputs);
            if ($model->save()) {
                if ($this->calculateUserPayroll($inputs, $model)) {
                    DB::commit();
                    return $this->redirectBack(SUCCESS, __('default_label.saved'), $this->defaultRedirectPath . "listing", "url");
                }
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

    private function calculateUserPayroll($inputs, $model)
    {
        $inputs['date'] = str_replace(',', '', $inputs['date']);
        $dateStr = date('Y-m', strtotime(str_replace(',', '', $inputs['date'])));
        foreach ($inputs['user_id'] as $key => $id) {
            if (
                !$this->model->newQuery()->where('date', $inputs['date'])->whereHas('payRolls', function ($q) use ($id) {
                    $q->where('user_pay_rolls.user_id', $id);
                })->exists()
                &&
                $this->user->newQuery()->whereId($id)->where(function ($q) use ($dateStr) {
                    $q->where(function ($q) use ($dateStr) {
                        $q->where('dot', '>=', $dateStr . '-01')
                            ->where('doj', '<=', $dateStr . '-31');
                    })
                        ->orWhereNull('dot');
                })->exists()

            ) {
                $user = $this->user->newQuery()->whereId($id)->first();
                // check if user increment on this month
                if ($increment = Increment::where('user_id', $user->id)->whereDate('month', date('Y-m-d', strtotime($model->date)))->whereStatus(0)->first()) {
                    $user->base_salary += $increment->increment;
                    $user->save();
                    $user = $user->fresh();
                    $increment->status = 1;
                    $increment->save();
                }

                $new = $this->userPayRoll->newInstance();
                $new->user_id = $id;
                $new->pay_roll_id = $model->id;
                $new->gross_salary = $user->base_salary;
                $new->base_salary = (66.66 / 100) * $user->base_salary;
                $new->house_rent = (30 / 100) * $user->base_salary;
                $new->utility = (3.34 / 100) * $user->base_salary;
                $new->net_salary = $user->base_salary;
                $new->govrt_tax = $user->govrt_tax;
                $new->user_id = $id;
                if ($new->save()) {
                    $allowances = getAllowances($user, $new);
                    $new->allowances = $allowances;
                    $deductions = getMonthDeductions($user, $inputs['date']);
                    $new->paid_working_days = number_format($deductions['paid_days'], 1);
                    $new->late_deduction = round($deductions['late_deduction']);
                    $new->leave_deduction = round($deductions['leave_deduction']);
                    $new->loan_deduction = round($deductions['loan_deduction']);
                    $new->leave_count = $deductions['leave_count'];
                    $new->net_salary = ($user->base_salary + $allowances) - (round($deductions['late_deduction']) + round($deductions['leave_deduction']) + round($deductions['loan_deduction']));
                    if (!$new->save()) {
                        return false;
                    }
                } else return false;
            }
        }
        return true;
    }

    /*
    |
    |   Creating Admin
    |
    */
    public function userPayRollAllowanceSave(AddAllowanceRequest $request)
    {
        try {
            DB::beginTransaction();
            $inputs = $request->all();
            $model = $this->userPayRoll->newQuery()->whereId($inputs['user_pay_roll_id'])->first();
            $allowance = $this->allowance->newQuery()->whereId($inputs['allowance_id'])->first();

            $new = $this->userPayRollAllowance->newInstance();
            $new->user_id = $model->user_id;
            $new->user_pay_roll_id = $model->id;
            $new->allowance_id = $allowance->id;
            $amount = 0;
            if ($allowance->type == ALLOWANCES_FIXED) {
                $amount = $allowance->value;
            } else {
                $amount = ($allowance->value / 100) * $model->user->base_salary;
            }
            $new->amount = $amount;
            $new->save();

            // $model->allowances = $model->allowances + $amount;
            // $model->net_salary = $model->net_salary + $amount;

            if ($model->save()) {
                DB::commit();
                return redirect()->back()->with('user_pay_roll_success', __('default_label.saved'))->with('id', $model->user_id);
            }

            DB::rollback();
            return redirect()->back()->with('error', __('default_label.save'));
        } catch (QueryException $e) {
            DB::rollBack();
            return Redirect()->back();
        } catch (Exception $e) {
            DB::rollBack();
            return Redirect()->back();
        }
    }

    /*
    |
    |   Creating Admin
    |
    */
    public function userPayRollExtraSave(AddExtraRequest $request)
    {
        try {
            DB::beginTransaction();
            $inputs = $request->all();
            $model = $this->userPayRoll->newQuery()->whereId($inputs['user_pay_roll_id'])->first();

            $new = $this->userPayRollExtra->newInstance();
            $new->user_id = $model->user_id;
            $new->user_pay_roll_id = $model->id;
            $new->name = $inputs['name'];
            $new->amount = $inputs['amount'];
            $new->type = $inputs['type'];
            $new->save();

            // if ($inputs['type'] == EXTRA_CONTRIBUTIONS) {
            //     $model->net_salary = $model->net_salary + $inputs['amount'];
            // } else {
            //     $model->net_salary = $model->net_salary - $inputs['amount'];
            // }


            if ($model->save()) {
                DB::commit();
                return redirect()->back()->with('user_pay_roll_success', __('default_label.saved'))->with('id', $model->user_id);
            }

            DB::rollback();
            return redirect()->back()->with('error', __('default_label.save'));
        } catch (QueryException $e) {
            DB::rollBack();
            return Redirect()->back();
        } catch (Exception $e) {
            DB::rollBack();
            return Redirect()->back();
        }
    }

    /*
    |
    |   Creating Admin
    |
    */
    public function userGovrtTaxUpdate(UserGovrtTaxRequest $request)
    {
        try {
            DB::beginTransaction();
            $inputs = $request->all();
            $model = $this->userPayRoll->newQuery()->whereId($inputs['user_pay_roll_id'])->first();

            // $model->net_salary = ($model->net_salary + $model->govrt_tax) - $inputs['govrt_tax'];
            $model->govrt_tax = $inputs['govrt_tax'];


            if ($model->save()) {
                DB::commit();
                return redirect()->back()->with('user_pay_roll_success', __('default_label.saved'))->with('id', $model->user_id);
            }

            DB::rollback();
            return redirect()->back()->with('error', __('default_label.save'));
        } catch (QueryException $e) {
            DB::rollBack();
            return Redirect()->back();
        } catch (Exception $e) {
            DB::rollBack();
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
            $model = $this->model->newQuery()->whereId($id)->whereLock(0)->first();
            $model->payRolls()->delete();
            if ($model && $model->delete()) {
                DB::commit();
                return $this->redirectBack("success", __('default_label.deleted'), $this->defaultRedirectPath . "listing");
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

    /*
    |
    |   Getting delete admin
    |
    */
    public function userPayRollDelete($id)
    {
        try {
            DB::beginTransaction();
            $model = $this->userPayRoll->newQuery()->whereId($id)->whereHas('payRoll', function ($q) {
                $q->whereLock(0);
            })->first();

            if ($model && $model->delete()) {
                DB::commit();
                return $this->redirectBack("success", __('default_label.deleted'), $this->defaultRedirectPath . "listing");
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

    /*
    |
    |   Add user to existing
    |
    */
    public function userPayRollAddPage($id)
    {
        try {
            DB::beginTransaction();
            $model = $this->model->newQuery()->whereId($id)->first();
            $users = $this->user->newQuery()->whereDoesntHave('payrolls', function ($query) use ($id) {
                $query->where('pay_roll_id', $id);
            })->where(function ($query) {
                $query->whereMonth('dot', Carbon::now()->subMonth()->format('m'))
                    ->orWhere('dot', NULL);
            })->get();
            if ($model) {
                if ($model->lock) {
                    return $this->redirectBack("error", __('default_label.payroll_locked'), $this->defaultRedirectPath . "detail/$id");
                } else return $this->successView("", $this->defaultViewPath . "add-user", __('payroll.add_page_heading'), ['users' => $users, 'payroll' => $model]);
            }
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

    /*
    |
    |   Add user to existing
    |
    */
    public function storeUser(Request $request)
    {
        try {
            DB::beginTransaction();
            $inputs = $request->all();
            $payroll = $this->model->newQuery()->where('id', $inputs['id'])->first();
            $inputs['date']  = str_replace(',', '', $payroll->date);
            if ($payroll) {
                if ($payroll->lock) {
                    return $this->redirectBack("error", __('default_label.payroll_locked'), $this->defaultRedirectPath . "detail/" . $inputs['id']);
                }
                if (!$this->calculateUserPayroll($inputs, $payroll)) {
                    DB::rollback();
                    return $this->redirectBack("", __('default_label.save'));
                }
            }
            DB::commit();
            return $this->redirectBack("success", __('default_label.saved'), $this->defaultRedirectPath . "detail/" . $inputs['id']);
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

    /*
    |
    |   Add user to existing
    |
    */
    public function userPayRollAdd($id)
    {
        try {
            DB::beginTransaction();
            $model = $this->userPayRoll->newQuery()->whereId($id)->whereHas('payRoll', function ($q) {
                $q->whereLock(0);
            })->first();

            if ($model && $model->delete()) {
                DB::commit();
                return $this->redirectBack("success", __('default_label.deleted'), $this->defaultRedirectPath . "listing");
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

    /*
    |
    |   Getting delete admin
    |
    */
    public function userPayRollAllowanceDelete($id)
    {
        try {
            DB::beginTransaction();
            $model = $this->userPayRollAllowance->newQuery()->whereId($id)->first();
            // $userPayRoll = $this->userPayRoll->newQuery()->whereId($model->user_pay_roll_id)->first();
            // $userPayRoll->allowances = $userPayRoll->allowances - $model->amount;
            // $userPayRoll->net_salary = $userPayRoll->net_salary - $model->amount;
            // $userPayRoll->save();
            if ($model && $model->delete()) {
                DB::commit();
                return redirect()->back()->with('user_pay_roll_success', __('default_label.deleted'))->with('id', $model->user_pay_roll_id);
            }

            DB::rollback();
            return redirect()->back()->with('error', __('default_label.delete'));
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
    /*
    |
    |   Getting delete admin
    |
    */
    public function userPayRollExtraDelete($id)
    {
        try {
            DB::beginTransaction();
            $model = $this->userPayRollExtra->newQuery()->whereId($id)->first();

            // $userPayRoll = $this->userPayRoll->newQuery()->whereId($model->user_pay_roll_id)->first();
            // if ($model->type == EXTRA_CONTRIBUTIONS) {
            //     $userPayRoll->net_salary = $userPayRoll->net_salary - $model->amount;
            // } else {
            //     $userPayRoll->net_salary = $userPayRoll->net_salary + $model->amount;
            // }
            // $userPayRoll->save();
            if ($model && $model->delete()) {
                DB::commit();
                return redirect()->back()->with('user_pay_roll_success', __('default_label.deleted'))->with('id', $model->user_pay_roll_id);
            }

            DB::rollback();
            return redirect()->back()->with('error', __('default_label.delete'));
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


    /*
    |
    |   EDIT view to add User
    |
    */
    public function detail(Request $req, $id)
    {
        try {
            DB::beginTransaction();
            $inputs = $req->all();
            $models = $this->userPayRoll->newQuery()->wherePayRollId($id);


            if (!empty($inputs['search'])) {
                $models->where(function ($q) use ($inputs) {
                    $this->search($q, $inputs['search'], getSearchColoumns('user_pay_rolls'));
                    $this->search($q, $inputs['search'], getSearchColoumns('users'), 'user');
                });
            }
            $models->orderBy(User::select('doj')->whereColumn('users.id', 'user_pay_rolls.user_id'), 'ASC')->with(['user']);
            return $this->successListView("", $this->defaultViewPath . "detail", __('payroll.detail_page_heading'), $models->get());

            DB::rollback();
            return $this->redirectBack("", __('default_label.fetch'));
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

    public function exportSalaryPDF(Request $request)
    {
        $query = $this->userPayRoll->newQuery();
        if ($request->segment(3) == 'details') {
            $query->where('id', $request->id);
        } else {
            $query->where('pay_roll_id', $request->id);
        }
        $payrolls = $query->whereHas('user')->with('user')->get();
        if (count($payrolls) > 0) {
            if ($request->segment(3) == 'details') {
                $name = $payrolls[0]->user->first_name . '-' . $payrolls[0]->user->last_name . '-' . date('M-Y', strtotime($payrolls[0]->payroll->date)) . '.pdf';
            } else {
                $name = date('M-Y', strtotime($payrolls[0]->payroll->date)) . '.pdf';
            }
            $pdf = MPDF::loadView('partials.admin.exports.salary-slip-pdf', ['payrolls' => $payrolls], [], ['title' => 'Salary Slip']);
            return $pdf->download($name);
        } else return $this->redirectBack("", __('default_label.fetch'));
    }

    // public function exportPayrollPDF(Request $request)
    // {
    //     $query = $this->userPayRoll->newQuery();
    //     if ($request->segment(3) == 'details') {
    //         $query->where('id', $request->id);
    //     } else {
    //         $query->where('pay_roll_id', $request->id);
    //     }
    //     $payrolls = $query->with('user', 'extras')->get();
    //     $extras = $this->userPayRollExtra->newQuery()->whereHas('userPayRoll', function ($q) {
    //         $q->where('pay_roll_id', request('id'));
    //     })->get()->unique('name');
    //     if (count($payrolls) > 0) {
    //         if ($request->segment(3) == 'details') {
    //             $name = $payrolls[0]->user->first_name . '-' . $payrolls[0]->user->last_name . '-' . date('M-Y', strtotime($payrolls[0]->payroll->date)) . '.pdf';
    //         } else {
    //             $name = date('M-Y', strtotime($payrolls[0]->payroll->date)) . '.pdf';
    //         }
    //         return view('payrollpdf')->with(['data' => $payrolls, 'extras' => $extras]);
    //         // $pdf = PDF::loadView('partials.admin.exports.salary-slip-pdf', ['payrolls' => $payrolls])->setPaper('a4', 'portrait')->setWarnings(false);
    //         $pdf = PDF::loadView('payrollpdf', ['data' => $payrolls, 'extras' => $extras])->setPaper('a4', 'landscape')->setWarnings(false);
    //         return $pdf->download($name);
    //     } else return $this->redirectBack("", __('default_label.fetch'));
    // }

    public function exportPayrollPDF(Request $request)
    {
        $query = $this->userPayRoll->newQuery();
        if ($request->segment(3) == 'details') {
            $query->where('id', $request->id);
        } else {
            $query->where('pay_roll_id', $request->id);
        }
        $payrolls = $query->whereHas('user')->with('user', 'extras')->get();
        $extras = $this->userPayRollExtra->newQuery()->whereHas('userPayRoll', function ($q) {
            $q->where('pay_roll_id', request('id'));
        })->get()->unique('name');
        if (count($payrolls) > 0) {
            if ($request->segment(3) == 'details') {
                $name = $payrolls[0]->user->first_name . '-' . $payrolls[0]->user->last_name . '-' . date('M-Y', strtotime($payrolls[0]->payroll->date)) . '.pdf';
            } else {
                $name = date('M-Y', strtotime($payrolls[0]->payroll->date)) . '.pdf';
            }
            // return view('payrollpdf')->with(['data' => $payrolls, 'extras' => $extras]);
            // $pdf = PDF::loadView('partials.admin.exports.salary-slip-pdf', ['payrolls' => $payrolls])->setPaper('a4', 'portrait')->setWarnings(false);
            $pdf = MPDF::loadView('payrollpdf', ['data' => $payrolls, 'extras' => $extras], [], ['title' => 'Payroll']);
            return $pdf->download($name);
        } else return $this->redirectBack("", __('default_label.fetch'));
    }

    public function exportSalaryExcel(Request $request)
    {
        $query = $this->userPayRoll->newQuery();
        if ($request->segment(3) == 'details') {
            $query->where('id', $request->id);
        } else {
            $query->where('pay_roll_id', $request->id);
        }
        $payroll = $query->whereHas('user')->with('user')->first();
        if ($payroll) {
            if ($request->segment(3) == 'details') {
                $name = $payroll->user->first_name . '-' . $payroll->user->last_name . '-' . date('M-Y', strtotime($payroll->payroll->date));
            } else {
                $name = date('M-Y', strtotime($payroll->payroll->date));
            }
            return Excel::download(new ExportMultipleSheets, $name . '.xlsx');
        } else return $this->redirectBack("", __('default_label.fetch'));
    }

    /*
    |
    |   Getting Recalculate PayRolls
    |
    */
    public function recalculatePayRoll($id)
    {
        try {
            DB::beginTransaction();
            $payRollUsers = $this->userPayRoll->newQuery()->wherePayRollId($id)->whereHas('payRoll', function ($q) {
                $q->whereLock(0);
            })->get();

            if (count($payRollUsers) > 0) {
                foreach ($payRollUsers as $key => $payRollUser) {
                    $updatePay = $payRollUser;
                    $user = $this->user->newQuery()->whereId($updatePay->user_id)->first();
                    $updatePay->gross_salary = $user->base_salary;
                    $updatePay->base_salary = (66.66 / 100) * $user->base_salary;
                    $updatePay->house_rent = (30 / 100) * $user->base_salary;
                    $updatePay->utility = (3.34 / 100) * $user->base_salary;
                    $updatePay->net_salary = $user->base_salary;
                    if ($updatePay->save()) {
                        $allowances = getAllowances($user, $updatePay);
                        $updatePay->allowances = $allowances;
                        $deductions = getMonthDeductions($user, $payRollUser->payRoll->date);
                        $updatePay->paid_working_days = number_format($deductions['paid_days'], 1);
                        $updatePay->late_deduction = round($deductions['late_deduction']);
                        $updatePay->leave_deduction = round($deductions['leave_deduction']);
                        $updatePay->loan_deduction = round($deductions['loan_deduction']);
                        $updatePay->leave_count = $deductions['leave_count'];
                        $updatePay->net_salary = ($user->base_salary + $allowances) - (round($deductions['late_deduction']) + round($deductions['leave_deduction']) + round($deductions['loan_deduction']));
                        $updatePay->save();
                    }
                }

                DB::commit();
                return $this->redirectBack(SUCCESS, __('default_label.updated'), $this->defaultRedirectPath . "listing", "url");
            }

            DB::rollback();
            return $this->redirectBack(ERROR, __('default_label.fetch'));
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

    /*
    |
    |   Getting Lock PayRolls
    |
    */
    public function lockPayRoll($id)
    {
        try {
            DB::beginTransaction();
            $model = $this->model->newQuery()->whereId($id)->first();

            if ($model) {
                $model->lock = 1;
                if ($model->save()) {
                    DB::commit();
                    return $this->redirectBack("success", __('default_label.updated'), $this->defaultRedirectPath . "listing");
                }
            }

            DB::rollback();
            return $this->redirectBack("", __('default_label.update'));
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

    /*
    |
    |   Monthly view attendance PDF
    |
    */
    public function attendancePDF(Request $request)
    {
        try {
            DB::beginTransaction();
            $payroll = $this->model->newQuery()->where('id', $request->id)->first();
            if ($payroll) {
                $users = $this->user->newQuery()->where('role_id', '>', SUPER_ADMIN)->orderBy('first_name', 'ASC')->get();
                $name = date('M-Y', strtotime(str_replace(',', '', $payroll->date))) . '.pdf';
                $pdf = MPDF::loadView('partials.admin.exports.attendance-pdf', ['data' => $users, 'payroll' => $payroll], [], ['title' => 'Employee Attendance Sheet']);
                return $pdf->download($name);
            }
            DB::rollback();
            return $this->redirectBack("", __('default_label.fetch'));
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

    /*
    |
    |   GENERATE ALL PDF
    |
    */
    public function generatePDF(Request $request)
    {
        try {
            DB::beginTransaction();
            $payroll = $this->model->newQuery()->where('id', $request->id)->first();
            $files    = glob(public_path() . '/uploads/gen-*');
            foreach ($files as $file) {
                if (is_file($file)) {
                    unlink($file);
                }
            }

            if ($payroll) {
                $date = strtoupper(date('F Y', strtotime($payroll->date)));
                $query = $this->userPayRoll->newQuery()->whereHas('user')->with(['user' => function ($q) {
                    $q->where('role_id', '>', SUPER_ADMIN)
                        ->orderBy('first_name', 'ASC');
                }, 'extras'])->where('pay_roll_id', $request->id);

                // $netSalary = $query->sum('net_salary');
                $grossSalary = $query->sum('gross_salary');
                $totalEmps = $query->count();
                $userPayRolls = $query->get();
                $netSalary = collect($userPayRolls)->sum('net_salary');
                $name = time() . '.pdf';
                // COVER PAGE
                $this->exportCoverSheetPDF($grossSalary, $totalEmps, $netSalary, public_path() . '/uploads/gen-cover-' . $name, $date);
                // PAYROLLS
                $this->exportPayRollsPdf($userPayRolls, public_path() . '/uploads/gen-pr-' . $name);
                // BANK LETTER
                $this->exportBankLetter($userPayRolls, public_path() . '/uploads/gen-bank-' . $name, $date);
                // CHEQUE DETAILS
                $this->exportChequeDetails($userPayRolls, public_path() . '/uploads/gen-cheque-' . $name, $date);
                // ATTENDANCE
                $this->exportAttendancesPdf($payroll, public_path() . '/uploads/gen-att-' . $name);

                $pdf = new \Clegginabox\PDFMerger\PDFMerger;

                $pdf->addPDF(public_path() . '/uploads/gen-cover-' . $name, 'all');
                $pdf->addPDF(public_path() . '/uploads/gen-pr-' . $name, 'all');
                $pdf->addPDF(public_path() . '/uploads/gen-bank-' . $name, 'all');
                $pdf->addPDF(public_path() . '/uploads/gen-cheque-' . $name, 'all');
                $pdf->addPDF(public_path() . '/uploads/gen-att-' . $name, 'all');
                $pdf->merge('file', public_path() . '/uploads/gen-payroll-' . $name);

                unlink(public_path('/uploads/gen-cover-' . $name));
                unlink(public_path('/uploads/gen-bank-' . $name));
                unlink(public_path('/uploads/gen-cheque-' . $name));
                unlink(public_path('/uploads/gen-pr-' . $name));
                unlink(public_path('/uploads/gen-att-' . $name));

                DB::commit();

                return response()->download(public_path() . '/uploads/gen-payroll-' . $name);
            }
            return $this->redirectBack(ERROR, "Something went wrong");
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

    /*
    |
    |   Cover Sheet
    |
    */
    private function exportCoverSheetPDF($totalMonthSalary, $totalEmps, $netSalary, $name, $date)
    {
        $pdf = MPDF::loadView('partials.admin.exports.cover-page-pdf', [
            'totalMonthSalary' => $totalMonthSalary,
            'totalEmps' => $totalEmps,
            'netSalary' => $netSalary,
            'date'      => $date
        ], [], ['orientation' => 'L']);

        // return $pdf->download($name);
        return $pdf->save($name);
    }

    /*
    |
    |   Bank Letter
    |
    */
    private function exportBankLetter($payRolls, $name, $date)
    {
        $companyProfile = CompanyProfile::first();

        $pdf = MPDF::loadView('partials.admin.exports.bank-letter-pdf', [
            'companyProfile' => $companyProfile,
            'payRolls' => $payRolls,
            'date'     => $date
        ], [], ['orientation' => 'P']);

        // return $pdf->download($name);
        return $pdf->save($name);
    }

    /*
    |
    |   Bank Letter
    |
    */
    private function exportChequeDetails($payRolls, $name, $date)
    {
        $companyProfile = CompanyProfile::first();

        $userWithoutAcc = [];
        $totalChequeAmount = 0;
        foreach ($payRolls as $key => $payRoll) {
            if (!empty($payRoll->user->account_no)) {
                $totalChequeAmount += $payRoll->net_salary;
            } else {
                $userWithoutAcc[$key] = $payRoll;
            }
        }
        $data["totalcheck"] = $totalChequeAmount;
        $data["users"] = $userWithoutAcc;

        $pdf = MPDF::loadView('partials.admin.exports.cheques-pdf', [
            'companyProfile' => $companyProfile,
            'userWithouAccount' => $userWithoutAcc,
            'totalChequeAmount' => $totalChequeAmount,
            'date'              => $date
        ], [], ['orientation' => 'L']);

        // return $pdf->download($name);
        return $pdf->save($name);
    }

    /*
    |
    |   PayRollPDFS
    |
    */
    private function exportPayRollsPdf($payrolls, $name)
    {
        $extras = $this->userPayRollExtra->newQuery()->whereHas('userPayRoll', function ($q) {
            $q->where('pay_roll_id', request('id'));
        })->get()->unique('name');
        if (count($payrolls) > 0) {
            $pdf = MPDF::loadView('payrollpdf', ['data' => $payrolls, 'extras' => $extras], [], ['orientation' => 'L']);
            // return $pdf->download($name);
            return $pdf->save($name);
        }
    }

    /*
    |
    |   AttendancePDFS
    |
    */
    private function exportAttendancesPdf($payroll, $name)
    {
        $user_ids = $this->userPayRoll->newQuery()->where('pay_roll_id', $payroll->id)->pluck('user_id')->toArray();
        $users = $this->user->newQuery()->whereIn('id', $user_ids)->where('role_id', '>', SUPER_ADMIN)->orderBy('first_name', 'ASC')->get();
        $pdf = MPDF::loadView('partials.admin.exports.attendance-pdf', ['data' => $users, 'payroll' => $payroll], [], ['title' => 'Employee Attendance Sheet', 'orientation' => 'L']);
        return $pdf->save($name);
    }

    public function payRollTaxSummary(Request $request)
    {

        // dd($request->all());
        $fromDate = '';
        try {
            $inputs = $request->all();
            $user_id = UserPayRoll::distinct()->get('user_id')->pluck('user_id')->toArray();
            $users = User::with('payrolls')->whereIn('id', $user_id)->get();
            $date = Carbon::now()->startOfMonth();
            $monthPeriod = $date->subMonths(12)->monthsUntil(now());

            if (isset($inputs['user_id'])) {
                $users = User::with('payrolls')->whereIn('id', $inputs['user_id'])->get();
            }
            if (isset($inputs['from_date'])) {
                $fromDate = Carbon::createFromFormat('F, Y', $inputs['from_date'])->startOfMonth();
            }
            if (isset($inputs['to_date'])) {
                $toDate = Carbon::createFromFormat('F, Y', $inputs['to_date'])->endOfMonth();
                $toDate = $fromDate->copy()->addMonth($toDate->diffInMonths($fromDate));
                $monthPeriod = CarbonPeriod::create($fromDate, '1 month', $toDate);
            }
            $date_list = '';
            $inputs = $request->all();
            if (!empty($inputs['search'])) {
                $users->where(function ($q) use ($inputs) {
                    $this->search($q, $inputs['search'], getSearchColoumns('attendances'));
                });
            }
            $totalTax = 0;
            foreach ($users as $user) {
                foreach ($monthPeriod as $period) {
                    $totalTax = $totalTax +  getUserTaxPaid($period->format('F, Y'), $user->id);
                    // $totalTax = $totalTax + $user->payrolls()
                    // ->where('data', '>=', $inputs['from_date'])
                    // ->where('data', '<=', $inputs['to_date'])
                    // ->sum('govrt_tax');
                }
            }
            $data = array('date_list' => $date_list, 'users' => $users, 'months' => $monthPeriod, 'total_tax' => $totalTax);
            return $this->successListView("", $this->defaultRedirectPath . "tax-summary", __('payroll.summary_page_heading'), $data, false, false);
        } catch (QueryException $e) {
            dd($e);
            Session::flash($e->getMessage());
            return Redirect()->back();
        } catch (Exception $e) {
            dd($e);
            Session::flash($e->getMessage());
            return Redirect()->back();
        }
    }
}
