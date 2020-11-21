@extends('layouts.dashboard')
@section('dashboard_content')

    <div class="row">

        <div class="col-sm-6">
            <div class="card">
                <h2 class="card-header">Account Details</h2>
                <form class="form-horizontal card-body" role="form" method="POST"
                      action="{{ route('dashboard.profile') }}">
                    {{ csrf_field() }}
                    <div class="row form-group">
                        <label for="username" class="col-md-3 control-label">Username</label>
                        <div class="col-md-9">
                            <input id="username" type="text" class="form-control" value="{{ $user->username }}" required
                                   readonly>
                        </div>
                    </div>
                    <div class="row form-group{{ $errors->has('first_name') ? ' has-error' : '' }}">
                        <label for="first_name" class="col-md-3 control-label">First Name</label>

                        <div class="col-md-9">
                            <input id="first_name" type="text" class="form-control" name="first_name"
                                   placeholder="John" value="{{ old('first_name',$user->first_name) }}" required
                                   autofocus>
                            @if ($errors->has('first_name'))
                                <span class="help-block">
                        <strong>{{ $errors->first('first_name') }}</strong>
                    </span>
                            @endif
                        </div>
                    </div>
                    <div class="row form-group{{ $errors->has('last_name') ? ' has-error' : '' }}">
                        <label for="last_name" class="col-md-3 control-label">Last Name</label>

                        <div class="col-md-9">
                            <input id="last_name" type="text" class="form-control" name="last_name"
                                   placeholder="Mary" value="{{ old('last_name',$user->last_name) }}" required
                                   autofocus>
                            @if ($errors->has('last_name'))
                                <span class="help-block">
                        <strong>{{ $errors->first('last_name') }}</strong>
                    </span>
                            @endif
                        </div>
                    </div>

                    <div class="row form-group{{ $errors->has('email') ? ' has-error' : '' }}">
                        <label for="email" class="col-md-3 control-label">E-Mail</label>

                        <div class="col-md-9">
                            <input id="email" type="email" class="form-control" name="email"
                                   placeholder="example@domain.com" value="{{ old('email',$user->email) }}" required>
                            @if ($errors->has('email'))
                                <span class="help-block">
                        <strong>{{ $errors->first('email') }}</strong>
                    </span>
                            @endif
                        </div>
                    </div>

                    <div class="row form-group{{ $errors->has('phone') ? ' has-error' : '' }}">
                        <label for="phone" class="col-md-3 control-label">Phone</label>

                        <div class="col-md-9">
                            <input id="phone" type="tel" class="form-control" name="phone"
                                   placeholder="(257) 563-XXXX"
                                   value="{{ old('phone',$user->phone) }}" required>
                            @if ($errors->has('phone'))
                                <span class="help-block">
                        <strong>{{ $errors->first('phone') }}</strong>
                    </span>
                            @endif
                        </div>
                    </div>

                <!--        <div class="row form-group{{ $errors->has('address') ? ' has-error' : '' }}">
                        <label for="address" class="col-md-3 control-label">Address</label>
                <div class="col-md-9">
                    <input required id="address" type="text" class="form-control" name="address"
                           placeholder="711-2880 Nulla St. Mankato Mississippi 96522"
                           value="{{ old('address',$user->address) }}">
                    @if ($errors->has('address'))
                    <span class="help-block">
                        <strong>{{ $errors->first('address') }}</strong>
                    </span>
                    @endif
                        </div>
                            </div>-->
                    <div class="row form-group{{ $errors->has('country') ? ' has-error' : '' }}">
                        <label for="country" class="col-md-3 control-label">Country</label>
                        <div class="col-md-9">
                            <select class="form-control" id="country" name="country" required
                                    data-validation-required-message="Please enter your country.">
                                <option>Select your country</option>
                                @foreach($countries as $country)
                                    <option {{old('country',$user->country_id) == $country->id ? 'selected' : '' }} value="{{$country->id}}">
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
                    <div class="row form-group{{ $errors->has('wallet_id') ? ' has-error' : '' }}">
                        <label for="wallet_id" class="col-md-3 control-label">Wallet Address</label>
                        <div class="col-md-9">
                            <input required id="wallet_id" type="text" class="form-control" name="wallet_id"
                                   placeholder="1SampleSLtKNngkdXEeobR76b53LETtpyT"
                                   value="{{ old('wallet_id',$user->wallet_id) }}">
                            @if ($errors->has('wallet_id'))
                                <span class="help-block">
                        <strong>{{ $errors->first('type') }}</strong>
                    </span>
                            @endif
                        </div>
                    </div>

                    <div class="row form-group">
                        <div class="col-md-8">
                            <strong class="green-text success-message">{{old('profileSaved')}}</strong>
                        </div>
                        <div class="col-md-4">
                            <button type="submit" class="btn btn-primary btn-rounded btn-icon no-margin">
                                Update
                                <i class="fa fa-check"></i>
                            </button>
                        </div>
                    </div>
                </form>
            </div>
        </div>

        <div class="col-sm-6">
            <div class="card">
                <h2 class="card-header">Photo</h2>
                <form class="card-body" role="form" method="POST" action="{{ route('dashboard.photo') }}"
                      enctype="multipart/form-data">
                    {{ csrf_field() }}
                    <div class="form-group row">
                        <div class="col-md-4">
                            <div class="text-center grey lighten-3"
                                 style="{{$user->photo ? '' :"height: 150px" }}">
                                @if($user->photo)
                                    <img style="max-height: 150px" src="{{ getStorageUrl($user->photo) }}"/>
                                @endif
                            </div>
                        </div>
                        <div class="col-md-8">
                            <div class="row">
                                <div class="col-sm-12 form-group{{ $errors->has('photo') ? ' has-error' : '' }}">
                                    <div class="row">
                                        <label for="photo" class="col-sm-12 control-label">
                                            Upload Profile Photo
                                        </label>
                                        <div class="col-sm-12">
                                            <input id="photo" type="file" class="form-control" accept="image/*"
                                                   name="photo">
                                            @if ($errors->has('photo'))
                                                <span class="help-block">
                                        <strong>{{ $errors->first('photo') }}</strong>
                                    </span>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                                <div class="row form-group">
                                    <div class="col-md-10">
                                        <strong class="green-text success-message">{{old('photoSaved')}}</strong>
                                    </div>
                                    <div class="col-md-2">
                                        <button type="submit" class="btn btn-primary btn-rounded btn-icon no-margin">
                                            Upload
                                            <i class="fa fa-check"></i>
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
            <div class="card">
                <h2 class="card-header">Authorization</h2>
                <form class="form-horizontal card-body" role="form" method="POST"
                      action="{{ route('dashboard.password') }}">
                    {{ csrf_field() }}
                    <div class="row form-group{{ $errors->has('password') ? ' has-error' : '' }}">
                        <label for="password" class="col-md-3 control-label">Password</label>

                        <div class="col-md-9">
                            <input id="password" required type="password" class="form-control" name="password">

                            @if ($errors->has('password'))
                                <span class="help-block">
                        <strong>{{ $errors->first('password') }}</strong>
                    </span>
                            @endif
                        </div>
                    </div>


                    <div class="row form-group{{ $errors->has('password_confirmation') ? ' has-error' : '' }}">
                        <label for="password-confirm" class="col-md-3 control-label">Confirm Password</label>

                        <div class="col-md-9">
                            <input id="password-confirm" required  type="password" class="form-control"
                                   name="password_confirmation">

                            @if ($errors->has('password_confirmation'))
                                <span class="help-block">
                        <strong>{{ $errors->first('password_confirmation') }}</strong>
                    </span>
                            @endif
                        </div>
                    </div>
                    <div class="row form-group">
                        <div class="col-md-7">
                            <strong class="green-text success-message">{{old('passwordSaved')}}</strong>
                        </div>
                        <div class="col-md-3">
                            <button type="submit" class="btn btn-primary btn-rounded btn-icon no-margin">
                                Change Password
                                <i class="fa fa-check"></i>
                            </button>
                        </div>
                    </div>
                </form>
            </div>
        </div>

        {{--
                <div class="col-sm-12">
                    <div class="card">
                        <h2 class="card-header">Identification</h2>
                        <form class="card-body" role="form" method="POST" action="{{ route('dashboard.identity') }}"
                              enctype="multipart/form-data">
                            {{ csrf_field() }}
                            <div class="form-group row">
                                <div class="col-md-4">
                                    <div class="text-center grey lighten-3"
                                         style="{{$user->identity_photo ? '' :"height: 150px" }}">
                                        @if($user->identity_photo)
                                            <img style="max-height: 150px" src="{{ getStorageUrl($user->identity_photo) }}"/>
                                        @endif
                                    </div>
                                </div>
                                <div class="col-md-8">
                                    <div class="row">
                                        <div class="col-sm-12 form-group{{ $errors->has('identity_photo') ? ' has-error' : '' }}">
                                            <div class="row">
                                                <label for="identity_photo" class="col-sm-12 control-label">
                                                    Upload International Passport or Driver's Licence
                                                </label>
                                                <div class="col-sm-12">
                                                    <input id="identity_photo" type="file" class="form-control" accept="image/*"
                                                           name="identity_photo">
                                                    @if ($errors->has('identity_photo'))
                                                        <span class="help-block">
                    <strong>{{ $errors->first('identity_photo') }}</strong>
                                            </span>
                                                    @endif
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-sm-12 form-group">
                                            <button type="submit" class="btn no-margin">
                                                Upload
                                            </button>
                                            <span class="green-text success-message">
                    <strong>{{ old('identitySaved')}}</strong>
                                    </span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>--}}

    </div>

@endsection

@section('head')
    @parent
    <style>
        .thm-button {
            margin: 0
        }

        .success-message {
            display: inline-block;
            padding: 0.5em
        }
    </style>
@endsection

@section('scripts')
    @parent

@endsection