@extends('layouts.directory')
@section('content')

<section class="billboard" style="background-image: url('../images/billboard/bill.jpg');">
    <div class="container">
        <!--Section Title Starts--> 
       <div class="row mt-0">
           <div class="col-xs-12">
                <!--Title Starts--> 
               <h2 class="title-head white-text">contact us</h2>
                <!--Title Ends--> 
               <hr>
                <!--Breadcrumb Starts--> 
               <ul class="breadcrumb tp">
                   <li><a href="index.html"> home</a></li>
                   <li class="white-text">contact</li>
               </ul>
                <!--Breadcrumb Ends--> 
           </div>
       </div>
        <!--Section Title Ends--> 
   </div>
</section>
<!-- Banner Area Ends -->
@include('parts.support')
@endsection


@section('head')
@parent

@endsection

@section('scripts')
@parent

@endsection
