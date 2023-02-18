<?php

namespace App\Http\Controllers\Api\SalaryHistory;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\SalaryHistory\SalaryHistoryRequest;
use App\Http\Requests\Api\SalaryHistory\SalarySlipDowloadRequest;
use App\UserPayRoll;
use Exception;
use Illuminate\Database\QueryException;
use Illuminate\Support\Facades\Auth;
use Barryvdh\DomPDF\Facade\Pdf;

class SalaryHistoryController extends Controller
{
    public function listing(SalaryHistoryRequest $request)
    {

        try {
            $inputs = $request->all();
            $date = $inputs['month'] . ', ' . $inputs['year'];
            $query = UserPayRoll::query()->with('payroll')->whereHas('payroll', function ($q) use ($date) {
                $q->where('date', $date);
            })->where('user_id', Auth::id());

            $salaries = $query->orderBy('id', 'DESC')->first();
            if ($salaries = $query->orderBy('id', 'DESC')->first()) {
                $toReturn['salaries'] = $salaries->toArray();
                $toReturn['salarySlip'] = $this->downloadSalarySlip($salaries['id']);
            } else {
                $toReturn['salaries'] =  null;
            }
            return $this->apiSuccessWithData('success', $toReturn);
        } catch (QueryException $e) {
            dd($e);
            return $this->apiError($e->getMessage(), ERROR_400);
        } catch (Exception $e) {
            dd($e);
            return $this->apiError($e->getMessage(), ERROR_400);
        }
    }

    public function downloadSalarySlip($payRoll_id)
    {
        try {
            $query = UserPayRoll::query()->where('id', $payRoll_id);
            $payrolls = $query->whereHas('user')->with('user')->get();
            if (count($payrolls) > 0) {
                $name = date('M-Y', strtotime($payrolls[0]->payroll->date)) . '.pdf';
                $pdf = Pdf::loadView('partials.admin.exports.salary-slip-pdf', ['payrolls' => $payrolls])->setPaper('a4', 'landscape');
                return $this->saveSalarySlip($pdf, $name);
            } else {
                return null;
            }
        } catch (QueryException $e) {
            return $this->apiError($e->getMessage(), ERROR_400);
        } catch (Exception $e) {
            return $this->apiError($e->getMessage(), ERROR_400);
        }
    }

    public function saveSalarySlip($pdf, $name)
    {
        $filePath = 'uploads/salary-slips/' . date('Y') . '/' . date('m') . '/';
        $name =  '_' . time() . '_' . rand(5000, 100000) . $name;
        $folderPath = public_path('/') . $filePath;
        if (!file_exists($folderPath)) {
            mkdir($folderPath, 0777, true);
        }
        $pdf->save($folderPath . '/' . $name);
        return $retrunData['salarySlipUrl'] = url($filePath . '/' . $name);
    }
}
