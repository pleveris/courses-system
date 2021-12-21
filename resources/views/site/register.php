<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>Accessibility assistant: register</title>

    <!-- Scripts -->
    <script src="{{ asset('js/app.js') }}" defer></script>

    <!-- Fonts -->
    <link rel="dns-prefetch" href="//fonts.gstatic.com">
    <link href="https://fonts.googleapis.com/css?family=Nunito" rel="stylesheet">

    <!-- Styles -->
    <link href="{{ asset('css/app.css') }}" rel="stylesheet">
    <link href="{{ asset('css/sb-admin-2.css') }}" rel="stylesheet">
    <link href="{{ asset('vendor/fontawesome-free/css/all.min.css') }}" rel="stylesheet">
</head>
<body>
    <div id="app">
        <nav class="navbar navbar-expand-md navbar-light bg-white shadow-sm">
            <div class="container">
                <a class="navbar-brand" href="{{ url('/sample') }}">Home</a>
                <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="{{ __('Toggle navigation') }}">
                    <span class="navbar-toggler-icon"></span>
                </button>

                <div class="collapse navbar-collapse" id="navbarSupportedContent">
            </div>
        </nav>

        <main class="py-5">
<form action="/site-reg-conf" method="get">
<div class="form-row">
                <div class="form-group col-md-6">

<label for="username">Username</label>
<input type="text" class="form-control"  name="username" id="username" required autofocus/>
</div>
</div>

<div class="form-row">
                <div class="form-group col-md-6">

<label for="email">Email</label>
<input type="email" class="form-control"  name="email" id="email" required/>
</div>
</div>

<div class="form-row">
                <div class="form-group col-md-6">

<label for="pass1">Password</label>
<input type="password" class="form-control"  name="pass1" id="pass2" required/>
</div>
</div>

<div class="form-row">
                <div class="form-group col-md-6">

<label for="pass2">Password (again)</label>
<input type="password" class="form-control"  name="pass2" id="pass2" required/>
</div>
</div>

<button type="submit" class="btn btn-primary">Register</button>
</form>
</main>
    </div>
</body>
</html>
