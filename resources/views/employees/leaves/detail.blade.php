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

        /* .files__list span:hover {
                    background: #F47429;
                    border: 1px solid #F47429;
                } */

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
                    </div>
                    <div class="card-body">
                        <form action="{{ route('employee.leaves.submit-leave-request') }}" method="POST">
                            @csrf
                            <div class="row">
                                <div class="form-group col-md-4">
                                    <label>Leave Type <span class="text-danger">*</span></label>
                                    <div class="form-group mb-0">
                                        <label class="radio-inline mr-3"><input type="radio" name="type" value="{{ SICK_LEAVE }}" {{ $leaveRequest->type == SICK_LEAVE ? 'checked' : '' }} disabled> Sick</label>
                                        <label class="radio-inline mr-3"><input type="radio" name="type" value="{{ CASUAL_LEAVE }}" {{ $leaveRequest->type == CASUAL_LEAVE ? 'checked' : '' }} disabled> Casual</label>
                                    </div>
                                </div>
                                <div class="form-group col-md-4">
                                    <label>Period <span class="text-danger">*</span></label>
                                    <div class="form-group mb-0">
                                        <label class="radio-inline mr-3"><input type="radio" name="period" value="{{ HALF_DAY_LEAVE }}" {{ $leaveRequest->period == HALF_DAY_LEAVE ? 'checked' : '' }} disabled> Full Day Leave</label>
                                        <label class="radio-inline mr-3"><input type="radio" name="period" value="{{ FULL_DAY_LEAVE }}" {{ $leaveRequest->period == FULL_DAY_LEAVE ? 'checked' : '' }} disabled> Half Day Leave</label>
                                    </div>
                                </div>
                                <div class="form-group col-md-4">
                                    <label>Period Type <span class="text-danger">*</span></label>
                                    <div class="form-group mb-0">
                                        <label class="radio-inline mr-3"><input type="radio" name="period_type" value="{{ FULL_DAY }}" {{ $leaveRequest->period_type == FULL_DAY ? 'checked' : '' }} disabled> Full Day</label>
                                        <label class="radio-inline mr-3"><input type="radio" name="period_type" value="{{ FIRST_HALF }}" {{ $leaveRequest->period_type == FIRST_HALF ? 'checked' : '' }} disabled> First Half</label>
                                        <label class="radio-inline mr-3"><input type="radio" name="period_type" value="{{ SECOND_HALF }}" {{ $leaveRequest->period_type == SECOND_HALF ? 'checked' : '' }} disabled> Second Half</label>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="form-group col-sm-6">
                                    <label>From Date <span class="text-danger">*</span></label>
                                    <input type="text" class="form-control" name="from_date" placeholder="Select Date" id="mdate1" value="{{ $leaveRequest->from_date }}" data-dtp="dtp_EzHDQ" readonly disabled>
                                </div>
                                <div class="form-group col-sm-6">
                                    <label>To Date <span class="text-danger">*</span></label>
                                    <input type="text" class="form-control" name="to_date" placeholder="Select Date" id="mdate2" value="{{ $leaveRequest->to_date }}" data-dtp="dtp_EzHDQ" readonly disabled>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-sm-6 {{ $leaveRequest->type == SICK_LEAVE ? '' : 'd-none' }}" id="uploadPrescription">
                                    <div class="mb-3">
                                        <label for="" class="form-label">Upload Prescriptions</label>
                                        <input class="form-control file_upload_input" type="file" name="attachments[]" multiple disabled>
                                        <div class="files_row d-flex">
                                            @foreach ($leaveRequest->leaveRequestAttachments as $key => $file)
                                                <div class="files__list"><a href="{{ asset($file->file) }}" target="_blank">Document_{{ $key + 1 }}</a><span class="remove_file_exist"> x</span>
                                                    <input type="text" name="files_exits[]" value="{{ $file->id }}" hidden>
                                                </div>
                                            @endforeach
                                        </div>
                                    </div>
                                </div>
                                <div class="form-group {{ $leaveRequest->type == CASUAL_LEAVE ? 'col-sm-12' : 'col-sm-6' }} " id="textArea">
                                    <label>Reason <span class="text-danger">*</span></label>
                                    <textarea class="form-control" name="reason" rows="2" id="comment" disabled>{{ $leaveRequest->reason }}</textarea>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-sm-12 text-right">
                                    <a href="{{ route('employee.leaves.listing') }}" class="btn btn-rounded btn-sm  btn-outline-primary">Back</a>
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

        $('#mdate1').bootstrapMaterialDatePicker({
            weekStart: 0,
            time: false,
            minDate: new Date(),
            clearButton: true
        });

        $('#mdate2').bootstrapMaterialDatePicker({
            weekStart: 0,
            time: false,
            minDate: date,
            clearButton: true

        });
    </script>
@endsection
