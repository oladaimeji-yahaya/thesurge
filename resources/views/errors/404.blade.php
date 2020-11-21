@extends('layouts.directory')
@section('content')
<section class="error-404 padding-top-2em padding-btm-2em">
    <div class="container">
        <div class="text-center">
            <h1>404</h1>
            <h2>Oops! That page could not be found</h2>
            <p>To help find your way, you can browse through the menu or go the home page</p>
            <a href="{{url('/')}}" class="btn btn-primary">go to home page</a>
        </div>
    </div>
</section>
@endsection