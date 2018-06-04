<!doctype html>
<html lang="{{ app()->getLocale() }}">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>Homepage</title>

    <!-- Fonts -->
    <link href="https://fonts.googleapis.com/css?family=Raleway:100,600" rel="stylesheet" type="text/css">
    <link rel="stylesheet" href="css/homepage.css">

</head>
<body>
<div class="flex-center position-ref full-height">
    @if (Route::has('login'))
        <div class="top-right links">
            @auth
                <a href="{{ url('/home') }}">Home</a>
            @else
                <a href="{{ route('login') }}">Login</a>
                <a href="{{ route('register') }}">Register</a>
            @endauth
        </div>
    @endif

    <div class="content">
        <div class="title m-b-md">
            <h1>Finances Assistant</h1>

            <h3>Welcome to your personal Finances Assistant</h3>
        </div>
        <div class="midlle estatistics">
            <div align="center">
                <div>Total of registered users {{$results_users}}</div>
                <div>Total number of accounts: {{$results_accounts}}</div>
                <div>Movements registered of plataform: {{$results_movements}}</div>
            </div>
        </div>
    </div>
</div>
</body>
</html>