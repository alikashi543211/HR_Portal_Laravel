 <table class="table table-responsive-md">
     <thead>
         <tr>
             <th class="width80"><strong>Sr.No</strong></th>
             <th><strong>Month</strong></th>
             <th><strong>Late Deduction</strong></th>
             <th><strong>Leave Deduction</strong></th>
             <th><strong>Loan Deduction</strong></th>
             <th><strong>Govt. tax</strong></th>
             <th><strong>Gross Salary</strong></th>
             <th><strong>Net Salary</strong></th>
             <th><strong>Actions</strong></th>
         </tr>
     </thead>
     <tbody>
         @php
             $GLOBALS['count'] = 0;
         @endphp
         @foreach ($salaries as $key => $salary)
             <tr>
                 <td><strong>{{ $salaries->firstItem() + $GLOBALS['count']++ }}</strong></td>
                 <td>{{ \Carbon\Carbon::parse($salary->payroll->date)->format('M, Y') }}</td>
                 <td>{{ $salary->late_deduction }}</td>
                 <td>{{ $salary->leave_deduction }}</td>
                 <td>{{ $salary->loan_deduction }}</td>
                 <td>{{ $salary->govrt_tax }}</td>
                 <td>{{ $salary->gross_salary }}</td>
                 <td>{{ $salary->net_salary }}</td>
                 <td class="font-awosome">
                     <a href="{{ route('employee.salary-history.downloadSalarySlip', $salary->id) }}" class="mr-2"><i class="fa-regular fa-download"></i></a>
                 </td>
             </tr>
         @endforeach
     </tbody>
 </table>
 <div class="custom-pagination">
     {!! $salaries->appends($_GET)->links() !!}
 </div>
