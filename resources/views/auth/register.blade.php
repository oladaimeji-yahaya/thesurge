@extends('layouts.directory')

@section('content')
 <section class="banner_area" id="">
        <div class="container">
        <!-- Section Title Starts -->
        <h2 class="margin-top-2em">
            Get started<br/>
            <small>Open account for free and start trading Bitcoins now!</small>
        </h2>
        <!-- Section Title Ends -->
        <!-- Form Starts -->
        <form name="sentMessage" id="contactForm" class="margin-top-2em"
              role="form" method="POST" action="{{ url('/register') }}" novalidate>
            {{ csrf_field() }}
            @if($ref)
                <div class="row">
                    <div class="col-md-12 form-group">
                        <label for="ref">Referral</label>
                        <input type="text" class="form-control" disabled value="{{$ref}}">
                    </div>
                </div>
            @endif
            <div class="row">
                <div class="col-md-6 form-group{{ $errors->has('username') ? ' has-error' : '' }} floating-label-form-group ">
                    <label style="color: #fff;">User Name</label>
                    <input class="form-control" id="username" type="text" name="username"
                           placeholder="Username" value="{{ old('first_name') }}" required autofocus
                           data-validation-required-message="Please choose a username.">
                    <p class="help-block text-danger"></p>
                    @if ($errors->has('username'))
                        <span class="help-block">
                                <strong>{{ $errors->first('username') }}</strong>
                            </span>
                    @endif
                </div>
                <div class="col-md-6 form-group{{ $errors->has('email') ? ' has-error' : '' }} floating-label-form-group ">
                    <label style="color: #fff;">Email Address</label>
                    <input class="form-control" id="email" type="email" name="email" placeholder="Email Address"
                           value="{{ old('email') }}" required
                           data-validation-required-message="Please enter your email.">
                    <p class="help-block text-danger"></p>
                    @if ($errors->has('email'))
                        <span class="help-block">
                                <strong>{{ $errors->first('email') }}</strong>
                            </span>
                    @endif
                </div>
            </div>
            <div class="row">
                <div class="col-md-6 form-group{{ $errors->has('first_name') ? ' has-error' : '' }} floating-label-form-group ">
                    <label style="color: #fff;">First Name</label>
                    <input class="form-control" id="first_name" type="text" name="first_name"
                           placeholder="First Name" value="{{ old('first_name') }}" required autofocus
                           data-validation-required-message="Please enter your first name.">
                    <p class="help-block text-danger"></p>
                    @if ($errors->has('first_name'))
                        <span class="help-block">
                                <strong>{{ $errors->first('first_name') }}</strong>
                            </span>
                    @endif
                </div>
                <div class="col-md-6 form-group{{ $errors->has('last_name') ? ' has-error' : '' }} floating-label-form-group ">
                    <label style="color: #fff;">Last Name</label>
                    <input class="form-control" id="last_name" type="text" name="last_name" placeholder="Last Name"
                           value="{{ old('last_name') }}" required autofocus
                           data-validation-required-message="Please enter your last name.">
                    <p class="help-block text-danger"></p>
                    @if ($errors->has('last_name'))
                        <span class="help-block">
                                <strong>{{ $errors->first('last_name') }}</strong>
                            </span>
                    @endif
                </div>
            </div>
            <div class="row">
                <div class="col-md-6 form-group{{ $errors->has('country') ? ' has-error' : '' }} floating-label-form-group ">
                    <label style="color: #fff;">Country</label>
                    <select class="form-control" id="country" name="country" required
                            data-validation-required-message="Please enter your country.">
                        <option>Select your country</option>
                        @foreach($countries as $country)
                            <option {{old('country') == $country->id ? 'selected' : '' }} value="{{$country->id}}">
                                {{$country->name}}</option>
                        @endforeach
                    </select>
                    <p class="help-block text-danger"></p>
                    @if ($errors->has('country'))
                        <span class="help-block">
                                <strong>{{ $errors->first('country') }}</strong>
                            </span>
                    @endif
                </div>
              
            </div>
            <div class="row">
                <div class="col-md-6 form-group{{ $errors->has('password') ? ' has-error' : '' }} floating-label-form-group ">
                    <label style="color: #fff;">Password</label>
                    <input class="form-control" id="password" type="password" placeholder="Password" name="password"
                           required data-validation-required-message="Please enter your password.">
                    <p class="help-block text-danger"></p>
                    @if ($errors->has('password'))
                        <span class="help-block">
                                <strong>{{ $errors->first('password') }}</strong>
                            </span>
                    @endif
                </div>
                <div class="col-md-6 form-group{{ $errors->has('password_confirmation') ? ' has-error' : '' }} floating-label-form-group ">
                    <label style="color: #fff;">Confirm Password</label>
                    <input class="form-control" id="password-confirm" type="password" placeholder="Confirm Password"
                           name="password_confirmation" required
                           data-validation-required-message="Please enter your password again.">
                    <p class="help-block text-danger"></p>
                    @if ($errors->has('password_confirmation'))
                        <span class="help-block">
                                <strong>{{ $errors->first('password_confirmation') }}</strong>
                            </span>
                    @endif
                </div>
            </div>
            <div class="row col-md-8">
                <p>
                    By registering on this site, you agree with our <a target="_blank" href="{{route('tandc')}}">Terms
                        and conditions</a>
                </p>

                <div class="form-group">
                    <button type="submit" class="btn btn-primary" style="float: right;">Register</button>

                    <a class="btn btn-link" href="{{ route('login') }}">
                        Already have an account?
                    </a>
                </div>
            </div>
        </form>
        <!-- Form Ends -->
    </div>
    </section>
    <!--banner-area end-->
    
@endsection
