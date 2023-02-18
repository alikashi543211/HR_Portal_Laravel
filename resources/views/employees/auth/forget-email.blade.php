@extends('employees.auth.base')


@section('content')
    <div class="login-form">
        <form action="{{ route('employee.sendVerficationCode') }}" method="POST">
            @csrf

            <div class="control">
                <input type="email" name="email" required="" placeholder="Enter Your Email">
            </div>
            {{-- <div class="control">
                <input type="password" required="" name="password" placeholder="Password">
                <a href="" class="forget-pass">Forgot Password</a>
            </div> --}}
            @if (session('error'))
                <h6 class="" style="color: red;">{{ session('error') }}</h6>
            @endif


            <br>
            <div class="control">
                <button>Send Verifcation Code</button>
            </div>
            <div class="control login_link">
                Return to <a href="{{ route('employee.get-login') }}">&nbsp;login</a>
            </div>
        </form>
    </div>
@endsection
