<div class="modal fade attendanceDetail" id="attendanceDetail" tabindex="-1" role="dialog" aria-labelledby="attendanceDetailLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="confirmationModalLabel">Attendance Details
                </h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col-md-3 col-3"></div>
                    <div class="col-md-6 col-6 ">
                        <label for="">Date: <span id="showDate">20/3/2020</span></label>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-3 col-3">
                    </div>
                    <div class="col-md-6 col-6 ">
                        <div class="checkInOut checkin-display">
                            <p>Check In At</p>
                            <p id="checkInDateTime"></p>
                        </div>
                        <div class="checkInOut checkin-input">
                            <input type="time" name="checkin_action_time" class="form-control ">
                            <input type="hidden" name="checkin_id" class="form-control ">
                            @csrf
                        </div>
                    </div>
                    <div class="col-md-3 col-3">
                        <span class="checkin_created_by badge badge-success"></span>
                        @if(checkPermission(ATTENDANCES, WRITE))
                        <a href="#!" title="Edit" class="btn edit-checkin-button btn-info">Edit</a>
                        @endif
                        @if(checkPermission(ATTENDANCES, WRITE))
                        <a href="#!" title="Edit" class="btn add-checkin-button btn-info">Add</a>
                        @endif
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-3 col-3"></div>
                    <div class="col-md-6 col-6  justify-content-center d-flex">
                        <div class="timeCircle justify-content-center d-flex align-items-center">
                            <p id="totalTime"></p>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-3 col-3"></div>
                    <div class="col-md-6 col-6 ">
                        <div class="checkInOut checkout-display">
                            <p>Check Out At</p>
                            <p id="checkOutDateTime"></p>
                        </div>
                        <div class="checkInOut checkout-input">
                            <input type="time" name="checkout_action_time" class="form-control ">
                            <input type="hidden" name="checkout_id" class="form-control ">
                        </div>
                    </div>
                    <div class="col-md-3 col-3">
                        <span class="checkout_created_by badge badge-success"></span>
                        @if(checkPermission(ATTENDANCES, WRITE))
                        <a href="#!" title="Edit" class="btn edit-checkout-button btn-info">Edit</a>
                        @endif
                        @if(checkPermission(ATTENDANCES, WRITE))
                        <a href="#!" title="Edit" class="btn Add-checkout-button btn-info">Add</a>
                        @endif
                    </div>
                </div>
                @if(checkPermission(ATTENDANCES, WRITE))
                <div class="row">
                    <div class="col-md-1 col-1"></div>
                    <div class="col-md-10 col-10 justify-content-center d-flex">
                        <a href="#!" class="btn add-half-leave btn-info push-right">Add Half Leave</a>
                        <a href="#!" class="btn add-full-leave btn-info push-right">Add Full Leave</a>
                        <a href="#!" class="btn add-attendance-exception btn-warning push-right">Add Exception</a>
                    </div>
                    <div class="col-md-3 col-3">
                    </div>
                </div>
                @endif
            </div>
        </div>
    </div>
</div>
<div class="modal fade openSummaryModalClass bd-example-modal-lg" id="openSummaryModal" tabindex="-1" role="dialog" aria-labelledby="openSummaryModalLabel" aria-hidden="true" data-backdrop="static">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="userNameHeading"></h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col-md-6">
                        <div class="row">
                            <div class="col-md-6">
                                <b>Total Late Minutes:</b>
                            </div>
                            <div class="col-md-6">
                                <b id="showMinutes">4758</b>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="row">
                            <div class="col-md-6">
                                <b>Total Late Deduction:</b>
                            </div>
                            <div class="col-md-6">
                                <b id="showDeduction">4758</b>
                            </div>
                        </div>
                    </div>
                </div>
                <table class="table table-striped">
                    <thead class=" text-white" style="background-color: #51bcda;">
                        <th> Date </th>
                        <th> Check In </th>
                        <th> Check Out </th>
                        <th> Late </th>
                        <th> Deduction </th>
                        <th> Status </th>
                        <th> Adjust Leave </th>
                    </thead>
                    <tbody id="userSummaryTBody">

                    </tbody>

                </table>
            </div>
        </div>
    </div>
</div>
<div class="modal fade leaveModal bd-example-modal-lg" id="leaveModal" tabindex="-1" role="dialog" aria-labelledby="leaveModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Adjust In Leave</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form action="{{url('admin/attendances/adjust/leave')}}" method="post">
                    @csrf
                    <input type="hidden" name="user_id" id="leaveUserId" value="{{old('user_id') ? old('user_id') : ''}}">
                    <input type="hidden" name="user_name" id="leaveUserName" value="{{old('user_name') ? old('user_name') : ''}}">
                    <input type="hidden" name="date" id="leaveDate" value="{{old('date') ? old('date') : ''}}">
                    <input type="hidden" name="summary" id="summaryBoolean" value="{{old('summary') ? old('summary') : '0'}}">

                    <div class="row">
                        <div class="col-md-6 offset-3 form-group">
                            @if(session('leave_error'))
                            <div class="alert alert-danger alert-dismissible fade show">
                                <button type="button" aria-hidden="true" class="close" data-dismiss="alert" aria-label="Close">
                                    <i class="nc-icon nc-simple-remove"></i>
                                </button>
                                <span>{{ session('leave_error') }}</span>
                            </div>
                            @endif
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6 offset-3 form-group">
                            <h5 id="UserName">@if(old('user_name')) {{old('user_name')}} @endif</h5>
                        </div>
                        <div class="col-md-3 form-group">
                            <button type="button" class="btn btn-warning add-attendance-exception mt-0">Add Exception</button>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6 offset-3 form-group">
                            <label for="">Leave Type</label>
                            <select required name="type" id="" class="form-control">
                                <option value="{{SICK_LEAVE}}" @if(old('type')==SICK_LEAVE) selected @endif>Sick</option>
                                <option value="{{CASUAL_LEAVE}}" @if(old('type')==CASUAL_LEAVE) selected @endif>Casual</option>
                            </select>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6 offset-3 form-group">
                            <label for="">Leave Adjust</label>
                            <select required name="leave_adjust" id="" class="form-control">
                                <option value="{{LEAVE_ADJUST}}" @if(old('leave_adjust')==LEAVE_ADJUST) selected @endif>Adjust</option>
                                <option value="{{LEAVE_NOT_ADJUST}}" @if(old('leave_adjust')==LEAVE_NOT_ADJUST) selected @endif>Not Adjust</option>
                            </select>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6 offset-3 form-group">
                            <label for="">Reason (optional)</label>
                            <textarea name="reason" id="" cols="30" rows="10" class="form-control"></textarea>
                        </div>
                    </div>
                    {{-- <div class="row">
                        <div class="col-md-6 offset-3 form-group">
                            <label for="">Period</label>
                            <select required name="period" id="leavePeriod" class="form-control">
                                <option value="{{HALF_DAY_LEAVE}}" @if(old('period') == HALF_DAY_LEAVE) selected @endif >Half Day</option>
                    <option value="{{FULL_DAY_LEAVE}}" @if(old('period')==FULL_DAY_LEAVE) selected @endif>Full Day</option>
                    </select>
            </div>
        </div>
        <div class="row" id="leavePeriodType">
            <div class="col-md-6 offset-3 form-group">
                <label for="">Period Type</label>
                <select required name="period_type" id="" class="form-control">
                    <option value="{{FIRST_HALF}}" @if(old('period_type')==FIRST_HALF) selected @endif>First Half</option>
                    <option value="{{SECOND_HALF}}" @if(old('period_type')==SECOND_HALF) selected @endif>Second Half</option>
                </select>
            </div>
        </div> --}}
        @if(checkPermission(ATTENDANCES, WRITE))
        <div class="row">
            <div class="update mx-auto">
                <button type="submit" class="btn btn-primary btn-round">{{__('default_label.add')}}</button>
            </div>
        </div>
        @endif
        </form>
    </div>
</div>
</div>
</div>
<div class="modal fade halfLeaveModal bd-example-modal-lg" id="halfLeaveModal" tabindex="-1" role="dialog" aria-labelledby="halfLeaveModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Add Half Leave</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form action="{{url('admin/attendances/adjust/half/leave')}}" method="post">
                    @csrf
                    <input type="hidden" name="user_id" id="halfLeaveUserId" value="{{old('user_id') ? old('user_id') : ''}}">
                    <input type="hidden" name="user_name" id="halfLeaveUserName" value="{{old('user_name') ? old('user_name') : ''}}">
                    <input type="hidden" name="date" id="halfLeaveDate" value="{{old('date') ? old('date') : ''}}">
                    <input type="hidden" name="summary" id="summaryBoolean" value="{{old('summary') ? old('summary') : '0'}}">

                    <div class="row">
                        <div class="col-md-6 offset-3 form-group">
                            @if(session('half_leave_error'))
                            <div class="alert alert-danger alert-dismissible fade show">
                                <button type="button" aria-hidden="true" class="close" data-dismiss="alert" aria-label="Close">
                                    <i class="nc-icon nc-simple-remove"></i>
                                </button>
                                <span>{{ session('half_leave_error') }}</span>
                            </div>
                            @endif
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6 offset-3 form-group">
                            <h5 id="halfUserName">@if(old('user_name')) {{old('user_name')}} @endif</h5>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6 offset-3 form-group">
                            <label for="">Leave Type</label>
                            <select required name="type" id="" class="form-control">
                                <option value="{{SICK_LEAVE}}" @if(old('type')==SICK_LEAVE) selected @endif>Sick</option>
                                <option value="{{CASUAL_LEAVE}}" @if(old('type')==CASUAL_LEAVE) selected @endif>Casual</option>
                            </select>
                        </div>
                    </div>
                    <div class="row" id="leavePeriodType">
                        <div class="col-md-6 offset-3 form-group">
                            <label for="">Period Type</label>
                            <select required name="period_type" id="" class="form-control">
                                <option value="{{FIRST_HALF}}" @if(old('period_type')==FIRST_HALF) selected @endif>First Half</option>
                                <option value="{{SECOND_HALF}}" @if(old('period_type')==SECOND_HALF) selected @endif>Second Half</option>
                            </select>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6 offset-3 form-group">
                            <label for="">Leave Adjust</label>
                            <select required name="leave_adjust" id="" class="form-control">
                                <option value="{{LEAVE_ADJUST}}" @if(old('leave_adjust')==LEAVE_ADJUST) selected @endif>Adjust</option>
                                <option value="{{LEAVE_NOT_ADJUST}}" @if(old('leave_adjust')==LEAVE_NOT_ADJUST) selected @endif>Not Adjust</option>
                            </select>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6 offset-3 form-group">
                            <label for="">Reason (optional)</label>
                            <textarea name="reason" id="" cols="30" rows="10" class="form-control"></textarea>
                        </div>
                    </div>
                    <div class="row">
                        <div class="update mx-auto">
                            <button type="submit" class="btn btn-primary btn-round">{{__('default_label.add')}}</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>


<div class="modal fade halfLeaveModal bd-example-modal-lg" id="leaveDetails" tabindex="-1" role="dialog" aria-labelledby="halfLeaveModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="leave-popup-date"></h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col-md-12">
                        <h5 id="leave-popup-name"></h5>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-12 form-group">
                        <label for="">Period Type</label>
                        <input type="text" class="form-control" disabled id="leave-popup-period">
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-12 form-group">
                        <label for="">Leave Type</label>
                        <input type="text" class="form-control" disabled id="leave-popup-type">
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-12 form-group">
                        <label for="">Leave Adjust</label>
                        <input type="text" class="form-control" disabled id="leave-popup-adjust">
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
