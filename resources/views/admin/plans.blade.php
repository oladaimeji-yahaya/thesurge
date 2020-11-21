@extends('layouts.admin')
@section('inner_content')
<div class="container Josefins">
    <div class="row">
        <div class="col-xs-12">
            <h2 class="pull-left no-margin">Plans</h2>
        </div>
    </div>
    @if(old('status') !== null)
    <p class='{{old('status')?'bg-success green-text':'bg-danger red-text'}} text-center padding-1em'>{{old('message')}}</p>
    @endif
    <div class="row margin-btm-4em">
        <form class="table-responsive col-xs-12" role="form" method="POST" action="{{route('admin.plans.update')}}">
            {{ csrf_field() }}
            <table class="table table-hover table-striped">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Plan</th>
                        <th>Rate (in %)</th>
                        <th>Cycle (In days)</th>
                        <th>Duration (In days)</th>
                        <th>Min. Amount (in $)</th>
                        <th>Min. Compounding (in $)</th>
                        <th>Last Updated</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($plans as $plan)
                    <tr>
                        <td>{{$plan->id}}</td>
                        <td>
                            <div class="form-group{{ $errors->has($plan->id.'::name') ? ' has-error' : '' }}">
                                <input id="{{$plan->id}}_name" type="text" class="form-control" name="{{$plan->id.'::name'}}" 
                                       value="{{ old($plan->id.'::name',$plan->name) }}" required autofocus>
                                @if ($errors->has($plan->id.'::name'))
                                <span class="help-block">
                                    <strong>{{ $errors->first($plan->id.'::name') }}</strong>
                                </span>
                                @endif
                            </div>
                        </td>
                        <td>
                            <div class="form-group{{ $errors->has($plan->id.'::rate') ? ' has-error' : '' }}">
                                <input id="{{$plan->id}}_rate" type="text" class="form-control" name="{{$plan->id.'::rate'}}" 
                                       value="{{ old($plan->id.'::rate',$plan->rate) }}" required autofocus>
                                @if ($errors->has($plan->id.'::rate'))
                                <span class="help-block">
                                    <strong>{{ $errors->first($plan->id.'::rate') }}</strong>
                                </span>
                                @endif
                            </div>
                        </td>
                        <td>
                            <div class="form-group{{ $errors->has($plan->id.'::incubation') ? ' has-error' : '' }}">
                                <input id="{{$plan->id}}_incubation" type="text" class="form-control" name="{{$plan->id.'::incubation'}}" 
                                       value="{{ old($plan->id.'::incubation',$plan->incubation) }}" required autofocus>
                                @if ($errors->has($plan->id.'::incubation'))
                                <span class="help-block">
                                    <strong>{{ $errors->first($plan->id.'::incubation') }}</strong>
                                </span>
                                @endif
                            </div>
                        </td>
                        <td>
                            <div class="form-group{{ $errors->has($plan->id.'::duration') ? ' has-error' : '' }}">
                                <input id="{{$plan->id}}_duration" type="text" class="form-control" name="{{$plan->id.'::duration'}}"
                                       value="{{ old($plan->id.'::duration',$plan->duration) }}" required autofocus>
                                @if ($errors->has($plan->id.'::duration'))
                                    <span class="help-block">
                                    <strong>{{ $errors->first($plan->id.'::duration') }}</strong>
                                </span>
                                @endif
                            </div>
                        </td>
                        <td>
                            <div class="form-group{{ $errors->has($plan->id.'::minimum') ? ' has-error' : '' }}">
                                <input id="{{$plan->id}}_minimum" type="text" class="form-control" name="{{$plan->id.'::minimum'}}" 
                                       value="{{ old($plan->id.'::minimum',$plan->minimum) }}" required autofocus>
                                @if ($errors->has($plan->id.'::minimum'))
                                <span class="help-block">
                                    <strong>{{ $errors->first($plan->id.'::minimum') }}</strong>
                                </span>
                                @endif
                            </div>
                        </td>
                        <td>
                            <div class="form-group{{ $errors->has($plan->id.'::compounding') ? ' has-error' : '' }}">
                                <input id="{{$plan->id}}_compounding" type="text" class="form-control" name="{{$plan->id.'::compounding'}}" 
                                       value="{{ old($plan->id.'::compounding',$plan->compounding) }}" required autofocus>
                                @if ($errors->has($plan->id.'::compounding'))
                                <span class="help-block">
                                    <strong>{{ $errors->first($plan->id.'::compounding') }}</strong>
                                </span>
                                @endif
                            </div>
                        </td>
                        <td>{{$plan->updated_at->diffForHumans()}}</td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
            <div class="btn-save-container">
                <button type="submit" class="col-md-offset-2 btn btn-primary">Update</button>
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