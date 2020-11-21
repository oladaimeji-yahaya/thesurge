
<!-- get in touch -->


<!-- Contact Section Starts -->
<section class="contact">
    <div class="container">
        <div class="row">
            <div class="col-xs-12 col-md-12 contact-form">
                <h3 class="col-xs-12">feel free to get in touch with us</h3>
                <p class="col-xs-12">Need to contact us? Do you have any queries or suggestions? 
                    Please contact us about all enquiries using the form below.</p>
                <!-- Contact Form Starts -->
                <form class="form form__contacts" action="#">
                    <!-- Input Field Starts -->
                    <div class="form-group col-md-8">
                        <input class="form__input form-control" name="name" id="name" required placeholder="Name" type="text">
                    </div>
                    <!-- Input Field Ends -->
                    <!-- Input Field Starts -->
                    <div class="form-group col-md-8">
                        <input class="form__input form-control" name="email" id="email" required placeholder="Email" type="text">
                    </div>
                    <!-- Input Field Ends -->
                    <!-- Input Field Starts -->
                    <div class="form-group col-md-8">
                        <input class="form__input form-control" name="subject" id="name" required placeholder="Subject" type="text">
                    </div>
                    <!-- Input Field Ends -->
                    <!-- Input Field Starts -->
                    <div class="form-group col-md-8">
                        <textarea class="form__textarea form-control" name="message" id="name" required placeholder="Message" type="text"></textarea>
                    </div>
                    <!-- Input Field Ends -->

                    <p class="notify"></p>

                    <!-- Submit Form Button Starts -->
                    <div class="form-group col-xs-12 col-md-8">
                        <button class="btn my-btn-primary btn-contact form__btn" type="submit">send message</button>
                    </div>
                    <!-- Submit Form Button Ends -->
                </form>
                <!-- Contact Form Ends -->
            </div>

        </div>
    </div>
</section>
<!-- Contact Section Ends -->


<section id="contacts" class="section padding-1em">
    <div class="container">
        <!--        <div class="row">
                     section title 
                    <div class="col-xs-12 animate" data-animate="fadeIn" data-duration="1.0s" data-delay="0.2s">
                        <h2 class="section__title">Get in Touch</h2>
                        <p class="section__text">
                            <span class="lnr lnr-inbox"></span> Email: 
                            support @ {{env('APP_DOMAIN')}}
                        </p>
                    </div>
                     end section title 
                    
                                <div class="col-xs-12 col-sm-6 animate" data-animate="fadeIn" data-duration="1.0s" data-delay="0.5s">
                                    <div class="contacts">
                                        <ul class="contacts__list">
                                            <li>
                                                <span class="lnr lnr-map"></span>
                                                {{env('APP_DOMAIN')}}, LLC <br>
                                                32 Barnard St. #145 Savannah, GA 80634
                                            </li>
                                            <li>
                                                <span class="lnr lnr-calendar-full"></span>Mon - Fri 08:00 - 19:00
                                            </li>
                                            <li>
                                                <span class="lnr lnr-inbox"></span>
                                                support @ {{env('APP_DOMAIN')}}
                                            </li>
                                            <li>
                                                <span class="lnr lnr-phone-handset"></span>
                                                <a href="tel:+18002345678">+1 (800) 234-5678</a>
                                            </li>
                                        </ul>
                                    </div>
                                </div>
        
                    <div class="col-xs-12 col-sm-6 animate" data-animate="fadeIn" data-duration="1.0s" data-delay="0.5s">
                    <div class="col-xs-12 col-sm-6 col-sm-offset-3 animate" data-animate="fadeIn" data-duration="1.0s" data-delay="0.5s">
                         form 
                        <form action="#" class="form form--contacts">
                            <input type="text" name="name" class="form__input" required placeholder="Name">
                            <input type="text" name="email" class="form__input" required placeholder="Email">
                            <input type="text" name="subject" class="form__input" placeholder="Subject">
                            <textarea class="form__textarea" name="message" required placeholder="Message"></textarea>
                            <p class="notify"></p>
                            <button class="form__btn" type="submit">Send</button>
                        </form>
                         end form 
                    </div>
                </div>-->

    </div>
</section>
<!-- end get in touch -->

@section('scripts')
@parent
<script>
    $('form.form--contacts').submit(function (event) {
        event.preventDefault();
        var form = $(this);
        $('.notify', form).css('color', 'black').show().text('Sending...');
        iAjax({
            url: "{{route('contact')}}",
            method: 'POST',
            data: form.serialize(),
            onSuccess: function (xhr) {
                notify($('.notify', form), xhr);
            },
            onFailure: function (xhr) {
                handleHttpErrors(xhr, form);
            }
        });
    });
</script>
@endsection
