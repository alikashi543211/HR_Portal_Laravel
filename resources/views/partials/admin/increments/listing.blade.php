<div class="table-responsive">
    <table class="table table-striped">
        <thead class=" text-primary">
            <th> Sr# </th>
            <th> User </th>
            {{-- <th> Previous </th> --}}
            <th> Increment </th>
            <th> Month </th>
            <th> Action </th>
        </thead>
        <tbody>
            @foreach($data as $index => $info)
            <tr>
                <td> {{$data->firstItem() + $index}} </td>
                <td> {{ $info->user->FullName ?? 'N/A'  }} </td>
                {{-- <td> {{ $info->previous  }} </td> --}}
                <td> {{ $info->increment  }} </td>
                <td> {{ $info->month }} </td>
                <td>
                    @if(checkPermission(INCREMENTS, WRITE) && $info->status == 0)
                    <a href="#!" data-url="{{url(config('data.admin.increments.delete').'/'.$info->id)}}" class="confirmation-popup btn btn-danger" title="Delete">Delete</a>
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
