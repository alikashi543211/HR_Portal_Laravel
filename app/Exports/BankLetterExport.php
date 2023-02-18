<?php

namespace App\Exports;

use App\CompanyProfile;
use App\Http\Controllers\Controller;
use App\PayRoll;
use App\User;
use App\UserPayRoll;
use App\UserPayRollExtra;
use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;
use Maatwebsite\Excel\Concerns\WithTitle;

class BankLetterExport implements FromView, WithTitle
{

    private $construct, $user, $userPayRoll, $userPayRollExtras;

    public function __construct()
    {
        $this->construct = new Controller();
        $this->user = new User();
        $this->userPayRoll = new UserPayRoll();
        $this->payroll = new PayRoll();
        $this->userPayRollExtras = new UserPayRollExtra();
    }

    private function getPayRolls()
    {
        $payroll = $this->payroll->newQuery()->where('id', request('id'))->first();
        $date = strtoupper(date('F Y', strtotime($payroll->date)));
        $query = $this->userPayRoll->newQuery()->whereHas('user')->with(['user' => function ($q) {
            $q->where('role_id', '>', SUPER_ADMIN)
                ->orderBy('first_name', 'ASC');
        }, 'extras'])->where('pay_roll_id', request('id'));
        return ['payrolls' => $query->get(), 'date' => $date, 'payroll' => $payroll];
    }

    public function view(): View
    {
        $companyProfile = CompanyProfile::first();
        $data = $this->getPayRolls();
        $payrolls = $data['payrolls'];
        $payroll = $data['payroll'];
        $date = $data['date'];
        return view('partials.admin.exports.bank-letter-excel', [
            'companyProfile' => $companyProfile,
            'payRolls' => $payrolls,
            'payroll' => $payroll,
            'date'     => $date
        ]);
    }

    /**
     * @return string
     */
    public function title(): string
    {
        return 'Bank Letter';
    }
}
