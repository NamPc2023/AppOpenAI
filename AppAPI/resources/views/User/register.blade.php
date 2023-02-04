<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        
        <title>Login</title>
        <link rel="stylesheet" href="{{asset('./style.css')}}">
    </head>
    <body>
        <div class="FormLogin">
            <h4>Login</h4>
            @if(count($errors) > 0)
                @foreach ($errors->all() as $error)
                <p style="color:rgb(255, 0, 0)">{{ $error }}</p>
                @endforeach
            @endif
            <form action="/user/post-register" method="POST">
                <input type="hidden" name="_token" value="{{ csrf_token() }}" />
                <div class="row">
                    <label for="">User Name</label>
                    <input type="text" name="username">
                </div>
                <div class="row">
                    <label for="">Email</label>
                    <input type="email" name="email">
                </div>
                <div class="row">
                    <label for="">Password</label>
                    <input type="password" name="password">
                </div>
                <div>
                    <button type="submit">Register</button>
                </div>
            </form>
        </div>
    </body>
</html>
