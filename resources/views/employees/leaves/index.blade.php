@extends('employees.layouts.app')
@section('title')
    Leaves
@endsection

@section('css')
    <style>
        tbody tr td.font-awosome {
            cursor: pointer;
        }

        .fa-trash:before {
            content: "\f1f8";
            font-family: 'FontAwesome';
        }

        .fa-pencil:before {
            content: "\f040";
            font-family: 'FontAwesome';
        }

        input.form-control.custom-height {
            height: 44px;
        }

        .custom-pagination nav {
            float: right;
        }

        .width-max-content {
            width: max-content;
        }

        .btn.btn-rounded.btn-sm.btn-outline-primary.ml-3 {
            width: 30rem;
            height: inherit;
            align-self: center;
        }

        button.btn.dropdown-toggle.btn-light.bs-placeholder {
            padding: 8px;
            border-radius: 7px;
        }

        .dropdown.bootstrap-select.form-control.default-select.show {
            margin-right: 20px;
        }

        button.btn.dropdown-toggle.btn-light.bs-placeholder {
            margin-top: 0.5rem;
        }

        .btn.dropdown-toggle.btn-light .filter-option {
            height: unset;
        }

        button.btn.dropdown-toggle.btn-light {
            height: 42px;
            margin-top: 8px;
        }

        .dropdown.bootstrap-select.form-control.default-select {
            border: none !important;
        }

        #searchForm .row.w-100 .col-lg-3 {
            margin: auto;
        }

        button.btn.dropdown-toggle.btn-light {
            border-radius: 8px
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
                        <form id="searchForm" class="w-100">
                            <div class="row w-100">
                                <div class="col-lg-3">
                                    <h4 class="card-title width-max-content">Leaves</h4>
                                </div>
                                <div class="col-lg-9 d-flex ">
                                    <div class="dropdown bootstrap-select form-control default-select show">
                                        <select class="form-control default-select" id="status" name="status" tabindex="-98">
                                            <option value="">Status</option>
                                            <option value="0" {{ request('status') != null && request('status') == 0 ? 'Selected' : '' }}>Pending</option>
                                            <option value="1" {{ request('status') != null && request('status') == 1 ? 'Selected' : '' }}>Approved</option>
                                            <option value="2" {{ request('status') != null && request('status') == 2 ? 'Selected' : '' }}>Rejected</option>
                                        </select>
                                    </div>
                                    <div class="dropdown bootstrap-select form-control default-select show">
                                        <select class="form-control default-select" id="type" name="type" tabindex="-98">
                                            <option value="">Type</option>
                                            <option value="{{ SICK_LEAVE }}" {{ request('type') != null && request('type') == SICK_LEAVE ? 'Selected' : '' }}>Sick Leave</option>
                                            <option value="{{ CASUAL_LEAVE }}" {{ request('type') != null && request('type') == CASUAL_LEAVE ? 'Selected' : '' }}>Casual Leave</option>
                                        </select>
                                    </div>
                                    <div class="dropdown bootstrap-select form-control default-select show">
                                        <select class="form-control default-select" id="period_type" name="period_type" tabindex="-98">
                                            <option value="">Period Type</option>
                                            <option value="{{ FULL_DAY }}" {{ request('period_type') != null && request('period_type') == FULL_DAY ? 'Selected' : '' }}>Full Day</option>
                                            <option value="{{ FIRST_HALF }}" {{ request('period_type') != null && request('period_type') == FIRST_HALF ? 'Selected' : '' }}>Fisrt Half</option>
                                            <option value="{{ SECOND_HALF }}" {{ request('period_type') != null && request('period_type') == SECOND_HALF ? 'Selected' : '' }}>Second Half</option>
                                        </select>
                                    </div>
                                    <a href="{{ route('employee.leaves.add-leave-Request') }}" class="btn btn-rounded btn-sm btn-outline-primary ml-3">Leave Request</a>
                                </div>
                            </div>
                        </form>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive" id="TableList">
                            @include('employees.leaves.row')
                        </div>
                    </div>
                </div>
            </div>

        </div>
    </div>


    <div class="modal fade show" id="basicModal" aria-modal="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Delete Leave Request</h5>
                    <button type="button" class="close" data-dismiss="modal"><span>×</span>
                    </button>
                </div>
                <div class="modal-body">Are you sure you want to delete this?</div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-danger light model-button-close" data-dismiss="modal">Close</button>
                    <a href="" id="deleteRequest" class="btn btn-primary model-button-continue">Continue</a>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('scripts')
    <script>
        $('.deleteBtn').on('click', function() {
            var id = $(this).attr('id');
            $('#deleteRequest').attr("href", $(this).attr('data-delete-url'))
        })

        $('#status').on('change', function() {
            ajaxcall()
        })
        $('#type').on('change', function() {
            ajaxcall()
        })
        $('#period_type').on('change', function() {
            ajaxcall()
        })

        var searchSub = ''

        function ajaxcall() {
            if (searchSub) {
                searchSub.abort();
            }
            searchSub = $.ajax({
                type: 'GET',
                url: "{{ route('employee.leaves.listing') }}",
                headers: {
                    'X-CSRF-TOKEN': "{{ csrf_token() }}"
                },
                data: $('#searchForm').serialize(),
                success: function(data) {
                    $('#TableList').html(data.html)
                }
            });

        }
    </script>
@endsection
