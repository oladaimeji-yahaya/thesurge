@extends('layouts.admin');
@section('inner_content')
<div class="container">
    <p>
        Pending and future transactions will be sent to this wallet address 
        <span class="tiny-padding grey lighten-2">{{$wire_address}}</span> after payment are confirmed.<br/>
        You can change the wallet address at the <a href="{{route('admin.settings.index')}}">settings</a> page.
    </p>   
    <div class="table-responsive">
        <table class="table table-hover table-striped">
            <thead>
                <tr>
                    <th>Details</th>
                    <th>Amount</th>
                    <th>Paid With</th>
                    <th>Pay Status</th>
                    <th>Received</th>
                    <th>Receive Status</th>
                    <th>Paid On</th>
                </tr>
            </thead>
            <tbody>
                @forelse($transactions as $transaction)
                <tr class="{{$transaction->isComplete() ?'':'grey-text'}}" >
                    <td>
                        From<br/>
                        <a class="btn-link" href="{{route('admin.users.view',['user'=>is_object($user = $transaction->user)?$transaction->user->id:''])}}">
                            {{is_object($user)?$user->name:'User Deleted'}}
                        </a>
                        <br/>
                        Reference<br/>
                        <a href="{{route('admin.investments.index',['q'=>$transaction->reference])}}">{{$transaction->reference}}</a>
                    </td>
                    @php
                    $investment = $investments->where('reference',$transaction->reference)->first();
                    @endphp
                    <td>{{$investment?to_currency($investment->amount):'Investment Deleted'}}</td>
                    <td>{{$transaction->coin}}</td>
                    <td>{{$transaction->status_text}}</td>
                    <td>
                        @if($transaction->wire_amount)
                        {{$transaction->wire_amount}} {{$transaction->wire_currency}} => {{$transaction->wire_address}}
                        @else
                        -
                        @endif
                    </td>
                    <td>{{$transaction->wireStatus()}}</td>
                    <td>{{$transaction->payment_created_at?$transaction->payment_created_at->format('M j, Y - g:i a'):'-'}}</td>
                </tr>
                @empty
                <tr>
                    <td colspan="7" class="text-center"><p class="lead">No transactions yet</p></td>
                </tr>
                @endforelse
            </tbody>
            <tfoot>
                <tr>
                    <td colspan="7">{{$transactions->appends($_GET)->links()}}</td>
                </tr>
            </tfoot>
        </table>
        <p>* Note that it could take a few minutes before payment is received at the configured wallet address after user completes payment.</p> 
    </div>
</div>
@endsection
