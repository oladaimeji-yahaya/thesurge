@extends('layouts.app1')

@section('head')
   
    
@endsection


@section('header')
 <script src="https://widgets.coingecko.com/coingecko-coin-converter-widget.js"></script>
<script src="https://widgets.coingecko.com/coingecko-coin-price-marquee-widget.js"></script>
    <header>
        <div class="header_area sticky-menu">
            <div class="container">
                <div class="row align-items-center">
                    <div class="col-lg-2 col-md-3">
                        <div class="header_logo">
                            <a href="home"><img src="1604759482856.png" alt="logo" style="width: 160px; height: 70px;" ></a>
                        </div>
                    </div>
                    <div class="col-lg-10">
                        <div class="right-btn d-none d-lg-block">
                           
                             @guest
                       <a href="{{route('login')}}"
                              class="theme_btn wow fadeInUp animated">Sign In</a>
                        
                                @else
                                        <a href="{{route('dashboard.index')}}"
                                               class="theme_btn th_btn">Dashboard</a>
                                @endguest
                   
                        </div>
                        <div class="main-menu ">
                            <nav id="mobile-menu">
                                <ul>
                                    <li><a href="{{route('home')}}">Home</a></li>
                                    <li><a href="{{route('home')}}#about">About</a></li>
                                    
                                    <li><a href="{{route('home')}}#features">Features</a></li>
                                    <li><a href="{{route('home')}}#testimonial">Plans</a></li>
                                    
                                    <li><a href="{{route('home')}}#faq">Faq</a></li>
                                    <li><a href="{{route('home')}}#contact">Contact</a></li>
                                </ul>
                            </nav>
                        </div>
                    </div>
                    <div class="col-lg-9">
                        <div class="mobile-menu"></div>
                    </div>
                </div>
            </div>
        </div>
    </header>
   
@endsection


@section('footer')
    @parent


    <footer id="contact" class="footer_area">
        <div class="container">
            <div class="row">
                <div class="col-lg-4 col-md-6 wow fadeInUp animated">
                    <div class="footer_widget">
                        <a class="fot_logo" href="index.html"><img src="images/logo/fot_logo1.png" alt=""></a>
                        <p>This isn’t a text generator at all but what this person did was put together some English gibberish in the form of what we use and create the most.</p>
                        <ul class="fot_list">
                            <li><a href="#"><i class="fab fa-facebook-f"></i></a></li>
                            <li><a href="#"><i class="fab fa-twitter"></i></a></li>
                            <li><a href="#"><i class="fab fa-linkedin-in"></i></a></li>
                            <li><a href="#"><i class="fab fa-vimeo-v"></i></a></li>
                        </ul>
                    </div>
                </div>
                <div class="col-lg-4 col-md-6 wow fadeInUp animated" data-wow-delay=".3s">
                    <div class="footer_widget">
                       <h5>Newsletter</h5>
                       <p>This isn’t a text generator at all but what this person did was put together.</p>
                       <div class="input_area">
                           <input type="text" class="form-control" placeholder="Email Address">
                           <button class="theme_btn">Submit</button>
                        </div>
                    </div>
                </div>
                <div class="col-lg-4 col-md-6 wow fadeInUp animated" data-wow-delay=".6s">
                    <div class="footer_widget">
                        <div class="footer_widget">
                            <h5>Contact us</h5>
                            <ul class="cta_list">
                                <li><a href="#"><i class="flaticon-call"></i> +09 504 5820</a></li>
                                <li><a href="#"><i class="flaticon-envelope"></i> info@domain.com</a></li>
                                <li><a href="#"><i class="flaticon-paper-plane"></i> 124 Unito, 10 Road,
                                    New York, USA</a></li>
                            </ul>
                         </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-lg-12">
                <div class="copy_right wow fadeInUp animated">
                    <a href="#"><i class="fas fa-at"></i> Copyright @ <script>document.write(new Date().getFullYear());</script> <span>thsurgepartners</span></a>
                </div>
            </div>
        </div>
    </footer>

   
    <button class="scroll-top">
        <i class="fa fa-angle-double-up"></i>
    </button> 

@endsection

@section('scripts')
    <script src="{{asset('assets/js/jquery-3.4.1.min.js')}}"></script> 
    <!-- Bootstrap v4.0.0 -->
    <script src="{{asset('assets/js/popper.min.js')}}"></script>
    <script src="{{asset('assets/js/bootstrap.min.js')}}"></script>  
    <!-- Extra Plugin -->
    <script src="{{asset('assets/vendors/animate-css/wow.min.js')}}"></script>  
    <script src="{{asset('assets/vendors/magnify-popup/jquery.magnific-popup.min.js')}}"></script>
    <script src="{{asset('assets/vendors/particle-js/particles.js')}}"></script>
    <script src="{{asset('assets/vendors/particle-js/app.js')}}"></script>
    <script src="{{asset('assets/vendors/onePageNav/one-page-nav-min.js')}}"></script> 
    <script src="{{asset('assets/vendors/meanMenu/jquery.meanmenu.min.js')}}"></script> 
    <script src="{{asset('assets/vendors/counterup/jquery.waypoints.min.js')}}"></script> 
    <script src="{{asset('assets/vendors/counterup/jquery.counterup.min.js')}}"></script>    
    <script src="{{asset('assets/vendors/owl-carousel/owl.carousel.min.js')}}"></script>   
    <script src="{{asset('assets/vendors/bootstrap-selector/jquery.nice-select.min.js')}}"></script>   
    
    <!-- Theme js / Custom js -->
    <script src="{{asset('assets/js/theme.js')}}"></script>
@endsection

