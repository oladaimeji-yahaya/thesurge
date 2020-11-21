@extends('layouts.admin')
@section('inner_content')
    <div class="container Josefins">
        <div class="row">
            <div class="col-xs-12 padding-btm-1em">
                <h2>
                    {{$user->name}} [{{$user->username}}]
                    <small class="badge {{$user->trashed()?'':($user->isSuspended()?'red':'green')}}">
                        {{$user->trashed()?'Deleted':($user->isSuspended()?'Suspended':'active')}}
                    </small>
                    @if($user->withdrawal_frozen)
                        <small class="badge amber">Withdrawal Frozen</small>
                    @endif
                    <a href="{{route('admin.users.view_referrals',['user'=>$user->id])}}" title="View Referrals">
                        <small class="glyphicon glyphicon-link"></small>
                    </a>
                    @if($user->super_affiliate)
                        <a href="{{route('admin.users.view_affiliates',['user'=>$user->id])}}">
                            <img class="affiliate_badge" style="width: 20px"
                                 src="{{asset('images/badges/super_affiliate.png')}}" alt=""/>
                        </a>
                    @endif
                    <div class="pull-right">
                        <a class="btn btn-default btn-sm" href="{{route('admin.users.index')}}">
                            &lt; All Users
                        </a>
                    </div>
                    <div class="pull-right">
                        <form action="{{route('admin.users.manage_list')}}" method="post" id="user-actions"
                              onsubmit="return false;">
                            <input name="action" value="" type="hidden">
                            <input name="id[]" value="{{$user->id}}" type="hidden">
                            <span class="pull-right">
                            <!-- Split button -->
                            <div class="btn-group btn-group-sm">
                                <a href="{{route('admin.users.login_as',['user'=>$user->id])}}" target="_blank"
                                   class="btn">
                                    Login as {{$user->first_name}}
                                </a>
                                <button type="button" class="btn btn-primary dropdown-toggle" data-toggle="dropdown"
                                        aria-haspopup="true"
                                        aria-expanded="false">
                                    Account Actions
                                    &nbsp;<span class="caret"></span>
                                    <span class="sr-only">Toggle Dropdown</span>
                                </button>
                                <ul class="dropdown-menu">
                                    {{--@if($user->super_affiliate)
                                        <li><button class="btn-link de-facto-text" value="remove_affiliate" href="#">Remove Affiliate</button></li>
                                    @else
                                        <li><button class="btn-link de-facto-text" value="add_affiliate" href="#">Make Affiliate</button></li>
                                    @endif--}}

                                    @if($user->withdrawal_frozen)
                                        <li><button class="btn-link de-facto-text" value="activate_withdrawal"
                                                    href="#">Activate Withdrawal</button></li>
                                    @else
                                        <li><button class="btn-link de-facto-text" value="freeze_withdrawal"
                                                    href="#">Freeze Withdrawal</button></li>
                                    @endif
                                    <li role="separator" class="divider"></li>

                                    @if($user->isSuspended())
                                        <li><button class="btn-link de-facto-text" value="unblock"
                                                    href="#">Activate</button></li>
                                    @else
                                        <li><button class="btn-link de-facto-text" value="block"
                                                    href="#">Suspend</button></li>
                                    @endif

                                    @if($user->trashed())
                                        <li><button class="btn-link de-facto-text" value="restore"
                                                    href="#">Restore</button></li>
                                    @else
                                        <li><button class="btn-link de-facto-text" value="delete"
                                                    href="#">Delete</button></li>
                                    @endif
                                    <li role="separator" class="divider"></li>

                                    @if($user->admin)
                                        <li><button class="btn-link de-facto-text" value="removeadmin" href="#">Remove Admin</button></li>
                                    @else
                                        <li><button class="btn-link de-facto-text" value="makeadmin"
                                                    href="#">Make Admin</button></li>
                                    @endif


                                    <li role="separator" class="divider"></li>
                                    <li><button class="btn-link de-facto-text" value="discard" href="#">Delete Permanently</button></li>
                                </ul>
                            </div>
                                <!-- Split button -->
                        </span>
                        </form>
                    </div>
                </h2>
            </div>
        </div>
        <div class="row margin-btm-2em">
            <div class="col-md-3 padding-btm-1em">
                <div class="text-center grey lighten-3"
                     style="{{$user->photo ? '' :"height: 150px" }}">
                    @if($user->photo)
                        <img style="max-height: 150px" src="{{ getStorageUrl($user->photo) }}"/>
                    @else
                        <p class="text-center padding-top-4em grey-text font-bold">No profile picture set</p>
                    @endif
                </div>
            </div>
            <div class="col-md-4 padding-btm-1em">
                <div class="text-center grey lighten-3"
                     style="{{$user->identity_photo ? '' :"height: 150px" }}">
                    @if($user->identity_photo)
                        <img style="max-height: 150px" src="{{ getStorageUrl($user->identity_photo) }}"/>
                    @else
                        <p class="text-center padding-top-4em grey-text font-bold">No identification set</p>
                    @endif
                </div>
            </div>
            <div class="col-md-5">
                <div class="row">
                    <div class="col-xs-12">
                        <strong>Contact:</strong><br/>
                        <a href="tel:{{$user->phone}}">{{$user->phone}}</a> -
                        <a href="mailto:{{$user->email}}">{{$user->email}}</a><br/>
                        {{$user->address}}
                    </div>
                    <div class="col-xs-12">
                        <strong>Wallet Address</strong><br/>
                        {{$user->wallet_id?:'none'}}
                    </div>
                    <div class="col-xs-12">
                        <strong>Balance</strong> <br/>
                        {{to_currency($user->withdrawableBalance())}}
                        ({{dollar_to_bitcoin($user->withdrawableBalance())}})
                    </div>
                    <div class="col-xs-12">
                        <strong>Bonus Unpaid</strong> <br/>
                        {{to_currency($user->unpaidBonus())}}
                        ({{dollar_to_bitcoin($user->unpaidBonus())}})
                        | <a href="{{route('admin.users.pay_bonus',[$user->id])}}">Mark Paid</a>
                    </div>
                </div>
            </div>
        </div>
        <h3>
            Transactions
            <div class="pull-right">
                <div class="btn-group btn-group-sm">
                    <button type="button" class="btn btn-primary dropdown-toggle"
                            data-toggle="dropdown" aria-haspopup="true"
                            aria-expanded="false">
                        Transaction Actions
                        &nbsp;<span class="caret"></span>
                        <span class="sr-only">Toggle Dropdown</span>
                    </button>
                    <ul class="dropdown-menu">
                        <li>
                            <button class="btn-link de-facto-text btn-deposit">Create Deposit</button>
                        </li>
                        <li>
                            <button class="btn-link de-facto-text btn-reinvest">Reinvest</button>
                        </li>
                        <li>
                            <button class="btn-link de-facto-text btn-withdraw">Withdraw</button>
                        </li>
                        <li>
                            <button data-toggle="modal" data-target="#bonus"
                                    class="btn-link de-facto-text btn-bonus">Add Bonus</button>
                        </li>
                    </ul>
                </div>
            </div>
        </h3>
        <p class="text-center padding-1em" hidden id="notify"></p>
        <div class="row">
            <div class="col-sm-6">
                <div class="text-center green white-text tiny-padding tab">
                    <h3 class="no-margin">Investments</h3>
                    <small class="white-text">{{to_currency($investments_amount)}}</small>
                    <p>Click to <span class="state">hide</span></p>
                </div>
                <div class="list tiny-padding white margin-top-1em margin-btm-1em scrollable">
                    @forelse($investments as $investment)
                        <a href="{{route('admin.investments.index',['q'=>$investment->reference])}}">
                            <div class="list-item">
                                <div class="{{$investment->trashed()?'grey-text':''}}">
                                    {{to_currency($investment->amount)}} ({{format_bitcoin($investment->btc)}})
                                    on {{$investment->created_at->format('M j, Y - g:i a')}}
                                </div>
                                <p class="{{$investment->trashed()?'grey-text':'black-text'}}">{{$investment->plan->name}}
                                    Plan</p>
                                @if($investment->expire_at->isPast() && $investment->verified_at)
                                    <div class="badge white-text green lighten-3">Completed
                                        on {{$investment->expire_at->format('M j, Y - g:i a')}}</div>
                                @elseif($investment->verified_at)
                                    <div class="badge white-text green">Verified
                                        on {{$investment->verified_at->format('M j, Y - g:i a')}}</div>
                                @elseif($investment->paid_at)
                                    <span class="badge white-text amber">Paid on {{$investment->paid_at->format('M j, Y - g:i a')}}</span>
                                @else
                                    <div class="badge">No payment yet</div>
                                @endif
                            </div>
                        </a>
                    @empty
                        <p>Nothing here</p>
                    @endforelse
                </div>
            </div>
            <div class="col-sm-6">
                <div class="text-center red white-text tiny-padding tab">
                    <h3 class="no-margin">Withdrawals</h3>
                    <small class="white-text">{{to_currency($withdrawals_amount)}}</small>
                    <p>Click to <span class="state">hide</span></p>
                </div>
                <div class="list tiny-padding white margin-top-1em margin-btm-1em scrollable">
                    @forelse($withdrawals as $withdrawal)
                        <a href="{{route('admin.withdrawals.index',['q'=>$withdrawal->reference])}}">
                            <div class="list-item">
                                <div class="{{$withdrawal->trashed()?'grey-text':''}}">
                                    {{to_currency($withdrawal->amount)}} ({{format_bitcoin($withdrawal->btc)}})
                                    on {{$withdrawal->created_at->format('M j, Y - g:i a')}}
                                </div>
                                @if($withdrawal->paid_at)
                                    <span class="badge white-text green lighten-3">Paid on {{$withdrawal->paid_at->format('M j, Y - g:i a')}}</span>
                                @else
                                    <div class="badge">No yet paid</div>
                                @endif
                                @if($withdrawal->reinvesting)
                                    <span class="badge">Reinvestment</span>
                                @endif
                            </div>
                        </a>
                    @empty
                        <p>Nothing here</p>
                    @endforelse
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-sm-6">
                <div class="text-center amber white-text tiny-padding tab">
                    <h3 class="no-margin">Bonuses</h3>
                    <small class="white-text">{{to_currency($user->totalBonus())}}</small>
                    <p>Click to <span class="state">view</span></p>
                </div>
                <div class="list tiny-padding white margin-top-1em margin-btm-1em scrollable hide">
                    @forelse($bonuses as $bonus)
                        <div class="list-item">
                            <div class="{{$bonus->used?'grey-text':''}}">
                                {{to_currency($bonus->amount)}} ({{dollar_to_bitcoin($bonus->amount)}})
                                on {{$bonus->created_at->format('M j, Y - g:i a')}}
                            </div>
                            <p class="{{$bonus->used?'grey-text':'black-text'}}">{{$bonus->name}}</p>
                            @if($bonus->used)
                                <div class="badge">Paid</div>
                            @else
                                <div class="badge white-text amber">Not paid</div>
                            @endif
                        </div>
                    @empty
                        <p>Nothing here</p>
                    @endforelse
                </div>
            </div>
        </div>
    </div>
    @include('parts.modal_calculator')

    <form class="modal fade product-details-content" tabindex="-1"
          id="withdraw" role="dialog">
        <div class="modal-dialog">
            <!-- Modal content-->
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                    <h4 class="modal-title">Withdraw</h4>
                </div>
                <div class="modal-body">
                    <div class="content-box plan row">
                        <div class="col-md-6">
                            <p class="font-bold">Amount ($):</p>
                            <p class="quantity">
                                <input name="amount" class="amount form-control"
                                       required type="number" placeholder="1000">
                                <input type="hidden" name="user" value="{{$user->id}}"/>
                            </p>
                            <button type="submit" class="btn btn-primary no-margin">
                                Withdraw <i class="fa fa-shopping-cart"></i>
                            </button>
                        </div>
                    </div>
                    <p class="notify"></p>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                </div>
            </div>
        </div><!-- /.modal -->
    </form>

    <form class="modal fade product-details-content" tabindex="-1"
          id="bonus" role="dialog">
        <div class="modal-dialog">
            <!-- Modal content-->
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                    <h4 class="modal-title">Add Bonus</h4>
                </div>
                <div class="modal-body">
                    <div class="content-box plan row">
                        <div class="col-md-10">
                            <p class="font-bold">Amount ($):</p>
                            <p class="quantity">
                                <input name="amount" class="amount form-control"
                                       required type="number" placeholder="1000">
                                <input type="hidden" name="user" value="{{$user->id}}"/>
                            </p>
                            <p class="font-bold">Reason:</p>
                            <p class="quantity">
                                <input name="name" class="form-control"
                                       required value="Referral bonus" placeholder="Referral bonus"/>
                            </p>
                            <button type="submit" class="btn btn-primary no-margin">
                                Add Bonus <i class="fa fa-shopping-cart"></i>
                            </button>
                        </div>
                    </div>
                    <p class="notify"></p>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                </div>
            </div>
        </div><!-- /.modal -->
    </form>


@endsection

@section('head')
    @parent
    <style>
        div.tab {
            cursor: pointer;
        }

        div.scrollable {
            max-height: 400px;
            overflow-x: hidden;
            overflow-y: scroll;
        }

        a:hover {
            text-decoration: none;
        }

        .list .list-item {
            border-bottom: lightgrey thin solid;
            padding: 10px;
        }
    </style>
@endsection

@section('scripts')
    @parent
    <script type="text/javascript">
        var plans = <?= json_encode($plans) ?>;
        var balance = <?= $user->withdrawableBalance(); ?>;
        var user_id = <?= $user->id ?>;
        var deposit_url = "{{route('admin.users.deposit')}}";
        var reinvest_url = "{{route('admin.users.reinvest')}}";
        var withdraw_url = "{{route('admin.users.withdraw')}}";
        var bonus_url = "{{route('admin.users.add_bonus')}}";

        $(function () {
            var NP = $('#notify');

            var account_form = $('#user-actions');
            $(":submit", account_form).click(function () {
                $("form").data("submit-action", this.value);
            });
            account_form.submit(function (e) {
                e.preventDefault();
                $('input[name=action]', account_form).val(button = $(this).data("submit-action"));
                $('button[type=submit]', account_form).attr('disabled', true);
                iAjax({
                    url: account_form.attr('action'),
                    method: "POST",
                    data: account_form.serialize(),
                    onSuccess: function (response) {
                        notify(NP, response);
                        if (response.status == true) {
                            setTimeout(function () {
                                window.location.reload();
                            }, 1000);
                        }
                    },
                    onFailure: function (xhr) {
                        handleHttpErrors(xhr, account_form, '#notify');
                    },
                    onComplete: function () {
                        $('button[type=submit]', account_form).removeAttr('disabled');
                    }
                });
            });

            var calculator_form = $('#calculator');
            calculator_form.append($('<input>').attr({name: 'user', value: user_id, type: 'hidden'}));
            $('.btn-deposit').click(function () {
                $('input[name=amount]', calculator_form).removeAttr('max');
                $('.modal-title', calculator_form).text('Deposit Funds');
                $('button[type=submit]', calculator_form).text('Deposit');
                $('select[name=coin]', calculator_form).prop('disabled', false);
                $('.coin', calculator_form).show();
                reinvest = false;
                setupCalculatorForm();
                calculator_form.modal('show');
            });

            $('.btn-reinvest').click(function () {
                $('input', calculator_form).attr({max: balance});
                $('.modal-title', calculator_form).text('Reinvest from Balance');
                $('button[type=submit]', calculator_form).text('Reinvest');
                $('select[name=coin]', calculator_form).prop('disabled', true);
                $('.coin', calculator_form).hide();
                reinvest = true;
                setupCalculatorForm();
                calculator_form.modal('show');
            });

            let withdraw_form = $('#withdraw');
            $('.btn-withdraw').click(function () {
                $('.modal-title', withdraw_form).text('Withdraw from Balance ($' + balance + ')');
                $('input', withdraw_form).attr({max: balance}).prop('disabled', !balance);
                $('button[type=submit]', withdraw_form).prop('disabled', !balance);
                withdraw_form.modal('show');
            });

            withdraw_form.off('submit')
                .on('submit', function (e) {
                    e.preventDefault();
                    iAjax({
                        url: withdraw_url,
                        data: withdraw_form.serialize(),
                        onSuccess: function (xhr) {
                            handleHttpErrors(xhr);
                            if (xhr.status) {
                                setTimeout(function () {
                                    window.location.reload();
                                }, 1000);
                            }
                        },
                        onFailure: function (xhr) {
                            handleHttpErrors(xhr);
                        }
                    });
                });

            var bonus_form = $('#bonus');
            bonus_form.off('submit')
                .on('submit', function (e) {
                    e.preventDefault();
                    iAjax({
                        url: bonus_url,
                        data: bonus_form.serialize(),
                        onSuccess: function (xhr) {
                            handleHttpErrors(xhr);
                            if (xhr.status) {
                                setTimeout(function () {
                                    window.location.reload();
                                }, 1000);
                            }
                        },
                        onFailure: function (xhr) {
                            handleHttpErrors(xhr);
                        }
                    });
                });

            function setupCalculatorForm() {
                calculator_form.off('submit')
                    .on('submit', function (e) {
                        e.preventDefault();
                        iAjax({
                            url: reinvest ? reinvest_url : deposit_url,
                            data: calculator_form.serialize(),
                            onSuccess: function (xhr) {
                                handleHttpErrors(xhr);
                                if (xhr.status) {
                                    setTimeout(function () {
                                        window.location.reload();
                                    }, 1000);
                                }
                            },
                            onFailure: function (xhr) {
                                handleHttpErrors(xhr);
                            }
                        });
                    });
            }

        });

        $('div.tab').click(function () {
            var body = $(this).next('div');
            if (body.hasClass('hide')) {
                body.removeClass('hide');
                $('span.state', this).text('hide');
            } else {
                body.addClass('hide');
                $('span.state', this).text('view');
            }
        });

    </script>
@endsection