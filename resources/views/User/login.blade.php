<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Login</title>
    <link rel="stylesheet" href="{{asset('UsersAsset/style.css')}}">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.css">
    <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.js"></script>
</head>
<body>
    @include('User.UserLoading')
    <h1 class="title-header mx-1">Silay Waste Management Driver Access</h1>
    <form id="login-form" class="login-form" autocomplete="off" role="main">
        @csrf
        <div>
          <label class="label-email">
            <input type="text" class="text" name="username" placeholder="Username" tabindex="1" required />
            <span class="required">Username</span>
          </label>
        </div>
        <input type="checkbox" name="show-password" class="show-password a11y-hidden" id="show-password" tabindex="3" />
        <label class="label-show-password" for="show-password">
          <span>Show Password</span>
        </label>
        <div>
          <label class="label-password">
            <input type="text" class="text" name="password" placeholder="Password" tabindex="2" required />
            <span class="required">Password</span>
          </label>
        </div>
        <input type="submit" value="Log In" />

        <figure aria-hidden="true">
          <div class="person-body"></div>
          <div class="neck skin"></div>
          <div class="head skin">
            <div class="eyes"></div>
            <div class="mouth"></div>
          </div>
          <div class="hair"></div>
          <div class="ears"></div>
          <div class="shirt-1"></div>
          <div class="shirt-2"></div>
        </figure>
      </form>

    <script src="{{ asset('Scripts/helper.js') }}"></script>
    <script src="{{ asset('Scripts/userLogin.js') }}"></script>
</body>
</html>
