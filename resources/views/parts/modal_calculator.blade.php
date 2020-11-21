<form action="<?= $calculator_ation ?? route('dashboard.invest') ?>" 
      class="modal fade product-details-content" tabindex="-1" 
      id="calculator" role="dialog">
    <div class="modal-dialog">
        <!-- Modal content-->
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h4 class="modal-title">Calculator</h4>
            </div>
            <div class="modal-body">
                <div class="title">
                    <h2>Select Plan</h2>
                </div>
                <div class="padding-top-1em padding-btm-1em">
                    <select required name="plan" class="selectpicker show-tick" data-width="100%" title="Click here to choose a plan">
                        <?php foreach ($plans as $plan): ?>
                            <option value="<?= $plan->id ?>" title="<?= $plan->name ?> plan selected">
                                <?= $plan->name ?> plan
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div class="content-box plan hidden row">
                    <p class="description col-sm-12">
                        Get <span class="rate"></span>% return of your investment every <span class="incubation"></span>
                        for a <span class="duration"></span> period
                    </p>
                    <div class="col-md-6">
                        <span class="line">ROI Rate: <span class="rate"></span>%</span>
                        <span class="line">Total ROI: <span class="trate"></span>%</span>
                        <span class="line">Interval <span class="incubation"></span></span>
                        {{--<span class="line">Compounding: <span class="compounding"></span></span>--}}
                        <span class="line">Duration: <span class="duration"></span></span>
                        <span class="line"></span>
                    </div>
                    <div class="col-md-6">
                        <p class="font-bold">Amount ($):</p>
                        <p class="quantity">
                            <input onkeyup="calculate()" 
                                   name="amount" class="amount form__input" 
                                   required type="number" placeholder="1000">
                        </p>
                        <p class="line font-bold">Total earning at the end of this plan will be: $<span class="earning">0</span></p>
                        @if(!setting('use_coinpayment'))
                            <div class="margin-btm-1em coin">
                                <label for="coins" class="font-bold">Select method of payment</label>
                                <select id="coins" name="coin" class="form-control">
                                    @foreach($coins as $coin)
                                        <option value="{{$coin->id}}">{{$coin->name}}</option>
                                    @endforeach
                                </select>
                            </div>
                        @endif
                        <button type="submit" class="btn btn-primary no-margin">
                            <?= $calculator_btn_text ?? 'Get Started' ?> <i class="fa fa-shopping-cart"></i>
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


@section('head')
<link href="<?= asset('plugins/bootstrap-select/dist/css/bootstrap-select.min.css') ?>" rel="stylesheet" type="text/css"/>
@parent
<style>
    .product-details-content .content-box .quantity input {
        width: 100%;
        text-align: left;
    }
    .product-details-content .content-box{
        display: block;
    }
    .product-details-content .content-box span.line {
        display: block;
        font-size: 18px;
        color: black;
        border-top: 1px solid #EAEAEA;
        padding-top: 10px;
        margin-top: 10px;
    }
    .product-details-content .content-box p.quantity{
        color: black;
    }
    .content-box.plan{
        padding: 10px;
    }

    /*Modal override*/
    .modal {
        background-color: rgba(0,0,0,.4); 
        width: auto; 
        max-width: none; 

        position: fixed;
        top: 0;
        right: 0;
        bottom: 0;
        left: 0;
        z-index: 1050;
        display: none;
        overflow: hidden;
        -webkit-overflow-scrolling: touch;
        outline: 0;
    }
</style>
@endsection


@section('scripts')
<script src="<?= asset('plugins/bootstrap-select/dist/js/bootstrap-select.min.js') ?>" type="text/javascript">
</script>
<script>
    var plans = <?= json_encode($plans) ?>;
</script>
<script src="<?= asset('js/common/calculator.js') ?>" type="text/javascript"></script>
@parent
@endsection
