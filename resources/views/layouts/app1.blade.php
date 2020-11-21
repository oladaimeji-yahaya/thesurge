<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <link rel="icon" href="{{asset('assets/images/favicon.png')}}" type="image/x-icon" />
 
    <<script>
        window.Laravel = <?= json_encode(['csrfToken' => csrf_token(),]); ?>;
        window.homepage = '<?= url('/'); ?>';
        window.updateBTC = '<?= shouldUpdateCrypto(); ?>';
    </script>
    <title>@yield('meta_title',($meta['title'] ?? 'Welcome')) - {{config('app.name')}}</title>
    @yield('head')
    
    <link href="{{asset('assets/css/style.css')}}" rel="stylesheet"> 
    <script src="{{asset('assets/css/bootstrap.min.css')}}"></script>  

</head>

<body>

<div class="wrapper">
    @yield('header')
    @yield('content')
    @yield('footer')
</div>
<!-- Wrapper Ends -->



@yield('scripts')
<script src="{{asset('js/utilities.js')}}?v=1.0.1"></script>
<script type="text/javascript">
    if (window.updateBTC) {
        $.getJSON("https://blockchain.info/ticker", function (data) {
            iAjax({
                url: window.homepage + '/updateTicker/btc',
                data: {update: data},
                method: 'POST'
            });
        });
        $.getJSON("https://api.blockchain.info/stats", function (data) {
            iAjax({
                url: window.homepage + '/updateTicker/btcinfo',
                data: {update: data},
                method: 'POST'
            });
        });
    }
</script>

@if(!app()->isLocal())
    <!--Tracking and chat scripts goes here-->

    
    <!--Google translate-->
    <div id="google_translate_element" style="position: fixed; bottom: 0; left: 0; width: 100%; z-index: 1000;"></div>
    <script type="text/javascript">
        function googleTranslateElementInit() {
            new google.translate.TranslateElement({
                pageLanguage: 'en',
                layout: google.translate.TranslateElement.InlineLayout.SIMPLE
            }, 'google_translate_element');
        }
    </script>
    <script type="text/javascript" src="//translate.google.com/translate_a/element.js?cb=googleTranslateElementInit">
    </script>
    <!--Google translate ends-->
@endif
</body>
</html>
