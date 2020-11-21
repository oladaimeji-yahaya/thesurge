@extends('layouts.admin')
@section('inner_content')
    <div class="container Josefins">
        <div class="row">
            <div class="col-xs-12 padding-btm-1em">
                <h2>
                    {{$user->name}} referrals |
                    <a href="{{route('admin.users.view',['user'=>$user->id])}}" title="View Profile">
                        <small class="glyphicon glyphicon-user"></small>
                    </a>
                </h2>
                Referred by {{is_object($user->referredBy)?$user->referredBy->user->name:'none'}}
            </div>
        </div>
        <div class="">
            <div class="title">
                <h2>Referred Users</h2>
                <div class="decor-line"></div>
            </div>
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
                                <td>
                                    @if(is_object($referral->referredUser))
                                        <a class="btn-link"
                                           href="{{route('admin.users.view',['user'=>$referral->referredUser->id])}}">
                                            {{$referral->referredUser->name}}
                                        </a>
                                    @else
                                        {{'User Removed'}}
                                    @endif
                                </td>
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
        <div class="title">
            <h2>Referral Link</h2>
        </div>
        <div class="reflinks-blk">
            <span class="reflinks grey lighten-2">{{$reflink = route('reflink',['ref'=>$user->ref_code])}}</span>
            <button class="btn btn-default" onclick="copyToClipboard('<?= $reflink ?>');alert('Copied');">copy
            </button>
        </div>

    </div>

@endsection

@section('head')
    @parent
    <style>

    </style>
@endsection

@section('scripts')
    @parent

@endsection