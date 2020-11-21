@extends('layouts.directory')
@section('content')

<section class="billboard" style="background-image: url('../images/billboard/bill.jpg');">
    <div class="container">
        <!--Section Title Starts--> 
       <div class="row mt-0">
           <div class="col-xs-12">
                <!--Title Starts--> 
               <h2 class="title-head white-text">faq</h2>
                <!--Title Ends--> 
               <hr>
                <!--Breadcrumb Starts--> 
               <ul class="breadcrumb tp">
                   <li><a href="index.html"> home</a></li>
                   <li class="white-text">frequently asked questions</li>
               </ul>
                <!--Breadcrumb Ends--> 
           </div>
       </div>
        <!--Section Title Ends--> 
   </div>
</section>
<!-- Banner Area Ends -->
<!-- Section FAQ Starts -->
<section class="faq">
    <div class="container">
        <div class="row">
            <div class="col-xs-12 col-md-8">

                <!-- Panel Group Starts -->
                <div class="panel-group" id="accordion">
                    @forelse($faqs as $faq)
                    <!-- Panel Starts -->
                    <div class="panel panel-default">
                        <!-- Panel Heading Starts -->
                        <div class="panel-heading">
                            <h4 class="panel-title">
                                <a data-toggle="collapse" data-parent="#accordion" href="#collapse{{$loop->index}}">
                                    {{$faq->question}}</a>
                            </h4>
                        </div>
                        <!-- Panel Heading Ends -->
                        <!-- Panel Content Starts -->
                        <div id="collapse{{$loop->index}}" class="panel-collapse collapse in">
                            <div class="panel-body">{!!nl2br($faq->answer)!!}</div>
                        </div>
                        <!-- Panel Content Starts -->
                    </div>
                    <!-- Panel Ends -->
                    @empty
                    <h3 class="text-center">Nothing here</h3>
                    @endforelse
                </div>
                <!-- Panel Group Ends -->

                <div class="padding-top-1em">{{$faqs->links()}}</div>
            </div>
            <!-- Sidebar Starts -->
            <div class="sidebar col-xs-12 col-md-4">
                <!-- Tags Widget Starts -->
                <div class="widget widget-tags">
                    <h3 class="widget-title">Popular Tags </h3>
                    <ul class="unstyled clearfix">
                        <li><a href="#">currency</a></li>
                        <li><a href="#">crypto</a></li>
                        <li><a href="#">trading</a></li>
                        <li><a href="#">wallet</a></li>
                        <li><a href="#">mining</a></li>
                        <li><a href="#">transaction</a></li>
                        <li><a href="#">financial</a></li>
                        <li><a href="#">security</a></li>
                    </ul>
                </div>
                <!-- Tags Widget Ends -->
            </div>
            <!-- Sidebar Ends -->
        </div>
    </div>
</section>
<!-- Section FAQ Ends -->
@endsection
