<div class="table-responsive">
    <table class="table table-striped">
        <thead class=" text-primary">
            <th> Sr# </th>
            <th> Name </th>
            <th> Image </th>
            <th> Amount </th>
            <th> PayRoll Month</th>
            <th> Action </th>
        </thead>
        <tbody>
            @foreach ($data as $index => $info)
                <tr>
                    <td> {{ $data->firstItem() + $index }} </td>
                    <td> {{ $info->name }} </td>
                    <td><img src="{{ asset('uploads/payroll-tax/' . $info->image) }}" class=".custom-image--payroll-tax openmodel" data-toggle="modal" data-target="#myModal" alt="Iamge.png"> </td>
                    <td> {{ $info->amount }} </td>
                    <td> {{ $info->payRoll->date }} </td>
                    <td>

                        @if (checkPermission(PAY_ROLLS, WRITE))
                            @if (!$info->lock)
                                <a href="#!" data-url="{{ url(config('data.admin.payroll.tax-delete') . '/' . $info->id) }}" class="confirmation-popup btn btn-sm btn-danger" title="Delete">Delete</a>
                            @endif
                            <a href="{{ url(config('data.admin.payroll.tax-edit') . '/' . $info->id) }}" title="Edit" class="btn btn-sm btn-info">Edit</a>
                        @endif
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>
    {{ $data->links() }}
</div>

<div class="modal fade" id="myModal">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">

            <!-- Modal Header -->
            <div class="modal-header">
                <h4 class="modal-title">Tax Image</h4>
                <button type="button" class="close" data-dismiss="modal">&times;</button>
            </div>

            <!-- Modal body -->
            <div class="modal-body">
                <img src="" id="taxImage" alt="">
            </div>

        </div>
    </div>
</div>


@section('footer_js')
    @parent
    <script>
        $(document).on('click', 'img.custom-image--payroll-tax.openmodel', function() {
            console.log($(this).attr('src'))
        })
        $('#myModal').on('show.bs.modal', function(event) {
            var img = $(event.relatedTarget);
            var src = $(img).attr('src');
            $('#taxImage').attr('src', src);

        });
    </script>
@endsection
