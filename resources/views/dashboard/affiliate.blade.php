@extends('layouts.dashboard')
@section('dashboard_content')
    <div class="card">
        <div class="card-body">
            <div class="title">
                <h2>Affiliate Progress</h2>
                <div class="decor-line"></div>
            </div>
            <div class="padding-top-1em row">
                <div class="col-md-6 text-center">
                    <p class="font-bold font-lg">{{$user->affiliateLevel->rank}}</p>
                    <p>Super Affiliate Level</p>
                </div>
                <div class="col-md-6 text-center" style="border-left: lightgray thin solid">
                    <p class="font-bold font-lg">
                        {{is_object($user->referredBy)?$user->referredBy->user->name:'none'}}
                    </p>
                    <p>Your Sponsor</p>
                </div>
            </div>

            <div class="title">
                <h2>Referral Link</h2>
                <div class="decor-line"></div>
            </div>
            <div class="reflinks-blk">
                <span class="reflinks grey lighten-2">{{$reflink = route('reflink',['ref'=>$user->ref_code])}}</span>
                <button class="btn btn-default" onclick="copyToClipboard('<?= $reflink ?>');alert('Copied');">copy
                </button>
            </div>

            <div class="title">
                <h2>Downliners
                    <small>(total {{$matrices->total()}})</small>
                </h2>
                <div class="decor-line"></div>
                <div class="form-group">
                    <label for="level">Levels</label>
                    <select autocomplete="off" class="form-control" id="level"
                            onchange="window.location = '?level='+this.value">
                        <option value="">All Levels</option>
                        @foreach($levels as $level)
                            <option value="{{$level->id}}" {{$level->id == request('level')?'selected':''}}>
                                {{$level->name}}
                            </option>
                        @endforeach
                    </select>
                </div>
            </div>
            <div class="grey lighten-2 padding-1em margin-top-1em" style="border: lightgray thin solid">
                <div class="table-responsive">
                    <table class="table table-striped">
                        <thead>
                        <tr>
                            <th>Downliner</th>
                            <th>Referrer</th>
                            <th>Level</th>
                            <th>Position</th>
                            <th>Amount</th>
                            <th>Percentage</th>
                            <th>Released</th>
                        </tr>
                        </thead>
                        <tbody>
                        @forelse($matrices as $matrix)
                            <tr class="">
                                <td>
                                    <button class="btn-link" onclick="$(this).next().toggleClass('hidden')">
                                        {{$matrix->user? $matrix->user->name : 'User Deleted'}}
                                    </button>
                                    @if($matrix->user)
                                        <div class="hidden font-bold tiny-padding white"
                                             style="border: lightgray thin solid">
                                            Contact Email: <a
                                                    href="mailto:{{$matrix->user->email}}">{{$matrix->user->email}}</a><br/>
                                            Contact Phone: <a
                                                    href="tel:{{$matrix->user->phone}}">{{$matrix->user->phone}}</a>
                                        </div>
                                    @endif
                                </td>
                                <td>
                                    {{$matrix->referral->user->name}}
                                </td>
                                <td>{{$matrix->level->name}}</td>
                                <td>{{$matrix->position}}</td>
                                <td>{{to_currency($matrix->amount)}}</td>
                                <td>{{$matrix->percentage}}%</td>
                                <td>{{$matrix->released? 'Yes' : 'No'}}</td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" class="text-center">You have no referrals yet</td>
                            </tr>
                        @endforelse
                        </tbody>
                        <tfoot>
                        <tr>
                            <td colspan="7">{{$matrices->appends($_GET)->links()}}</td>
                        </tr>
                        </tfoot>
                    </table>
                </div>
            </div>
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

    </style>
@endsection

@section('scripts')
    @parent

@endsection