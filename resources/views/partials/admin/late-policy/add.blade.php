@php
$allUsers = $data['allUsers'];
@endphp
<div class="row">
    <div class="col-md-8 offset-2">
        <form method="post" action="{{url(config('data.admin.late-policy.save'))}}" enctype="multipart/form-data">
            @csrf
            <div class="row">
                <div class="col-md-12 form-group">
                    <label>Name</label>
                    <input name="name" type="text" class="form-control" required value="{{ old('name') }}" />
                </div>
            </div>
            <div class="row">
                <div class="col-md-6 form-group">
                    <label>Start Time</label>
                    <input name="start_time" type="time" class="form-control" required value="{{ old('start_time') }}" />
                </div>
                <div class="col-md-6 form-group">
                    <label>End Time</label>
                    <input name="end_time" type="time" class="form-control" required value="{{ old('end_time') }}" />
                </div>
            </div>
            <div class="row">
                <div class="col-md-6 form-group">
                    <label>Deduction starts from</label>
                    <input name="relax_time" type="time" class="form-control" required value="{{ old('relax_time') }}" />
                </div>
                <div class="col-md-6 form-group">
                    <label>Applicable Users</label>
                    <select name="exception_users[]" class="form-control select2" multiple="multiple">
                        @foreach ($allUsers as $user)
                        <option value="{{ $user->id }}" @if(!empty(old('exception_users')) && in_array($user->id,old('exception_users'))) selected @endif>{{ $user->first_name . ' ' . $user->last_name }}</option>
                        @endforeach
                    </select>
                </div>
            </div>
            <div class="row ">
                <div class="col-md-6 form-group">
                    <label>Deduction Type</label>
                    <select name="type" class="form-control type" id="">
                        <option value="0" @if(old('type')==0) selected @endif>Per Minute</option>
                        <option value="1" @if(old('type')==1) selected @endif>Half Day</option>
                    </select>
                </div>
                <div class="col-md-6 form-group per-minute" style="display: {{old('type') == '1' ? 'none' : 'block'}}">
                    <label>Deduction Per Minute (pkr)</label>
                    <input name="per_minute" type="number" class="form-control" min="1" max="100" step="1" value="{{ old('per_minute') }}" />
                </div>
                <div class="col-md-6 form-group variation-add">
                    <a href="#!" class="btn btn-success  mt-4">Add Per Minute Variations</a>
                </div>
            </div>
            <div class="variations">
                @if(!empty(old('variations')) && count(old('variations')))
                @php
                $variations = old('variations');
                // dd($variations);
                @endphp
                @foreach($variations['time'] AS $key => $variation)
                <div class="row variation-parent">
                    <div class="col-md-3 form-group">
                        <label>Deduction starts from </label>
                        <input name="variations[time][]" type="time" value="{{$variations['time'][$key]}}" class="form-control" required />
                    </div>
                    <div class="col-md-3 form-group">
                        <label>Deduction Type </label>
                        <select name="variations[type][]" class="form-control variation-type">
                            <option value="0" @if($variations['type'][$key]=='0' ) selected @endif>Per Minute</option>
                            <option value="1" @if($variations['type'][$key]=='1' ) selected @endif>Half Day</option>
                        </select>
                    </div>
                    <div class="col-md-4 form-group variation-price-parent" @if($variations['type'][$key]=='1' ) style="display:none" @endif>
                        <label>Deduction Per Minute (pkr) </label>
                        <input name="variations[price][]" type="number" value="{{$variations['price'][$key]}}" class="form-control" @if($variations['type'][$key]=='0' ) required @endif min="1" max="100" step="1" />
                    </div>
                    <div class="col-md-2 form-group ">
                        <a href="#!" class="btn btn-danger variation-remove mt-4">Remove</a>
                    </div>
                </div>
                @endforeach
                @endif
            </div>
            <div class="row">
                <div class="update ml-auto mr-auto">
                    @if(checkPermission(LATE_POLICY, WRITE))
                    <button type="submit" class="btn btn-primary btn-round">{{__('default_label.add')}}</button>
                    @endif
                </div>
            </div>
        </form>
    </div>
</div>
<hr>
@section('footer_js')
@parent
<script>
    $(document).ready(function () {
        $('.select2').select2({
            placeholder: 'Select Users',
            allowClear: true
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
        $('.type').on('change', function () {
            var perMinute = $('.per-minute');
            var variationAdd = $('.variation-add');
            var varitions = $('.variations');
            var value = $(this).val();
            if (value == '0') {
                perMinute.show();
                varitions.show();
                variactionAllow = true;
                $('.variation-parent').each(function (index, element) {
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
        $('.variation-add').on('click', function () {

            $('.variations').append(varitionHtml);

        });
        $('body').on('click', '.variation-remove', function () {
            $(this).parents('.variation-parent').remove();
            $('.variation-parent').each(function (index, element) {
                var value = $(this).find('select').val();
                if (value == '1') {
                    variactionAllow = false;
                }
            });
            if (variactionAllow) {
                $('.variation-add').show();
            }
        });
        $('body').on('change', '.variation-type', function () {
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
