@if(old('message'))
    <p class="padding-1em {{old('status')?'bg-success green-text ':'bg-danger red-text '}}text-center">
        {{old('message')}}
    </p>
@endif


@if($reinvest)
    @section('meta_title','Reinvest')
@endif

@if($hasIncompleteInvestment)
    <div id="complete-purchase-form">
        <div class="title margin-btm-1em">
            <h2>Make Payment</h2>
        </div>
        <div class="content-box padding-btm-1em row">
            <div class="col-sm-6">
                <div class="margin-btm-1em">
                    Pay <span class="green lighten-4 font-bold">{{format_crypto($coinpayment['amountf'] ,$incompleteInvestment->exchange_id)}}</span>
                    equivalent of <span class="font-bold">{{to_currency($incompleteInvestment->amount)}}</span>
                    @if(!setting('use_coinpayment'))
                        to the wallet address:
                        <div class="input-group coin">
                            <input type="text" class="form-control" readonly="" value="{{$wallet}}">
                            <div class="input-group-append">
                                <button class="btn btn-primary"
                                        onclick="copyToClipboard('{{$wallet}}');">Copy
                                </button>
                            </div>
                        </div>
                    @endif
                </div>

                <span class="line">Amount: <span>{{format_dollar($incompleteInvestment->amount)}}</span></span>
                <span class="line">Reference: <span>{{$incompleteInvestment->reference}}</span></span>
            </div>
            <div class="col-sm-6">
                @if(!setting('use_coinpayment'))
                    <img alt="Payment QR" class="coin"
                         src="https://chart.googleapis.com/chart?chs=230x230&cht=qr&chl={{$coinURI}}:{{$wallet}}?amount={{round($coinpayment['amountf'],3)}}"/>
                @endif
            </div>

            <div class="col-sm-12">
                 <form onsubmit="$('button[type=submit]', this).prop('disabled', true)"
                              action="{{route('dashboard.receipts')}}" method="post" enctype="multipart/form-data">
                            <p class="font-bold">Proof of Payment</p>
                            <div class="form-group files">
                                {{ csrf_field() }}
                                <input type="hidden" name="reference" value="{{$incompleteInvestment->reference}}"/>
                                <div>
                                    <input required
                                           onchange="$('#complete-purchase-form button[type=submit]').prop('disabled', false);
                                                           $('#complete-purchase-form .btn-more').prop('disabled', false)"
                                           type="file" name="receipt[]" accept="image/*"/>
                                </div>
                            </div>
                            <div class="form-group">
                                <button type="button"
                                        class="btn btn-sm btn-more btn-link" disabled
                                        onclick="$('#complete-purchase-form .files').append($('<div>')
                                                                .append('<input required style=\'display: inline-block\' type=\'file\' class=\'more\' name=\'receipt[]\' accept=\'image/*\'/>')
                                                                .append('<button type=\'button\' class=\'btn btn-link btn-sm\' onclick=\'$(this).parent().remove()\' >remove</button>'))">
                                    <i class="fa fa-plus"></i> Add More
                                </button>
                                <button type="submit" disabled class="btn-paid btn btn-rounded btn-icon btn-success">Upload Proof of Payment
                                </button>
                               
                            </div>
                        </form>
                        <hr>
                @if(setting('use_coinpayment'))
                    <p>Your payment will be automatically confirmed and verified, make sure to send the complete
                        amount of bitcoin</p>
                    <button class="btn btn-rounded btn-icon btn-primary"
                            onclick="$('#<?= $coinpayment['btn_form_id'] ?>').submit()">
                        <i class="fa fa-bitcoin"></i>
                        Pay with CoinPayment
                    </button>
                    {!!$coinpayment['btn_form']!!}
                @else
                    <a href="{{route('dashboard.paidInvestment',['reference'=>$incompleteInvestment->reference])}}"
                       class="btn-paid btn btn-rounded btn-icon btn-success">
                        <i class="fa fa-check"></i>
                        I've made payment
                    </a>
                @endif

                <a href="{{route('dashboard.cancelInvestment',['reference'=>$incompleteInvestment->reference])}}"
                   class="btn-cancel btn">Cancel</a>
            </div>
        </div>
    </div>
@else

    <form id="purchase-form" class="tiny-padding" method="post"
          action="{{route('dashboard.invest')}}">
        <div class="title">
            <h2>
                @if(!$reinvest && $plan = Auth::user()->suggestReinvestPlan())
                    Deposit
                    <a href="{{route('dashboard.invest',['reinvest'=>true,'plan'=>$plan->id,'amount'=>Auth::user()->withdrawableBalance()])}}">
                        or reinvest
                    </a>
                @else
                    @if($reinvest)
                        Reinvesting from balance
                    @else
                        Select Plan
                    @endif
                @endif
            </h2>
            <div class="decor-line"></div>
        </div>
        <div class="padding-top-1em padding-btm-1em">
            <select required name="plan" class="form-control" data-width="100%" title="Click here to choose a plan">
                <option value="" title="Choose a plan">Choose a plan</option>
                @foreach($plans as $plan)
                    @if($reinvest && $plan->minimum > $balance)
                        @continue
                    @endif
                    <option value="{{$plan->id}}" title="{{$plan->name}} plan selected">
                        {{$plan->name}} plan
                    </option>
                @endforeach
            </select>
        </div>
        <div class="content-box plan hidden row">
            <div class="col-md-6">
                <span class="line">ROI Rate: <span class="rate">0</span>%</span>
                <!--<span class="line">Total ROI: <span class="trate"><0/span>%</span><br/>-->
                <span class="line">ROI Interval: <span class="incubation">0</span></span>
                {{--<span class="line">Minimum Compounding: $<span class="compounding">0</span></span>--}}
                <span class="line">Duration: <span class="duration">0</span></span>
                <span class="line description">
                    You'll earn <span class="rate">0</span>% return of your investment every <span
                            class="incubation">0</span>
                    for a <span class="duration">0</span> period
                </span>
            </div>
            <div class="col-md-6">
                <p class="quantity">
                    <label class="font-bold">Amount ($):</label>
                    <input onkeyup="calculate()"
                           name="amount" class="amount form-control"
                           required type="number" placeholder="1000">
                </p>
                <p class="line text-muted">
                    <i class="icon-info"></i>
                    Your total earning at the end of this plan will be: $<span
                            class="earning">0</span>
                </p>
                @if(!setting('use_coinpayment') && !$reinvest)
                    <div class="margin-btm-1em">
                        <label for="coins" class="font-bold">Select method of payment</label>
                        <select id="coins" name="coin" class="form-control">
                            @foreach($coins as $coin)
                                <option value="{{$coin->id}}">{{$coin->name}}</option>
                            @endforeach
                        </select>
                    </div>
                @endif
                <input name="reinvest" type="hidden" value="{{$reinvest}}">
                <button type="submit" class="btn btn-primary btn-rounded btn-icon">
                    <i class="fa fa-shopping-cart"></i>
                {{$reinvest?'Reinvest':'Proceed'}}
                <!--Change "Proceed" to "Make Payment" later, when coinpayment is implemented-->
                </button>
            </div>
        </div>
    </form>
@endif

@section('head')
    @parent
    <style>
        span.line {
            display: block;
            padding: 10px 0;
        }

        span.line span {
            font-weight: bold;
        }

        span.line:not(:first-child) {
            border-top: #e0e0e0 thin solid;
        }
    </style>
@endsection

@section('scripts')
    @parent
    <script src="{{asset('js/common/calculator.js?v=1.0.1')}}" type="text/javascript"></script>
    <script>
        var plans = <?= json_encode($plans) ?>;
        var reinvest = <?= $reinvest ? 'true' : 'false' ?>;
        var balance = <?= $balance ?>;
        var checkPayment = <?= $hasIncompleteInvestment ? 'true' : 'false' ?>;
        var reference = '<?= $hasIncompleteInvestment ? $incompleteInvestment->reference : '' ?>';
        var investmentPage = "{{route('dashboard.investments')}}";
        $(function () {
            var plan = getUrlParameter('plan');
            if (plan !== '' && plan !== null) {
                // $('select[name=plan].selectpicker').selectpicker();
                // $('select[name=plan]').selectpicker("val", plan);

                var amount = parseInt(getUrlParameter('amount'));
                updateInfo(true, amount);
            }

            if (checkPayment) {
                var chkPay = setInterval(function () {
                    iAjax({
                        url: "{{route('coinpayment.verifypayment')}}",
                        data: {reference: reference},
                        onSuccess: function (xhr) {
                            if (xhr.status) {
                                clearInterval(chkPay);
                                showAlertModal({
                                    title: 'Deposit Received!',
                                    text: "Great, we've received your deposit and awaiting confirmations",
                                    okAction: function () {
                                        window.location.reload();
                                    }
                                });
                            }
                        },
                        onFailure: function () {
                            clearInterval(chkPay);
                        }
                    });
                }, 5000);
            }
        });

        $('#complete-purchase-form .btn-cancel').click(function (e) {
            e.preventDefault();
            var button = this;
            var okAction = function () {
                ajaxUsingBtnURL(button, function (response) {
                    showAlertModal({
                        'title': 'Request Cancelled Sucessfully',
                        'type': 'success',
                        okAction: function () {
                            window.location.reload();
                        }
                    });
                });
            };

            showAlertModal({
                'title': 'Are you sure?',
                'text': 'Investment will be cancelled',
                type: 'question',
                showCancel: true,
                okAction: okAction
            });
        });

        $('#purchase-form').submit(function (e) {
            e.preventDefault();
            var form = this;
            var amount = $('input.amount', form).val();
            var plan = $('select[name=plan]', form).val();
            if (amount && plan) {
                ajaxUsingFormURL(form, function (response) {
                    if (reinvest) {
                        window.location = investmentPage;
                    } else {
                        window.location.reload();
                    }
                });
            } else {
                showAlertModal('Please enter an amount');
            }
        });

    </script>
@endsection
