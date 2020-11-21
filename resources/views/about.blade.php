@extends('layouts.directory')
@section('content')

<!-- Banner Area Starts -->

<section class="billboard" style="background-image: url('../images/billboard/bill.jpg');">
    <div class="container">
        <!--Section Title Starts--> 
       <div class="row mt-0">
           <div class="col-xs-12">
                <!--Title Starts--> 
               <h2 class="title-head white-text">about us</h2>
                <!--Title Ends--> 
               <hr>
                <!--Breadcrumb Starts--> 
               <ul class="breadcrumb tp">
                   <li><a href="index.html"> home</a></li>
                   <li class="white-text">about us</li>
               </ul>
                <!--Breadcrumb Ends--> 
           </div>
       </div>
        <!--Section Title Ends--> 
   </div>
</section>
<!-- Banner Area Ends -->

<section class="about-page">
    <div class="container">
        <!-- Section Content Starts -->
        <div class="row about-content">

            <!-- Content Starts -->
            <div class="col-sm-12 col-md-12 col-lg-12">
                <div class="feature-about">
                    <h3 class="title-about">Welcome to {{config('app.name')}}</h3>
                    <p>
                        {{config("app.name")}} is a registered company in Hides Hill House, Hides Hill Lane, Beaulieu, United Kingdom, SO42 7GZ.<br/> 
                        We take advantage of cryptocurrency market opportunities with our unique arbitrage strategy.<br/> 
                        When the prices are volatile, a large price difference emerges between stock exchanges (“spreads”), 
                        which opens an opportunity for us to profit. In the markets for major digital currencies such as Bitcoin, 
                        this is a very common occurrence.
                    </p>
                    <p>
                        Our platform began operating over several years now and by visiting our main page, 
                        you can check our accumulated earnings history in the profit statistics section.
                    </p>
                    <p>
                        Many professional investors from all over the world are already well aware {{config("app.name")}} 
                        as a reliable partner who specializes in the highly profitable cryptocurrency trading and training of 
                        doing business. We have registered our company in United Kingdom and since then have been providing quality 
                        service for the profitable use the funds to obtain a stable income.<br/> 
                        Company’s Stoke City office is always open for you, our financial experts and managers gladly provide 
                        regular training courses for beginner Cryptocurrency traders.<br/> 
                        The basis of our trading activity of {{config("app.name")}} is secure and competent technical analysis 
                        as well as avoiding of unnecessary risks. That is why our business strategy is quite popular and in demand 
                        among the numerous crypto traders on the Crypto market and cryptocurrency exchanges. Despite the popularity of our 
                        trainings, {{config("app.name")}} not only teaches other people how to trade, the company financial managers also 
                        provide professional investment services.
                    </p>
                    <p>
                        If you are not willing to trade on your own but still want to have a stable income, 
                        then refer to our experts and we will help you to realize the potential of your investment budget. <br/>
                        To do this, you only need to make a deposit, and our traders will do the rest. To increase our financial 
                        assets on accounts and as well as to continue its growth the company offers favorable terms of 
                        profitable interaction between traders and investors. We are making such proposal for everyone regardless of 
                        understanding of the market structure and principles of multi-currency trading as well. <br/>
                        {{config("app.name")}} offers several plans of same investment strategy. Depending on your preferences 
                        and the size of your deposit, you can get your profit at the end of a certain period.
                    </p>
                    <p>
                        The company provides with the possibility to make as many deposits as you fit. <br/>
                        Stable weekly return is available to you in the amount up to the plan you choose, including your deposit back. <br/>
                        This is an excellent opportunity to earn up to 10% weekly returns including your deposit back. <br/>
                        It is very easy to take part in our investment program. 
                        Just sign up and make a deposit from the minimum investment {{to_currency($minimum)}} through any payment system that
                        is available to you <br/>
                        Automated processes allow you to quickly add deposit amount and withdraw profit. 
                        To prevent fraudulent manipulation and conduct continuous monitoring of movements of financial funds we 
                        only use the manual method for operations related to the payout.  
                    </p>
                </div>
                <div class="feature-about">
                    <h3 class="title-about risk-title">Our Mission</h3>
                    <p>
                        To be market leaders in making the best investment decisions, our experienced experts are well 
                        informed in analytical skills which enable us to make investment decisions at the right time.
                    </p>
                    <p>
                        We have built an enviable reputation in the consumer goods, heavy industry, high-tech, manufacturing, medical, 
                        recreational vehicle & transportation sectors. 
                        Multidisciplinary team of engineering experts, who loves or pursues or desires to obtain pain of itself,
                        the master of except to obtain some advantage.
                    </p>
                </div>
                <!--<a class="btn btn-primary btn-services" href="services.html">Our services</a>-->
            </div>
            <!-- Content Ends -->
        </div>
</section>

@endsection
