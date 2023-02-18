<div class="row">
    <div class="col-md-12">
        <div class="card">
            <div class="card-header">
                <h4 class="card-title"> {{ $page_title }}</h4>
            </div>
            <div>
                <div class="col-md-8 offset-2">
                    {{-- {{ dd(config('data.admin.increments.save')) }} --}}
                    <form method="post" action="{{ url(config('data.admin.inventories.save')) }}">
                        @csrf
                        <div class="row">
                            <div class="col-md-6 form-group">
                                <label>Name</label>
                                <input name="name" class="form-control" required value="{{ old('name') }}" />

                            </div>
                            <div class="col-md-6 form-group">
                                <label>Code</label>
                                <input type="text" autocomplete="off" name="code" class="form-control" value="{{ old('code') }}" required>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6 form-group ">
                                <label>Select User</label>
                                <select name="user_id" class="form-control select2 ">
                                    <option value="">Select User</option>
                                    @foreach ($data as $user)
                                        <option value="{{ $user->id }}" @if (old('user_id') == $user->id) selected @endif>{{ $user->first_name . ' ' . $user->last_name }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-md-6 form-group ">
                                <label>Status</label>
                                <select name="status" class="form-control select2 ">
                                    <option value="">Select Status</option>
                                    <option value="{{ INVENTORY_OFFICE }}" selected>Office</option>
                                    <option value="{{ INVENTORY_HOME }}">Home</option>
                                </select>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6 form-group">
                                <label>Request</label>
                                <select name="request" class="form-control select2 ">
                                    <option value="{{ INVT_REQ_DEFAULT }}" selected>{{ INVT_REQ_DEFAULT }}</option>
                                    <option value="{{ INVT_REQ_HOME_APPROVED }}">{{ INVT_REQ_HOME_APPROVED }}</option>
                                    <option value="{{ INVT_REQ_HOME_REJECTED }}">{{ INVT_REQ_HOME_REJECTED }}</option>
                                    <option value="{{ INVT_REQ_HOME_PENDING }}">{{ INVT_REQ_HOME_PENDING }}</option>
                                </select>
                            </div>
                            <div class="col-md-6 form-group">
                                <div class="update ml-auto mr-auto mt-3">
                                    @if (checkPermission(INVENTORY, WRITE))
                                        <button type="submit" class="btn btn-primary btn-round">{{ __('default_label.add') }}</button>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
<hr>
@section('footer_js')
    @parent
    <script>
        $(document).ready(function() {
            $('.select2').select2();
            $('#datetimepicker1').datepicker({
                autoClose: true,
                zIndexOffset: 9999,
                minViewMode: 'months',
                format: 'MM, yyyy'
            });
        });

        // $(document).on('change', 'select[name=user_id]', function() {
        //     $('input[name=previous]').val($('select[name=user_id] option:selected').data('salary'));
        // })
    </script>
@endsection
