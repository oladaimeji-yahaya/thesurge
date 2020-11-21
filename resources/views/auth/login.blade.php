@extends('layouts.directory')

@section('content')
    <section class="banner_area" >
        <div class="container">
            <h2 class="margin-top-2em">
            Member login<br/>
            <small>Secure and easiest way to invest in Cryptocurrency</small>
        </h2>
        <!-- Section Title Ends -->
        <!-- Form Starts -->
        <form name="sentMessage" class="margin-top-2em"
              role="form" method="POST" action="{{ url('/login') }}" novalidate>
            {{ csrf_field() }}
            <div class="control-group form-group{{ $errors->has('username') ? ' has-error' : '' }}">
                <div class="form-group{{ $errors->has('password') ? ' has-error' : '' }} floating-label-form-group controls">
                    <label for="username" style="color: #fff;">Username</label>
                    <input class="form-control" id="username" type="text" placeholder="Username" name="username"
                           value="{{ old('username') }}"
                           required autofocus data-validation-required-message="Please enter your username.">
                    <p class="help-block text-danger"></p>
                    @if ($errors->has('username'))
                        <span class="help-block">
                                <strong>{{ $errors->first('username') }}</strong>
                            </span>
                    @endif
                </div>
            </div>
            <div class="control-group form-group{{ $errors->has('password') ? ' has-error' : '' }}">
                <div class="form-group{{ $errors->has('password') ? ' has-error' : '' }} floating-label-form-group controls">
                    <label for="password" style="color: #fff;">Password</label>
                    <input class="form-control" id="password" type="password" placeholder="Password" name="password"
                           required data-validation-required-message="Please enter your password.">
                    <p class="help-block text-danger"></p>
                    @if ($errors->has('password'))
                        <span class="help-block">
                                <strong>{{ $errors->first('password') }}</strong>
                            </span>
                    @endif
                </div>
            </div>
            <div class="form-group">
                <input type="hidden" value="on" name="remember"/>

                <button type="submit" class="btn btn-primary">
                    Login
                </button>

                <a class="btn btn-link" href="{{ route('register') }}">
                    Sign Up
                </a>
                <a class="btn btn-link" href="{{ url('/password/reset') }}">
                    Forgot Your Password?
                </a>
            </div>
        </form>
        <!-- Form Ends -->

        </div>
    </section>
    <!--banner-area end-->
   
   
@endsection