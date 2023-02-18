<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <style>
        .page-break {
            page-break-after: always;
        }

        .align-center {
            text-align: center;
        }

        .title {
            font-size: 20px;
            font-weight: bolder;
        }

        .address-office {
            font-size: 16px;
        }
    </style>
</head>

<body>
    @foreach ($payrolls as $payroll)
        @php
            $user = $payroll->user;
            
            $leaves = \App\UserLeave::where('user_id', $user->id)->get();
            $netDays = cal_days_in_month(CAL_GREGORIAN, date('m', strtotime($payroll->payroll->date)), date('Y', strtotime($payroll->payroll->date)));
            $totalEarnings = $payroll->base_salary + $payroll->house_rent + $payroll->utility + $payroll->allowances;
            $totalDeductions = $payroll->late_deduction + $payroll->leave_deduction + $payroll->govrt_tax;
        @endphp
        <table style="width: 100%">
            <tr>
                <td class="title align-center" colspan="2">
                    DEVSTUDIO
                </td>
            </tr>
            <tr>
                <td class="address-office align-center" colspan="2">
                    2nd Floor, 28 - CCA, Sector C, Phase V, DHA Lahore, Pakistan
                </td>
            </tr>
            <tr>
                <td height="30" colspan="2"></td>
            </tr>
            <tr class="">
                <td>
                    <table style="width: 100%">
                        <tr>
                            <td>Name : </td>
                            <td>{{ $user->first_name }} {{ $user->last_name }}</td>
                        </tr>
                        <tr>
                            <td>Designation : </td>
                            <td>{{ $user->designation }}</td>
                        </tr>
                        <tr>
                            <td>Address : </td>
                            <td>{{ $user->address ? $user->address : '--' }}</td>
                        </tr>
                        <tr>
                            <td>Gross Working Days : </td>
                            <td>{{ $netDays }}</td>
                        </tr>
                        <tr>
                            <td>Gross Salary : </td>
                            <td>{{ convertValueToCommaSeparated($payroll->gross_salary) }}</td>
                        </tr>
                    </table>
                </td>
                <td>
                    <table style="width: 100%">
                        <tr>
                            <td>Month : </td>
                            <td>{{ $payroll->payroll->date }}</td>
                        </tr>
                        <tr>
                            <td>CNIC No : </td>
                            <td>{{ $user->cnic }}</td>
                        </tr>
                        <tr>
                            <td>Date of Joining : </td>
                            <td>{{ date('d/M/Y', strtotime($user->doj)) }}</td>
                        </tr>
                        <tr>
                            <td>Net Working Days : </td>
                            <td>{{ $payroll->paid_working_days }}</td>
                        </tr>
                        <tr>
                            <td>&nbsp;</td>
                            <td>&nbsp;</td>
                        </tr>
                    </table>
                </td>
            </tr>
            <tr>
                <td colspan="2">
                    <table border="1" width="100%" cellspacing="0" cellpadding="0">
                        <tr>
                            <th style="border-left: none;border-top:none;" width="50%">Salary</th>
                            <th style="border-left: none;border-top:none;border-right: none;" width="25%">Earnings</th>
                            <th style="border-top:none;border-right: none;" width="25%">Deductions</th>
                        </tr>
                        <tr>
                            <td valign="top" style="border-left: none;border-top:none">
                                <table border="0">
                                    <tr>
                                        <td>Base Salary</td>
                                    </tr>
                                    <tr>
                                        <td>House Rent</td>
                                    </tr>
                                    <tr>
                                        <td>Utility</td>
                                    </tr>
                                    <tr>
                                        <td>Allowances</td>
                                    </tr>
                                    @foreach ($payroll->extras as $extra)
                                        <tr>
                                            <td>{{ $extra->name }}</td>
                                        </tr>
                                    @endforeach
                                    <tr>
                                        <td>Late Deductions</td>
                                    </tr>
                                    <tr>
                                        <td>Leave Deductions</td>
                                    </tr>
                                    <tr>
                                        <td>Loan Deductions</td>
                                    </tr>
                                    <tr>
                                        <td>Govt. Tax</td>
                                    </tr>
                                </table>
                            </td>
                            <td valign="top" style="padding-right: 5px;border-right:none;border-bottom:1px solid black;">
                                <table style="text-align: right;" align="right" border="0">
                                    <tr>
                                        <td>{{ convertValueToCommaSeparated($payroll->base_salary) }}</td>
                                    </tr>
                                    <tr>
                                        <td>{{ convertValueToCommaSeparated($payroll->house_rent) }}</td>
                                    </tr>
                                    <tr>
                                        <td>{{ convertValueToCommaSeparated($payroll->utility) }}</td>
                                    </tr>
                                    <tr>
                                        <td>{{ convertValueToCommaSeparated($payroll->allowances) }}</td>
                                    </tr>
                                    @foreach ($payroll->extras as $extra)
                                        <tr>
                                            <td>
                                                @if ($extra->type == 'Contribution')
                                                    @php
                                                        $totalEarnings += $extra->amount;
                                                    @endphp
                                                    {{ convertValueToCommaSeparated($extra->amount) }}
                                                @else
                                                    &nbsp;
                                                @endif
                                            </td>
                                        </tr>
                                    @endforeach
                                    <tr>
                                        <td>&nbsp;</td>
                                    </tr>
                                    <tr>
                                        <td>&nbsp;</td>
                                    </tr>
                                    <tr>
                                        <td>&nbsp;</td>
                                    </tr>
                                </table>
                            </td>
                            <td valign="top" style="padding-right: 5px;border-left: 1px solid black;border-top:none;border-bottom:1px solid black">
                                <table style="text-align: right;" align="right" border="0">
                                    <tr>
                                        <td>&nbsp;</td>
                                    </tr>
                                    <tr>
                                        <td>&nbsp;</td>
                                    </tr>
                                    <tr>
                                        <td>&nbsp;</td>
                                    </tr>
                                    <tr>
                                        <td>&nbsp;</td>
                                    </tr>
                                    @foreach ($payroll->extras as $extra)
                                        <tr>
                                            <td>
                                                @if ($extra->type == 'Deduction')
                                                    @php
                                                        $totalDeductions += $extra->amount;
                                                    @endphp
                                                    {{ convertValueToCommaSeparated($extra->amount) }}
                                                @else
                                                    &nbsp;
                                                @endif
                                            </td>
                                        </tr>
                                    @endforeach
                                    <tr>
                                        <td>{{ convertValueToCommaSeparated($payroll->late_deduction) }}</td>
                                    </tr>
                                    <tr>
                                        <td>{{ convertValueToCommaSeparated($payroll->leave_deduction) }}</td>
                                    </tr>
                                    <tr>
                                        <td>{{ convertValueToCommaSeparated($payroll->loan_deduction) }}</td>
                                    </tr>
                                    <tr>
                                        <td>{{ convertValueToCommaSeparated($payroll->govrt_tax) }}</td>
                                    </tr>
                                </table>
                            </td>
                        </tr>
                        <tr>
                            <td style="border-right:1px solid black;border-bottom:1px solid black;font-weight: bolder;text-align: center">Total</td>
                            <td style="border-right:none;border-bottom:1px solid black;font-weight: bold;text-align: right;padding-right:3px;">{{ convertValueToCommaSeparated($totalEarnings) }}</td>
                            <td style="border-left:1px solid black;border-bottom:1px solid black;font-weight: bold;text-align: right;padding-right:3px;">{{ convertValueToCommaSeparated($totalDeductions) }}</td>
                        </tr>
                        <tr>
                            <td style="border-right:1px solid black;border-bottom:1px solid black;font-weight: bolder;text-align: center">Net Pay</td>
                            <td colspan="2" style="border-bottom:1px solid black;font-weight: bold;text-align: center;height:30px;">{{ convertValueToCommaSeparated($payroll->net_salary) }}</td>
                        </tr>
                        <tr valign="bottom">
                            <td style="vertical-align: bottom; border-right:1px solid black;border-bottom:1px solid black;height:60px;font-size:12px;padding-left:2px;padding-bottom:2px;font-weight:bolder">Employee's Signature</td>
                            <td style="vertical-align: bottom; border-bottom:1px solid black;height:60px;font-size:12px;padding-left:2px;padding-bottom:2px;font-weight:bolder" colspan="2">Employer's Signature</td>
                        </tr>
                    </table>
                </td>
            </tr>
        </table>
        @if (!$loop->last)
            <div class="page-break"></div>
        @endif
    @endforeach
</body>

</html>
