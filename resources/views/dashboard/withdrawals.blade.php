@extends('layouts.dashboard')
@section('dashboard_content')

    <div class="card">
        <div class="card-body">
            <div class="row margin-btm-1em">
                <div class="col-sm-12">
                    <span class="pull-left">Available for withdrawal: <b>{{to_currency($balance)}}</b></span>
                    @if($plan = Auth::user()->suggestReinvestPlan())
                        <a class="btn btn-primary btn-rounded btn-icon pull-right margin-l-5"
                           href="{{route('dashboard.invest')}}?reinvest=1&plan={{$plan->id}}&amount={{Auth::user()->withdrawableBalance()}}">
                            Reinvest Balance
                            <i class="fa fa-recycle"></i>
                        </a>
                    @endif
                    <button type="button" class="btn btn-warning btn-rounded btn-icon pull-right withdraw">
                        Checkout
                        <i class="fa fa-dollar"></i>
                    </button>
                </div>
            </div>

            <!--Withdrawal form-->
            <div id="withdrawal-form" class="d-none">
                @if($profileComplete)
                    @if($balance)
                        <form action="{{route('dashboard.withdraw')}}" method="post"
                              class="grey lighten-3 padding-1em form-inline">
                            <div class="form-group">
                                <label for="amount" class="margin-r-5">Amount</label>
                                <input id="amount" max="{{$balance}}" required type="number"
                                       class="form-control amount"
                                       name="amount" autofocus
                                       data-validation-required-message="Please enter an amount.">
                            </div>
                            <div class="form-group">
                                <button type="submit" class="btn btn-sm btn-primary">Request</button>
                                <button type="button" class="btn btn-sm btn-danger cancel-withdraw">Cancel</button>
                            </div>
                        </form>
                    @else
                        <p class="text-center red-text">You do not have enough balance for withdrawal</p>
                    @endif
                @else
                    <p class="text-center red-text">Some information about you is missing, please complete your
                        <a href="{{route('dashboard.profile')}}">profile</a>
                        first.
                    </p>
                @endif
            </div>

            <div class="table-responsive">
                <table class="table table-striped">
                    <thead>
                    <tr>
                        <th>Reference</th>
                        <th>Created</th>
                        <th>Amount</th>
                        <th>BTC</th>
                        <th>Approved</th>
                        <th>Paid</th>
                        <th></th>
                    </tr>
                    </thead>
                    <tbody>
                    @forelse($withdrawals as $h)
                        <tr class="">
                            <td>{{$h->reference}}</td>
                            <td>{{$h->created_at->format('M j, Y - g:i a')}}</td>
                            <td>{{to_currency($h->amount)}}</td>
                            <td>{{format_bitcoin($h->btc)}}</td>
                            <td>
                                {{$h->approved_at?$h->approved_at->format('M j, Y - g:i a'):'Pending'}}
                            </td>
                            <td>
                                {{$h->paid_at?$h->paid_at->format('M j, Y - g:i a'):'No'}}
                            </td>
                            <td>
                                @if(empty($h->approved_at))
                                    <a href="{{route('dashboard.cancelWithdrawal',['reference'=>$h->reference])}}"
                                       class="btn btn-sm btn-del">
                                        <i class="fa fa-trash"></i>
                                    </a>
                                @endif
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="text-center">You haven&apos;t made any withdrawals yet</td>
                        </tr>
                    @endforelse
                    </tbody>
                    <tfoot>
                    <tr>
                        <td colspan="7">{{$withdrawals->links()}}</td>
                    </tr>
                    </tfoot>
                </table>
            </div>
        </div>
    </div>


@endsection

@section('head')
    @parent

@endsection

@section('scripts')
    @parent
    <script>
        $('button.withdraw').click(function (e) {
            $('#withdrawal-form').removeClass('d-none');
            $(this).hide();
        });
        $('button.cancel-withdraw').click(function (e) {
            $('#withdrawal-form').addClass('d-none');
            $('button.withdraw').show();
        });
        $('tr .btn-del').click(function (e) {
            e.preventDefault();
            var btn = this;
            var okAction = function () {
                ajaxUsingBtnURL(btn, function (response) {
                    showAlertModal({
                        'title': 'Successful',
                        'type': 'success',
                        okAction: function () {
                            window.location.reload();
                        }
                    });
                });
            };

            showAlertModal({
                title: 'Are you sure?',
                text: 'Withdrawal request will be deleted',
                type: 'question',
                showCancel: true,
                okAction: okAction
            });
        });

        $('#withdrawal-form form').submit(function (e) {
            e.preventDefault();
            var form = this;
            var amount = $('input.amount', form).val();
            if (amount) {
                var okAction = function () {
                    ajaxUsingFormURL(form, function (response) {
                        showAlertModal({
                            'title': 'Successful',
                            'type': 'success',
                            okAction: function () {
                                window.location.reload();
                            }
                        });
                    });
                };

                showAlertModal({
                    title: 'Are you sure?',
                    text: 'We will create a withdrawal request for $' + amount,
                    type: 'info',
                    showCancel: true,
                    okAction: okAction
                });
            } else {
                showAlertModal('Please enter an amount');
            }
        });
    </script>
@endsection