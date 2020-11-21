@extends('layouts.oldapp')
@section('head')
    <style type="text/css">
        body {
            padding-top: 70px;
            background-image: none;
        }
    </style>
@endsection
@section('header')
    <nav class="navbar navbar-default navbar-fixed-top">
        <div class="container">
            <!-- Brand and toggle get grouped for better mobile display -->
            <div class="navbar-header">
                <button type="button" class="navbar-toggle collapsed" data-toggle="collapse"
                        data-target="#navbar-admin-top"
                        aria-expanded="false">
                    <span class="sr-only">Toggle navigation</span>
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                </button>
                <a class="navbar-brand" href="{{route('home')}}">
                    <img src="{{asset('images/logo-full.png')}}" alt="{{config('app.name')}}"/>
                </a>
            </div>

            <!-- Collect the nav links, forms, and other content for toggling -->
            <div class="collapse navbar-collapse" id="navbar-admin-top">
                <form class="navbar-form navbar-left hidden-sm" id="search">
                    <div class="input-group input-group-sm">
                        <input type="text" name="q" value="{{request('q')}}" class="form-control" placeholder="Search">
                        <span class="input-group-btn">
                        <button class="btn btn-default" type="button">
                            <span class="glyphicon glyphicon-search" aria-hidden="true"></span>
                        </button>
                    </span>
                    </div>
                </form>
                <ul class="nav navbar-nav navbar-right">
                    <li><a href="{{route('admin.dashboard.index')}}">Dashboard</a></li>
                    <li><a href="{{route('admin.investments.index')}}">Investments</a></li>
                    <li><a href="{{route('admin.withdrawals.index')}}">Withdrawals</a></li>
                    <li><a href="{{route('admin.coinpayment.trasactions')}}">Transactions</a></li>
                    <li class="dropdown">
                        <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true"
                           aria-expanded="false">
                            People
                            <span class="caret"></span>
                        </a>
                        <ul class="dropdown-menu">
                            <li><a href="{{route('admin.users.index')}}">Users</a></li>
                        <!--<li><a href="{{route('admin.affiliate.index')}}">Affiliates</a></li>-->
                            <li><a href="{{route('admin.admins.index')}}">Admins</a></li>
                        <!--<li><a href="{{route('admin.affiliate.requests.index')}}">Affiliate Requests</a></li>-->
                        </ul>
                    </li>
                    <li class="dropdown">
                        <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true"
                           aria-expanded="false">
                            Faker
                            <span class="caret"></span>
                        </a>
                        <ul class="dropdown-menu">
                        <!--                        <li><a href="{{route('admin.faker.team.index')}}">Team</a></li>
                                                <li><a href="{{route('admin.faker.testimonials.index')}}">Testimonials</a></li>
                                                <li><a href="{{route('admin.faker.payouts.index')}}">Payouts</a></li>-->
                            <li><a href="{{route('admin.faker.faqs.index')}}">FAQs</a></li>
                        </ul>
                    </li>
                    <li class="dropdown">
                        <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true"
                           aria-expanded="false">
                            Settings
                            <span class="caret"></span>
                        </a>
                        <ul class="dropdown-menu">
                            <li><a href="{{route('admin.plans.index')}}">Plans</a></li>
                        <!--<li><a href="{{route('admin.exchanges.index')}}">Exchanges</a></li>-->
                            <li><a href="{{route('admin.settings.index')}}">System</a></li>
                        </ul>
                    </li>
                    <li class="dropdown">
                        <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true"
                           aria-expanded="false">
                            <span class="glyphicon glyphicon-user" aria-hidden="true"></span>
                            <span class="caret"></span>
                        </a>
                        <ul class="dropdown-menu">
                            <li><a href="{{route('dashboard.profile')}}">Update Profile</a></li>
                            <li role="separator" class="divider"></li>
                            <li><a href="{{route('dashboard.index')}}">User Dashboard</a></li>
                            <li>
                                <form id="logout-form" action="{{ route('logout') }}" method="POST"
                                      style="display: none;">
                                    {{ csrf_field() }}
                                </form>
                                <a href="#"
                                   onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                                    Logout
                                </a>
                            </li>
                        </ul>
                    </li>
                </ul>
            </div><!-- /.navbar-collapse -->
        </div><!-- /.container-fluid -->
        <noscript>
            <div class="text-center orange white-text">
                <p class="no-margin">JavaScript is not enabled.
                    Enable JavaScript for better experience with this site.</p>
            </div>
        </noscript>
    </nav>
@endsection

@section('content')
    <div class="" style="min-height: 100vh">
        @yield('inner_content')
    </div>
@endsection


@section('scripts')
    <!--Alert Modal-->
    <div class="modal fade" tabindex="-1" id="alertModal" role="dialog">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header" style="border-bottom: none;padding-bottom: 0">
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

    <script src="{{asset('js/utilities.js')}}" type="text/javascript">
    </script>
    <script>
        $(".toggle-btn").click(function () {
            var checkBoxes = $($(this).attr('data-toggle'));
            checkBoxes.prop("checked", !checkBoxes.prop("checked"));
        });
    </script>
@endsection