<!DOCTYPE html>
<html lang="en">
​

<head>
    ​
    <link href="//maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css" rel="stylesheet" id="bootstrap-css">
    <script src="//maxcdn.bootstrapcdn.com/bootstrap/4.0.0/js/bootstrap.min.js"></script>
    <script src="//cdnjs.cloudflare.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>
    <link rel="stylesheet" href="{{ asset('assets/css/login.css') }}" />
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.14.0/css/all.min.css" rel="stylesheet">
</head>
​<style>

</style>

<body class="">
    ​
    <div class="wrapper fadeInDown">
        @if (session('success'))
            <div class="alert alert-success alert-dismissible fade show">
                <button type="button" aria-hidden="true" class="close" data-dismiss="alert" aria-label="Close">
                    <i class="nc-icon nc-simple-remove"></i>
                </button>
                <span>{{ session('success') }}</span>
            </div>
        @endif


        <div id="formContent">
            <h1><i class="fas fa-user-circle"> </i></h1>
            ​
            <!-- Login Form -->
            <form method="post" action="{{ URL('login') }}" enctype="multipart/form-data">
                @csrf
                <div class="row">
                    <div class="col-md-12">
                        <div class="row">
                            <div class="col-md-10 offset-md-1">
                                @if (session('error'))
                                    <div class="alert alert-danger fade show">
                                        <button type="button" aria-hidden="true" class="close" data-dismiss="alert" aria-label="Close">
                                            <i class="nc-icon nc-simple-remove"></i>
                                        </button>
                                        <span>{{ session('error') }}</span>
                                    </div>
                                @endif

                                @if ($errors->any())
                                    <div class="alert alert-danger fade show">
                                        <button type="button" aria-hidden="true" class="close" data-dismiss="alert" aria-label="Close">
                                            <i class="nc-icon nc-simple-remove"></i>
                                        </button>
                                        <ul>
                                            @foreach ($errors->all() as $error)
                                                <li>{{ $error }}</li>
                                            @endforeach
                                        </ul>
                                    </div>
                                @endif
                            </div>
                        </div>
                        <div class="form-group">
                            <input name="email" class="form-control" id="login" type="email" placeholder="Enter Email" required value="{{ !empty(old('email')) ? old('email') : '' }}" />
                        </div>
                        <div class="form-group">
                            <input name="password" id="password" type="password" placeholder="Enter Password" required value="" class="form-control" />
                        </div>
                        <input type="submit" value="Login">
                    </div>
                </div>
            </form>

        </div>
    </div>
    ​
</body>
​

</html>
