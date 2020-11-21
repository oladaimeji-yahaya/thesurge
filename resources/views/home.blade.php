@extends('layouts.directory')
@section('content')


    <section class="banner_area" id="particles-js">
        <div class="container">
            <div class="row align-items-center">
                <div class="col-xl-6 col-lg-6 col-md-12">
                    <div class="banner_content">
                        <h1 class="wow fadeInLeft animated">The Surge Partners </h1>
                          <p class="wow fadeInLeft" data-wow-delay="0.2s">We are <strong>your</strong> sustainable wealth partners through the strategic diversification we provide into the growing asset class. Our firm invest primarily in Blockchain Technology, Cryptocurrency Trading, Media, Real Estate and Foriegn Exchange.</p>
                          <ul class="btn_list">
                              <li><a class="theme_btn wow fadeInUp animated" href="{{route('register')}}">
                                <i class="flaticon-user"></i> Become a partner</a></li>
                                
                              
                          </ul>
                    </div>
                </div>
                <div class="col-xl-6 col-lg-6 col-md-12 wow zoomIn animated">
                    <div class="right-ilustrat">
                        <img class="up_animat" src="{{asset('assets/images/banner/banner_ilustration1.png')}}" alt="">
                    </div>
                </div>
            </div>
        </div>
    </section>
    <!--banner-area end-->
 <coingecko-coin-price-marquee-widget  coin-ids="bitcoin,ethereum,eos,ripple,litecoin" currency="usd" background-color="#ffffff" locale="en"></coingecko-coin-price-marquee-widget>

    <!--our_mission_area start-->
    <section id="about" class="our_mission_area">
        <div class="container-fluid">
            <div class="row">
                <div class="col-lg-6 offset-lg-3">
                    <div class="title">
                        <h2>Our<span>  Services</span></h2>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-lg-3 col-md-6 wow fadeInUp animated">
                    <div class="mission_text">
                        <div class="icon_img">
                                <img src="{{asset('images/icons/green/add-bitcoins.png')}}" alt="">
                                
                            </div>
                        <h5>Stocks</h5>
                        <p>
Invest in hundreds of stocks from leading markets and stock exchanges around the world. Analyze, discuss and trade along with over 10 million users.
We trade with Powerful and sophisticated software, award winning platform with advanced trading tools. Any profits are tax free when you trade with us.</p>
                    </div>
                </div>
                <div class="col-lg-3 col-md-6 wow fadeInUp animated" data-wow-delay="0.3s">
                    <div class="mission_text mt_1">
                        <div class="icon_img">
                                <img src="{{asset('images/icons/green/high-liquidity.png')}}" alt="">
                            </div>
                        <h5>Cryptocurrency Trading</h5>
                        <p>Cryptocurrency trading This is the ability to trade in different binary coins for USD and other currency around the Globe. The rise in cryptocurrency value is shaking up the financial markets. Cryptocurrency market was valued at $500 billion U.S Dollars at the end of 2017. Its value rose over 360% from the beginning of 2017. Cryptocurrencies are known for their rapid increase on the web market, providing potential high returns on investment.</p>
                    </div>
                </div>
                <div class="col-lg-3 col-md-6 wow fadeInUp animated" data-wow-delay="0.6s">
                    <div class="mission_text mt_01">
                        <div class="icon_img">
                               <img src="{{asset('images/icons/green/1604756911078.png')}}">
                            </div>
                        <h5>Real Estate</h5>
                        <p>We partner with experienced developers that have a track record of success, invests in properties in locations with high growth potential, offers investments in major markets with solid fundamentals to avoid potential bubbles and invests in projects that will improve neighborhoods, eventually boosting the properties’ overall value. </p>
                    </div>
                </div>
                <div class="col-lg-3 col-md-6 wow fadeInUp animated" data-wow-delay="0.9s">
                    <div class="mission_text">
                         <div class="icon_img">
                                <img src="{{asset('images/icons/green/buy-sell-bitcoins.png')}}" alt="">
                            </div>
                        <h5>Foriegn Exchange</h5>
                        <p>Forex is the largest and most liquid market in the world with an average daily turnover of $3.98 trillion. The Fx market is open 24 hours a day, 5 days a week with the most important world trading centers being located in London, New York, Tokyo, Zurich, Frankfurt, Hong Kong, Singapore, Paris, and Sydney. Trading is said to be conducted ‘over the counter’; it’s not like stocks where there is a central marketplace with all orders processed like the NYSE.</p>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <!--our_mission_area end-->
    <!--mining_invest_area start-->
    <section id="token" class="mining_invest_area">
        <div class="shape_area">
            <img class="pulseBig" src="{{asset('assets/images/invest/round_shap1.png')}}" alt="">
        </div>
        <div class="container">
                <div class="row align-items-center">
                    <div class="col-lg-5 col-md-6 wow fadeInUp animated">
                        <div class="mining_text">
                            <div class="title ti_2">
                                <h2>Our <span>mission</span></h2>
                                <p>Be it known that this is not a get rich quick scheme but in line with  UNITED NATIONS SUSTAINABLE DEVELOPMENT GOALS (SDGs) NO. 8 GOAL "Decent work and economic growth" Our vision is to develop a global partnership for development which will metamorphose to global economic growth and make decent work available for everyone because we see a world where job creation is multiplied.</p>
                            </div>
                           
                        </div>
                    </div>
                    <div class="col-lg-7 col-md-6">
                        <img src="{{asset('assets/images/invest/invest_ilust1.png')}}" alt="" class="right_img up_animat">
                    </div>
                </div>
        </div>
    </section>
    <!--mining_invest_area end-->
    <!--start_mining_area start-->
    <section class="start_mining_area">
        <div class="container-fluid">
            <div class="row">
                <div class="col-lg-6 offset-lg-3">
                    <div class="title">
                        <h2> We Mine with top 4 <span>cryptocurrency</span></h2>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-lg-3 col-md-4 col-sm-6 wow fadeInUp animated">
                    <div class="mining_text">
                        <h3>Bitcoin <span>$8,274.06</span></h3>
                        <span>$2.12 kW/h</span>
                        <span><i class="fas fa-arrow-up"></i> +2.50%</span>
                        <a href="#">Start Mining <i class="fas fa-chevron-right"></i></a>
                    </div>
                </div>
                <div class="col-lg-3 col-md-4 col-sm-6 wow fadeInUp animated" data-wow-delay="0.3s">
                    <div class="mining_text mt_3 mt_2">
                        <h3>Ethereum <span>$782.19</span></h3>
                        <span>$2.12 kW/h</span>
                        <span class="mts_1"><i class="fas fa-arrow-down"></i> -1.50%</span>
                        <a href="#">Start Mining <i class="fas fa-chevron-right"></i></a>
                    </div>
                </div>
                <div class="col-lg-3 col-md-4 col-sm-6 wow fadeInUp animated" data-wow-delay="0.6s">
                    <div class="mining_text mt_4 mt_2">
                        <h3>Ripple <span>$0.764531</span></h3>
                        <span>$2.12 kW/h</span>
                        <span class="mts_1"><i class="fas fa-arrow-down"></i> -1.50%</span>
                        <a href="#">Start Mining <i class="fas fa-chevron-right"></i></a>
                    </div>
                </div>
                <div class="col-lg-3 col-md-4 col-sm-6 wow fadeInUp animated" data-wow-delay="0.9s">
                    <div class="mining_text mt_5">
                        <h3>Litecoin <span>$138.53</span></h3>
                        <span>$2.12 kW/h</span>
                        <span><i class="fas fa-arrow-up"></i> +2.50%</span>
                        <a href="#">Start Mining <i class="fas fa-chevron-right"></i></a>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <!--start_mining_area end-->
    <!--popular_feature_area start-->
    <section id="features" class="popular_feature_area">
        <div class="container-fluid">
            <div class="row">
                <div class="col-lg-6 offset-lg-3">
                    <div class="title">
                        <h2>Why The Surge Partners<br><span>Our most popular features</span></h2>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-lg-3 col-md-4 col-sm-6 wow fadeInUp animated">
                    <div class="feature_box wow fadeInUp animated">
                        <div class="icon_img">
                                <img src="{{asset('assets/images/feature/feat1.png')}}" alt="">
                        </div>
                        <h5>Active Asset Management</h5>
                        <p>We participate in active day-trading, swing trading, automated trading algorithms, and futures trading through the CBOE.</p>
                    </div>
                </div>
                <div class="col-lg-3 col-md-4 col-sm-6 wow fadeInUp animated" data-wow-delay="0.3s">
                        <div class="feature_box">
                            <div class="icon_img">
                                <img src="{{asset('assets/images/feature/feat2.png')}}" alt="">
                            </div>
                            <h5>Passive Asset Management</h5>
                            <p>We invest in, run and maintain masternodes and participate in accurate staking thereby minimizing risk to the minimum.</p>
                        </div>
                </div>
                <div class="col-lg-3 col-md-4 col-sm-6 wow fadeInUp animated" data-wow-delay="0.6s">
                    <div class="feature_box">
                            <div class="icon_img">
                                <img src="{{asset('assets/images/feature/feat3.png')}}" alt="">
                            </div>
                            <h5>Venture Capital Management </h5>
                            <p>We're often offered early access to many initial coin offerings (ICOs), security token offerings (STOs), and equity deals in various crypto startups.</p>
                    </div>
                </div>
                <div class="col-lg-3 col-md-4 col-sm-6 wow fadeInUp animated" data-wow-delay="0.9s">
                    <div class="feature_box">
                            <div class="icon_img">
                                <img src="{{asset('assets/images/feature/feat4.png')}}" alt="">
                            </div>
                            <h5>Enhanced Security</h5>
                            <p>In any instance where safeguarding data is important, we know how sensitive or critical information is, so to prevent fraud and other unauthorized activity we keep all your informations safe at all times.</p>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <!--popular_feature_area end-->
    <!--our_roadmap_area start-->
    <section class="our_roadmap_area">
        <div class="container">
            <div class="row">
                <div class="col-lg-6 offset-lg-3">
                        <div class="title">
                            <h2>Our roadmap<br><span>we leading the right road</span></h2>
                        </div>
                </div>
            </div>
            <div class="">
                <div class="item">
                    <div class="row">
                        <div class="col-lg-4 col-md-4">
                            <div class="road_text rac">
                                <h6>How to buy</h6>
                                <p>You can buy bitcoin or sell by going to any local bitcoin site to make transactions. </p>
                            </div>
                        </div>
                        <div class="col-lg-4 col-md-4">
                            <div class="road_text rac">
                                <h6>How to transfer bitcoin</h6>
                                <p>You can send bitcoin through a specified bitcoin wallet address that will be provided by the reciever. </p>
                            </div>
                        </div>
                        <div class="col-lg-4 col-md-4">
                            <div class="road_text">
                                <h6>Investing with us</h6>
                                <p>You choose a preferable plan to you and follow the process of increasing your wealth. </p>
                            </div>
                        </div>
                    </div>
                    <div class="row row_border">
                        <div class="col-lg-4 col-md-4">
                            <div class="road_text rt_2 rac">
                                <h6>How to get a wallet, buy and sell BTC</h6>
                                <p>Register with <a href="https://www.paxful.com" target="_blank">https://www.paxful.com</a> or <a href="https://www.blockchain.com/" target="_blank">https://www.blockchain.com</a>  to create a wallet address, buy and sell bitcoin. </p>
                            </div>
                        </div>
                        <div class="col-lg-4 col-md-4">
                            <div class="road_text rt_2">
                                <h6>How to contact us.</h6>
                                <p>We are onling 24/7, you can contact us via the 24hours chatbot. </p>
                            </div>
                        </div>
                        <div class="col-lg-4 col-md-4">
                            <div class="road_text rt_2">
                                <h6>Investment Plan?</h6>
                                <p>The first timestamping scheme invented was the proof-of-work scheme. </p>
                            </div>
                        </div>
                    </div>
                </div>
               
            </div>
        </div>
    </section>
    <!--our_roadmap_area end-->
    <!--make_bitcoin_area start-->
     <section class="mining_invest_area make_bitcoin">
        <div class="container">
             <div class="title">        
                          <h2>Our Plans</h2>
                 </div>
            <div class="row align-items-center">
               
                <div class="col-lg-4 col-md-4">
                    <div class="mining_text">
                        <div class="panel panel-default" style="background-color: #0d2841; box-shadow: 0 1px 2px 0 rgba(0, 0, 0, 0.1);
                                padding-top: 20px; border-radius: 40px 40px;">
                           <center>
                            <div class="panel-body" style="box-shadow: 0 5px 18px rgba(0, 0, 0, 0.15);
                               margin: 20px auto 15px;
                                  padding: 15px;
                                 text-align: center;
                                 width: 250px;
                                 border-radius: 20px 20px;">
                        
                                <h2 style="color: #fff;">Gold Plan</h2>
                                
                            </div>
                            </center>
                            <hr>
                            <div class="pricing-features">
                                 <center>
                                     <span style="color: #fff;">
                                        Start with as low as $200 - $1000<hr/>
                                        <p style="color: #fff;"> 
                                       <br/>
                                       9% weekly ROI.<br/>
                                    </p>
                                        <small style="color: #fff;">Earn daily profits.</small>
                                       
                                    </span>
                                    <hr/>
                                    
                                    <p style="color: #fff;"> 
                                       <br/>
                                       10% Referral bonus<br/>
                                       
                                    </p>
                                    <hr/>
                                    
                                    <p style="color: #fff;"> 
                                       <br/>
                                       1 years contract duration.<br/>
                                    </p>
                                   
                                   
                                </center>
                            </div>                          
                   
                        </div>
                        
                    </div>
                </div>
                <div class="col-lg-4 col-md-4">
                    <div class="mining_text">
                        <div class="panel panel-default" style="background-color: #0d2841; box-shadow: 0 1px 2px 0 rgba(0, 0, 0, 0.1);
                                padding-top: 20px; border-radius: 40px 40px;">
                           <center><div class="panel-body" style="box-shadow: 0 5px 18px rgba(0, 0, 0, 0.15);
                               margin: 20px auto 15px;
                                  padding: 15px;
                                 text-align: center;
                                 width: 250px;
                                 border-radius: 30px 30px;">
                        
                                <h2 style="color: #fff;">Diamond Plan</h2>
                                
                            </div>
                            </center>
                            <hr>
                            <div class="pricing-features">
                                 <center>
                                     <span style="color: #fff;">
                                         $1000 - $50000<hr/>
                                         <p style="color: #fff;"> 
                                       <br/>
                                       11% weekly ROI.<br/>
                                    </p>
                                        <small style="color: #fff;">Earn daily profits.</small>
                                    </span>
                                    <hr/>
                                    
                                    <p style="color: #fff;"> 
                                       <br/>
                                       10% Referral bonus<br/>
                                       
                                    </p>
                                    <hr/>
                                    <p style="color: #fff;"> 
                                       <br/>
                                       10 years contract duration.<br/>
                                    </p>
                                   
                                </center>
                            </div>                          
                   
                        </div>
                        
                    </div>
                </div>
                 <div class="col-lg-4 col-md-4">
                    <div class="mining_text">
                        <div class="panel panel-default" style="background-color: #0d2841; box-shadow: 0 1px 2px 0 rgba(0, 0, 0, 0.1);
                                padding-top: 20px; border-radius: 40px 40px;">
                           <center><div class="panel-body" style="box-shadow: 0 5px 18px rgba(0, 0, 0, 0.15);
                               margin: 20px auto 15px;
                                  padding: 15px;
                                 text-align: center;
                                 width: 250px;
                                 border-radius: 30px 30px;">
                        
                                <h2 style="color: #fff;">Platinum Plan</h2>
                                
                            </div>
                            </center>
                            <hr>
                            <div class="pricing-features">
                                 <center>
                                     <span style="color: #fff;">
                                         $50000 - $100000 <hr/>
                                          <p style="color: #fff;"> 
                                       <br/>
                                       14% weekly ROI.<br/>
                                    </p>
                                        <small style="color: #fff;">Earn daily profits.</small>
                                    </span>
                                    <hr/>
                                    
                                    <p style="color: #fff;"> 
                                       <br/>
                                       10% Referral bonus.<br/>
                                      
                                    </p>
                                    <hr/>
                                    <p style="color: #fff;"> 
                                       <br/>
                                       10 years contract duration.<br/>
                                    </p>
                                </center>
                            </div>                          
                   
                        </div>
                        
                    </div>
                </div>

            </div>
        </div>
    </section>
    
    
     <section id="faq" class="faq_area">
        <div class="container">
            <div class="row">
                <div class="col-lg-6 offset-lg-3">
                    <div class="title">
                        <h2>{{config('app.name')}}<br><span>FAQ</span></h2>
                    </div>
                </div>
            </div>
            <div class="col-lg-8 offset-lg-2">
                <nav>
                    <div class="nav nav-tabs" id="nav-tab" role="tablist">
                      <a class="nav-item nav-link active" id="nav-home-tab" data-toggle="tab" href="#nav-home" role="tab" aria-controls="nav-home" aria-selected="true"><h6>Regular questions</h6></a>
                      <a class="nav-item nav-link" id="nav-profile-tab" data-toggle="tab" href="#nav-profile" role="tab" aria-controls="nav-profile" aria-selected="false"><h6>Popular questions</h6></a>
                      <!--<a class="nav-item nav-link" id="nav-contact-tab" data-toggle="tab" href="#nav-contact" role="tab" aria-controls="nav-contact" aria-selected="false"><h6>Trending questions</h6></a>-->
                    </div>
                  </nav>
                  <div class="tab-content" id="nav-tabContent">
                    <div class="tab-pane fade show active wow fadeInLeft animated" id="nav-home" role="tabpanel" aria-labelledby="nav-home-tab">
                        <div class="faq_desc">
                             <h5>Who can join Thesurgepartners ?</h5>
                            <p>Everybody and anybody.
                                       We contribute to the common good. The participants are asked only to follow the recommendations and avoid penalties.</p>
                                       <h5>Is the Company Registered?</h5>
<p>Yes we are registered and approved by S.E.C (Security and Exchange Security) with CIK #0001392694. <a href="https://sec.report/CIK/0001392694" target="_blank">Learn more</a> .</p>
                            <h5>How to participate safely ?</h5>
                            <p>The system on it's own track and monitor suspicious and fraudulent activities.It monitors the integrity of participants and if your guilt is proven, you will be excluded from the surge partners. That may be cool but we cannot do it all on our own, on your part, ensure that you do not share you credentials such as, credit card details and passwords with someone else. Note that the the surge partners Team will never ask for them.</p>
                            <h5>How much money can I raise ?</h5>
                            <p>We offer unlimited income potential. Many of our members earn thousands very quickly. We cannot, however, make income projections for you.
This will be due to your efforts, perseverance and marketing consistency. This is the most powerful automated system online today and was created for novices online as well as advanced marketers. The only income limits are ones that you make on yourself.</p>

                        </div>
                    </div>
                    <div class="tab-pane fade wow fadeInLeft animated" id="nav-profile" role="tabpanel" aria-labelledby="nav-profile-tab">
                        <div class="faq_desc">
                           
                            <h5>What is bitcoin mining ?</h5>
                            <p>Bitcoin mining is simply just software code run on computer. People set up and install this code on computer to verify transactions. People purchase machine to verify transactions and earn BTC. Although you can set up on your computer to run this software, it requires a better type of machine to handle a large of amount of transactions</p>
                            <h5>How does a bitcoin works ?</h5>
                            <p>Bitcoin is a person to person electronic cash system that allows individual to send and receive money anywhere in the world, sender and receiver don't need a bank account. All they need is 34 words phrases. For example: 1BvBMSEYstWetqTFn5Au4m4GFg7xJaNVN2</p>
                            <h5>How much money can I raise ?</h5>
                            <p>We offer unlimited income potential. Many of our members earn thousands very quickly. We cannot, however, make income projections for you.
This will be due to your efforts, perseverance and marketing consistency. This is the most powerful automated system online today and was created for novices online as well as advanced marketers. The only income limits are ones that you make on yourself.</p>
                        </div>
                    </div>
                    <!--<div class="tab-pane fade wow fadeInLeft animated" id="nav-contact" role="tabpanel" aria-labelledby="nav-contact-tab">-->
                    <!--    <div class="faq_desc">-->
                    <!--        <h5>What is bitcoin mining ?</h5>-->
                    <!--        <p>This product is meant for educational purposes only. Any resemblance to real persons, living or dead is purely coincidental. Void where prohibited. Some assembly required. List each check separately by bank number. Batteries not included.</p>-->
                    <!--        <h5>How dose a bitcoin works ?</h5>-->
                    <!--        <p>This product is meant for educational purposes only. Any resemblance to real persons, living or dead is purely coincidental. Void where prohibited. Some assembly required.</p>-->
                    <!--        <h5>How to collect a token ?</h5>-->
                    <!--        <p>This product is meant for educational purposes only. Any resemblance to real persons, living or dead is purely coincidental. Void where prohibited. Some assembly required.</p>-->
                    <!--    </div>-->
                    <!--</div>-->
                  </div>
            </div>
        </div>
    </section>
    <!--team_area end-->
    <!--brand_area start-->
    <section class="brand_area">
        <div class="container">
                <div class="row">
                <div class="col-lg-6 offset-lg-3">
                    <div class="title">
                       <h2>Bitcoin <span>Calculator</span></h2>
                    </div>
                </div>
            </div>            
                       
        <coingecko-coin-converter-widget  coin-id="bitcoin" currency="usd" background-color="" font-color="#4c4c4c" locale="en"  border-radius: 20px 20px;></coingecko-coin-converter-widget>
    </div>
    </section>
 @endsection

 