@extends('layouts.directory')
@section('content')
<section>
    <div class="container">
        <div class="padding-1em white">
            <div>
                <div tabindex="0">
                    <h1 align="left" style="color:#000;"><strong> Support </strong></h1>
                </div>
            </div>
            <hr />
            <div style="text-align: left">
                <div>
                    <div tabindex="0">
                        <h3 align="left">
                            <strong>Who can join {{config('app.name')}}?</strong>
                        </h3>
                    </div>
                    <p>
                        Everybody and anybody.<br/>
                        We contribute to the common good. The participants are asked only to
                        follow the recommendations and avoid penalties.
                    </p>

                    <div tabindex="0">
                        <h3 align="left"><strong>How to participate safely</strong></h3>
                    </div>
                    <p>
                        The system on it&apos;s own track and monitor suspicious and fraudulent activities.
                        It monitors the integrity of participants and if your guilt is proven,
                        you will be excluded from {{config('app.name')}}.
                        That may be cool but we cannot do it all on our own, on your part, ensure that you do not share you
                        credentials such as, credit card details and passwords with someone else.
                        Note that the {{config('app.name')}} Team will never ask for them.
                    </p>

                    <div tabindex="0"><h3 align="left"><strong>How much money can I raise?</strong></h3>
                    </div>
                    <p>
                        We offer unlimited income potential. Many of our members earn thousands
                        very quickly. We cannot, however, make income projections for you.<br/>
                        This will be due to your efforts, perseverance and marketing consistency.
                        This is the most powerful automated system online today and was created for novices
                        online as well as advanced marketers. The only income limits are ones that you make
                        on yourself.
                    </p>
                </div>
                @include('parts.support')
            </div>
        </div>
    </div>
</section>

@endsection

@section('head')
@parent
<!--Start of Tawk.to Script-->
<!--<script type="text/javascript">
    var Tawk_API = Tawk_API || {}, Tawk_LoadStart = new Date();
    (function () {
        var s1 = document.createElement("script"), s0 = document.getElementsByTagName("script")[0];
        s1.async = true;
        s1.src = 'https://embed.tawk.to/592b8ec94374a471e7c50350/default';
        s1.charset = 'UTF-8';
        s1.setAttribute('crossorigin', '*');
        s0.parentNode.insertBefore(s1, s0);
    })();
</script>-->
<!--End of Tawk.to Script-->
@endsection

@section('scripts')
@parent
@endsection