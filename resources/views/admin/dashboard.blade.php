@extends('layouts.admin')
@section('inner_content')
<div class="container">
    <div class="row stat">
        <div class="col-sm-3">
            <a href="{{route('admin.users.index')}}">
                <div class="green text-center">
                    <p class="font-lg no-margin">{{$users_count}}</p>
                    <small>Users</small>
                </div>
            </a>
        </div>
        <div class="col-sm-3">
            <a href="{{route('admin.investments.index',['filter'=>'pending'])}}">
                <div class="blue text-center">
                    <p class="font-lg no-margin">{{$paid_investment_count}}</p>
                    <small>Pending Verification</small>
                </div>
            </a>
        </div>
        <div class="col-sm-3">
            <a href="{{route('admin.withdrawals.index',['filter'=>'pending'])}}">
                <div class="orange lighten-1 text-center">
                    <p class="font-lg no-margin">{{$queue_count}}</p>
                    <small>Pending Withdrawals</small>
                </div>
            </a>
        </div>
        <div class="col-sm-3">
            <a href="{{route('admin.admins.index')}}">
                <div class="grey text-center">
                    <p class="font-lg no-margin">{{$admin_count}}</p>
                    <small>Admins</small>
                </div>
            </a>
        </div>
    </div>
    <div class="row padding-btm-1em">
        <div class="col-md-6">
            <h3>Exchange Rates (Coin Market Cap)
                <!--<a href="{{route('admin.update.rates')}}" class="btn btn-sm">update</a>-->
            </h3>
            <div class="table-responsive scrollable">
                <table class="table table-hover table-striped">
                    <thead>
                        <tr>
                            <th>Rank</th>
                            <th>Name</th>
                            <th>USD</th>
                            <th>BTC</th>
                            <th>Updated</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($exchanges as $info)
                        <tr>
                            <td>{{$info->rank}}</td>
                            <td>{{$info->name}} ({{$info->symbol}})</td>
                            <td>{{$info->price_usd}}</td>
                            <td>{{$info->price_btc}}</td>
                            <td>{{$info->updated_at->diffForHumans()}}</td>
                        </tr>
                        @empty
                        <tr><td colspan="3">Not updated yet</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
        <div class="col-md-6">
            <h3>Bitcoin in Details (Blockchain)
                <!--<a href="{{route('admin.update.rates')}}" class="btn btn-sm">update</a>-->
            </h3>
            <div class="table-responsive scrollable">
                <table class="table table-hover table-striped">
                    <thead>
                        <tr>
                            <th>Currency</th>
<!--                            <th>15 minutes delayed</th>
                            <th>Last</th>-->
                            <th>Buy</th>
                            <th>Sell</th>
                            <th>Updated</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($btcrates as $rate)
                        <tr>
                            <td>{{$rate->currency}} ({{$rate->symbol}})</td>
<!--                            <td>{{$rate->_15m}}</td>
                            <td>{{$rate->last}}</td>-->
                            <td>{{$rate->buy}}</td>
                            <td>{{$rate->sell}}</td>
                            <td>{{$rate->updated_at->diffForHumans()}}</td>
                        </tr>
                        @empty
                        <tr><td colspan="7">Not updated yet</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection

@section('head')
@parent
<style>
    div.scrollable{
        max-height: 400px;
        /*overflow-x: hidden;*/
        overflow-y: scroll;
    }
</style>
@endsection