@extends('employees.auth.base')


@section('content')
    <div class="login-form">
        <form action="{{ route('employee.login') }}" method="POST">
            @csrf

            <div class="control">
                <input type="email" name="email" required="" placeholder="Email">
            </div>
            <div class="control">
                <input type="password" required="" name="password" placeholder="Password">
                <a href="{{ route('employee.getVerficationCode') }}" class="forget-pass">Forgot Password?</a>
            </div>
            @if (session('error'))
                <h6 class="" style="color: red;">{{ session('error') }}</h6>
            @endif


            <br>
            <div class="control">

                <button>Login</button>
            </div>
            <!-- <div class="text-center control forgot-pass">
                        Forgit your password? <a href="javascript:;"
                            class="text-orange">Get a new one here</a>
                    </div> -->
        </form>
    </div>
@endsection
