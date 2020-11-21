@extends('layouts.dashboard')
@section('dashboard_content')
    <div class="card">
        <h2 class="card-header">Referral Link</h2>
        <div class="card-body">
            <div class="reflinks-blk">
{{--                <span class="reflinks grey lighten-2">{{$reflink = route('reflink',['ref'=>$user->ref_code])}}</span>--}}
                <span class="reflinks grey lighten-2">{{$reflink = route('reflink',['ref'=>$user->username])}}</span>
                <button class="btn btn-default" onclick="copyToClipboard('{{$reflink}}');">copy
                </button>
            </div>

            <p class="padding-top-1em">
                You were referred by {{is_object($user->referredBy)?$user->referredBy->user->name:'none'}}
            </p>
            <small>(This may be automatically assigned if you were not referred)</small>
        </div>
    </div>

    <div class="card">
        <h2 class="card-header">Referred Users</h2>
        <div class="card-body">
            @if($referrals->count())
                <div class="table-responsive">
                    <table class="table">
                        <thead>
                        <tr>
                            <th>Name</th>
                            <th>Registered</th>
                            <th>Bonus</th>
                            <th>Status</th>
                        </tr>
                        </thead>
                        <tbody>
                        @foreach($referrals as $referral)
                            <tr class="{{$referral->status()}}">
                                <td>{{is_object($referral->referredUser)?$referral->referredUser->name:'User Removed'}}</td>
                                <td>{{$referral->created_at->diffForHumans()}}</td>
                                <td>{{format_dollar($referral->is_confirmed)}}</td>
                                <td>{{ucfirst($referral->status())}}</td>
                            </tr>
                        @endforeach
                        </tbody>
                        <tfoot>
                        <tr>
                            <td colspan="4" class="legend">
                                <div class="confirmed"></div>
                                <abbr title="User has made payments">Confirmed</abbr>
                                <div class="pending"></div>
                                <abbr title="User has not made payments yet">Pending</abbr>
                                <div class="used"></div>
                                <abbr title="Confirmed and bonus awarded">Used</abbr>
                            </td>
                        </tr>
                        <tr>
                            <td colspan="4" class="line">
                                <div>Total {{$referralsCount}}</div>
                                <div>Used {{$used}}</div>
                                <div>Confirmed {{$confirmed}}</div>
                                <div>Pending {{$pending}}</div>
                            </td>
                        </tr>
                        <tr>
                            <td colspan="4">
                                {{$referrals->links()}}
                            </td>
                        </tr>
                        </tfoot>
                    </table>
                </div>
            @else
                <div class="">
                    <p>No referrals yet.</p>
                </div>
            @endif
        </div>
    </div>
@endsection

@section('head')
    @parent
    <style>
        .reflinks {
            display: inline-block;
            padding: 3px;
            border: #aaa thin solid;
            border-radius: 5px;
            margin-right: 5px;
        }

        .reflinks-blk {
            margin-top: 3px;
        }

        tfoot td.line div {
            display: inline-block;
            margin-right: 20px;
        }

        table > tbody tr.used,
        table > tbody tr.used:nth-of-type(odd),
        table > tfoot .used {
            color: #aaa;
            background-color: rgba(136, 136, 136, 0.64);
        }

        table > tbody tr.pending,
        table > tbody tr.pending:nth-of-type(odd),
        table > tfoot .pending {
            color: white;
            background-color: rgba(39, 152, 228, 0.68);
            /*background-color: skyblue;*/
        }

        table > tbody tr.confirmed:nth-of-type(odd),
        table > tfoot .confirmed {
            border: lightgrey thin solid;
            /*background-color: #222;*/
        }

        tfoot td.legend div {
            display: inline-block;
            height: 10px;
            width: 30px;
        }

        tfoot td.legend abbr {
            margin: auto 20px auto 2px;
        }
    </style>
@endsection

@section('scripts')
    @parent

@endsection