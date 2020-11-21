@extends('layouts.directory')
@section('content')
@php
$name = config('app.name');
@endphp


<section class="billboard" style="background-image: url('../images/billboard/bill.jpg');">
    <div class="container">
        <!--Section Title Starts--> 
       <div class="row mt-0">
           <div class="col-xs-12">
                <!--Title Starts--> 
               <h2 class="title-head white-text">how it works</h2>
                <!--Title Ends--> 
               <hr>
                <!--Breadcrumb Starts--> 
               <ul class="breadcrumb tp">
                   <li><a href="index.html"> home</a></li>
                   <li class="white-text">guide</li>
               </ul>
                <!--Breadcrumb Ends--> 
           </div>
       </div>
        <!--Section Title Ends--> 
   </div>
</section>
<!-- Banner Area Ends -->

<section id="privacy"> 
    <div class="container"> 
        <div class="padding-1em white"> 
            <h1 align="left" style="color:#000;">
                <strong>Guide</strong>
            </h1> 
            <hr /> 
            <p class="large"> 
                {{$name}} adopt the privacy provided by its network and add more layers to it by combining its decade-old 
                financial system with the most-effective technology available today. 
            </p> 
            <p> 
                {{$name}} enters smart contracts with its clients and partners to manage their funds for profit making. 
                Funds are then channeled through online trading, real estate and asset-backed securities. 
                The profits from making these investments are shared and disbursed using crypto. 
            </p> 
            <div class="sec-title medium"> 
                <h2>{{$name}} Investment levels</h2> 
                <div class="decor-line"></div> 
            </div> 
            <p class="font-bold"> Basic (minimum capital required - {{to_currency($plans[0]->minimum)}}, compounding from {{to_currency($plans[0]->compounding)}}) </p> 
            <p> 
                With as little as $500 invested into the system, our basic partners are guaranteed 
                10% return on investment every 7 days. 
            </p> 
            <p class="font-bold"> Standard (minimum capital required - {{to_currency($plans[1]->minimum)}}, compounding from {{to_currency($plans[1]->compounding)}}) </p> 
            <p> Our standard partners are guaranteed up to 45% return on investment every month. </p> 
            <p class="font-bold"> Premium (minimum capital required - {{to_currency($plans[2]->minimum)}}, compounding from {{to_currency($plans[2]->compounding)}}) </p> 
            <p> Our VIP partners earn as much as 50% return on investment every month. </p> 
            <p> All partners are entitled to occasional bonuses and treats which are mostly carried out by our national representatives. </p> 

            <div class="sec-title medium"> 
                <h2>Referral Program</h2> 
                <div class="decor-line"></div> 
            </div> 
            <p> 
                Partners earn up to 10x more by inviting their friends and colleagues to join {{$name}} using their unique referral link.</p> 
            <p> 
                Partners earn 10% commission on investments from all referrals.<br/>
            </p> 
            <a href="{{route('register')}}" class="btn my-btn-primary btn-lg" style="margin-top: 30px;"> 
                Register Now 
            </a> 
        </div> 
    </div>
</section>
@endsection

@section('head')
<style>
    table > thead > tr > td{
        text-transform: uppercase;
        font-weight: bold;
    }
    table > tbody > tr > td:first-child{
        text-transform: uppercase;
    }
</style>
@endsection