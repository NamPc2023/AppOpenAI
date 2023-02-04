<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        
        <title>Login</title>

        @include('Admin.layout.header');
    </head>
    <body>
        <h3 style="text-align: center">Login</h3>
        <form action="/user/post-login" method="POST">
            <input type="hidden" name="_token" value="{{ csrf_token() }}" />
            <div class="mb-3">
              <label for="exampleInputEmail1" class="form-label">Email address</label>
              <input type="email" class="form-control" name="email">
            </div>
            <div class="mb-3">
              <label for="exampleInputPassword1" class="form-label">Password</label>
              <input type="password" class="form-control" name="password">
            </div>
            <button type="submit" class="btn btn-primary">Login</button>
            <a style="margin-left: 40px" href="/user/register">Register</a>
        </form>
    </body>
</html>
