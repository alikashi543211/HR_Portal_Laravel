<div class="table-responsive">
    <table class="table table-striped">
        <thead class=" text-primary">
            <th> Sr# </th>
            <th> Holiday Name </th>
            <th> Type </th>
            <th> Date </th>
            <th> Created By </th>
            <th> Action </th>
        </thead>
        <tbody>
            @foreach($data as $index => $info)
                <tr>
                    <td> {{$data->firstItem() + $index}} </td>
                    <td> {{ $info->name }}</td>
                    <td> @if($info->type == 0) Holiday @else Working Day @endif </td>
                    <td> {{ date('d M, Y', strtotime($info->date))  }} </td>
                    <td> {{ $info->createdBy ? $info->createdBy->full_name : 'N/A'  }} </td>
                    <td>
                        @if(checkPermission(HOLIDAYS, WRITE))
                            <a href="#!" data-url="{{url(config('data.admin.holiday.delete').'/'.$info->id)}}" class="confirmation-popup btn btn-danger" title="Delete">Delete</a>
                            <a href="{{url(config('data.admin.holiday.edit').'/'.$info->id)}}" title="Edit" class="btn btn-info">Edit</a>
                        @endif
                    </td>
                </tr>
            @endforeach </tbody>
    </table>
    {{ $data->links() }}
</div>



@section('footer_js')
@parent
<script>
    $(document).ready(function() {});
</script>
@endsection
