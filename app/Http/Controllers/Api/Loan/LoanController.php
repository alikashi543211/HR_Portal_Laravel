<?php

namespace App\Http\Controllers\Api\Loan;

use App\Drivers\FirebaseDriver;
use App\Http\Controllers\Controller;
use App\Http\Requests\Api\Loan\LoanDeleteRequest;
use App\Http\Requests\Api\Loan\LoanRequest;
use App\Loan;
use App\LoanInstallment;
use Exception;
use Illuminate\Database\QueryException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class LoanController extends Controller
{
    public function listing()
    {
        $query = Loan::query()->with('installments')->where('user_id', Auth::id());
        $toReturn['loans'] = $query->orderBy('created_at', 'desc')->paginate(DATA_PER_PAGE);
        return $this->apiSuccessWithData('success', $toReturn);
    }

    public function store(LoanRequest $request)
    {
        try {
            DB::beginTransaction();
            $inputs = $request->all();
            $loan = new Loan();
            $loan->amount = $inputs['totalAmount'];
            $loan->status = LOAN_PENDING;
            $loan->user_id = Auth::id();
            $loan->added_by = Auth::id();
            if ($loan->save()) {
                foreach ($inputs['installments'] as $key => $ins) {
                    $installment = new LoanInstallment();
                    $installment->amount = $ins["amount"];
                    $installment->month = $ins["month"];
                    $installment->loan_id = $loan->id;
                    if (!$installment->save()) {
                        DB::rollBack();
                        return $this->apiError('Something went wrong', ERROR_400);
                    }
                }
            }
            $notify = new FirebaseDriver();
            $notify->setMessageBody("Loan Request", "You Have New Loan Request", NOTIFICATION_LOAN, $loan->id);
            $notify->sendNotificationToUser("Admins", $loan->user->id);
            DB::commit();
            return $this->apiSuccess("Loan request successfully added");
        } catch (QueryException $e) {
            DB::rollBack();
            return $this->apiError('Something went wrong', ERROR_400);
        } catch (Exception $e) {
            DB::rollBack();
            return $this->apiError('Something went wrong', ERROR_400);
        }
    }

    public function update(LoanRequest $request)
    {
        try {
            DB::beginTransaction();
            $inputs = $request->all();
            $loan = Loan::where('id', $inputs['loan_id'])->first();
            if ($loan->status == PENDING) {
                $loan->amount = $inputs['totalAmount'];
            } else {
                return $this->apiError('You are not allowed to edit this loan', ERROR_400);
            }
            if ($loan->save()) {
                LoanInstallment::where('status', PENDING)->where('loan_id', $loan->id)->delete();
                foreach ($inputs['installments'] as $key => $ins) {
                    $installment = new LoanInstallment();
                    $installment->amount =  $ins["amount"];
                    $installment->month =  $ins["month"];
                    $installment->loan_id = $loan->id;
                    if (!$installment->save()) {
                        DB::rollBack();
                        return $this->apiError('Something went wrong', ERROR_400);
                    }
                }
            }
            DB::commit();
            return $this->apiSuccess("Loan request successfully updated");
        } catch (QueryException $e) {
            DB::rollBack();
            return $this->apiError('Something went wrong', ERROR_400);
        } catch (Exception $e) {
            DB::rollBack();
            return $this->apiError('Something went wrong', ERROR_400);
        }
    }

    public function delete(LoanDeleteRequest $request)
    {
        try {
            DB::beginTransaction();
            $loan = Loan::find($request->id);
            if ($loan->status == 0) {
                $loan->installments()->delete();
                $loan->delete();
            } else {
                return $this->apiSuccess("You are not allowed to delete this loan");
            }
            db::commit();
            return $this->apiSuccess("Loan request successfully deleted");
        } catch (QueryException $e) {
            DB::rollBack();
            return $this->apiError('Something went wrong', ERROR_400);
        } catch (Exception $e) {
            DB::rollBack();
            return $this->apiError('Something went wrong', ERROR_400);
        }
    }


    public function detail($id)
    {
        if ($loan = Loan::with('installments')->where('id', $id)->where('user_id', Auth::id())->first()) {
            $toReturn['loan'] = $loan;
            return $this->apiSuccessWithData('', $toReturn);
        } else {
            return $this->apiError("Data not exists", ERROR_400);
        }
    }
}
