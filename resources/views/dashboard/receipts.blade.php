@extends('layouts.dashboard')
@section('dashboard_content')
    <div class="card">
        <div class="card-body">
            @if($status)
                @foreach(collect($images)->chunk(2) as $imgs)
                    <div class="row">
                        @foreach($imgs as $img)
                            <div class="col-lg-6" style="padding: 5px;">
                                <img src="{{$img}}" alt=""/>
                            </div>
                        @endforeach
                    </div>
                @endforeach
            @else
                <p class="text-center de-facto-text">{{$message}}</p>
            @endif
        </div>
    </div>
@endsection


@section('head')
    @parent
    <style>
        #complete-purchase-form img {
            width: 100%;
        }
    </style>
@endsection