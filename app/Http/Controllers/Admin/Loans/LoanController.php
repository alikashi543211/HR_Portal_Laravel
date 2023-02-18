<?php

namespace App\Http\Controllers\Admin\Loans;

use App\Drivers\FirebaseDriver;
use App\Http\Controllers\Controller;
use App\Loan;
use App\LoanInstallment;
use App\User;
use Exception;
use Illuminate\Database\QueryException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class LoanController extends Controller
{
    private $loan, $installment, $user;

    public function __construct()
    {
        $this->loan = new Loan();
        $this->installment = new LoanInstallment();
        $this->user = new User();
        $this->defaultRedirectPath = URL_ADMIN . "loans/";
        $this->defaultViewPath = "admin.loans.";
    }

    public function listing(Request $request)
    {
        $inputs = $request->all();
        $query = $this->loan->newQuery();
        if (!empty($inputs['search'])) {
            $query->where(function ($q) use ($inputs) {
                $this->search($q, $inputs['search'], ['first_name', 'last_name', 'email', 'personal_email', 'phone_number', 'cnic', 'doj', 'dob', 'finger_print_id', 'employee_id', 'designation', 'nationality', 'emergency_contact_name', 'emergency_contact_relation', 'base_salary', 'emergency_contact_number', 'dop'], 'user');
            });
        }
        $loans = $query->orderBy('id', 'DESC')->paginate(DATA_PER_PAGE);
        return $this->successListView("", $this->defaultViewPath . "listing", __('loans.page_heading'), $loans, true, true);
    }

    public function add()
    {
        $users = $this->user->newQuery()->where('role_id', '!=', SUPER_ADMIN)->get();
        return $this->successView(NULL, $this->defaultViewPath . 'add', 'New Loan', $users);
    }

    public function store(Request $request)
    {
        try {
            DB::beginTransaction();
            $inputs = $request->all();
            $loan = $this->loan->newInstance();
            $loan->amount = $inputs['amount'];
            $loan->status = LOAN_APPROVED;
            $loan->user_id = $inputs['user_id'];
            $loan->added_by = Auth::id();
            if ($loan->save()) {
                foreach ($inputs['installment'] as $key => $ins) {
                    $installment = $this->installment->newInstance();
                    $installment->amount = $inputs['installment'][$key];
                    $installment->month = date('Y-m-d', strtotime($inputs['month'][$key]));
                    $installment->loan_id = $loan->id;
                    if (!$installment->save()) {
                        DB::rollBack();
                        return $this->redirectBack(ERROR, __('default_label.save'), $this->defaultRedirectPath . "add");
                    }
                }
            }
            DB::commit();
            return $this->redirectBack(SUCCESS, __('default_label.saved'), $this->defaultRedirectPath . "listing");
        } catch (QueryException $e) {
            DB::rollBack();
            return $this->redirectBack(ERROR, __('default_label.save'), $this->defaultRedirectPath . "add");
        } catch (Exception $e) {
            DB::rollBack();
            return $this->redirectBack(ERROR, __('default_label.save'), $this->defaultRedirectPath . "add");
        }
    }

    public function edit($id)
    {
        $loan = $this->loan->newQuery()->where('id', $id)->with('installments')->first();
        $users = $this->user->newQuery()->where('role_id', '!=', SUPER_ADMIN)->get();
        return $this->successView(NULL, $this->defaultViewPath . 'edit', 'Edit Loan', ['loan' => $loan, 'users' => $users]);
    }

    public function update(Request $request)
    {
        try {
            DB::beginTransaction();
            $inputs = $request->all();
            $loan = $this->loan->newQuery()->where('id', $inputs['id'])->first();
            if ($loan->status == PENDING) {
                $loan->amount = $inputs['amount'];
            }
            if ($loan->save()) {
                $this->installment->newQuery()->where('status', PENDING)->where('loan_id', $loan->id)->delete();
                foreach ($inputs['installment'] as $key => $ins) {
                    $installment = $this->installment->newInstance();
                    $installment->amount = $inputs['installment'][$key];
                    $installment->month = date('Y-m-d', strtotime($inputs['month'][$key]));
                    $installment->loan_id = $loan->id;
                    if (!$installment->save()) {
                        DB::rollBack();
                        return $this->redirectBack(ERROR, __('default_label.save'), $this->defaultRedirectPath . "add");
                    }
                }
            }
            DB::commit();
            return $this->redirectBack(SUCCESS, __('default_label.saved'), $this->defaultRedirectPath . "listing");
        } catch (QueryException $e) {
            DB::rollBack();
            return $this->redirectBack(ERROR, __('default_label.save'), $this->defaultRedirectPath . "add");
        } catch (Exception $e) {
            DB::rollBack();
            return $this->redirectBack(ERROR, __('default_label.save'), $this->defaultRedirectPath . "add");
        }
    }

    public function delete($id)
    {
        try {
            DB::beginTransaction();
            $loan = Loan::where('id', $id)->first();
            $loan->delete();
            DB::commit();
            return $this->redirectBack(SUCCESS, __('loans.delete'), $this->defaultRedirectPath . "listing");
        } catch (QueryException $e) {
            DB::rollBack();
            return $this->redirectBack(ERROR, __('default_label.delete'), $this->defaultRedirectPath . "listing");
        } catch (Exception $e) {
            DB::rollBack();
            return $this->redirectBack(ERROR, __('default_label.delete'), $this->defaultRedirectPath . "listing");
        }
    }
    public function statusUpdate(Request $request)
    {
        try {
            DB::beginTransaction();
            $loan = Loan::where('id', $request->id)->first();
            $loan->status = $request->status;
            $loan->save();

            if ($request->status == 1) {
                $notify = new FirebaseDriver();
                $notify->setMessageBody("Loan Request Approval", "Your loan request has been approved", NOTIFICATION_LOAN, $loan->id);
                $notify->sendNotificationToUser("Employees", $loan->user->id);
            } else if ($request->status == 2) {
                $notify = new FirebaseDriver();
                $notify->setMessageBody("Loan Request Rejection", "Your loan request has been rejected", NOTIFICATION_LOAN, $loan->id);
                $notify->sendNotificationToUser("Employees", $loan->user->id);
            }
            DB::commit();
            return $this->redirectBack(SUCCESS, __('loans.update'), $this->defaultRedirectPath . "listing");
        } catch (QueryException $e) {
            DB::rollBack();
            return $this->redirectBack(ERROR, __('default_label.update'), $this->defaultRedirectPath . "listing");
        } catch (Exception $e) {
            DB::rollBack();
            return $this->redirectBack(ERROR, __('default_label.update'), $this->defaultRedirectPath . "listing");
        }
    }
}
