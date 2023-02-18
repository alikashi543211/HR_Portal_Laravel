@php
    $exceptionDates = $data['exceptionDates'];
    $policies = $data['policies'];
@endphp
<div class="table-responsive">
    <table class="table  table-fixed  table-striped">
        <thead class=" text-white" style="background-color: #51bcda;">
            <th> Sr# </th>
            <th> Name </th>
            <th>Users</th>
            <th>Actions</th>
        </thead>
        <tbody>
            @if (count($policies))
                @foreach ($policies as $index => $res)
                    <tr>
                        <td> {{ $policies->firstItem() + $index }} </td>
                        <td>{{ $res->name }}</td>
                        <td>{{ $res->user_count }}</td>
                        <td>
                            @if (checkPermission(LATE_POLICY, WRITE))
                                <a href="{{ url(config('data.admin.late-policy.edit') . '/' . $res->id) }}" class="btn btn-primary text-light btn-round">{{ __('default_label.edit') }}</a>
                                <a href="{{ url(config('data.admin.late-policy.delete') . '/' . $res->id) }}" class="btn btn-danger text-light btn-round">{{ __('default_label.delete_label') }}</a>
                            @endif
                        </td>
                    </tr>
                @endforeach
            @else
                <tr>
                    <td colspan="4" class="text-center">No Policies found</td>
                </tr>
            @endif
        </tbody>

    </table>
    {{ $policies->links() }}
</div>
<hr>
<div class="row">
    <div class="col-md-6">
        <h4>Exception Dates</h4>
    </div>
    <div class="col-md-6" style="text-align: end;
    margin-top: 1rem;">
        <button type="button" data-toggle="modal" data-target="#ExceptionDatesModel" class="btn btn-success">Add <i class="nc-icon nc-simple-add"></i> </button>
    </div>
</div>
<div class="row">
    <div class="col-md-12 table-responsive">
        {{-- <div class="row">
            <div class="col-md-6">
                <form method="post" action="{{ url(config('data.admin.late-policy.update-dates')) }}" enctype="multipart/form-data">
                    @csrf
                    <div class="form-group">
                        <label for="date">Select Dates</label>
                        <input type="text" class="form-control" id="multi-date-picker" required name="exception_dates">
                    </div>
                    <div class="row">
                        <div class="update ml-auto mr-auto">
                            @if (checkPermission(LATE_POLICY, WRITE))
                                <button type="button" data-toggle="modal" data-target="#ExceptionDatesModel" class="btn btn-primary btn-round">{{ __('default_label.update_btn') }}</button>
                            @endif
                        </div>
                    </div>
                </form>
            </div>
        </div> --}}
        @if (count($exceptionDates) > 0)
            <table class="table  table-fixed  table-striped">
                <thead class=" text-white" style="background-color: #51bcda;">
                    <tr>

                        <td>Date</td>
                        <td>Action</td>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($exceptionDates as $key => $date)
                        <tr>
                            <td>{{ date('M, d Y, h:i a', strtotime($date->date)) }}</td>
                            <td>
                                @if (checkPermission(LATE_POLICY, WRITE))
                                    <a href="{{ url(config('data.admin.late-policy.delete-date') . '/' . $date->id) }}">
                                        <button class="btn btn-danger" style="border-radius:30px">Delete</button>
                                    </a>
                                @endif
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        @else
            <h4>No Dates added</h4>
        @endif
    </div>
</div>


<div class="modal fade uploadExcelModal" id="ExceptionDatesModel" tabindex="-1" role="dialog" aria-labelledby="uploadExcelModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="">Add Exception Dates</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col-md-12">
                        <form method="post" action="{{ url(config('data.admin.late-policy.update-dates')) }}" enctype="multipart/form-data">
                            @csrf
                            <div class="form-group">
                                <label for="date">Select Dates</label>
                                <input type="text" class="form-control" id="multi-date-picker" required name="exception_dates">
                            </div>
                            <div class="row">
                                <div class="update ml-auto mr-auto">
                                    @if (checkPermission(LATE_POLICY, WRITE))
                                        <button type="submit" class="btn btn-primary btn-round">Add</button>
                                    @endif
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@section('footer_js')
    @parent
    <script>
        $(document).ready(function() {
            $('.select2').select2({
                placeholder: 'Select Users',
                allowClear: true
            });
            $('#multi-date-picker').datetimepicker({
                autoclose: true,
                datesDisabled: "{{ implode(',', $exceptionDates->pluck('date')->toArray()) }}"
            });
            variactionAllow = true;
            var varitionHtml = `<div class="row variation-parent">
                                <div class="col-md-3 form-group">
                                    <label>Deduction starts from </label>
                                    <input name="variations[time][]" type="time" class="form-control" required  />
                                </div>
                                <div class="col-md-3 form-group">
                                    <label>Deduction Type </label>
                                    <select name="variations[type][]" class="form-control variation-type">
                                        <option value="0" selected >Per Minute</option>
                                        <option value="1" >Half Day</option>
                                    </select>
                                </div>
                                <div class="col-md-4 form-group variation-price-parent">
                                    <label>Deduction Per Minute (pkr) </label>
                                    <input name="variations[price][]" type="number" class="form-control" required min="1" max="100" step="1" />
                                </div>
                                <div class="col-md-2 form-group">
                                    <a href="#!" class="btn btn-danger variation-remove mt-4">Remove</a>
                                </div>
                            </div>`;
            $('.type').on('change', function() {
                var perMinute = $('.per-minute');
                var variationAdd = $('.variation-add');
                var varitions = $('.variations');
                var value = $(this).val();
                if (value == '0') {
                    perMinute.show();
                    varitions.show();
                    variactionAllow = true;
                    $('.variation-parent').each(function(index, element) {
                        var value = $(this).find('select').val();
                        if (value == '1') {
                            variactionAllow = false;
                        }
                    });
                    if (variactionAllow) {
                        variationAdd.show();
                    }
                    perMinute.attr('required', true);
                } else {
                    perMinute.hide();
                    varitions.hide();
                    variationAdd.hide();
                    perMinute.attr('required', false);
                }

            });
            $('.variation-add').on('click', function() {

                $('.variations').append(varitionHtml);

            });
            $('body').on('click', '.variation-remove', function() {
                $(this).parents('.variation-parent').remove();
                $('.variation-parent').each(function(index, element) {
                    var value = $(this).find('select').val();
                    if (value == '1') {
                        variactionAllow = false;
                    }
                });
                if (variactionAllow) {
                    $('.variation-add').show();
                }
            });
            $('body').on('change', '.variation-type', function() {
                if ($(this).val() == '1') {
                    $(this).parents('.variation-parent').find('.variation-price-parent').hide();
                    $(this).parents('.variation-parent').find('.variation-price-parent').find('input').attr('required', false);
                    $('.variation-add').hide();
                } else {
                    console.log($(this).parents('.variation-parent').find('.variation-price-parent'));
                    $(this).parents('.variation-parent').find('.variation-price-parent').show();
                    $(this).parents('.variation-parent').find('.variation-price-parent').find('input').attr('required', true);
                    $('.variation-add').show();
                }
            });
        });
    </script>
@endsection
