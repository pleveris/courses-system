@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-12">
            <div class="jumbotron">
                <h1 class="display-4">Hey, welcome to courses app!</h1>
                <p class="lead">This is a simple app, demonstrating creating APIs in Laravel Backend, and using them in VueJS components. Done by learning purposes.</p>
            </div>
            @auth
                <h1 class="h3 mb-3 text-gray-800">Top 3 courses visited at most in 6 months:</h1>
                <best-courses-component> </best-courses-component>
            @endauth
        </div>
    </div>
</div>

@endsection