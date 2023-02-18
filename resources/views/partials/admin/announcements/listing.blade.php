<div class="table-responsive">
    <table class="table table-striped table-fixed">
        <thead class=" text-white" style="background-color: #51bcda;">
            <th> Sr# </th>
            <th> Title </th>
            <th> Added By </th>
            @if(checkPermission(ANNOUNCEMENTS, WRITE))
            <th> Actions </th>
            @endif
        </thead>
        <tbody>
            @foreach($data AS $index => $announce)
            <tr style="cursor: pointer">
                <td> {{ $index+1 }} </td>
                <td> {{ $announce->title }} </td>
                <td> {{ $announce->createdBy->first_name.' '.$announce->createdBy->last_name }} </td>
                <td>
                    @if(checkPermission(ANNOUNCEMENTS, WRITE))
                    <a href="{{url(config('data.admin.announcements.edit').'/'.$announce->id)}}" class="btn btn-sm btn-success" data-id="{{$announce->id}}" title="Edit">Edit</a>
                    <a href="{{url(config('data.admin.announcements.delete').'/'.$announce->id)}}" class="btn btn-sm btn-danger" data-id="{{$announce->id}}" title="Delete">Delete</a>
                    <a href="{{url(config('data.admin.announcements.send-mail').'/'.$announce->id)}}" class="btn btn-sm btn-warning" data-id="{{$announce->id}}" title="Mail">Send Mail</a>
                    @endif
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>
    {{ $data->appends(request()->query())->links() }}
</div>
