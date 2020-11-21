@extends('layouts.directory')
@section('content')
<section>
    <div class="container">
        <div>
            <h1>Meet the Leaders</h1>
        </div>
    </div>
</section>
<section class="about about--wave">
    <!--<h2 class="text-center padding-btm-1em white-text">Join the Apexcoins Affiliate program and start earning money today.</h2>-->
    <div class="container">
        @foreach($members->chunk(4) as $memberRow)
        <div class="row">
            @foreach($memberRow as $member)
            <div class="col-md-3">
                <div class="panel" style="border: lightgray thin solid">
                    <div class="panel-body">
                        <div class="text-center">
                            <img style="height: 210px; width: 210px; border-radius: 50%;" 
                                 alt="{{$member->name}}, {{$member->title}}"
                                 src="{{$member->image?url($member->image):asset('images/default/user.png')}}"/>
                        </div>
                        <div class="text-center">
                            <span style="font-size: 2rem">
                                {{$member->name}}
                            </span><br/>
                            <span class="box4__text">{{$member->title}}</span>
                        </div>
                        <div>
                            <a target="_blank" href="{{$member->linkedin_url}}"><i class="fa fa-linkedin-square"></i></a>
                            <a href="mailto:{{$member->email}}"><i class="fa fa-envelope"></i></a>
                        </div>
                    </div>
                </div>
            </div>
            @endforeach
        </div>
        @endforeach
    </div>
</section>
@endsection


@section('head')
@parent
<style>
    .panel{
        min-height: 200px;
        /*background-color: lightgray;*/
    }
    .panel .panel-heading{
        font-size: 2em;
        margin-bottom: 20px;
        padding-bottom: 0;
    }
    .panel .panel-body{
        font-size: 1.2em;
    }
</style>
@endsection

@section('scripts')
@parent

<script>
</script>
@endsection
