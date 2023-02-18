<div class="table-responsive">
    <table class="table table-striped">
        <thead class=" text-primary">
            <th> Sr# </th>
            <th> Full Name </th>
            <th> Causal Leaves </th>
            <th> Sick Leaves </th>
            <th>Action</th>
        </thead>
        <tbody>
            <form action="{{url('admin/leaves/update')}}" method="post">
                @foreach($data as $index => $info)
                @csrf
                <input type="hidden" name="id[]" value="{{$info->id}}">
                <tr>
                    <td> {{$data->firstItem() + $index}} </td>
                    <td> {{$info->full_name}} </td>
                    <td>
                        <input type="number" step="0.5" name="remaining_casual_leaves[]" value="{{$info->leaveQuota->remaining_casual_leaves}}" min="0" max="15" class="form-control">
                    </td>
                    <td>
                        <input type="number" step="0.5" name="remaining_sick_leaves[]" value="{{$info->leaveQuota->remaining_sick_leaves}}" min="0" max="15" class="form-control">
                    </td>
                    <td>
                        <a href="{{ url('admin/leaves/history/'.$info->id) }}?year={{ date('Y') }}&doj={{ date('Y', strtotime($info->doj)) }}" title="History" class="btn btn-sm btn-info">
                            History
                        </a>
                    </td>

                </tr>
                @endforeach
                @if (checkPermission(LEAVES, WRITE))
                <button title="Update" type="submit" class="btn btn-info float-right">Update</button>
                @endif
            </form>
        </tbody>
    </table>
    {{ $data->links() }}
</div>

@section('footer_js')
@parent
<script>

</script>
@endsection
