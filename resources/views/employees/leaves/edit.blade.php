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

        i.material-icons {
            color: #fff;
        }

        .dtp table.dtp-picker-days tr>td>a.selected {
            background: #f47429;
            color: #fff;
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
    </style>
@endsection

@section('content')
    <div class="container-fluid">

        <!-- row -->

        <div class="row">
            <div class="col-lg-12">
                <div class="card">
                    <div class="card-header">
                        <h4 class="card-title">Request Leave Detail</h4>
                        <a href="{{ route('employee.leaves.listing') }}" class="btn btn-sm orange">Back</a>
                    </div>
                    <div class="card-body">
                        <form id="requestForm" action="{{ route('employee.leaves.update') }}" method="POST" enctype="multipart/form-data">
                            @csrf
                            <input type="text" name="id" value="{{ $leaveRequest->id }}" hidden>
                            <div class="row">
                                <div class="form-group col-md-6">
                                    <label>Leave Type <span class="text-danger">*</span></label>
                                    <div class="form-group mb-0">
                                        <label class="radio-inline mr-3"><input type="radio" name="type" id="sick" value="{{ SICK_LEAVE }}" {{ $leaveRequest->type == SICK_LEAVE ? 'checked' : '' }}> Sick</label>
                                        <label class="radio-inline mr-3"><input type="radio" name="type" id="casual" value="{{ CASUAL_LEAVE }}" {{ $leaveRequest->type == CASUAL_LEAVE ? 'checked' : '' }}> Casual</label>
                                    </div>
                                </div>

                                <div class="form-group col-md-6">
                                    <label>Period Type <span class="text-danger">*</span></label>
                                    <div class="form-group mb-0">
                                        <label class="radio-inline mr-3"><input type="radio" name="period_type" value="{{ FULL_DAY }}" {{ $leaveRequest->period_type == FULL_DAY ? 'checked' : '' }}> Full Day</label>
                                        <label class="radio-inline mr-3"><input type="radio" name="period_type" value="{{ FIRST_HALF }}" {{ $leaveRequest->period_type == FIRST_HALF ? 'checked' : '' }}> First Half</label>
                                        <label class="radio-inline mr-3"><input type="radio" name="period_type" value="{{ SECOND_HALF }}" {{ $leaveRequest->period_type == SECOND_HALF ? 'checked' : '' }}> Second Half</label>
                                    </div>
                                </div>
                            </div>
                            <div class="row {{ $leaveRequest->type == SICK_LEAVE ? '' : 'd-none' }}" id="sick_dates">
                                <div class="form-group col-sm-6">
                                    <label>From Date <span class="text-danger">*</span></label>
                                    <input type="text" class="form-control from_leave_date" name="from_date" placeholder="Select Date" value="{{ $leaveRequest->from_date }}" id="mdate_sick_1" data-dtp="dtp_EzHDQ" required>
                                    <h6 class="d-none text-danger date-alert">To Date should be greater than From Date</h6>
                                </div>
                                <div class="form-group col-sm-6">
                                    <label>To Date <span class="text-danger">*</span></label>
                                    <input type="text" class="form-control to_leave_date" name="to_date" placeholder="Select Date" value="{{ $leaveRequest->to_date }}" id="mdate_sick_2" data-dtp="dtp_EzHDQ" required>
                                    <h6 class="d-none text-danger date-alert">To Date should be greater than From Date</h6>
                                </div>
                            </div>
                            <div class="row  {{ $leaveRequest->type == CASUAL_LEAVE ? '' : 'd-none' }}" id="casual_dates">
                                <div class="form-group col-sm-6">
                                    <label>From Date <span class="text-danger">*</span></label>
                                    <input type="text" class="form-control from_casual_date" name="from_date" placeholder="Select Date" value="{{ $leaveRequest->from_date }}" id="mdate_casual_1" data-dtp="dtp_EzHDQ">
                                    <h6 class="d-none text-danger date-alert">To Date should be greater than From Date</h6>
                                </div>
                                <div class="form-group col-sm-6">
                                    <label>To Date <span class="text-danger">*</span></label>
                                    <input type="text" class="form-control to_casual_date" name="to_date" placeholder="Select Date" value="{{ $leaveRequest->to_date }}" id="mdate_casual_2" data-dtp="dtp_EzHDQ">
                                    <h6 class="d-none text-danger date-alert">To Date should be greater than From Date</h6>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-sm-6 {{ $leaveRequest->type == SICK_LEAVE ? '' : 'd-none' }}" id="uploadPrescription">
                                    <div class="mb-3">
                                        <label for="" class="form-label">Upload Prescriptions</label>
                                        <input class="form-control file_upload_input" type="file" name="attachments[]" multiple>
                                        <div class="files_row d-flex old">
                                            @foreach ($leaveRequest->leaveRequestAttachments as $key => $file)
                                                <div class="files__list"><a href="{{ asset($file->file) }}" target="_blank">Document_{{ $key + 1 }}</a><span class="remove_file_exist"> x</span>
                                                    <input type="text" name="files_exits[]" value="{{ $file->id }}" hidden>
                                                </div>
                                            @endforeach
                                        </div>
                                        <div class="files_row d-flex" id="newUpload">

                                        </div>
                                    </div>
                                </div>
                                <div class="form-group {{ $leaveRequest->type == CASUAL_LEAVE ? 'col-sm-12' : 'col-sm-6' }} " id="textArea">
                                    <label>Reason <span class="text-danger">*</span></label>
                                    <textarea class="form-control" name="reason" rows="2" id="comment" required>{{ $leaveRequest->reason }}</textarea>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-sm-12 text-right">
                                    <a href="{{ route('employee.leaves.listing') }}" class="btn btn-rounded btn-sm  btn-outline-primary">Back</a>
                                    <button class="btn btn-rounded btn-sm btn-outline-primary">Update Request</button>
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
        date.setDate(date.getDate() - 1);
        $('#mdate_sick_1').bootstrapMaterialDatePicker({
            weekStart: 0,
            time: false,
            clearButton: true
        });

        $('#mdate_sick_2').bootstrapMaterialDatePicker({
            weekStart: 0,
            time: false,
            clearButton: true

        });
        $('#mdate_casual_1').bootstrapMaterialDatePicker({
            weekStart: 0,
            time: false,
            minDate: new Date(),
            clearButton: true
        });

        $('#mdate_casual_2').bootstrapMaterialDatePicker({
            weekStart: 0,
            time: false,
            minDate: date,
            clearButton: true

        });

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


        $('#sick').on('click', function() {
            $('#casual_dates').addClass('d-none');
            $('.date-alert').removeClass('d-none');
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

        $('.remove_file_exist').on('click', function() {
            $(this).closest('.files__list').remove();
        })

        $(document).on('change', '.file_upload_input', function() {
            $html = '';
            let exisitedFilesLen = $('.files_row.d-flex.old .files__list').length;
            $.each($(this)[0].files, (key, element) => {
                mainDt.items.add($(this)[0].files[key]);
            })
            $.each(mainDt.files, (key, element) => {
                $url = window.URL.createObjectURL(element);
                $html = $html +
                    `
                <div class="files__list">
                    <a href="${$url}" target="_blank" >Document_${ key + 1 + exisitedFilesLen }</a><span class="remove_file" data-index='${key}'> x</span>
                </div>
                `
            })
            $(this)[0].files = mainDt.files
            $('#newUpload').html($html);
        })

        $(document).on('click', '.remove_file', function() {
            removeFileFromFileList($(this).attr('data-index'));
            $(this).closest('.files__list').remove();
        })

        function removeFileFromFileList(index) {
            let dt = new DataTransfer()
            $html = '';
            let exisitedFilesLen = $('.files_row.d-flex.old .files__list').length;
            const input = document.querySelector('.form-control.file_upload_input')
            const {
                files
            } = input
            for (let i = 0; i < files.length; i++) {
                const file = files[i]
                if (index != i) {
                    dt.items.add(file) // here you exclude the file. thus removing it.
                }
            }
            input.files = dt.files // Assign the updates list
            mainDt = new DataTransfer(); // Assign the updates list
            mainDt.files = dt.files;
            $.each(dt.files, (key, element) => {
                mainDt.items.add(element);
            })
            $.each(mainDt.files, (key, element) => {
                $url = window.URL.createObjectURL(element);
                $html = $html +
                    `
                <div class="files__list">
                    <a href="${$url}" target="_blank">Document_${ key + 1 + exisitedFilesLen }</a><span class="remove_file" data-index='${key}'> x</span>
                </div>
                `
            })
            $('#newUpload').html($html);
        }
    </script>
@endsection
