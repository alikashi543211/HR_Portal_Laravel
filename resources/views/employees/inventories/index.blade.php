@extends('employees.layouts.app')
@section('title')
    My Devices
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

        .fa-download:before {
            content: "\f019";
            font-family: 'FontAwesome';
        }

        .col-lg-6.text-right.custom-flex {
            display: flex;
            justify-content: flex-end;
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
                        <div class="row w-100">
                            <div class="col-lg-6">
                                <h4 class="card-title">Devices</h4>
                            </div>
                            <div class="col-lg-6 text-right custom-flex">
                            </div>
                        </div>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive" id="TableList">
                            @include('employees.inventories.row')
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
                <div class="modal-body">Are you sure want to delete it.</div>
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

        $('#searchBar').on('input change', function() {
            ajaxcall()
        })


        var searchSub = ''

        function ajaxcall() {
            if (searchSub) {
                searchSub.abort();
            }
            searchSub = $.ajax({
                type: 'GET',
                url: "{{ route('employee.inventories.listing') }}",
                headers: {
                    'X-CSRF-TOKEN': "{{ csrf_token() }}"
                },
                data: $('#SearchForm').serialize(),
                success: function(data) {
                    $('#TableList').html(data.html)
                    // $('#TableList').html();
                }
            });
        }
    </script>
@endsection
