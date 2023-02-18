<?php

namespace App\Exports;

use App\Http\Controllers\Controller;
use App\User;
use App\UserPayRoll;
use App\UserPayRollExtra;
use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;
use Maatwebsite\Excel\Concerns\WithTitle;

class PayRollExport implements FromView, WithTitle
{
    private $construct, $user, $userPayRoll, $userPayRollExtras;

    public function __construct()
    {
        $this->construct = new Controller();
        $this->user = new User();
        $this->userPayRoll = new UserPayRoll();
        $this->userPayRollExtras = new UserPayRollExtra();
    }

    private function getPayRolls()
    {
        return $this->userPayRoll->newQuery()->where('pay_roll_id', request('id'))->with('user', 'extras')->get();
    }

    private function getExtras()
    {
        return $this->userPayRollExtras->newQuery()->whereHas('userPayRoll', function ($q) {
            $q->where('pay_roll_id', request('id'));
        })->get()->unique('name');
    }

    public function view(): View
    {
        return view('partials.admin.exports.salary-slip-excel', [
            'data' => $this->getPayRolls(),
            'extras' => $this->getExtras()
        ]);
    }

    /**
     * @return string
     */
    public function title(): string
    {
        return 'Payroll';
    }
}
