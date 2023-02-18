@extends('employees.auth.base')

@section('content')
    <div class="login-form">
        <form action="{{ route('employee.setupPassword') }}" method="POST">
            @csrf
            <input type="text" name="user_id" value="{{ $user_id }}" hidden>
            <div class="control">
                <input type="password" name="password" required="" placeholder="Enter Password">
            </div>
            <div class="control">
                <input type="password" name="password_confirmation" required="" placeholder="Enter Confirm Password">
            </div>
            @if (session('error'))
                <h6 class="" style="color: red;">{{ session('error') }}</h6>
            @endif


            <br>
            <div class="control">
                <button>Change Password</button>
            </div>
            <div class="control login_link">
                Return to <a href="{{ route('employee.get-login') }}">&nbsp;login</a>
            </div>
        </form>
    </div>
@endsection
