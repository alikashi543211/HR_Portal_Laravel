<div class="table-responsive">
    <table class="table table-striped">
        <thead class=" text-primary">
            <th> Sr# </th>
            <th> Name </th>
            <th> Code </th>
            <th> User </th>
            <th> Satus </th>
            <th> Request </th>
            <th> Action </th>
        </thead>
        <tbody>
            @foreach ($data as $index => $info)
                <tr>
                    <td> {{ $data->firstItem() + $index }} </td>
                    <td> {{ $info->name ?? 'N/A' }} </td>
                    <td> {{ $info->code }} </td>
                    <td> {{ $info->user ? $info->user->first_name . ' ' . $info->user->last_name : UNASSIGNED }} </td>
                    <td> {{ $info->status == INVENTORY_OFFICE ? 'Office' : 'Home' }} </td>
                    <td>
                        @if ($info->request == INVT_REQ_HOME_APPROVED)
                            {{ INVT_REQ_HOME_APPROVED }}
                        @elseif($info->request == INVT_REQ_HOME_REJECTED)
                            {{ INVT_REQ_HOME_REJECTED }}
                        @elseif($info->request == INVT_REQ_HOME_PENDING)
                            {{ INVT_REQ_HOME_PENDING }}
                        @elseif($info->request == INVT_REQ_OFFICE_APPROVED)
                            {{ INVT_REQ_OFFICE_APPROVED }}
                        @elseif($info->request == INVT_REQ_OFFICE_REJECTED)
                            {{ INVT_REQ_OFFICE_REJECTED }}
                        @elseif($info->request == INVT_REQ_OFFICE_PENDING)
                            {{ INVT_REQ_OFFICE_PENDING }}
                        @elseif($info->request == INVT_REQ_DEFAULT)
                            {{ INVT_REQ_DEFAULT }}
                        @endif
                    </td>
                    <td class="">
                        @if ($info->request == INVT_REQ_HOME_PENDING || $info->request == INVT_REQ_OFFICE_PENDING)
                            @if (checkPermission(INVENTORY, WRITE))
                                <div class="forms-custom">
                                    <form action="{{ url(config('data.admin.inventories.request-inventory')) }}" method="POST" class="mr-2">
                                        @csrf
                                        <input type="text" name="id" value="{{ $info->id }}" hidden>
                                        <input type="text" name="request" value="{{ $info->request == INVT_REQ_HOME_PENDING ? INVT_REQ_HOME_APPROVED : INVT_REQ_OFFICE_APPROVED }}" hidden>
                                        <button type="submit" class=" btn btn-info" title="Approve">Approve</button>
                                    </form>
                                    <form action="{{ url(config('data.admin.inventories.request-inventory')) }}" method="POST">
                                        @csrf
                                        <input type="text" name="id" value="{{ $info->id }}" hidden>
                                        <input type="text" name="request" value="{{ $info->request == INVT_REQ_HOME_PENDING ? INVT_REQ_HOME_REJECTED : INVT_REQ_OFFICE_REJECTED }}" hidden>
                                        <button type="submit" class=" btn btn btn-danger" title="Reject">Reject</button>
                                    </form>
                                </div>
                            @endif
                        @else
                            @if ((checkPermission(INVENTORY, WRITE) && $info->status == INVENTORY_OFFICE) || (checkPermission(INVENTORY, WRITE) && $info->status == INVENTORY_HOME))
                                <a href="{{ url(config('data.admin.inventories.edit') . '/' . $info->id) }}" class=" btn btn-info mr-2" title="Edit">Edit</a>
                            @endif
                            @if ((checkPermission(INVENTORY, WRITE) && $info->status == INVENTORY_OFFICE) || (checkPermission(INVENTORY, WRITE) && $info->status == INVENTORY_HOME))
                                <a href="#!" data-url="{{ url(config('data.admin.inventories.delete') . '/' . $info->id) }}" class="confirmation-popup btn btn-danger" title="Delete">Delete</a>
                            @endif
                        @endif
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>
    {{ $data->links() }}
</div>



@section('footer_js')
    @parent
    <script>
        $(document).ready(function() {
            $(document).ready(function() {
                $('.select2').select2();

            });
        });
    </script>
@endsection
