<!DOCTYPE html>
<html lang="en">

    <head>

        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
        <meta name="description" content="">
        <link rel="icon" type="image/png" href="{{asset('images/favicon.png')}}">
        <link rel="apple-touch-icon" href="{{asset('images/favicon.png')}}">
        <!--	<link rel="apple-touch-icon" sizes="72x72" href="icon/apple-touch-icon-72x72.png">
                <link rel="apple-touch-icon" sizes="114x114" href="icon/apple-touch-icon-114x114.png">
                <link rel="apple-touch-icon" sizes="144x144" href="icon/apple-touch-icon-144x144.png">-->

        <!-- Bootstrap core CSS -->
        <link href="{{asset('/css/bootstrap.min.css')}}" rel="stylesheet" type="text/css"/>
        <link href="{{asset('/css/colors.css')}}" rel="stylesheet" type="text/css"/>
        <link href="{{asset('/css/custom.css')}}" rel="stylesheet" type="text/css"/>


        <!-- Temporary navbar container fix -->
        <style>
            .navbar-toggler {
                z-index: 1;
            }

            @media (max-width: 576px) {
                nav > .container {
                    width: 100%;
                }
            }
        </style>
        <!-- CSRF Token -->
        <meta name="csrf-token" content="{{ csrf_token() }}">
        <script>
            window.Laravel = <?= json_encode(['csrfToken' => csrf_token(),]); ?>;
            window.homepage = '<?= url('/'); ?>';
        </script>
        <title>{{$meta['title'] or 'Welcome'}} - {{config('app.name')}}</title>

        @yield('head')
    </head>

    <body class="index" id="page-top" >
        <nav>
            @yield('nav')
        </nav>

        <!--header-->
        <header>
            @yield('header')
        </header>
        <!--end header-->
        <!--content-->
        <main class="bg-white">
            @yield('content')
        </main>
        <!--end content-->

        @yield('body')

        <footer class="text-center padding-1em">
            Copyright &copy; <?php echo date('Y') . ' ' . config('app.name') ?> . All rights reserved.
        </footer>
        <div id="page-loader">
            <?php
            $loader_text = 'Just a moment...';
            $loader_classes = '';
            ?>
            @include('parts.loader')
        </div>
        <!-- Placed at the end of the document so the pages load faster -->
        <script src="https://ajax.googleapis.com/ajax/libs/jquery/2.2.4/jquery.min.js"></script>
        <script>window.jQuery || document.write('<script src="<?= asset('/js/jquery-2.2.4.min.js') ?>"><\/script>');</script>
        <script src="{{ asset('/js/bootstrap.min.js') }}"></script>
        <script type="text/javascript">
            $(window).scroll(function (e) {
                if ($(window).scrollTop() > 1000) {
                    $('#scroll-top').show();
                } else {
                    $('#scroll-top').hide();
                }
            });

            function showPageLoader() {
                $('#page-loader, #page-loader #loader').show();
            }
            function hidePageLoader() {
                $('#page-loader, #page-loader #loader').hide();
            }
        </script>
        @yield('scripts')
    </body>
</html>
