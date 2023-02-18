@php
$latePolicy = $data['latePolicy'];
$policyUsers = $data['policyUsers'];
$allUsers = $data['allUsers'];
@endphp
<div class="row">
    <div class="col-md-8 offset-2">

        <form method="post" action="{{url(config('data.admin.late-policy.update'))}}" enctype="multipart/form-data">
            @csrf
            <input type="hidden" name="id" value="{{ $latePolicy->id }}">
            <div class="row">
                <div class="col-md-12 form-group">
                    <label>Name</label>
                    <input readonly name="name" type="text" class="form-control" required value="{{ $latePolicy->name }}" />
                </div>
            </div>
            <div class="row">
                <div class="col-md-6 form-group">
                    <label>Start Time</label>
                    <input readonly name="start_time" type="time" class="form-control" required value="{{$latePolicy->start_time}}" />
                </div>
                <div class="col-md-6 form-group">
                    <label>End Time</label>
                    <input readonly name="end_time" type="time" class="form-control" required value="{{$latePolicy->end_time}}" />
                </div>
            </div>
            <div class="row">
                <div class="col-md-6 form-group">
                    <label>Deduction starts from</label>
                    <input readonly name="relax_time" type="time" class="form-control" required value="{{$latePolicy->relax_time}}" />
                </div>
                <div class="col-md-6 form-group">
                    <label>Applicable Users</label>
                    <select name="exception_users[]" class="form-control select2" multiple="multiple">
                        @foreach ($allUsers as $user)
                        <option value="{{ $user->id }}" {{ in_array($user->id,$policyUsers) ? 'selected' : '' }}>{{ $user->first_name . ' ' . $user->last_name }}</option>
                        @endforeach
                    </select>
                </div>
            </div>
            <div class="row ">
                <div class="col-md-6 form-group">
                    <label>Deduction Type</label>
                    <select readonly name="type" class="form-control type" id="">
                        <option value="0" @if($latePolicy->type == '0') selected @else disabled @endif >Per Minute</option>
                        <option value="1" @if($latePolicy->type == '1') selected @else disabled @endif >Half Day</option>
                    </select>
                </div>
                <div class="col-md-6 form-group per-minute" style="display: {{$latePolicy->type == '1' ? 'none' : 'block'}}">
                    <label>Deduction Per Minute (pkr)</label>
                    <input readonly name="per_minute" type="number" class="form-control" @if($latePolicy->type == '0') required @endif value="{{$latePolicy->per_minute}}" min="1" max="100" step="1" />
                </div>
                {{-- <div class="col-md-6 form-group variation-add" style="display: {{$latePolicy->type == '1' || $latePolicy->add_variation_allow == false ? 'none' : 'block'}}">
                <a href="#!" class="btn btn-success  mt-4">Add Per Minute Variations</a>
            </div> --}}
    </div>
    <div class="row">

    </div>
    <div class="variations">
        @if(count($latePolicy->variations))
        @foreach($latePolicy->variations AS $key => $variation)
        <div class="row variation-parent">
            <div class="col-md-3 form-group">
                <label>Deduction starts from </label>
                <input readonly name="variations[time][]" type="time" value="{{$variation->time}}" class="form-control" required />
            </div>
            <div class="col-md-3 form-group">
                <label>Deduction Type </label>
                <select readonly name="variations[type][]" class="form-control variation-type">
                    <option value="0" @if($variation->type == '0') selected @else disabled @endif >Per Minute</option>
                    <option value="1" @if($variation->type == '1') selected @else disabled @endif >Half Day</option>
                </select>
            </div>
            @if($variation->type == '0')
            <div class="col-md-4 form-group variation-price-parent">
                <label>Deduction Per Minute (pkr) </label>
                <input readonly name="variations[price][]" type="number" value="{{$variation->price}}" required class="form-control" min="1" max="100" step="1" />
            </div>
            @endif
            {{-- <div class="col-md-2 form-group">
                        <a href="#!" class="btn btn-danger variation-remove mt-4">Remove</a>
                    </div> --}}
        </div>
        @endforeach
        @endif
    </div>
    <div class="row">
        <div class="update ml-auto mr-auto">
            @if(checkPermission(LATE_POLICY, WRITE))
            <button type="submit" class="btn btn-primary btn-round">{{__('default_label.update_btn')}}</button>
            @endif
        </div>
    </div>
    </form>
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
            if ($(".variation-parent").last().find(".variation-type").val() == 1) {
                $(".variation-add").addClass('d-none');
                return;
            } else {
                $(".variation-add").removeClass('d-none');
            }
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
            if ($(".variation-parent").last().find(".variation-type").val() == 1) {
                $(".variation-add").addClass('d-none');
                return;
            } else {
                $(".variation-add").removeClass('d-none');
            }
            console.log($(this).val());
            if ($(this).val() == '1') {
                $(this).parents('.variation-parent').find('.variation-price-parent').hide();
                $(this).parents('.variation-parent').find('.variation-price-parent').find('input').attr('required', false);
                $('.variation-add').hide();
            } else {
                $(this).parents('.variation-parent').find('.variation-price-parent').show();
                $(this).parents('.variation-parent').find('.variation-price-parent').find('input').attr('required', true);
                $('.variation-add').show();
            }
        });
    });
</script>
@endsection