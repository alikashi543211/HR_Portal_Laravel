<div class="row">
    <div class="col-md-12">
        <div id="accordion">
            @foreach ($data['roles'] as $key => $role)
            <div class="card">
                <div class="card-header role-header" data-toggle="collapse" href="#div{{ $key }}" style="cursor: pointer">
                    <a class="card-link">
                        {{ $role->title }}
                    </a>
                </div>
                <div id="div{{ $key }}" class="collapse @if($loop->first)show @endif" data-parent="#accordion">
                    <div class="card-body">
                        <div class="card-body">
                            <div class="row">
                                <div class="col-md-4">Module</div>
                                <div class="col-md-4">Read</div>
                                <div class="col-md-4">Write</div>
                            </div>
                            <hr>
                            @foreach ($data['permissions'] as $i => $permission)
                            <div class="form-group">
                                <div class="row">
                                    <div class="col-md-4">{{ $permission->name }}</div>
                                    <div class="col-md-4">
                                        <div class="custom-control custom-checkbox">
                                            <input @if(!checkPermission(PERMISSIONS, WRITE)) disabled @endif class="custom-control-input" type="checkbox" id="{{ $role->id }}-{{ $permission->id }}-read" data-role_id="{{ $role->id }}" data-permission_id="{{ $permission->id }}" data-type="{{ READ }}" @if($permission->readPermission($role->id)) checked @endif>
                                            <label for="{{ $role->id }}-{{ $permission->id }}-read" class="custom-control-label"></label>
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="custom-control custom-checkbox">
                                            <input @if(!checkPermission(PERMISSIONS, WRITE)) disabled @endif class="custom-control-input" type="checkbox" id="{{ $role->id }}-{{ $permission->id }}-write" data-role_id="{{ $role->id }}" data-permission_id="{{ $permission->id }}" data-type="{{ WRITE }}" @if($permission->writePermission($role->id)) checked @endif>
                                            <label for="{{ $role->id }}-{{ $permission->id }}-write" class="custom-control-label"></label>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            @endforeach

                            @if($role->id > EMPLOYEE)
                            <a href="#!" data-url="{{url(config('data.admin.permission.delete').'/'.$role->id)}}" class="confirmation-popup pull-right btn btn-danger" title="Delete">Delete</a>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
            @endforeach
        </div>
    </div>
</div>

@section('footer_js')
@parent
<script type="text/javascript">
    $(document).on('change', 'input[type=checkbox]', function(e) {
        var t = $(this);
        $.ajax({
            url: "{{ url('admin/permissions/update') }}",
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            data: {
                permission_id: t.data('permission_id'),
                role_id: t.data('role_id'),
                action_id: t.data('type')
            },
            success: function(res) {
                console.log(res);
                t.prop('checked', res.permission);
            }
        });
    });
</script>
@endsection