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
</head>

@php
$totalAmount = 0;
@endphp

<body>
    <br /><br /><br /><br /><br />
    <table style="width: 100%; border:0px">
        <tr>
            <td style="border: 0px;">
                {{ !empty($companyProfile->respective_title) ? $companyProfile->respective_title : ''}} {{ !empty($companyProfile->respective_first_name) ? $companyProfile->respective_first_name : ''}} {{ !empty($companyProfile->respective_last_name) ? $companyProfile->respective_last_name : ''}},
            </td>
            <td align="right" style="border: 0px;">
                <b> {{ date('d-m-y') }} </b>
            </td>
        </tr>
        <tr>
            <td style="border: 0px;">
                {{ !empty($companyProfile->respective_designation) ? $companyProfile->respective_designation . ',' : ''}}
            </td>
        <tr>
            <td style="border: 0px;">
                {{ !empty($companyProfile->respective_bank_name) ? $companyProfile->respective_bank_name . ',' : ''}}
            </td>
        </tr>
        <tr>
            <td style="border: 0px;">
                {{ !empty($companyProfile->respective_address_1) ? $companyProfile->respective_address_1 . ',' : ''}}
            </td>
        </tr>
        <tr>
            <td style="border: 0px;">
                {{ !empty($companyProfile->respective_address_2) ? $companyProfile->respective_address_2 . '.' : ''}}
            </td>
        </tr>
    </table>

    <p>
        Dear {{ !empty($companyProfile->respective_title) ? $companyProfile->respective_title : ''}} {{ !empty($companyProfile->respective_first_name) ? $companyProfile->respective_first_name : ''}},
    </p>
    <p style="text-align: center;">
        <b><u> REQUEST TO DISBURSE SALARIES FOR THE M/O {{$date}} </u></b>
    </p>
    @if(count($payRolls) > 0)
    As mentioned in the subject line, please disburse the following figures to the Employees accounts of DevStudio.
    <table style="width: 100%;" cellspacing="0" border="1px solid black" cellpadding="5">
        <tr>
            <th width="100"> Sr. No. </th>
            <th> ACCOUNT TITLE </th>
            <th> ACCOUNT NUMBER </th>
            <th> CURRENCY </th>
            <th> AMOUNT </th>
        </tr>
        @php
            $counter = 1;
        @endphp
        @foreach($payRolls as $index => $payRoll)
        @if(!empty($payRoll->user) AND !empty($payRoll->user->account_no))
        <tr>
            <td align="center" style="font-size:9.5px;"> {{ $counter }} </td>
            <td align="center" style="font-size:9.5px;">
                {{ strtoupper($payRoll->user->first_name) }} {{ strtoupper($payRoll->user->last_name) }}
            </td>
            <td align="center" style="font-size:9.5px;">
                {{ $payRoll->user->account_no }}
            </td>
            <td align="center" style="font-size:9.5px;">
                PKR
            </td>
            <td align="center" style="font-size:9.5px;">
                {{ convertValueToCommaSeparated($payRoll->net_salary) }}
                @php
                $totalAmount += $payRoll->net_salary;
                @endphp
            </td>
        </tr>
        @php
            $counter++;
        @endphp
        @endif
        @endforeach
        <tr>
            <td colspan="4" align="center"> <b> TOTAL </b></td>
            <td align="center"> <b>{{ convertValueToCommaSeparated($totalAmount) }}</b> </td>
        </tr>
    </table>
    @endif
    <p>
        Thanking you in advance for your cooperation.
    </p>
    <br />
    <p style="text-align: right; margin-right: 100px;">
        {{ $companyProfile->authorized_name }}
        <br />
        {{ $companyProfile->authorized_designation }}
        <br />
        {{ $companyProfile->name }}
    </p>
</body>

</html>
