<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <style>
        table,
        th,
        td {
            border: 1px solid black;
        }

        table {
            border-collapse: collapse;
        }

    </style>
    <style type="text/css" media="print">
        @page {
            size: landscape;
        }

    </style>
</head>
@php
$extraArray = [];
$totalGrossSalary = 0;
$totalAllowances = 0;
$totalLateDeduction = 0;
$totalLeaveDeduction = 0;
$totalLoanDeduction = 0;
$totalGovrtTax = 0;
$totalNetSalary = 0;
$totalMainContribution = 0;
$totalMainDeduction = 0;
@endphp

<body onload="window.print()">
    <div>
        <table width="100%">
            <thead>
                <tr>
                    <th>Sr.</th>
                    <th>Name</th>
                    <th>Designation</th>
                    <th>Date of Joining</th>
                    <th>Net Working Days</th>
                    <th>Gross Working Days</th>
                    <th>Gross Salary</th>
                    <th>Allowances</th>
                    @foreach ($extras as $extra)
                    @if($extra->type == 'Contribution')
                    @php
                    if(!isset($extraArray[$extra->id])){
                    $extraArray[$extra->id] = 0;
                    }
                    @endphp
                    <th>{{ $extra->name }}</th>
                    @endif
                    @endforeach
                    <th>Total Contribution</th>
                    @foreach ($extras as $extra)
                    @if($extra->type != 'Contribution')
                    @php
                    if(!isset($extraArray[$extra->id])){
                    $extraArray[$extra->id] = 0;
                    }
                    @endphp
                    <th>{{ $extra->name }}</th>
                    @endif
                    @endforeach
                    <th>Late Deductions</th>
                    <th>Leave Deductions</th>
                    <th>Loan Deductions</th>
                    <th>Govt. Tax</th>
                    <th>Total Deductions</th>
                    <th>Net Pay</th>
                </tr>
            </thead>
            <tbody>
                <tr></tr>
                @foreach($data AS $index => $payroll)
                <tr style="cursor: pointer">
                    <td>{{ $index+1 }}</td>
                    @php
                    $user = $payroll->user;
                    $netDays = cal_days_in_month(CAL_GREGORIAN,date('m',strtotime($payroll->payroll->date)),date('Y',strtotime($payroll->payroll->date)));
                    $totalEarnings = $payroll->base_salary+$payroll->house_rent+$payroll->utility+$payroll->allowances;
                    $totalDeductions = $payroll->late_deduction+$payroll->leave_deduction+$payroll->govrt_tax;
                    $totalGrossSalary += $payroll->gross_salary;
                    $totalAllowances += $payroll->allowances;
                    $totalLateDeduction += $payroll->late_deduction;
                    $totalLeaveDeduction += $payroll->leave_deduction;
                    $totalLoanDeduction += $payroll->loan_deduction;
                    $totalGovrtTax += $payroll->govrt_tax;
                    $totalNetSalary += $payroll->net_salary;
                    $totalContribution = 0;
                    @endphp
                    <td>{{ $user->first_name }} {{ $user->last_name }}</td>
                    <td>{{ $user->designation }}</td>
                    <td>{{ date('d-m-Y',strtotime($user->doj)) }}</td>
                    <td>{{ $payroll->paid_working_days }}</td>
                    <td>{{ $netDays }}</td>
                    <td>{{ $payroll->gross_salary }}</td>
                    <td>{{ $payroll->allowances }}</td>
                    @foreach ($extras as $payExtra)
                    @if($payExtra->type == 'Contribution')
                    @php
                    $userExtra = \DB::table('user_pay_roll_extras')->where('name',$payExtra->name)->whereType('Contribution')->where('user_id',$payroll->user_id)->where('user_pay_roll_id',$payroll->id)->first();
                    if($userExtra){
                    $extraArray[$payExtra->id] += $userExtra->amount;
                    $totalContribution += $userExtra->amount;
                    }
                    @endphp
                    @if($userExtra)
                    <td>{{ $userExtra->amount }}</td>
                    @else
                    <td>0</td>
                    @endif
                    @endif
                    @endforeach
                    <td>{{$totalContribution}}</td>
                    @foreach ($extras as $payExtra)
                    @if($payExtra->type != 'Contribution')
                    @php
                    $userExtra = \DB::table('user_pay_roll_extras')->where('name',$payExtra->name)->whereType('Deduction')->where('user_id',$payroll->user_id)->where('user_pay_roll_id',$payroll->id)->first();
                    if($userExtra){
                    $extraArray[$payExtra->id] += $userExtra->amount;
                    $totalDeductions += $userExtra->amount;
                    }
                    @endphp
                    @if($userExtra)
                    <td>{{ $userExtra->amount }}</td>
                    @else
                    <td>0</td>
                    @endif
                    @endif
                    @endforeach
                    <td>{{ $payroll->late_deduction }}</td>
                    <td>{{ $payroll->leave_deduction }}</td>
                    <td>{{ $payroll->loan_deduction }}</td>
                    <td>{{ $payroll->govrt_tax }}</td>
                    <td>{{ $totalDeductions }}</td>
                    <td>{{ $payroll->net_salary }}</td>
                </tr>
                @php
                $totalMainContribution += $totalContribution;
                $totalMainDeduction += $totalDeductions;
                @endphp
                @endforeach
                <tr>
                    <td></td>
                    <td><b>Total</b></td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td><b>{{$totalGrossSalary}}</b></td>
                    <td><b>{{$totalAllowances}}</b></td>

                    @foreach ($extras as $extra)
                    @if($extra->type == 'Contribution')
                    <td><b>{{$extraArray[$extra->id]}}</b></td>
                    @endif
                    @endforeach
                    <th style="text-align: left"><b>{{$totalMainContribution}}</b></th>
                    @foreach ($extras as $extra)
                    @if($extra->type != 'Contribution')
                    <td><b>{{$extraArray[$extra->id]}}</b></td>
                    @endif
                    @endforeach

                    <td><b>{{$totalLateDeduction}}</b></td>
                    <td><b>{{$totalLeaveDeduction}}</b></td>
                    <td><b>{{$totalLoanDeduction}}</b></td>
                    <td><b>{{$totalGovrtTax}}</b></td>
                    <td><b>{{$totalMainDeduction}}</b></td>
                    <td><b>{{$totalNetSalary}}</b></td>
                </tr>
            </tbody>
        </table>
    </div>
</body>

</html>
