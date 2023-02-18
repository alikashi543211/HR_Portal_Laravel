@extends('employees.layouts.app')
@section('title')
    Leaves
@endsection


@section('css')
    <link href="{{ asset('employeesAsset/vendor/bootstrap-material-datetimepicker/css/bootstrap-material-datetimepicker.css') }}" rel="stylesheet" type="text/css" />
    <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet" type="text/css" />
    <style>
        .btn.btn-sm.orange {
            border-radius: 5px
        }

        .dtp div.dtp-date,
        .dtp div.dtp-time {
            background: #F47429 !important;
            color: #fff !important;
        }

        .dtp>.dtp-content>.dtp-date-view>header.dtp-header {
            background: #924619 !important;
            color: #fff !important;
        }

        .dtp table.dtp-picker-days tr>td>a.selected {
            background: #F47429;
            color: #fff;
        }

        .dtp .p10>a {
            color: #ffffff;
        }

        .files_row.d-flex {
            justify-content: flex-start;
            margin-top: 10px;
            width: fit-content;
            flex-wrap: wrap;
        }

        .files__list {
            padding: 0.5rem 1rem 0.5rem 1rem;
            background: #191919;
            margin-right: 1rem;
            text-align: center;
            border-radius: 1rem;
            color: #fff;
            position: relative;
            width: 12rem;
            display: flex;
            flex-basis: 12rem;
            margin-top: 0.5rem;
        }

        .files__list span {
            position: absolute;
            top: 10px;
            right: 15px;
            display: flex;
            border: 1px solid #fff;
            border-radius: 100%;
            width: 20px;
            height: 20px;
            justify-content: center;
            align-items: center;
            cursor: pointer
        }

        .files__list span:hover {
            background: #F47429;
            border: 1px solid #F47429;
        }

        .files__list a {
            color: #fff;
        }

        .files__list a:hover {
            color: #fff;
        }

        .datepicker.datepicker-dropdown td.day:hover,
        .datepicker.datepicker-dropdown th.next:hover,
        .datepicker.datepicker-dropdown th.prev:hover {
            box-shadow: none;
            color: #fff;
            background-color: black !important;
        }

        .gradient-9,
        .datepicker.datepicker-dropdown td.day:hover,
        .datepicker.datepicker-dropdown th.next:hover,
        .datepicker.datepicker-dropdown th.prev:hover,
        .datepicker table tr td.selected,
        .datepicker table tr td.active,
        .datepicker table tr td.today,
        .datepicker table tr td.today:hover,
        .datepicker table tr td.today.disabled,
        .datepicker table tr td.today.disabled:hover {
            box-shadow: none;
            color: #fff;
            background-color: black !important;
        }
    </style>
@endsection

@section('content')
    <div class="container-fluid">

        <!-- row -->

        <div class="row">
            <div class="col-lg-12">
                <div class="card">
                    <div class="card-header">
                        <h4 class="card-title">Add Request Leave</h4>

                    </div>
                    <div class="card-body">
                        <form id="requestForm" action="{{ route('employee.leaves.submit-leave-request') }}" method="POST" enctype="multipart/form-data">
                            @csrf
                            <div class="row">
                                <div class="form-group col-md-6">
                                    <label>Leave Type <span class="text-danger">*</span></label>
                                    <div class="form-group mb-0">
                                        <label class="radio-inline mr-3"><input type="radio" name="type" id="sick" value="{{ SICK_LEAVE }}" {{ old('type') == FULL_DAY ? 'Selected' : '' }} required> Sick</label>
                                        <label class="radio-inline mr-3"><input type="radio" name="type" id="casual" value="{{ CASUAL_LEAVE }}" {{ old('type') == FULL_DAY ? 'Selected' : '' }} required> Casual</label>
                                    </div>
                                </div>
                                <div class="form-group col-md-6">
                                    <label>Period Type <span class="text-danger">*</span></label>
                                    <div class="form-group mb-0">
                                        <label class="radio-inline mr-3"><input type="radio" name="period_type" value="{{ FULL_DAY }}" {{ old('period_type') == FULL_DAY ? 'Selected' : '' }} required> Full Day</label>
                                        <label class="radio-inline mr-3"><input type="radio" name="period_type" value="{{ FIRST_HALF }}" {{ old('period_type') == FIRST_HALF ? 'Selected' : '' }} required> First Half</label>
                                        <label class="radio-inline mr-3"><input type="radio" name="period_type" value="{{ SECOND_HALF }}" {{ old('period_type') == SECOND_HALF ? 'Selected' : '' }} required> Second Half</label>
                                    </div>
                                </div>
                            </div>
                            <div class="row" id="sick_dates">
                                <div class="form-group col-sm-6">
                                    <label>From Date <span class="text-danger">*</span></label>
                                    <input type="text" class="form-control from_leave_date" name="from_date" placeholder="Select Date" id="mdate_sick_1" value="{{ request('from_date') }}" data-dtp="dtp_EzHDQ" required>
                                    <h6 class="d-none text-danger date-alert">To Date should be greater than From Date</h6>
                                </div>
                                <div class="form-group col-sm-6">
                                    <label>To Date <span class="text-danger">*</span></label>
                                    <input type="text" class="form-control to_leave_date" name="to_date" placeholder="Select Date" id="mdate_sick_2" value="{{ request('to_date') }}" data-dtp="dtp_EzHDQ" required>
                                    <h6 class="d-none text-danger date-alert">To Date should be greater than From Date</h6>
                                </div>
                            </div>
                            <div class="row d-none" id="casual_dates">
                                <div class="form-group col-sm-6">
                                    <label>From Date <span class="text-danger">*</span></label>
                                    <input type="text" class="form-control from_casual_date" name="from_date" placeholder="Select Date" id="mdate_casual_1" value="{{ request('from_date') }}" data-dtp="dtp_EzHDQ">
                                    <h6 class="d-none text-danger date-alert">To Date should be greater than From Date</h6>
                                </div>
                                <div class="form-group col-sm-6">
                                    <label>To Date <span class="text-danger">*</span></label>
                                    <input type="text" class="form-control to_casual_date" name="to_date" placeholder="Select Date" id="mdate_casual_2" value="{{ request('to_date') }}" data-dtp="dtp_EzHDQ">
                                    <h6 class="d-none text-danger date-alert">To Date should be greater than From Date</h6>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-sm-6 d-none" id="uploadPrescription">
                                    <div class="mb-3">
                                        <label for="" class="form-label">Upload Prescriptions </label>
                                        <input class="form-control file_upload_input" type="file" name="attachments[]" multiple>
                                        <div class="files_row d-flex">
                                            {{-- <div class="files__list"><a href="{{ asset($file->file) }}">Document_{{ $key + 1 }}</a><span> x</span> --}}
                                        </div>
                                    </div>
                                </div>
                                <div class="form-group col-sm-12" id="textArea">
                                    <label>Reason <span class="text-danger">*</span></label>
                                    <textarea class="form-control" name="reason" rows="2" id="comment" required></textarea>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-sm-12 text-right">
                                    <a href="{{ route('employee.leaves.listing') }}" class="btn btn-rounded btn-sm  btn-outline-primary">Back</a>
                                    <button class="btn btn-rounded btn-sm btn-outline-primary" id="submitBtn">Submit Request</button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>

        </div>
    </div>
@endsection

@section('scripts')
    <script src="{{ asset('employeesAsset/vendor/bootstrap-material-datetimepicker/js/bootstrap-material-datetimepicker.js') }}" type="text/javascript"></script>
    <script src="{{ asset('employeesAsset/js/plugins-init/material-date-picker-init.js') }}" type="text/javascript"></script>
    <script>
        var date = new Date();
        let DefaultDay = new Date();




        // var currentDate = new Date();


        if ('{{ $nextDateCheck }}' == "enable") {
            dateCustom = new Date()
            DefaultDay.setTime(dateCustom.getTime() + 25 * 60 * 60 * 1000);
            // DefaultDay = DefaultDay.addHours(24);
        } else {
            dateCustom = new Date()
            DefaultDay.setTime(dateCustom.getTime() + 48 * 60 * 60 * 1000);
            // DefaultDay = DefaultDay.addHours(48);
        }

        function addHours(numOfHours, date = new Date()) {
            date.setTime(date.getTime() + numOfHours * 60 * 60 * 1000);
            return date;
        }

        function removeHours(numOfHours, date = new Date()) {
            date.setTime(date.getTime() - numOfHours * 60 * 60 * 1000);
            return date;
        }
        $('#requestForm').on('submit', function(e) {
            var startDate;
            var toDate;
            if (!$('#sick_dates').hasClass('d-none')) {
                startDate = new Date($('.from_leave_date').val());
                toDate = new Date($('.to_leave_date').val());
            } else {
                startDate = new Date($('.from_casual_date').val());
                toDate = new Date($('.to_casual_date').val());
            }
            if (toDate < startDate) {
                $('.date-alert').removeClass('d-none')
            } else {
                $('.date-alert').addClass('d-none')
                return;
            }
            e.preventDefault();
            $(this).submit();

        })

        $('#mdate_sick_1').bootstrapMaterialDatePicker({
            weekStart: 0,
            time: false,
            clearButton: true,
            autoclose: true,
        }).on('dateSelected', function(ev) {});

        $('#mdate_sick_2').bootstrapMaterialDatePicker({
            weekStart: 0,
            time: false,
            clearButton: true,
            autoclose: true,

        });
        $('#mdate_casual_1').bootstrapMaterialDatePicker({
            weekStart: 0,
            time: false,
            minDate: DefaultDay,
            clearButton: true
        });

        $('#mdate_casual_2').bootstrapMaterialDatePicker({
            weekStart: 0,
            time: false,
            minDate: DefaultDay,
            clearButton: true

        });

        $('#sick').on('click', function() {
            $('#casual_dates').addClass('d-none');
            $('.date-alert').addClass('d-none')
            $('#casual_dates').find('input').attr('required', false);
            $('#casual_dates').find('input').attr('disabled', true);
            $('#sick_dates').removeClass('d-none');
            $('#sick_dates').find('input').attr('required', true);
            $('#sick_dates').find('input').attr('disabled', false);

            $('#uploadPrescription').removeClass('d-none');
            $('#textArea').removeClass('col-sm-12');
            $('#textArea').addClass('col-sm-6');

        })
        $('#casual').on('click', function() {
            $('#casual_dates').removeClass('d-none');
            $('.date-alert').addClass('d-none')
            $('#casual_dates').find('input').attr('required', true);
            $('#casual_dates').find('input').attr('disabled', false);
            $('#sick_dates').addClass('d-none');
            $('#sick_dates').find('input').attr('required', false);
            $('#sick_dates').find('input').attr('disabled', true);

            $('#uploadPrescription').addClass('d-none');
            $('#textArea').addClass('col-sm-12');
            $('#textArea').removeClass('col-sm-6');

        })


        // files related code

        let mainDt = new DataTransfer();

        $(document).on('change', '.file_upload_input', function() {
            $html = '';
            $.each($(this)[0].files, (key, element) => {
                mainDt.items.add($(this)[0].files[key]);
            })
            $.each(mainDt.files, (key, element) => {
                $url = window.URL.createObjectURL(element);
                $html = $html +
                    `
                <div class="files__list">
                    <a href="${$url}" target="_blank">Document_${ key + 1 }</a><span class="remove_file" data-index='${key}'> x</span>
                </div>
                `
            })
            $(this)[0].files = mainDt.files
            $('.files_row.d-flex').html($html);
        })

        $(document).on('click', '.remove_file', function() {
            console.log($(this).attr('data-index'));
            removeFileFromFileList($(this).attr('data-index'));
            $(this).closest('.files__list').remove();
        })

        function removeFileFromFileList(index) {
            $html = '';
            const dt = new DataTransfer()
            const input = document.querySelector('.form-control.file_upload_input')
            const {
                files
            } = input
            console.log(files);
            for (let i = 0; i < files.length; i++) {
                const file = files[i];
                if (index != i) {
                    dt.items.add(file) // here you exclude the file. thus removing it.
                }
            }
            // console.log(dt.files);
            // console.log(mainDt.files);

            input.files = dt.files // Assign the updates list
            mainDt = new DataTransfer(); // Assign the updates list
            mainDt.files = dt.files // Assign the updates list
            $.each(dt.files, (key, element) => {
                mainDt.items.add(element);
            })

            $.each(mainDt.files, (key, element) => {
                $url = window.URL.createObjectURL(element);
                $html = $html +
                    `
                <div class="files__list">
                    <a href="${$url}" target="_blank">Document_${ key + 1 }</a><span class="remove_file" data-index='${key}'> x</span>
                </div>
                `
            })
            $('.files_row.d-flex').html($html);
            // console.log(dt.files);
            // console.log(mainDt.files);

        }
    </script>
@endsection
