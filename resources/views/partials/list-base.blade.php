@extends('master')
​
@section('title', isset($page_title) ? $page_title : '')
​
@section('page_content')
    @php
        $original_path = Request::path();
        $path = str_replace('listing', 'add', Request::path());
        if (strpos($path, 'admin/pay-rolls/detail') !== false) {
            $path = str_replace('admin/pay-rolls/detail', 'admin/pay-rolls/detail/add', $path);
        }
        $permissionId = App\Permission::whereCode(Request::segment(2))->first()->id;
    @endphp
    ​
    @if (checkPermission($permissionId, READ))
        <div class="row">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header">
                        <div class="row">
                            <div class="{{ config('data.admin.inventories.listing') == Route::getCurrentRoute()->uri ? 'col-md-6' : 'col-md-8' }}">
                                <h4 class="card-title"> {{ isset($page_title) ? $page_title : '' }}
                                    @if (strpos(Request::path(), 'pay-rolls/detail/') !== false)
                                        @if (count($data))
                                            ({{ date('F, Y', strtotime($data[0]->payRoll->date)) }})
                                        @endif
                                    @endif
                                </h4>
                            </div>
                            @if (config('data.admin.inventories.listing') == Route::getCurrentRoute()->uri)
                                {{-- <div class="col-md-3 form-group ">
                                    <select name="user_id" class="form-control select2 ">
                                        <option value="">Search User</option>
                                        @foreach (\App\User::all() as $user)
                                            <option value="{{ $user->id }}" @if (old('user_id') == $user->id) selected @endif>{{ $user->first_name . ' ' . $user->last_name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="col-md-3 form-group ">
                                    <select name="user_id" class="form-control">
                                        <option value="">Select Status</option>
                                        <option value="{{ ASSIGNED }}">{{ ASSIGNED }}</option>
                                        <option value="{{ UNASSIGNED }}">{{ UNASSIGNED }}</option>
                                    </select>
                                </div> --}}
                                <div class="col-md-6">
                                    <div class="text-right">
                                        @if ($search)
                                            <form action="" method="GET" id="inventory" class="d-flex d-flex-form">
                                                <div class="form-group form-group-custom">
                                                    <select name="status" class="form-control status">
                                                        <option value="">Select Status</option>
                                                        <option value="{{ ASSIGNED }}" {{ app('request')->input('status') == ASSIGNED ? 'Selected' : '' }}>{{ ASSIGNED }}</option>
                                                        <option value="{{ UNASSIGNED }}" {{ app('request')->input('status') == UNASSIGNED ? 'Selected' : '' }}>{{ UNASSIGNED }}</option>
                                                    </select>
                                                </div>
                                                <div class="form-group form-group-custom">
                                                    <select name="user_id" class="form-control select2 user_id">
                                                        <option value="">Search User</option>
                                                        @foreach (\App\User::all() as $user)
                                                            <option value="{{ $user->id }}" @if (app('request')->input('user_id') == $user->id) selected @endif>{{ $user->first_name . ' ' . $user->last_name }}</option>
                                                        @endforeach
                                                    </select>
                                                </div>
                                                <div class="input-group no-border my-custom">
                                                    <input type="text" name="search" class="form-control submit_search" placeholder="Search..." value="{{ app('request')->input('search') }}">
                                                    <div class="input-group-append">
                                                        <div class="input-group-text">
                                                            <i class="nc-icon nc-zoom-split"></i>
                                                        </div>
                                                    </div>
                                                </div>

                                            </form>
                                            <a href="{{ URL($original_path) }}">
                                                <button type="button" class="btn btn-info">{{ __('default_label.clear_filter') }} </button>
                                            </a>
                                            @if ($add && checkPermission($permissionId, WRITE))
                                                <a href="{{ URL($path) }}">
                                                    <button type="button" class="btn btn-success">{{ __('default_label.add') }} <i class="nc-icon nc-simple-add"></i> </button>
                                                </a>
                                            @endif
                                        @endif
                                    </div>
                                </div>
                            @else
                                <div class="col-md-4">
                                    <div class="text-right">
                                        @if ($search)
                                            <form action="" method="GET">
                                                <div class="input-group no-border">
                                                    <input type="text" name="search" class="form-control submit_search" placeholder="Search..." value="{{ app('request')->input('search') }}">
                                                    @if (Request::segment(2) == 'attendances')
                                                        <input type="hidden" name="date" value="{{ request('date') }}">
                                                    @endif
                                                    <div class="input-group-append">
                                                        <div class="input-group-text">
                                                            <i class="nc-icon nc-zoom-split"></i>
                                                        </div>
                                                    </div>
                                                </div>
                                            </form>
                                            <a href="{{ URL($original_path) }}">
                                                <button type="button" class="btn btn-info">{{ __('default_label.clear_filter') }} </button>
                                            </a>
                                        @endif
                                        @if ($add && Request::segment(2) == 'users' && Request::segment(3) != 'notifications' && checkPermission($permissionId, WRITE))
                                            <a href="{{ url('admin/users/join-mail-to-all') }}">
                                                <button type="button" class="btn btn-info">{{ __('default_label.send_join_mail_all') }} <i class="nc-icon nc-simple-send"></i> </button>
                                            </a>
                                        @endif
                                        @if (checkPermission($permissionId, WRITE) && Request::path() == 'admin/leaves/listing')
                                            <a href="{{ url('admin/leaves/summary') }}">
                                                <button type="button" class="btn btn-primary">{{ __('default_label.leave_summary') }} <i class="nc-icon nc-simple-send"></i> </button>
                                            </a>
                                        @endif
                                        @if (checkPermission($permissionId, WRITE) && Request::path() == 'admin/pay-rolls/listing')
                                            <a href="{{ url('admin/pay-rolls/summary') }}">
                                                <button type="button" class="btn btn-primary">{{ __('payroll.summary_page_heading') }} <i class="nc-icon nc-simple-send"></i> </button>
                                            </a>
                                        @endif
                                        @if ($add && checkPermission($permissionId, WRITE) && Request::segment(3) != 'notifications')
                                            <a href="{{ URL($path) }}">
                                                <button type="button" class="btn btn-success">{{ __('default_label.add') }} <i class="nc-icon nc-simple-add"></i> </button>
                                            </a>
                                            @if (Request::segment(2) == 'attendances')
                                                <div class="d-flex justify-content-end">
                                                    <form method="post" id="upload-excel-sheet-form" action="{{ url(config('data.admin.attendance.excel-impot')) }}" enctype="multipart/form-data">
                                                        @csrf
                                                        <input name="excel" style="display: none" type="file" accept=".csv" required />
                                                        <button class="btn btn-secondary " id="upload-excel-sheet-button">{{ __('default_label.excel_button_import') }} <i class="nc-icon nc-simple-add"></i> </button>
                                                    </form>
                                                    <a href="{{ url(config('data.admin.a    ttendance.excel-export') . '?' . http_build_query(Request::query())) }}">
                                                        <button class="btn btn-secondary">{{ __('default_label.excel_button_export') }} <i class="nc-icon nc-cloud-download-93"></i> </button>
                                                    </a>
                                                </div>
                                            @endif
                                        @endif
                                    </div>
                                </div>
                            @endif
                        </div>
                    </div>
                    <div class="card-body">
                        @if (!empty($data) && count($data) > 0)
                            @component('partials.' . $partials, ['data' => isset($data) && !empty($data) ? $data : ''])
                            @endcomponent
                        @else
                            <center> {{ __('default_label.no_record_found') }} </center>
                        @endif

                    </div>
                </div>
            </div>
        </div>
    @else
        <div class="row">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-body">
                        <center> {{ __('default_label.permission_denied') }} </center>
                    </div>
                </div>
            </div>
        </div>
    @endif
    ​
    @component('partials.confirmation-popup')
    @endcomponent
    ​
@endsection
​
@section('footer_js')
    @parent
    <script>
        $(document).ready(function() {
            $('.submit_search').keyup(function(event) {
                if (event.keyCode == 13) {
                    this.form.submit();
                    return false;
                }
            });
            $(".status").on('change', function() {
                $('#inventory').submit();
            })
            $(".user_id").on('change', function() {
                $('#inventory').submit();
            })
        });
    </script>
@endsection
