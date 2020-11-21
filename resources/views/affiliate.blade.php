@extends('layouts.directory')
@section('content')
<section>
    <div class="container">
        <div>
            <!--<h1>Contact Us</h1>-->
        </div>
    </div>
</section>
<section class="about about--wave">
    <h2 class="text-center padding-btm-1em white-text">Join the Apexcoins Affiliate program and start earning money today.</h2>
    <div class="container">
        <div class="row">
            <div class="col-md-4">
                <div class="panel" style="border: lightgray thin solid">
                    <h3 class="panel-heading text-center box4__text">Join</h3>
                    <div class="panel-body">
                        It's easy and free to join<br/>
                        Get up and running with a free {{to_currency(500)}} on the basic plan
                        if you're approved as an affiliate.
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="panel" style="border: lightgray thin solid">
                    <h3 class="panel-heading text-center box4__text">Advertise</h3>
                    <div class="panel-body">
                        Share our promotional materials within your network 
                        online and offline to invite new partners to {{config('app.name')}}.
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="panel" style="border: lightgray thin solid">
                    <h3 class="panel-heading text-center box4__text">Earn</h3>
                    <div class="panel-body">
                        Earn up to {{to_currency(100000)}} in referral bonus by 
                        referring partners to sign up to a plan using your referral link.
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
<!-- get in touch -->
<!--<section class="section">
    <div class="container">
        
    </div>
</section>-->

<section id="contacts" class="section padding-1em">
    <div class="container">
        <div class="row">
            <!-- section title -->
            <div class="col-xs-12 animate" data-animate="fadeIn" data-duration="1.0s" data-delay="0.2s">
                <h2 class="section__title">Apply</h2>
                <p class="section__text">
                    Interested in becoming an afiliate? fill the form below.
                </p>
            </div>
            <!-- end section title -->

            <!--<div class="col-xs-12 col-sm-6 animate" data-animate="fadeIn" data-duration="1.0s" data-delay="0.5s">-->
            <div class="col-xs-12 col-sm-6 col-sm-offset-3 animate" data-animate="fadeIn" data-duration="1.0s" data-delay="0.5s">
                <!-- form -->
                <form action="#" class="form form--contacts">
                    <input type="text" name="name" class="form__input" required placeholder="Name">
                    <input type="text" name="email" class="form__input" required placeholder="Email">
                    <input type="text" name="location" class="form__input" placeholder="Location">
                    <textarea class="form__textarea" name="message" required placeholder="Comment"></textarea>
                    <p class="notify"></p>
                    <button class="form__btn" type="submit">Send</button>
                </form>
                <!-- end form -->
            </div>
        </div>
    </div>
</section>
<!-- end get in touch -->

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
    $('form.form--contacts').submit(function (event) {
        event.preventDefault();
        var form = $(this);
        $('.notify', form).css('color', 'black').show().text('Sending...');
        iAjax({
            url: "{{route('affiliate')}}",
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
