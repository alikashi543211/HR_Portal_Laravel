<?php

namespace App\Http\Controllers\Employee\SalaryHistory;

use App\Http\Controllers\Controller;
use App\PayRoll;
use App\UserPayRoll;
use Exception;
use Illuminate\Database\QueryException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Barryvdh\DomPDF\Facade\Pdf;

class SalaryHistoryController extends Controller
{
    public function listing(Request $request)
    {
        $inputs = $request->all();
        $query = UserPayRoll::query()->with(['payroll' => function ($query) {
        }])->where('user_id', Auth::id());

        if (isset($inputs['search'])) {
            $query->where(function ($q) use ($inputs) {
                $this->search($q, $inputs['search'], ['date'], 'payroll');
            });
        }
        $salaries = $query->get()->sortByDesc(function ($data) {
            return $data->payroll->date;
        });

        $salaries = Controller::paginate($salaries, DATA_PER_PAGE);
        // $salaries = $query->orderBy('id', 'DESC')->paginate(DATA_PER_PAGE);
        $html = view('employees.salary-history.row', compact('salaries'))->render();
        if ($request->ajax()) {
            return response()->json(['html' => $html]);
        } else {
            return view('employees.salary-history.index', compact('salaries'));
        }
    }


    public function downloadSalarySlip(Request $request)
    {
        try {
            $query = UserPayRoll::query()->where('id', $request->id);

            $payrolls = $query->whereHas('user')->with('user')->get();
            if (count($payrolls) > 0) {
                if ($request->segment(3) == 'details') {
                    $name = $payrolls[0]->user->first_name . '-' . $payrolls[0]->user->last_name . '-' . date('M-Y', strtotime($payrolls[0]->payroll->date)) . '.pdf';
                } else {
                    $name = date('M-Y', strtotime($payrolls[0]->payroll->date)) . '.pdf';
                }
                $pdf = Pdf::loadView('partials.admin.exports.salary-slip-pdf', ['payrolls' => $payrolls])->setPaper('a4', 'landscape');
                return $pdf->download($name);
            } else return redirect()->back()->with('error', 'Something went wrong.');
        } catch (QueryException $e) {
            return redirect()->back()->with('error', $e->getMessage());
        } catch (Exception $e) {
            return redirect()->back()->with('error', $e->getMessage());
        }
    }
}
