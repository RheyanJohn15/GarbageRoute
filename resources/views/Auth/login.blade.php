<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Login</title>
    <link rel="stylesheet" href="{{asset('Auth/style.css')}}">
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.css">
  <script>
    var LoginApi = "{{route('APIPost', ['data'=> 'user', 'method'=> 'login'])}}";
    var dashboard = '{{route('dashboard')}}'
  </script>
</head>
<body>
    @include('Components.dashload')
    <div class="container">
        <section id="content">
            <h1>Silay Waste Management</h1>
            <form id="loginForm" method="post">
                @csrf
                <h1>Login Form</h1>
                <div>
                    <input type="text" name="username" placeholder="Username" required="" id="username" />
                </div>
                <div>
                    <input type="password" name="password" placeholder="Password" required="" id="password" />
                </div>
                <div style="display: flex; justify-content:center; width:100%">
                    <input type="checkbox" name="" id="showPass">
                    <label for="showPass">Show Password</label>
                </div>
                <div>
                    <button id="loginButton" type="submit">Log in</button>
                    {{-- <a href="#">Lost your password?</a>
                    <a href="#">Register</a> --}}
                </div>
            </form><!-- form -->
        </section><!-- content -->
    </div><!-- container -->
    </body>
    <script src="{{asset('helper.js')}}"></script>
    <script src="{{asset('Scripts/helper.js')}}"></script>
    <script src="{{asset('Auth/login.js')}}"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.js"></script>
</html>
