<script src="{{ asset('assets/js/jquery.min.js') }}"></script>
<script src="{{ asset('assets/js/popper.min.js') }}"></script>
<script src="{{ asset('assets/js/bootstrap.min.js') }}"></script>
<script src="{{ asset('assets/plugins/perfect-scrollbar.jquery.min.js') }}"></script>
<script src="{{ asset('assets/js/select2.min.js') }}"></script>

<script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.9.0/moment.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.9.0/locales.js"></script>
<script src="{{ asset('assets/bootstrap-datetimepicker-master/js/bootstrap-datetimepicker.min.js') }}"></script>
<script src="https://unpkg.com/bootstrap-datepicker@1.9.0/dist/js/bootstrap-datepicker.min.js"></script>
<script src="https://www.gstatic.com/firebasejs/7.23.0/firebase.js"></script>
<script src="https://unpkg.com/js-year-calendar@latest/dist/js-year-calendar.min.js"></script>


{{-- script for birthday calender --}}
<script>
    let Jsondata;
    let dataSet = [];
    var currentYear = new Date().getFullYear();
    let calendar = null;
    calendar = new Calendar('#upcommingBirthdays-calender', {
        enableContextMenu: true,
        enableRangeSelection: true,
        allowOverlap: true,
        // startDate: new Date(),
        mouseOnDay: function(e) {
            if (e.events.length > 0) {
                var content = '';

                for (var i in e.events) {
                    content += '<div class="event-tooltip-content">' +
                        '<div class="event-name" style="color:' + e.events[i].color + '">' + e.events[i].name + '</div>' +
                        '<div class="event-location">' + e.events[i].location + '</div>' +
                        '</div>';
                }

                $(e.element).popover({
                    trigger: 'manual',
                    container: 'body',
                    html: true,
                    content: content
                });

                $(e.element).popover('show');
            }
        },
        mouseOutDay: function(e) {
            if (e.events.length > 0) {
                $(e.element).popover('hide');
            }
        },
        dayContextMenu: function(e) {
            $(e.element).popover('hide');
        },
        // dataSource: 

    });
    getbirthdays()
    // document.querySelector('#upcommingBirthdays-calender').addEventListener('renderEnd', function(e) {
    //     getbirthdays();
    //     calendar.setDataSource(makingDataSet(currentYear));
    //     console.log("makingDataSet(currentYear)");
    // })

    document.querySelector('#upcommingBirthdays-calender').addEventListener('yearChanged', function(e) {
        getbirthdays(e.currentYear);
        calendar.setDataSource(makingDataSet(e.currentYear));
    });

    function getbirthdays(year = null) {
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': '{{ csrf_token() }}'
            }
        });

        $.ajax({
            type: 'POST',
            url: "{{ url(config('data.admin.dashboard.get-birthdays')) }}",
            data: {
                id: 1
            },
            success: function(data) {
                Jsondata = JSON.parse(data);
                dataSet = makingDataSet(year == null ? currentYear : year);
                console.log(calendar);
                if (year == null) {
                    calendar.setDataSource(dataSet);
                }
            }
        });
    }

    function makingDataSet(currentYear) {
        dataSet = [];
        Jsondata.forEach((element, key) => {
            console.log(element);
            $object = {
                'id': (key - 1),
                'name': element.full_name,
                'location': 'BirthDay',
                'startDate': new Date(currentYear, (element.dob.split("-")[1] - 1), element.dob.split("-")[2]),
                'endDate': new Date(currentYear, (element.dob.split("-")[1] - 1), element.dob.split("-")[2])
            }
            dataSet.push($object);
        });
        return dataSet
    }
</script>

<script>
    $(document).ready(function() {
        $('.confirmation-popup').click(function(e) {
            e.preventDefault();
            $('#confirmationModal').modal('show');
            $('.confirmation-yes').attr("href", $(this).attr('data-url'));
        });
        $('.confirmation-no').click(function(e) {
            e.preventDefault();
            $('#confirmationModal').modal('hide');
        });

        $('#upload-excel-sheet-button').on('click', function(e) {
            e.preventDefault();
            $("input[name=excel]").click();
        });
        $("input[name=excel]").on('change', function() {
            $('#upload-excel-sheet-form').submit();
        });
    });
</script>

<script>
    jQuery('.navbar-toggle button.navbar-toggler').click(function() {
        jQuery('.sidebar').toggleClass('active');
        jQuery('.main-panel').css('width', '0 px');
        jQuery('.close-bar').toggleClass('d-none');
    });

    jQuery('.close-bar').click(function() {
        jQuery('.sidebar').toggleClass('active');
        jQuery('.close-bar').toggleClass('d-none');
    });
</script>

<script>
    var firebaseConfig = {
        apiKey: "AIzaSyBI_-2VliQ7dzqLRwsSI4KUZy27s4Yeqec",
        authDomain: "push-notification-6c9bd.firebaseapp.com",
        projectId: "push-notification-6c9bd",
        storageBucket: "push-notification-6c9bd.appspot.com",
        messagingSenderId: "345671504039",
        appId: "1:345671504039:web:dbd6f116528e6ad3738b82",
        measurementId: "G-W429M9388V"
    };

    firebase.initializeApp(firebaseConfig);
    const messaging = firebase.messaging();
    initFirebaseMessagingRegistration();

    function initFirebaseMessagingRegistration() {
        messaging
            .requestPermission()
            .then(function() {
                return messaging.getToken()
            })
            .then(function(token) {
                console.log(token);

                $.ajaxSetup({
                    headers: {
                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                    }
                });

                $.ajax({
                    url: '{{ route('employee.save-token') }}',
                    type: 'POST',
                    data: {
                        token: token
                    },
                    dataType: 'JSON',
                    success: function(response) {
                        console.log('Token saved successfully.');
                    },
                    error: function(err) {
                        console.log('User Chat Token Error' + err);
                    },
                });

            }).catch(function(err) {
                console.log('User Chat Token Error' + err);
            });
    }

    let data = messaging.onMessage(function(payload) {
        const noteTitle = payload.data.title;
        const noteOptions = {
            body: payload.data.body,
            icon: payload.data.icon,
        };
        var number = parseInt($('#notificationNumber').html());
        number = number + 1;
        $('#notificationNumber').html(number);
        getNotificationAjax();
        new Notification(noteTitle, noteOptions);


    });
</script>

<script>
    $(document).on('click', '.notification-click', function() {
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': '{{ csrf_token() }}'
            }
        });

        var id = $(this).attr('data-id');

        $.ajax({
            type: 'POST',
            url: "{{ route('adminNotificationStatus') }}",
            data: {
                id: id
            },
            success: function(data) {
                console.log(data);
            }
        });
    })

    getNotificationAjax();
    $('#notificationAjax').on('click', function() {
        console.log('data')
        getNotificationAjax();

    })



    function getNotificationAjax() {
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': '{{ csrf_token() }}'
            }
        });

        var id = $('#notificationAjax').attr('data-id');

        $.ajax({
            type: 'POST',
            url: "{{ route('admniGetNotifications') }}",
            data: {
                id: id
            },
            success: function(data) {
                console.log(data);
                $('#displayNotifications').html(data.html);
            }
        });

    }
</script>

<script>
    var listener = new BroadcastChannel('listener');
    listener.onmessage = function(e) {
        var number = parseInt($('#notificationNumber').html());
        number = number + 1;
        $('#notificationNumber').html(number);
        getNotificationAjax()
        console.log('asdasd');
    };
</script>
