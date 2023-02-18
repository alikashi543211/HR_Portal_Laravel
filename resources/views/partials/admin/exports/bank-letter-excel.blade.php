@php
$totalAmount = 0;
@endphp
@if(count($payRolls) > 0)
    <table>
        <tr>
           <th colspan="4" height="80" valign="top">
                {{ !empty($companyProfile->respective_title) ? $companyProfile->respective_title : ''}} {{ !empty($companyProfile->respective_first_name) ? $companyProfile->respective_first_name : ''}} {{ !empty($companyProfile->respective_last_name) ? $companyProfile->respective_last_name : ''}}, <br>
                {{ !empty($companyProfile->respective_designation) ? $companyProfile->respective_designation . ',' : ''}} <br>
                {{ !empty($companyProfile->respective_bank_name) ? $companyProfile->respective_bank_name . ',' : ''}} <br>
                {{ !empty($companyProfile->respective_address_1) ? $companyProfile->respective_address_1 . ',' : ''}} <br>
                {{ !empty($companyProfile->respective_address_2) ? $companyProfile->respective_address_2 . '.' : ''}} <br>
           </th>
            <th colspan="1" align="right" valign="top">{{ date('d-m-y') }}</th>
        </tr>
        <tr>
           <th colspan="5">
                Dear {{ !empty($companyProfile->respective_title) ? $companyProfile->respective_title : ''}} {{ !empty($companyProfile->respective_first_name) ? $companyProfile->respective_first_name : ''}},
           </th>
        </tr>
        <tr>
           <td colspan="5" height="35" valign="center" style="text-decoration: underline;font-size:12pt;" align="center">
                <strong>
                    REQUEST TO DISBURSE SALARIES FOR THE M/O {{ $date }}
                </strong>
           </td>
        </tr>
        <tr>
           <td colspan="5" height="35" valign="center">
               As mentioned in the subject line, please disburse the following figures to the Employees accountsof DevStudio.
           </td>
        </tr>
        <tr>
            <th width="25" align="center"  style="border: 1px solid black"> <strong>Sr. No. </strong> </th>
            <th width="25" align="center"  style="border: 1px solid black"> <strong>ACCOUNT TITLE </strong> </th>
            <th width="25" align="center"  style="border: 1px solid black"> <strong>ACCOUNT NUMBER </strong> </th>
            <th width="25" align="center"  style="border: 1px solid black"> <strong>CURRENCY </strong> </th>
            <th width="25" align="center"  style="border: 1px solid black"> <strong>AMOUNT </strong> </th>
        </tr>
        @php
            $counter = 1;
        @endphp
        @foreach($payRolls as $index => $payRoll)
        @if(!empty($payRoll->user) AND !empty($payRoll->user->account_no))
        <tr>
            <td align="center" style="border: 1px solid black"> {{ $counter }} </td>
            <td align="center" style="border: 1px solid black">
                {{ strtoupper($payRoll->user->first_name) }} {{ strtoupper($payRoll->user->last_name) }}
            </td>
            <td align="center" style="border: 1px solid black">
                {{ $payRoll->user->account_no }}
            </td>
            <td align="center" style="border: 1px solid black">
                PKR
            </td>
            <td align="center" style="border: 1px solid black">
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
            <td colspan="4" align="center"  style="border: 1px solid black"> <b> TOTAL </b></td>
            <td align="center"  style="border: 1px solid black"> <b>{{ convertValueToCommaSeparated($totalAmount) }}</b> </td>
        </tr>
        <tr></tr>
        <tr>
            <td colspan="5">Thanking you in advance for your cooperation.</td>
        </tr>
        <tr>
            <td colspan="3"></td>
            <td colspan="2" align="right" height="50">
                 {{ $companyProfile->authorized_name }}
                <br />
                {{ $companyProfile->authorized_designation }}
                <br />
                {{ $companyProfile->name }}
            </td>
        </tr>

    </table>
@endif