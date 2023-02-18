<div class="table-responsive">
    <table class="table table-striped">
        <thead class=" text-primary">
            <th> Sr# </th>
            <th> Name </th>
            <th> Type </th>
            <th> For All Users </th>
            <th> Value </th>
            <th> Created By </th>
            <th> Action </th>
        </thead>
        <tbody>
            @foreach($data as $index => $info)
            <tr>
                <td> {{$data->firstItem() + $index}} </td>
                <td> {{$info->name}}</td>
                <td> {{$info->type_name}}</td>
                <td> {{$info->for_all}}</td>
                <td> {{$info->value}}</td>
                <td> {{ $info->createdBy ? $info->createdBy->full_name : 'N/A'  }} </td>
                <td>
                    @if(checkPermission(ALLOWANCES, WRITE))
                    <a href="#!" data-url="{{url(config('data.admin.allowance.delete').'/'.$info->id)}}" class="confirmation-popup btn btn-danger" title="Delete">Delete</a>
                    <a href="{{url(config('data.admin.allowance.edit').'/'.$info->id)}}" title="Edit" class="btn btn-info">Edit</a>
                    @endif
                </td>
            </tr> @endforeach </tbody>
    </table>
    {{ $data->links() }}
</div>



@section('footer_js')
@parent
<script>
    $(document).ready(function() {});
</script>
@endsection