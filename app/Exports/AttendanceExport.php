<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromCollection;
use App\Http\Controllers\Controller;
use App\User;
use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;

class AttendanceExport implements FromView
{
    private $construct, $user;

    public function __construct()
    {
        $this->construct = new Controller();
        $this->user = new User();
    }

    private function getAttendances()
    {
        $user = $this->user->newQuery()->where('role_id', '>', ADMIN)->where('status', '!=', USER_STATUS_TERMINATE)->orderBy('first_name', 'ASC');
        if (!empty(request('search'))) {
            $user->where(function ($q) {
                $this->construct->search($q, request('search'), getSearchColoumns('attendances'));
            });
        }
        return $user->get();
    }
    public function view(): View
    {
        return view('partials.admin.exports.attendance', [
            'data' => $this->getAttendances()
        ]);
    }
}
