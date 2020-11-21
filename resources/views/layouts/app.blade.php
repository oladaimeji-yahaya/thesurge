<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8"/>
    <meta name="viewport" content="width=device-width, initial-scale=1.0"/>

    <!-- Favicon -->
    <link rel="shortcut icon" href="{{asset('images/favicon.png')}}">

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <script>
        window.Laravel = <?= json_encode(['csrfToken' => csrf_token(),]); ?>;
        window.homepage = '<?= url('/'); ?>';
        window.updateBTC = '<?= shouldUpdateCrypto(); ?>';
    </script>
    <title>@yield('meta_title',($meta['title'] ?? 'Welcome')) - {{config('app.name')}}</title>
    @yield('head')
</head>
<body class="light" style="background-color: #0d2841;
">
<!-- Wrapper Starts -->
<div class="wrapper">
    @yield('header')
    @yield('content')
    @yield('footer')
</div>
<!-- Wrapper Ends -->

<div id="page-loader">
    <?php
    $loader_text = 'Just a moment...';
    $loader_classes = '';
    ?>
    @include('parts.loader')
</div>

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

    <script type="text/javascript">
        window.$crisp = [];
        window.CRISP_WEBSITE_ID = "20db4a39-f6d3-4076-bd7e-389e07b15750";
        (function () {
            d = document;
            s = d.createElement("script");
            s.src = "https://client.crisp.chat/l.js";
            s.async = 1;
            d.getElementsByTagName("head")[0].appendChild(s);
        })();
    </script>

{{--    <!--Start of Tawk.to Script-->--}}
{{--    <script type="text/javascript">--}}
{{--        var Tawk_API = Tawk_API || {}, Tawk_LoadStart = new Date();--}}
{{--        (function () {--}}
{{--            var s1 = document.createElement("script"), s0 = document.getElementsByTagName("script")[0];--}}
{{--            s1.async = true;--}}
{{--            s1.src = 'https://embed.tawk.to/5e85055169e9320caabf5d8b/default';--}}
{{--            s1.charset = 'UTF-8';--}}
{{--            s1.setAttribute('crossorigin', '*');--}}
{{--            s0.parentNode.insertBefore(s1, s0);--}}
{{--        })();--}}
{{--    </script>--}}
{{--    <!--End of Tawk.to Script-->--}}

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
