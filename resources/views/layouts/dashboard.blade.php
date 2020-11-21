@extends('layouts.app')

@section('head')
    <!-- Common Plugins -->
    <link href="{{asset('dash/lib/bootstrap/css/bootstrap.min.css')}}" rel="stylesheet">

    <!-- Vector Map Css-->
    <link href="{{asset('dash/lib/vectormap/jquery-jvectormap-2.0.2.css')}}" rel="stylesheet"/>

    <!-- Chart C3 -->
    <link href="{{asset('dash/lib/chart-c3/c3.min.css')}}" rel="stylesheet">
    <link href="{{asset('dash/lib/chartjs/chartjs-sass-default.css')}}'" rel="stylesheet">

    <!-- DataTables -->
    <link href="{{asset('dash/lib/datatables/jquery.dataTables.min.css')}}" rel="stylesheet" type="text/css">
    <link href="{{asset('dash/lib/datatables/responsive.bootstrap.min.css')}}" rel="stylesheet" type="text/css">
    <link href="{{asset('dash/lib/toast/jquery.toast.min.css')}}" rel="stylesheet">

    <!-- Custom Css-->
    <link href="{{asset('/css/colors.css')}}" rel="stylesheet" type="text/css"/>
    <link href="{{asset('/css/custom.css')}}" rel="stylesheet" type="text/css"/>
    <link href="{{asset('dash/scss/style.css')}}" rel="stylesheet">
    <link href="{{asset('dash/scss/toastr.min.css')}}" rel="stylesheet">

@endsection


@section('header')
    <?php $unreadNot = Auth::user()->unreadNotifications->count() ?>

    <!-- ============================================================== -->
    <!-- 						Topbar Start 							-->
    <!-- ============================================================== -->
    <div class="top-bar primary-top-bar" style="background-color: #06092c;">
        <div class="container-fluid">
            <div class="row">
                <div class="col">
                    <a class="admin-logo" href="{{env('HOME_URL')}}">
                        <h1>
                            <img alt="" src="{{asset('dash/img/logos/logo-half.png')}}" class="logo-icon margin-r-10">
                            <img alt="" src="{{asset('dash/img/logos/logo-writeup.png')}}" class="toggle-none hidden-xs">
                        </h1>
                    </a>
                    <div class="left-nav-toggle">
                        <a href="#" class="nav-collapse"><i class="fa fa-bars"></i></a>
                    </div>
                    <div class="left-nav-collapsed">
                        <a href="#" class="nav-collapsed"><i class="fa fa-bars"></i></a>
                    </div>
                    <ul class="list-inline top-right-nav">
                        <li class="dropdown avtar-dropdown">
                            <a class="dropdown-toggle" data-toggle="dropdown" href="#">
                                {{--                                <img alt="" class="rounded-circle" src="{{asset('dash/img/avtar-2.png')}}" width="30">--}}
                                {{Auth::user()->name}}
                                @if($unreadNot)
                                    <span class="badge badge-pill badge-danger float-right mr-2">{{fancyMaxCount($unreadNot)}}</span>
                                @endif
                            </a>
                            <ul class="dropdown-menu top-dropdown">
                                <li>
                                    <a class="dropdown-item" href="{{route('dashboard.notifications')}}"><i
                                                class="icon-bell"></i>
                                        Notifications
                                        @if($unreadNot)
                                            <span class="badge badge-pill badge-danger float-right mr-2">{{fancyMaxCount($unreadNot)}}</span>
                                        @endif
                                    </a>
                                </li>
                                <li>
                                    <a class="dropdown-item" href="{{route('dashboard.profile')}}"><i
                                                class="icon-user"></i> Profile</a>
                                </li>
                                @if(Auth::user()->admin)
                                    <li>
                                        <a class="dropdown-item" href="{{route('admin.dashboard.index')}}"><i
                                                    class="icon-diamond"></i> Admin</a>
                                    </li>
                                @endif
                                <li class="dropdown-divider"></li>
                                <li>
                                    <a class="dropdown-item logout-btn" href="#"><i class="icon-logout"></i> Logout</a>
                                </li>
                            </ul>
                        </li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
    <!-- ============================================================== -->
    <!--                        Topbar End                              -->
    <!-- ============================================================== -->

@endsection


@section('content')
    <!-- ============================================================== -->
    <!-- 						Navigation Start 						-->
    <!-- ============================================================== -->
    <div class="main-sidebar-nav default-navigation" style="background-color: #0d2841;">
        <div class="nano">
            <div class="nano-content sidebar-nav">

                <div class="card-body border-bottom nav-profile">
                    {{--<div class="notify setpos"><span class="heartbit"></span> <span class="point"></span></div>--}}
                    {{--<img alt="profile" class="margin-b-10  " src="{{asset('dash/img/avtar-2.png')}}" width="80">--}}
                    <p class="lead margin-b-0 toggle-none">{{Auth::user()->name}}</p>
                    <p class="text-muted mv-0 toggle-none">Welcome</p>
                </div>

                <ul class="metisMenu nav flex-column" id="menu">
                    {{--@if($user->super_affiliate)
                        <a href="{{route('dashboard.affiliate')}}">
                            <div class="text-center padding-1em white margin-top-1em"
                                 style="border: lightgray thin solid">
                                <img class="affiliate_badge" style="width: 60px"
                                     src="{{asset('images/badges/super_affiliate.png')}}" alt=""/>
                                <p class="font-bold">Super Affiliate</p>
                                {{$user->affiliateLevel->name}}
                            </div>
                        </a>
                    @endif--}}

                    <li class="nav-heading"><span>MAIN</span></li>
                    <li class="nav-item">
                        <a class="nav-link" href="{{route('dashboard.index')}}"><i class="fa fa-home"></i> <span
                                    class="toggle-none">Dashboard</span></a>
                    </li>
                    <li class="nav-heading"><span>Finances</span></li>
                    <li class="nav-item">
                        <a class="nav-link" href="javascript: void(0);" aria-expanded="false"><i
                                    class="fa fa-google-wallet"></i> <span class="toggle-none">Transactions<span
                                        class="fa arrow"></span></span></a>
                        <ul class="nav-second-level nav flex-column sub-menu" aria-expanded="false">
                            <li class="nav-item"><a class="nav-link" href="{{route('dashboard.invest')}}">Deposit Funds</a></li>
                            <li class="nav-item"><a class="nav-link" href="{{route('dashboard.withdrawals')}}">Withdraw Funds</a></li>
                        </ul>
                    </li>
                    <li class="nav-heading"><span>Account Summary</span></li>
                    <li class="nav-item">
                        <a class="nav-link" href="javascript: void(0);" aria-expanded="false"><i
                                    class="fa fa-database"></i>
                            <span class="toggle-none">History<span class="fa arrow"></span></span></a>
                        <ul class="nav-second-level nav flex-column sub-menu" aria-expanded="false">
                            <li class="nav-item"><a class="nav-link" href="{{route('dashboard.investments')}}">Deposit
                                    History</a></li>
                            <li class="nav-item"><a class="nav-link" href="{{route('dashboard.withdrawals')}}">Withdrawal
                                    History</a></li>
                            <li class="nav-item"><a class="nav-link" href="{{route('dashboard.bonuses')}}">Bonuses</a>
                            </li>
                            @if($user->super_affiliate)
                                <li class="nav-item"><a class="nav-link" href="{{route('dashboard.affiliate')}}">
                                        Super Affiliate Referrals</a></li>
                            @endif
                        </ul>
                    </li>
                    <li class="nav-heading"><span>Referrals</span></li>
                    <li class="nav-item">
                        <a class="nav-link" href="{{route('dashboard.referrals')}}" aria-expanded="false">
                            <i class="fa fa-link"></i> <span
                                    class="toggle-none">Referrals</span></a>
                    </li>
                    <li class="nav-heading"><span>Settings</span></li>

                    <li class="nav-item">
                        <a class="nav-link" href="{{route('dashboard.profile')}}" aria-expanded="false"><i class="fa fa-cogs"></i> <span
                                    class="toggle-none">Profile</span></a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="#" aria-expanded="false"><i class="fa fa-lock"></i> <span
                                    class="toggle-none logout-btn">Logout</span></a>
                    </li>
                </ul>
            </div>
        </div>
    </div>
    <!-- ============================================================== -->
    <!-- 						Navigation End	 						-->
    <!-- ============================================================== -->

    <!-- ============================================================== -->
    <!-- 						Content Start	 						-->
    <!-- ============================================================== -->
    <div class="row page-header" style="background-color: #0d2841;">
        <div class="col-lg-6 align-self-center ">
            <h2>{{$meta['title'] or ''}}</h2>
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="#">Home</a></li>
                <li class="breadcrumb-item active">{{$meta['title'] or ''}}</li>
            </ol>
        </div>
        <div class="col-lg-6 align-self-center text-right">
            <a href="{{route('dashboard.invest')}}" class="btn btn-success box-shadow btn-icon btn-rounded">
                <i class="fa fa-plus"></i>Deposit</a>
        </div>
    </div>

    <section class="main-content">
        @yield('dashboard_content')

        <footer class="footer">
            <span>Copyright &copy; <?php echo date('Y') . ' ' . config('app.name') ?> . All rights reserved.</span>
        </footer>

    </section>
    <!-- ============================================================== -->
    <!-- 						Content End		 						-->
    <!-- ============================================================== -->
@endsection


@section('scripts')
    <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
        {{ csrf_field() }}
    </form>
    <!--Alert Modal-->
    <div class="modal fade" tabindex="-1" id="alertModal" role="dialog">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header" style="border-bottom: none;padding-bottom: 0; color: #000">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span
                                aria-hidden="true">&times;</span></button>
                </div>
                <div class="modal-body text-center">
                    <img style="margin-bottom: 10px"/>
                    <h2 class="modal-title"></h2>
                    <div class="modal-text"></div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-primary ok"></button>
                    <button type="button" class="btn btn-default" data-dismiss="modal"></button>
                </div>
            </div><!-- /.modal-content -->
        </div><!-- /.modal-dialog -->
    </div><!-- /.modal -->
    <img src="{{asset('images/alert/error.png')}}" alt="" hidden=""/>
    <img src="{{asset('images/alert/info.png')}}" alt="" hidden=""/>
    <img src="{{asset('images/alert/question.png')}}" alt="" hidden=""/>
    <img src="{{asset('images/alert/success.png')}}" alt="" hidden=""/>
    <img src="{{asset('images/alert/warning.png')}}" alt="" hidden=""/>
    <!--<script src="{{asset('js/utilities.js')}}" type="text/javascript"></script>-->

    <!-- Common Plugins -->
    <script src="{{asset('dash/lib/jquery/dist/jquery.min.js')}}"></script>
    <script src="{{asset('dash/lib/bootstrap/js/popper.min.js')}}"></script>
    <script src="{{asset('dash/lib/bootstrap/js/bootstrap.min.js')}}"></script>
    <script src="{{asset('dash/lib/pace/pace.min.js')}}"></script>
    <script src="{{asset('dash/lib/jasny-bootstrap/js/jasny-bootstrap.min.js')}}"></script>
    <script src="{{asset('dash/lib/slimscroll/jquery.slimscroll.min.js')}}"></script>
    <script src="{{asset('dash/lib/nano-scroll/jquery.nanoscroller.min.js')}}"></script>
    <script src="{{asset('dash/lib/metisMenu/metisMenu.min.js')}}"></script>
    <script src="{{asset('dash/js/custom.js')}}"></script>
    {{--<script src="{{asset('dash/js/control.js')}}"></script>--}}

    <!--Chart Script-->
    <script src="{{asset('dash/lib/chartjs/chart.min.js')}}"></script>
    <script src="{{asset('dash/lib/chartjs/chartjs-sass.js')}}"></script>

    <!--Vetor Map Script-->
    <script src="{{asset('dash/lib/vectormap/jquery-jvectormap-2.0.2.min.js')}}"></script>
    <script src="{{asset('dash/lib/vectormap/jquery-jvectormap-us-aea-en.js')}}"></script>

    <!-- Chart C3 -->
    <script src="{{asset('dash/lib/chart-c3/d3.min.js')}}"></script>
    <script src="{{asset('dash/lib/chart-c3/c3.min.js')}}"></script>

    <!-- Sparkline Chart-->
    <script src="{{asset('dash/lib/sparkline/jquery.sparkline.min.js')}}"></script>

    <!-- Datatables-->
    <script src="{{asset('dash/lib/datatables/jquery.dataTables.min.js')}}"></script>
    <script src="{{asset('dash/lib/datatables/dataTables.responsive.min.js')}}"></script>
    <script src="{{asset('dash/lib/toast/jquery.toast.min.js')}}"></script>
    <script src="{{asset('dash/js/dashboard.js')}}"></script>
    <script src="{{asset('dash/js/crypto.custom.js')}}"></script>
    <script>
        $('.logout-btn').click(function (e) {
            e.preventDefault();
            $('#logout-form').submit();
        });
    </script>
@endsection

