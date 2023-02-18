<div class="table-responsive">
    <table class="table table-striped">
        <thead class=" text-white" style="background-color: #51bcda;">
            <th style="width: 20px">Name</th>
            <th style="width: 20px">Designation</th>
            <th style="width: 20px">Address</th>
            <th style="width: 20px">CNIC No</th>
            <th style="width: 20px">Month</th>
            <th style="width: 20px">Date of Joining</th>
            <th style="width: 20px">Gross Working Days</th>
            <th style="width: 20px">Net Working Days</th>
            <th style="width: 20px">Gross Salary</th>
            <th style="width: 20px">Base Salary</th>
            <th style="width: 20px">House Rent</th>
            <th style="width: 20px">Utility</th>
            <th style="width: 20px">Allowances</th>
            @foreach ($extras as $extra)
            <th style="width: 20px">{{ $extra->name }}</th>
            @endforeach
            <th style="width: 20px">Late Deductions</th>
            <th style="width: 20px">Leave Deductions</th>
            <th style="width: 20px">Loan Deductions</th>
            <th style="width: 20px">Govt. Tax</th>
            <th style="width: 20px">Total Earnings</th>
            <th style="width: 20px">Total Deductions</th>
            <th style="width: 20px">Net Pay</th>
        </thead>
        <tbody>
            <tr></tr>
            @foreach($data AS $index => $payroll)
            <tr style="cursor: pointer">
                @php
                $user = $payroll->user;
                $netDays = cal_days_in_month(CAL_GREGORIAN,date('m',strtotime($payroll->payroll->date)),date('Y',strtotime($payroll->payroll->date)));
                $totalEarnings = $payroll->base_salary+$payroll->house_rent+$payroll->utility+$payroll->allowances;
                $totalDeductions =$payroll->late_deduction+$payroll->leave_deduction+$payroll->govrt_tax;
                @endphp
                <td>{{ $user->first_name }} {{ $user->last_name }}</td>
                <td>{{ $user->designation }}</td>
                <td>{{ $user->address ? $user->address : '--' }}</td>
                <td>{{ $user->cnic}}</td>
                <td>{{ $payroll->payroll->date }}</td>
                <td>{{ date('d/M/Y',strtotime($user->doj)) }}</td>
                <td>{{ $payroll->paid_working_days }}</td>
                <td>{{ $netDays }}</td>
                <td>{{ convertValueToCommaSeparated($payroll->gross_salary) }}</td>
                <td>{{ convertValueToCommaSeparated($payroll->base_salary) }}</td>
                <td>{{ convertValueToCommaSeparated($payroll->house_rent) }}</td>
                <td>{{ convertValueToCommaSeparated($payroll->utility) }}</td>
                <td>{{ convertValueToCommaSeparated($payroll->allowances) }}</td>
                @foreach ($extras as $payExtra)
                <td>
                    @php
                    $userExtra = \DB::table('user_pay_roll_extras')->where('name',$payExtra->name)->where('user_id',$payroll->user_id)->where('user_pay_roll_id',$payroll->id)->first();
                    if($userExtra){
                    if($userExtra->type == 'Contribution'){
                    $totalEarnings += $userExtra->amount;
                    }else{
                    $totalDeductions += $userExtra->amount;
                    }
                    }
                    @endphp
                    @if($userExtra)
                    {{ convertValueToCommaSeparated($userExtra->amount) }}
                    @else
                    &nbsp;
                    @endif
                </td>
                @endforeach
                <td>{{ convertValueToCommaSeparated($payroll->late_deduction) }}</td>
                <td>{{ convertValueToCommaSeparated($payroll->leave_deduction) }}</td>
                <td>{{ convertValueToCommaSeparated($payroll->loan_deduction) }}</td>
                <td>{{ convertValueToCommaSeparated($payroll->govrt_tax) }}</td>
                <td>{{ convertValueToCommaSeparated($totalEarnings) }}</td>
                <td>{{ convertValueToCommaSeparated($totalDeductions) }}</td>
                <td>{{ convertValueToCommaSeparated($payroll->net_salary) }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>
</div>
