@extends('employees.layouts.app')
@section('title')
    Profile
@endsection

@section('css')
    <style>
        .col-sm-4.text-right.profile--image {
            position: relative;
        }

        .col-sm-4.text-right.profile--image .profile-photo {
            position: relative;
        }

        .row.custom {
            width: 45.2rem;
        }


        .profile-photo {
            width: 10rem;
            height: 10rem;
            border-radius: 50%;
            /* overflow: hidden; */
        }

        .profile-photo img {
            max-width: 100%;
            max-height: 100%;
            text-align: center;
            display: inherit;
            padding: 0;
            margin: 0;
            width: inherit;
            height: inherit;

        }

        .col-sm-6.text-right.profile--image.d-flex {
            flex-direction: column;
            align-items: center;
            justify-content: space-between;
            position: absolute;
            left: 60rem;
            width: fit-content;
            top: 0;
        }

        input {
            border-radius: 8px !important
        }

        .border-color {
            border: 2px solid rgb(236, 53, 53);
        }

        .position-set {
            position: relative;
        }

        .row.custom {
            width: 55%;
        }

        .file-lable {
            width: 2rem;
            position: absolute;
            text-align: center;
            top: 1rem;
            left: 8.5rem;
        }

        input#profile-file {
            opacity: 0;
        }

        .file-lable .file-image {
            cursor: pointer;
            transition: ease all 0.3s;
        }

        .file-lable .file-image:hover {
            cursor: pointer;
            filter: invert(0.2);
            box-shadow: 0px 3px 6px rgb(0 0 0 / 16%);
            transform: scale(1.1);
            transition: ease all 0.3s;
        }
    </style>
@endsection

@section('content')
    <div class="container-fluid">

        <!-- row -->

        <div class="row">
            <div class="col-lg-12">
                <form action="{{ route('employee.profile.updateProfile') }}" method="POST">
                    @csrf
                    <div class="card">
                        <div class="card-header d-flex justify-content-between">
                            <h4 class="card-title">Personal Details</h4>
                            <button class="btn btn-rounded btn-sm btn-outline-primary"> Update Peronal Info.</button>
                        </div>
                        <div class="card-body position-set">

                            <div class="row custom ">
                                <div class="form-group col-sm-6">
                                    <label>First Name</label>
                                    <input type="text" class="form-control" name="first_name" value="{{ $data->first_name }}" disabled>
                                </div>
                                <div class="form-group col-sm-6">
                                    <label>Last Name</label>
                                    <input type="text" class="form-control" name="last_name" value="{{ $data->last_name }}" disabled>
                                </div>
                                <div class="col-sm-6 text-right profile--image d-flex">
                                    <div class="profile-photo">
                                        <img src="{{ asset('uploads/employee/' . $data->picture) ?? asset('employeesAsset/images/profile/profile.png') }}" id="uploadedImage" class="img-fluid rounded-circle" alt="">
                                        <form id="changeIamgeForm" action="{{ route('employee.profile.changeImage') }}" method="POST" enctype="multipart/form-data">
                                            @csrf
                                            <label class="file-lable" for="profile-file">
                                                <img class="file-image" src="{{ asset('employeesAsset/icons/camera_icon.svg') }}" alt="">
                                                <input id="profile-file" type="file" name="profileImage" readonly />
                                            </label>
                                        </form>
                                    </div>
                                    {{-- <input type="file" name="profile_image" id="proImage"> --}}
                                </div>
                            </div>

                            <div class="row custom">
                                <div class="form-group col-sm-6">
                                    <label>Email</label>
                                    <input type="text" class="form-control" name="email" value="{{ $data->email }}" disabled>
                                </div>
                                <div class="form-group col-sm-6">
                                    <label>Phone Number</label>
                                    <input type="text" class="form-control" name="phone_number" value="{{ $data->phone_number }}">
                                </div>
                            </div>
                            <div class="row">
                                <div class="form-group col-sm-3">
                                    <label>Gender</label>
                                    <input type="text" class="form-control" name="gender" value="{{ $data->gender }}" disabled>
                                </div>
                                <div class="form-group col-sm-3">
                                    <label>CNIC</label>
                                    <input type="text" class="form-control" name="cnic" value="{{ $data->cnic }}" disabled>
                                </div>
                                <div class="form-group col-sm-3">
                                    <label>Personal Email</label>
                                    <input type="text" class="form-control" name="personal_email" value="{{ $data->personal_email }}">
                                </div>
                                <div class="form-group col-sm-3">
                                    <label>Date of Birth</label>
                                    <input type="text" class="form-control" name="dob" value="{{ $data->dob }}" disabled>
                                </div>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
            <div class="col-lg-12">
                <div class="card">
                    <div class="card-header d-flex justify-content-between">
                        <h4 class="card-title">Office Details</h4>
                    </div>
                    <div class="card-body position-set">
                        <div class="row">
                            <div class="form-group col-sm-4">
                                <label>Status</label>
                                <input type="text" class="form-control" name="dob" value="{{ $data->status }}" disabled>
                            </div>
                            <div class="form-group col-sm-4">
                                <label>Date of Joining</label>
                                <input type="text" class="form-control" name="cnic" value="{{ $data->doj }}" disabled>
                            </div>
                            <div class="form-group col-sm-4">
                                <label>Date of Permananent</label>
                                <input type="text" class="form-control" name="personal_email" value="{{ $data->dop }}" disabled>
                            </div>
                        </div>
                        <div class="row">
                            <div class="form-group col-sm-4">
                                <label>Designation</label>
                                <input type="text" class="form-control" name="gender" value="{{ $data->designation }}" disabled>
                            </div>
                            <div class="form-group col-sm-4">
                                <label>Account No.</label>
                                <input type="text" class="form-control" name="personal_email" value="{{ $data->account_no }}" disabled>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-lg-12">
                <form action="{{ route('employee.profile.updateProfile') }}" method="POST">
                    @csrf
                    <div class="card">
                        <div class="card-header d-flex justify-content-between">
                            <h4 class="card-title">Emergency Details</h4>
                            <button class="btn btn-rounded btn-sm btn-outline-primary"> Update Emergency Info.</button>
                        </div>
                        <div class="card-body position-set">
                            <div class="row">
                                <div class="form-group col-sm-4">
                                    <label>Emergency Contact Name</label>
                                    <input type="text" class="form-control" name="emergency_contact_name" value="{{ $data->emergency_contact_name }}">
                                </div>
                                <div class="form-group col-sm-4">
                                    <label>Emergency Contact Relation</label>
                                    <input type="text" class="form-control" name="emergency_contact_relation" value="{{ $data->emergency_contact_relation }}">
                                </div>
                                <div class="form-group col-sm-4">
                                    <label>Emergency Contact Number</label>
                                    <input type="text" class="form-control" name="emergency_contact_number" value="{{ $data->emergency_contact_number }}">
                                </div>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
        </div>
        <div class="row">
            <div class="col-lg-12">
                <div class="card">
                    <div class="card-header">
                        <h4 class="card-title">Change Passwod</h4>
                    </div>
                    <div class="card-body">
                        <form action="{{ route('employee.profile.changePassword') }}" id="passwordUpdate" method="POST">
                            @csrf
                            <div class="row">
                                <div class="form-group col-sm-4">
                                    <label>Old Password <span class="text-danger">*</span></label>
                                    <input type="password" id="old_password" class="form-control" name="old_password" required>
                                </div>
                                <div class="form-group col-sm-4">
                                    <label>New Password <span class="text-danger">*</span></label>
                                    <input type="password" id="password" class="form-control" name="password" required>
                                    <h6 class="text text-danger mt-1 d-none passwordAlert">New password and Confirm password are not same</h6>
                                </div>
                                <div class="form-group col-sm-4">
                                    <label>Confirm Password <span class="text-danger">*</span></label>
                                    <input type="password" id="confirm_password" class="form-control" name="password_confirmation" required>
                                    {{-- <h6 class="text text-danger mt-1">New password and Confirm password are not same</h6> --}}
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-lg-12 text-right">
                                    <button type="submit" id="" class="btn btn-rounded btn-sm btn-outline-primary">Change Password</button>
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
    <script>
        $('#passwordUpdateBtn').on('click', function() {
            $('#confirm_password').removeClass('border-color')
            $('#password').removeClass('border-color')
            $('#confirm_password').next().addClass('passwordAlert');

            if ($('#confirm_password').val() != $('#password').val()) {
                $('#confirm_password').addClass('border-color')
                $('#confirm_password').next().removeClass('passwordAlert');
                $('#password').addClass('border-color')
            }
            if ($('#confirm_password').val() != '' &&
                $('#password').val() != '' &&
                $('#old_password').val() != '') {
                $('#passwordUpdate').submit();
            }
        })
        $('#profile-file').on('change', function() {
            previewFile();
            submitForm();
        });


        function previewFile() {
            var file = $("#profile-file").get(0).files[0];
            if (file) {
                var reader = new FileReader();

                reader.onload = function() {
                    $("#uploadedImage").attr("src", reader.result);
                    $(".navbar-nav.header-right .nav-item.dropdown.header-profile .nav-link img").attr("src", reader.result);
                }
                reader.readAsDataURL(file);
            }
        }


        function submitForm() {
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                }
            });

            var data = new FormData();
            var file = $("#profile-file")[0].files[0];

            data.append('profileImage', file);
            data.append("_token", "{{ csrf_token() }}")
            $.ajax({
                type: 'POST',
                url: "{{ route('employee.profile.changeImage') }}",
                type: "POST",
                enctype: 'multipart/form-data',
                processData: false, // Important!
                contentType: false,
                cache: false,
                data: data,
                success: function(data) {
                    toastr.success("Image Updated Successfully", "Success", {
                        positionClass: "toast-bottom-right",
                        timeOut: 5e3,
                        closeButton: !0,
                        debug: !1,
                        newestOnTop: !0,
                        progressBar: !0,
                        preventDuplicates: !0,
                        onclick: null,
                        showDuration: "300",
                        hideDuration: "1000",
                        extendedTimeOut: "1000",
                        showEasing: "swing",
                        hideEasing: "linear",
                        showMethod: "fadeIn",
                        hideMethod: "fadeOut",
                        tapToDismiss: !1
                    });
                }
            });

        }
    </script>
@endsection
