@extends('employees.auth.base')


@section('content')
    <div class="login-form">
        <form action="{{ route('employee.verifyCode') }}" method="POST">
            @csrf
            <div class="control">
                <input type="text" name="code" required="" placeholder="Enter Verification Code">
            </div>
            @if (session('error'))
                <div class="d-flex">
                    <h6 class="" style="color: red;">{{ session('error') }}</h6>
                    <a href="{{ route('employee.getVerficationCode') }}" class="login_page">resend code</a>
                </div>
            @endif


            <br>
            <div class="control">
                <button>Verify Code</button>
            </div>
            <div class="control login_link">
                Return to <a href="{{ route('employee.get-login') }}">&nbsp; login</a>
            </div>
        </form>
    </div>
@endsection
