@extends('layouts.admin')
@section('inner_content')
<div class="container Josefins">
    <div class="row">
        <div class="col-xs-12">
            <h2 class="pull-left no-margin">Settings</h2>
        </div>
    </div>
    @if(old('status') !== null)
    <p class='{{old('status')?'bg-success green-text':'bg-danger red-text'}} text-center padding-1em'>{{old('message')}}</p>
    @endif
    <div class="row margin-btm-4em">
        <form class="form-horizontal" role="form" method="POST" action="{{route('admin.settings.update')}}">
            {{ csrf_field() }}
            @foreach($settings as $setting)
            @if(!$setting->system)
            <div class="col-xs-12">
                <div class="form-group{{ $errors->has($setting->name) ? ' has-error' : '' }}">
                    <label for="{{$setting->name}}" class="col-md-4 control-label">{{ucfirst($setting->label)}}</label>

                    <div class="col-md-6">
                        @if($setting->type === 'boolean')
                        <select id="{{$setting->name}}" name="{{$setting->name}}" class="form-control">
                            <option {{ old($setting->name,$setting->value)?'selected':'' }} value="1">Yes</option>
                            <option {{ old($setting->name,$setting->value)?'':'selected' }} value="0">No</option>
                        </select>
                        @elseif($setting->type === 'number')
                        <input id="{{$setting->name}}" type="number" class="form-control" name="{{$setting->name}}" value="{{ old($setting->name,$setting->value) }}" required autofocus>
                        @else
                        <input id="{{$setting->name}}" type="text" class="form-control" name="{{$setting->name}}" value="{{ old($setting->name,$setting->value) }}" required autofocus>
                        @endif

                        @if ($errors->has($setting->name))
                        <span class="help-block">
                            <strong>{{ $errors->first($setting->name) }}</strong>
                        </span>
                        @endif
                    </div>
                </div>
            </div>
            @endif
            @endforeach
            <div class="btn-save-container">
                <button type="submit" class="col-md-offset-4 btn btn-primary">Save</button>
            </div>
        </form>
    </div>
</div>
@endsection


@section('head')
@parent
<style>
    .btn-save-container{
        position: fixed;
        width: 100%;
        bottom: 0;
        left: 0;
        padding: 10px;
        background-color: rgba(0,0,0,.9);
    }
</style>
@endsection