<script>
    @if(session('summaryModal'))
    $("td > .openSummaryModal[data-id= {{ session('userId') }}]").click();
    @endif
    var csrf_token = $('input[name=_token]').val();
    var userIdInput = $('input[name=user_id]').val();
    var totalTime = $('#totalTime');
    var currentUserId = '';
    var showDate = $('#showDate');
    var modal = $('#attendanceDetail');

    var checkInActionTime = $('input[name=checkin_action_time]');
    var checkInId = $('input[name=checkin_id]');
    var checkInDateTime = $('#checkInDateTime');
    var checkinInput = $('.checkin-input');
    var checkinDisplay = $('.checkin-display');
    var editCheckInButton = $('.edit-checkin-button');
    var addCheckInButton = $('.add-checkin-button');

    var checkOutActionTime = $('input[name=checkout_action_time]');
    var checkOutId = $('input[name=checkout_id]');
    var checkOutDateTime = $('#checkOutDateTime');
    var checkoutInput = $('.checkout-input');
    var checkoutDisplay = $('.checkout-display');
    var editCheckOutButton = $('.edit-checkout-button');
    var addCheckOutButton = $('.add-checkout-button');
    var checkInUpdatedByUser = $('.checkin_created_by');
    var checkOutUpdatedByUser = $('.checkout_created_by');
    $(document).ready(function() {
        $('.view-attendance-popup').click(function(e) {
            e.preventDefault();
            checkInDateTime.html('');
            checkOutDateTime.html('');
            totalTime.html('');
            checkinDisplay.show();
            checkinInput.hide();
            checkoutDisplay.show();
            checkoutInput.hide();
            editCheckOutButton.show();
            editCheckInButton.show();
            addCheckOutButton.hide();
            addCheckInButton.hide();
            modal.modal('show');
            var date = $(this).data('date');
            showDate.html(date);
            currentUserId = $(this).data('id');

            $('#halfUserName').html($(this).data('name') + ' (' + $(this).data('date') + ')');
            $('#halfLeaveUserName').val($(this).data('name') + ' (' + $(this).data('date') + ')');
            $('#halfLeaveUserId').val($(this).data('id'));
            $('#halfLeaveDate').val($(this).data('date'));
            $('#UserName').html($(this).data('name') + ' (' + $(this).data('date') + ')');
            $('#leaveUserName').val($(this).data('name') + ' (' + $(this).data('date') + ')');
            $('#leaveUserId').val($(this).data('id'));
            $('#leaveDate').val($(this).data('date'));

            callAjaxForPopup();

        });
        $(editCheckInButton).on('click', function() {
            checkinInput.toggle();
            checkinDisplay.toggle();
            changeText($(this), 'Edit');
        });
        $(addCheckInButton).on('click', function() {
            checkinInput.toggle();
            checkinDisplay.toggle();
            changeText($(this), 'Add');
        });
        $(checkInActionTime).on('focusout', function() {
            var time = showDate.html() + " " + $(this).val();
            var id = checkInId.val();
            if (id) {
                var url = "{{url('admin/attendances/ajax/update')}}";
            } else {
                var url = "{{url('admin/attendances/ajax/store')}}";
            }
            $.ajax({
                url: url,
                context: document.body,
                method: 'POST',
                data: {
                    id: id,
                    type: 'Check In',
                    user_id: currentUserId,
                    action_time: time,
                    _token: csrf_token
                }
            }).done(function(data) {
                checkinInput.toggle();
                checkinDisplay.toggle();
                addCheckInButton.hide();
                editCheckInButton.show();
                editCheckInButton.html('Edit');
                callAjaxForPopup();
            });
        });
        $(editCheckOutButton).on('click', function() {
            checkoutInput.toggle();
            checkoutDisplay.toggle();
            changeText($(this), 'Edit');
        });
        $(addCheckOutButton).on('click', function() {
            checkoutInput.toggle();
            checkoutDisplay.toggle();
            changeText($(this), 'Add');
        });
        $(checkOutActionTime).on('focusout', function() {
            var time = showDate.html() + " " + $(this).val();
            var id = checkOutId.val();
            if (id) {
                var url = "{{url('admin/attendances/ajax/update')}}";
            } else {
                var url = "{{url('admin/attendances/ajax/store')}}";
            }
            $.ajax({
                url: url,
                context: document.body,
                method: 'POST',
                data: {
                    id: id,
                    type: 'Check Out',
                    user_id: currentUserId,
                    action_time: time,
                    _token: csrf_token
                }
            }).done(function(data) {
                checkoutInput.toggle();
                checkoutDisplay.toggle();
                addCheckOutButton.hide();
                editCheckOutButton.show();
                editCheckOutButton.html('Edit');
                callAjaxForPopup();
            });
        });
        $(function() {
            $('#datepicker').datepicker({
                zIndexOffset: 9999,
                minViewMode: 'months',
                format: 'MM, yyyy',
                autoclose: true
            });
        });
        $('#datepicker').on('change', function() {
            $('#dateForm').submit();
        });
        // user summary
        $('.openSummaryModal').click(function(e) {
            e.preventDefault();
            $('#openSummaryModal').modal('show');
            $('#userNameHeading').html($(this).data('name'));
            var userId = $(this).data('id');
            var month = "{{request('date')}}";
            $.ajax({
                url: "{{url('admin/attendances/get/user/summary?user_id=')}}" + userId + "&month=" + month,
                context: document.body,
            }).done(function(data) {
                $('#userSummaryTBody').html(data);
                var totalMinutes = 0;
                $('.minutes').each(function() {
                    totalMinutes += $(this).data('minute');
                });
                $('#showMinutes').html(totalMinutes + ' minutes');
                var totalDeduction = 0;
                $('.deduction').each(function() {
                    totalDeduction += $(this).data('deduction');
                });
                $('#showDeduction').html(totalDeduction + ' pkr');

            });
        });
        $('body').on('click', '.minutes', function() {

            if ($(this).data('type') == 'read_able') {
                $(this).html($(this).data('minute') + ' minutes');
                $(this).data('type', 'minute');
            } else {
                $(this).html($(this).data('readable'));
                $(this).data('type', 'read_able');
            }
        });

        $(document).on('click', '.add-half-summary-leave', function() {
            $('#halfUserName').html($(this).data('name') + ' (' + $(this).data('date') + ')');
            $('#halfLeaveUserName').val($(this).data('name') + ' (' + $(this).data('date') + ')');
            $('#halfLeaveUserId').val($(this).data('id'));
            $('#halfLeaveDate').val($(this).data('date'));
            $('#summaryBoolean').val('1');

            $('#halfLeaveModal').modal('show');
        });
        $(document).on('click', '.add-full-summary-leave', function() {
            $('#UserName').html($(this).data('name') + ' (' + $(this).data('date') + ')');
            $('#leaveUserName').val($(this).data('name') + ' (' + $(this).data('date') + ')');
            $('#leaveUserId').val($(this).data('id'));
            $('#leaveDate').val($(this).data('date'));
            $('#summaryBoolean').val('1');
            $('#leaveModal').modal('show');
        });


        $(document).on('click', '.add-half-leave', function() {

            $('#halfLeaveModal').modal('show');
        });
        $(document).on('click', '.add-full-leave', function() {
            $('#leaveModal').modal('show');
        });

        @if(session('half_leave_error'))
        modal.modal('show');
        callAjaxForPopup();
        $('#halfLeaveModal').modal('show');
        @endif

        $(document).on('click', '.add-attendance-exception', function() {
            if (currentUserId == "") {
                currentUserId = $(this).data('id');
            }

            $.ajax({
                url: "{{url('admin/attendances/ajax/add-exception')}}",
                context: document.body,
                method: 'POST',
                data: {
                    user_id: currentUserId,
                    date: showDate.html(),
                    _token: csrf_token
                }
            }).done(function(data) {
                location.reload();
            });
        });
    });

    function callAjaxForPopup() {
        @if(old('user_id'))
        currentUserId = "{{old('user_id')}}";
        checkInDateTime.html('');
        checkOutDateTime.html('');
        totalTime.html('');
        checkinDisplay.show();
        checkinInput.hide();
        checkoutDisplay.show();
        checkoutInput.hide();
        editCheckOutButton.show();
        editCheckInButton.show();
        addCheckOutButton.hide();
        addCheckInButton.hide();
        @endif
        var date = showDate.html();
        @if(old('date'))
        date = "{{old('date')}}";
        @endif
        $.ajax({
            url: "{{url('admin/attendances/get/date-details?date=')}}" + date + "&user_id=" + currentUserId,
            context: document.body
        }).done(function(data) {
            $('.add-half-leave').show();
            if (data.check_in) {
                checkInDateTime.html(data.check_in.time);
                checkInActionTime.val(data.check_in.time);
                checkInId.val(data.check_in.id);
                checkInUpdatedByUser.html(data.checkin_updated_by);
            } else {
                editCheckInButton.hide();
                addCheckInButton.show();
            }
            if (data.check_out) {
                checkOutDateTime.html(data.check_out.time);
                checkOutActionTime.val(data.check_out.time);
                checkOutId.val(data.check_out.id);
                checkOutUpdatedByUser.html(data.checkout_updated_by);
            } else {
                editCheckOutButton.hide();
                addCheckOutButton.show();
            }
            if (data.total_time) {
                totalTime.html(data.total_time);
            }
        });
    }

    function changeText(element, text) {
        if (element.html() == text) {
            element.html('Cancel');
        } else {
            element.html(text);
        }
    }
</script>