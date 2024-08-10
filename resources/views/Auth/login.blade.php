<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Login</title>
    <link rel="stylesheet" href="{{asset('Auth/style.css')}}">
</head>
<body>
    <div class="container">
        <section id="content">
            <h1>Silay Waste Management</h1>
            <form action="">
                <h1>Login Form</h1>
                <div>
                    <input type="text" placeholder="Username" required="" id="username" />
                </div>
                <div>
                    <input type="password" placeholder="Password" required="" id="password" />
                </div>
                <div>
                    <input type="submit" value="Log in" />
                    {{-- <a href="#">Lost your password?</a>
                    <a href="#">Register</a> --}}
                </div>
            </form><!-- form -->
        </section><!-- content -->
    </div><!-- container -->
    </body>
</html>