<?php

namespace App\Http\Controllers\Employee\Loan;

use App\Drivers\FirebaseDriver;
use App\Http\Controllers\Controller;
use App\Loan;
use App\LoanInstallment;
use Carbon\Carbon;
use Exception;
use Illuminate\Database\QueryException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class LoanController extends Controller
{
    public function listing(Request $request)
    {
        $query = Loan::query()->with('user', 'installments')->where('user_id', Auth::id());
        $loans = $query->orderBy('created_at', 'desc')->paginate(DATA_PER_PAGE);
        return view('employees.loans.index', compact('loans'));
    }

    public function create(Request $request)
    {
        return view('employees.loans.create');
    }

    public function store(Request $request)
    {
        try {
            DB::beginTransaction();
            $inputs = $request->all();
            $loan = new Loan();
            $loan->amount = $inputs['amount'];
            $loan->status = LOAN_PENDING;
            $loan->user_id = Auth::id();
            $loan->added_by = Auth::id();
            if ($loan->save()) {
                foreach ($inputs['installment'] as $key => $ins) {
                    $dateArray = explode(',', $inputs['month'][$key]);
                    $installment = new LoanInstallment();
                    $installment->amount = $inputs['installment'][$key];
                    $installment->month = Carbon::parse($dateArray[1] . '-' . $dateArray[0])->startOfMonth()->format('Y-m-d');
                    $installment->loan_id = $loan->id;
                    if (!$installment->save()) {
                        DB::rollBack();
                        return redirect()->back()->with('error', 'Something went wrong');
                    }
                }
            }
            $notify = new FirebaseDriver();
            $notify->setMessageBody("Loan Request", "You Have New Loan Request", NOTIFICATION_LOAN, $loan->id);
            $notify->sendNotificationToUser("Admins", $loan->user->id);
            DB::commit();
            return redirect()->route('employee.loans.listing')->with('success', "Loan request successfully added");
        } catch (QueryException $e) {
            DB::rollBack();
            return redirect()->back()->with('error', 'Something went wrong');
        } catch (Exception $e) {
            DB::rollBack();
            return redirect()->back()->with('error', 'Something went wrong');
        }
    }
    public function detail($id)
    {
        $loan = Loan::where('id', $id)->with(['installments' => function ($query) {
            $query->orderBy('month', "ASC");
        }])->first();
        return view('employees.loans.detail', compact('loan'));
    }

    public function edit($id)
    {
        $loan = Loan::where('id', $id)->with(['installments' => function ($query) {
            $query->orderBy('month', "ASC");
        }])->first();
        return view('employees.loans.edit', compact('loan'));
    }

    public function update(Request $request)
    {
        try {
            DB::beginTransaction();
            $inputs = $request->all();
            $loan = Loan::where('id', $inputs['id'])->first();
            if ($loan->status == PENDING) {
                $loan->amount = $inputs['amount'];
            }
            if ($loan->save()) {
                LoanInstallment::where('status', PENDING)->where('loan_id', $loan->id)->delete();
                foreach ($inputs['installment'] as $key => $ins) {
                    $dateArray = explode(',', $inputs['month'][$key]);
                    $installment = new LoanInstallment();
                    $installment->amount = $inputs['installment'][$key];
                    $installment->month = Carbon::parse($dateArray[1] . '-' . $dateArray[0])->startOfMonth()->format('Y-m-d');
                    $installment->loan_id = $loan->id;
                    if (!$installment->save()) {
                        DB::rollBack();
                        return redirect()->back()->with('error', 'Something went wrong');
                    }
                }
            }
            DB::commit();
            return redirect()->route('employee.loans.listing')->with('success', "Loan request successfully updated");
        } catch (QueryException $e) {
            dd($e->getMessage());
            DB::rollBack();
            return redirect()->back()->with('error', 'Something went wrong');
        } catch (Exception $e) {
            dd($e->getMessage());
            DB::rollBack();
            return redirect()->back()->with('error', 'Something went wrong');
        }
    }

    public function delete($id)
    {
        try {
            DB::beginTransaction();
            $loan = Loan::find($id);
            $loan->delete();
            db::commit();
            return redirect()->route('employee.loans.listing')->with('success', 'Loan request successfully deleted');
        } catch (QueryException $e) {
            DB::rollBack();
            return redirect()->back()->with('error', 'Something went wrong');
        } catch (Exception $e) {
            DB::rollBack();
            return redirect()->back()->with('error', 'Something went wrong');
        }
    }
}
