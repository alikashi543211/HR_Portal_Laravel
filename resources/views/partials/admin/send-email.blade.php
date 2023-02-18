@php
    $users = $data['data'];
@endphp
<div class="row">
    <div class="col-md-12">
        <div class="card">
            <div class="card-header">
                <h4 class="card-title"> {{ $page_title }}</h4>
            </div>
            <div>
                <div class="col-md-12">
                    <form method="post" action="{{ url(config('data.admin.emails.send')) }}">
                        @csrf
                        <div class="row">
                            <div class="col-md-12 form-group">
                                <label>
                                    Emails
                                    @if (checkPermission(SEND_EMAIL, WRITE))
                                        <span><button type="button" class="btn btn-sm btn-primary mx-2" data-class="all">Select All</button></span>
                                        <span><button type="button" class="btn btn-sm btn-warning mx-2" data-class="active">Select Active</button></span>
                                    @endif
                                </label>
                                <select style="width: 100%" class="form-control js-example-basic-multiple select2 emails" multiple="multiple" name="emails[]" {{ checkPermission(SEND_EMAIL, WRITE) == false ? 'disabled' : '' }}required>

                                </select>
                            </div>
                            <div class="col-md-12 form-group">
                                <label>Subject</label>
                                <input type="text" autocomplete="off" name="subject" class="form-control" {{ checkPermission(SEND_EMAIL, WRITE) == false ? 'disabled' : '' }}>
                            </div>
                            <div class="col-md-12 form-group">
                                <label>CC</label>
                                <select style="width: 100%" class="form-control js-example-basic-multiple select2" multiple="multiple" name="cc[]" required>
                                    @foreach ($users as $key => $user)
                                        <option value="{{ $user->id }}">{{ $user->email }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-md-12 form-group">
                                <label>Description</label>
                                <textarea name="description" id="description" class="form-control" {{ checkPermission(SEND_EMAIL, WRITE) == false ? 'disabled' : '' }} rows="20">{{ old('description') ? old('description') : '' }}</textarea>
                            </div>
                        </div>
                        <div class="row">
                            <div class="update ml-auto mr-auto">
                                @if (checkPermission(SEND_EMAIL, WRITE))
                                    <button type="submit" class="btn btn-primary btn-round">{{ __('default_label.send_btn') }}</button>
                                @endif
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
    <script src="{{ url('assets/ckeditor/ckeditor.js') }}"></script>
    <script src="{{ url('assets/ckeditor/adapters/jquery.js') }}"></script>
    <script>
        var usersData = [];
        @foreach ($users as $key => $user)
            usersData.push({
                "id": "{{ $user->id }}",
                "text": "{{ $user->email }}",
                "status": "{{ $user->status }}",
                "selected": false
            });
        @endforeach
        $(document).ready(function() {
            $('#description').ckeditor();
            $('.select2').select2({
                placeholder: 'Select Users',
                allowClear: true,
                data: usersData
            });
        });

        $('button.btn-sm').on('click', function() {
            className = $(this).data('class');
            if (className == 'all') {
                updateSelect2(usersData);
            }
            if (className == 'active') {
                data = usersData.filter((value, index) => {
                    value.selected = true;
                    return (value.status == 'Permanent' || value.status == '1M Probation' || value.status == '2M Probation' || value.status == '3M Probation');
                })
                // console.log(className, data);
                updateSelect2(data);
            }

        });

        function updateSelect2(data) {
            $('.emails.select2 option').remove();
            if (data.length == 0) {
                $('.emails.select2').val(null).trigger('change');

            } else {
                data.forEach(element => {
                    var newOption = new Option(element.text, element.id, true, true);
                    $('.emails.select2').append(newOption).trigger('change');
                });

            }
        }
    </script>
@endsection
