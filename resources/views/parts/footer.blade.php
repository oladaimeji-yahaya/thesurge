
<!-- Footer Starts -->
<footer class="footer">
    <!-- Footer Top Area Starts -->
    <div class="top-footer">
        <div class="container">
            <div class="row">
                <!-- Footer Widget Starts -->
                <div class="col-sm-4 col-md-2">
                    <h4>Our Company</h4>
                    <div class="menu">
                        <ul class="white-text">
                            <li class="white-text"><a href="{{url('/')}}">Home</a></li>
                            <li><a href="{{route('about')}}">About</a></li>
                            <li><a href="{{route('packages')}}">Services</a></li>
                            <li><a href="{{route('contact')}}">Contact</a></li>
                        </ul>
                    </div>
                </div>
                <!-- Footer Widget Ends -->
                <!-- Footer Widget Starts -->
                <div class="col-sm-4 col-md-2">
                    <h4>Help & Support</h4>
                    <div class="menu">
                        <ul>
                            <li><a href="{{route('faq')}}">FAQ</a></li>
                            <li><a href="{{route('tandc')}}">Terms of Services</a></li>
                            <li><a href="{{route('register')}}">Register</a></li>
                            <li><a href="{{route('login')}}">Login</a></li>
                        </ul>
                    </div>
                </div>
                <!-- Footer Widget Ends -->
                <!-- Footer Widget Starts -->
                <div class="col-sm-4 col-md-3">
                    <h4>Contact Us </h4>
                    <div class="contacts">
                        <div>
                            <span>info @ {{env('APP_DOMAIN')}}</span>
                        </div>
                        <div>
                            <span>+44 7476 089612</span>
                        </div>
                        <div>
                            <span>Hides Hill House, Hides Hill Lane, Beaulieu, United Kingdom, SO42 7GZ.</span>
                        </div>
                    </div>
                    <!-- Social Media Profiles Starts -->
                    <!--                    <div class="social-footer">
                                            <ul>
                            <li><a href="#" target="_blank"><i class="fa fa-facebook"></i></a></li>
                            <li><a href="#" target="_blank"><i class="fa fa-twitter"></i></a></li>
                            <li><a href="#" target="_blank"><i class="fa fa-google-plus"></i></a></li>
                            <li><a href="#" target="_blank"><i class="fa fa-linkedin"></i></a></li>
                        </ul>
                                        </div>-->
                    <!-- Social Media Profiles Ends -->
                </div>
                <!-- Footer Widget Ends -->
                <!-- Footer Widget Starts -->
                <div class="col-sm-12 col-md-5">
                    <!-- Facts Starts -->
                    <div class="facts-footer">
                        <div>
                            <h5>$98.76M</h5>
                            <span>Market cap</span>
                        </div>
                        <div>
                            <h5>23K</h5>
                            <span>daily transactions</span>
                        </div>
                        <div>
                            <h5>239K</h5>
                            <span>active accounts</span>
                        </div>
                        <div>
                            <h5>127</h5>
                            <span>supported countries</span>
                        </div>
                    </div>
                    <!-- Facts Ends -->
                    <!--<hr/>-->
                    <!-- Supported Payment Cards Logo Starts -->
                    <!--                    <div class="payment-logos">
                                            <h4 class="payment-title">supported payment methods</h4>
                        <img src="images/icons/payment/american-express.png" alt="american-express"/>
                        <img src="images/icons/payment/mastercard.png" alt="mastercard"/>
                        <img src="images/icons/payment/visa.png" alt="visa"/>
                        <img src="images/icons/payment/paypal.png" alt="paypal"/>
                        <img class="last" src="images/icons/payment/maestro.png" alt="maestro"/>
                                        </div>-->
                    <!-- Supported Payment Cards Logo Ends -->
                    
                    <!--Google translate-->
                    <!--<div id="google_translate_element"></div>-->
                    <script type="text/javascript">
                        function googleTranslateElementInit() {
                            new google.translate.TranslateElement({pageLanguage: 'en', layout: google.translate.TranslateElement.InlineLayout.SIMPLE}, 'google_translate_element');
                        }
                    </script>
                    <script type="text/javascript" src="//translate.google.com/translate_a/element.js?cb=googleTranslateElementInit">
                    </script>

                    <!--Google translate ends-->
                </div>
                <!-- Footer Widget Ends -->
            </div>
        </div>
    </div>
    <!-- Footer Top Area Ends -->
    <!-- Footer Bottom Area Starts -->
    <div class="bottom-footer">
        <div class="container">
            <div class="row">
                <div class="col-xs-12">
                    <!-- Copyright Text Starts -->
                    <p class="text-center">
                        Copyright &copy; <?php echo date('Y') . ' ' . config('app.name') ?> . All rights reserved.
                    </p>
                    <!-- Copyright Text Ends -->
                </div>
            </div>
        </div>
    </div>
    <!-- Footer Bottom Area Ends -->
</footer>
<!-- Footer Ends -->


@section('scripts')
@parent
<script>
    $('form.subscribe').submit(function (event) {
        event.preventDefault();
        var form = $(this);
        iAjax({
            url: "{{route('subscribe')}}",
            data: form.serialize(),
            onSuccess: function (xhr) {
                notify($('form .notify'), xhr);
            },
            onFailure: function (xhr) {
                handleHttpErrors(xhr, form);
            }
        });
    });
</script>
@endsection
