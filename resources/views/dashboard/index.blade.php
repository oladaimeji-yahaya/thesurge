@extends('layouts.dashboard')

@section('dashboard_content')
    <div class="row margin-b-30">
        <div class="col-md-3">
            <div class="widget  bg-light">
                <div class="row row-table ">
                    <div class="margin-b-20">
                        {{--<img src="{{asset('dash/img/coin/32/bitcoin.png')}}" class="float-right margin-r-10">--}}
                        <h2 class="margin-b-5">Deposits</h2>
                        <p class="text-muted">{{dollar_to_bitcoin($deposits)}}</p>
                        <span class="text-indigo widget-r-m" id="curr_inv">{{to_currency($deposits)}}</span>
                    </div>
                    <p class="text-muted float-left margin-b-0">
                        <i class="icon-info"></i> Total amount invested</p>
                </div>
            </div>
        </div>

        <div class="col-md-3">
            <div class="widget bg-light">
                <div class="row row-table ">
                    <div class="margin-b-20">
                        {{--<img src="{{asset('dash/img/coin/32/bitcoin.png')}}" class="float-right margin-r-10">--}}
                        <h2 class="margin-b-5">Withdrawals</h2>
                        <p class="text-muted">{{dollar_to_bitcoin($withdrawals)}}</p>
                        <span class="text-warning widget-r-m" id="today_profit">{{to_currency($withdrawals)}}</span>
                    </div>
                    <p class="text-muted float-left margin-b-0">
                        <i class="icon-info"></i> Total amount withdrawn</p>
                </div>
            </div>
        </div>

        <div class="col-md-3">
            <div class="widget  bg-light">
                <div class="row row-table ">
                    <div class="margin-b-20">
                        {{--<img src="{{asset('dash/img/coin/32/bitcoin.png')}}" class="float-right margin-r-10">--}}
                        <h2 class="margin-b-5">Ledger Balance</h2>
                        <p class="text-muted">{{dollar_to_bitcoin($ledger)}}</p>
                        <span class="text-muted widget-r-m" id="last_withdrawal">{{to_currency($ledger)}}</span>
                    </div>
                    <p class="text-muted float-left margin-b-0">
                        <i class="icon-info"></i> Total balance</p>
                </div>
            </div>
        </div>

        <div class="col-md-3">
            <div class="widget  bg-light">
                <div class="row row-table ">
                    <div class="margin-b-20">
                        {{--<img src="{{asset('dash/img/coin/32/bitcoin.png')}}" class="float-right margin-r-10">--}}
                        <h2 class="margin-b-5">Current Balance</h2>
                        <p class="text-muted">{{dollar_to_bitcoin($balance)}}</p>
                        <span class="text-muted widget-r-m" id="account_balance">{{to_currency($balance)}}</span>
                    </div>
                    <p class="text-muted float-left margin-b-0">
                        <i class="icon-info"></i> Withdrawable balance</p>
                </div>
            </div>
        </div>
    </div>
{{--

    <div class="row">
        <div class="col-md-3">
            <div class="widget widget-chart white-bg padding-0">
                <div class="widget-title">
                    <span class="label label-primary float-right">Income</span>
                    <h2 class="margin-b-0">Net Income</h2>
                </div>
                <div class="widget-content">
                    <h1 class="margin-b-10  text-primary" id="total_profit"></h1>
                    <p class="text-muted margin-b-0">Total income</p>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="widget widget-chart white-bg padding-0">
                <div class="widget-title">
                    <span class="label label-success float-right">Profit</span>
                    <h2 class="margin-b-0">This Month</h2>
                </div>
                <div class="widget-content">
                    <h1 class="margin-b-10 text-success">$0</h1>
                    <p class="text-muted margin-b-0">Total profit</p>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="widget widget-chart white-bg padding-0">
                <div class="widget-title">
                    <span class="label label-warning float-right">Deposit</span>
                    <h2 class="margin-b-0">This Month</h2>
                </div>
                <div class="widget-content">
                    <h1 class="margin-b-10 text-warning">$0</h1>
                    <p class="text-muted margin-b-0">Total deposit</p>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="widget widget-chart white-bg padding-0">
                <div class="widget-title">
                    <span class="label label-danger float-right">Withdrawal</span>
                    <h2 class="margin-b-0">Monthly</h2>
                </div>
                <div class="widget-content">
                    <h1 class="margin-b-10 text-danger">$0</h1>
                    <p class="text-muted margin-b-0">Total Withdrawal</p>
                </div>
            </div>
        </div>
    </div>
--}}

    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header card-default">
                    Live Crypto Prices
                </div>
                <div class="card-body">
                    <!-- TradingView Widget BEGIN -->
                    <div class="tradingview-widget-container">
                        <div class="tradingview-widget-container__widget"></div>
                        <script type="text/javascript"
                                src="https://s3.tradingview.com/external-embedding/embed-widget-screener.js" async>
                            {
                                "width"
                            :
                                "100%",
                                    "height"
                            :
                                "500",
                                    "defaultColumn"
                            :
                                "overview",
                                    "screener_type"
                            :
                                "crypto_mkt",
                                    "displayCurrency"
                            :
                                "USD",
                                    "colorTheme"
                            :
                                "light",
                                    "locale"
                            :
                                "en",
                                    "isTransparent"
                            :
                                true
                            }
                        </script>
                    </div>
                    <!-- TradingView Widget END -->
                </div>
            </div>
        </div>
    </div>

@endsection
