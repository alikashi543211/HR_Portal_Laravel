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
$totalChqAmount = $totalChequeAmount;
$totalInvoiceAmount = $totalChequeAmount;
$sr = 1;
@endphp

<body>

    <p style="text-align: center; background-color: lightblue;">
        <b> Cheques for the M/O {{$date}} </b>
    </p>
    <table style="width: 100%;" cellspacing="0" border="1px solid black" cellpadding="5">
        <tr>
            <th> Sr. No. </th>
            <th> PARTY </th>
            <th> INVOICE AMOUNT </th>
            <th> CHQ AMOUNT </th>
            <th> REMARKS </th>
        </tr>
        <tr>
            <td align="center"> 1 </td>
            <td align="center"> {{ !empty($companyProfile->respective_bank_name) ? $companyProfile->respective_bank_name : ''}} </td>
            <td align="center"> {{ convertValueToCommaSeparated($totalChequeAmount) }} </td>
            <td align="center"> {{ convertValueToCommaSeparated($totalChequeAmount) }} </td>
            <td></td>
        </tr>
        @if(count($userWithouAccount) > 0)
        @foreach($userWithouAccount as $index => $payRoll)
        <tr>
            <td align="center"> {{ $sr + 1 }} </td>
            <td align="center"> {{ $payRoll->user->first_name }} {{ $payRoll->user->last_name }} </td>
            <td align="center"> {{ convertValueToCommaSeparated($payRoll->net_salary) }} </td>
            <td align="center"> {{ convertValueToCommaSeparated($payRoll->net_salary) }} </td>
            <td></td>
        </tr>
        @php
        $totalChequeAmount += $payRoll->net_salary;
        @endphp

        @php
            $sr++;
        @endphp
        @endforeach
        @endif
        <tr>
            <td></td>
            <td align="center"> <b> GRAND TOTAL </b></td>
            <td align="center"> <b>{{ convertValueToCommaSeparated($totalChequeAmount) }}</b> </td>
            <td align="center"> <b>{{ convertValueToCommaSeparated($totalChequeAmount) }}</b> </td>
            <td></td>
        </tr>
    </table>
</body>

</html>
