@extends('layouts.directory')
@section('content')

<section class="billboard" style="background-image: url('../images/billboard/bill.jpg');">
    <div class="container">
        <!--Section Title Starts--> 
       <div class="row mt-0">
           <div class="col-xs-12">
                <!--Title Starts--> 
               <h2 class="title-head white-text">our packages</h2>
                <!--Title Ends--> 
               <hr>
                <!--Breadcrumb Starts--> 
               <ul class="breadcrumb tp">
                   <li><a href="index.html"> home</a></li>
                   <li class="white-text">packages</li>
               </ul>
                <!--Breadcrumb Ends--> 
           </div>
       </div>
        <!--Section Title Ends--> 
   </div>
</section>
<!-- Banner Area Ends -->

<section class="padding-top-2em">
    <div class="container">
        <div>
            <h1 class="title-about">Packages and Pricing</h1>
        </div>
        <div class="row">
            <div class="col-md-12">
                <p>
                    We offer different packages to suit multiple price and duration limits, so we can accomodate a wider client and partner base.
                </p>
            </div>

            <div class="col-md-12">
                <div class="row">
                    @foreach($plans as $plan)
                    <div class="col-md-4">
                        <h4 class="padding-1em de-facto white-text">{{$plan->name}} payout plan</h4>
                        @php

                        @endphp
                        <div class="padding-1em grey lighten-3">
                            <p class="line">ROI Rate: {{$plan->rate}}%</p>
                            <p class="line">ROI Interval: {{$plan->incubation}} days</p>
                            <p class="line">Minimum Investment: ${{$plan->minimum}}</p>
                            <p class="line">Minimum Compounding: ${{$plan->compounding}}</p>
                            <p class="line">Duration: {{$plan->duration}} days</p>
                            <div class="infos-inner">
                                <a href="{{route('dashboard.index',['plan'=>$plan->id])}}" 
                                   class="btn my-btn-primary">Purchase</a>
                            </div>
                        </div>
                    </div>
                    @endforeach
                </div>
            </div>
        </div>
</section>
@endsection


@section('head')
@parent

@endsection

@section('scripts')
@parent

@endsection