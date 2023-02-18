<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <style>
        table {
            border: 4px solid black;
            margin: 0 auto;
        }

        table {
            border-collapse: collapse;
        }
    </style>
</head>

<body>
    <br /><br /><br /><br /><br /><br /><br />
    <table cellspacing="0" border="1px solid black" cellpadding="5">
        <tr>
            <td align="center" colspan="3" style="padding-top: 50px;"><b> PAYROLL FOR THE MONTH OF {{$date}} </b></td>
        </tr>
        <tr>
            <td></td>
        </tr>
        <tr>
            <td></td>
        </tr>
        <tr>
            <td></td>
        </tr>
        <tr>
            <td></td>
        </tr>
        <tr>
            <td></td>
        </tr>
        <tr>
            <td></td>
        </tr>
        <tr>
            <td width="15%"></td>
            <td width="55%"> TOTAL MONTHLY SALARY </td>
            <td width="20%"> <b> {{convertValueToCommaSeparated($totalMonthSalary)}} </b>
        </tr>
        <tr>
            <td></td>
        </tr>
        <tr>
            <td></td>
            <td> NO. OF EMPLOYEES CURRENT </td>
            <td> <b> {{$totalEmps}} </b>
        </tr>
        <tr>
            <td></td>
        </tr>
        <tr>
            <td></td>
            <td> <b>NET PAYABLE AMOUNT</b> </td>
            <td> <b> {{convertValueToCommaSeparated($netSalary)}} </b>
        </tr>
        <tr>
            <td></td>
        </tr>
        <tr>
            <td></td>
            <td> <b>Approved By:</b> </td>
            <td></td>
        </tr>
        <tr>
            <td></td>
        </tr>
        <tr>
            <td></td>
            <td colspan="2" style="padding-bottom: 50px;"> <b>Human Resources &nbsp; &nbsp; Accounts &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; Chief Exective Officer</b> </td>

        </tr>
    </table>
</body>

</html>