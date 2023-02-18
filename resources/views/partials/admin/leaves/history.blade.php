<div class="table-responsive">
    <table class="table table-striped">
        <thead class=" text-primary">
            <th> Sr# </th>
            <th> Type </th>
            <th> Period </th>
            <th> Period Type </th>
            <th> Reason </th>
            <th> Date </th>
            <th> User </th>
            <th> Leave Adjust </th>
        </thead>
        <tbody>
                @php
                    $record = false;
                @endphp

                @foreach($data as $index => $info)
                    @if(($leave_year = date('Y', strtotime($info->date)) == request()->year))
                        @php
                            $record = true;
                        @endphp
                        <tr>
                            <td> {{$loop->iteration}} </td>
                            <td>
                                @if($info->type == SICK_LEAVE)
                                    Sick Leave
                                @else
                                    Casual Leave
                                @endif
                            </td>
                            <td>
                                @if($info->period == HALF_DAY_LEAVE)
                                    Half Day Leave
                                @else
                                    Full Day Leave
                                @endif
                            </td>
                            <td>
                                @if($info->period_type == FULL_DAY)
                                    Full Day
                                @elseif($info->period_type == FIRST_HALF)
                                    First Half
                                @else
                                    Second Half
                                @endif
                            </td>
                            <td> {{ $info->reason }} </td>
                            <td> {{ $info->date }} </td>
                            <td> {{ $info->user->FullName }} </td>
                            <td>
                                @if($info->leave_adjust == LEAVE_ADJUST)
                                    Adjust
                                @else
                                    Not Adjust
                                @endif
                            </td>
                        </tr>
                    @else
                        @continue
                    @endif
                @endforeach
                @if(!$record)
                    <tr>
                        <td colspan="9" class="text-center">
                            <span>No Record Found</span>
                        </td>
                    </tr>
                @endif
        </tbody>
    </table>
    <nav aria-label="...">
        <ul class="pagination">
        <li class="page-item @if((request()->year == request()->doj)) disabled @endif">
            <a class="page-link" href="?year={{ decYear(request()->year) }}&doj={{ request()->doj }}" tabindex="-1">Previous</a>
        </li>
        <li class="page-item @if((request()->year == date('Y'))) disabled @endif">
            <a class="page-link" href="?year={{ incYear(request()->year) }}&doj={{ request()->doj }}">Next</a>
        </li>
        </ul>
    </nav>
</div>

@section('footer_js')
@parent
<script>

</script>
@endsection
