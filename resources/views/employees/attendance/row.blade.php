 <table class="table table-responsive-md">
     <thead>
         <tr>
             <th class="width80" rowspan="2"><strong>Months</strong></th>
             <th class="width80 text-center" colspan="31"><strong>Dates</strong></th>
         </tr>
         <tr>
             @for ($i = 1; $i <= 31; $i++)
                 <th><strong>{{ $i }}</strong></th>
             @endfor
         </tr>
     </thead>
     <tbody>
         {{-- @foreach ($data['attendance'] as $key => $item)
             <tr>

                 <th><strong>{{ $item[0]['month'] }}</strong></th>
                 @foreach ($item as $key => $val)
                     <td>
                         <a href="{{ route('employee.attendance.detail', $item[0]['month']) }}">
                             <div class="{{ $val['icon'] }}" title="{{ $val['title'] }}"></div>
                         </a>
                     </td>
                 @endforeach

             </tr>
         @endforeach --}}
         {{-- {!! $data['attendance']->links('employees.attendance.pagination') !!} --}}

     </tbody>
 </table>
