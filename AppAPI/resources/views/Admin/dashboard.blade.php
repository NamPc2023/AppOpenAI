<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        
        <title>Login</title>
        <link rel="stylesheet" href="{{asset('./style.css')}}">
    </head>
    <body>
        <h1>Dashboard</h1>
        @if (Auth::check()) 
            <a href="/user/logout">Logout</a>
        @endif
    </body>
</html>
