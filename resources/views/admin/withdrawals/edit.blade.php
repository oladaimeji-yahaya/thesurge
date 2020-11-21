@extends('layouts.admin')
@section('inner_content')
    <div class="container">
        <div class="panel">
            <h2 class="panel-heading">Withdrawal {{$withdrawal->reference}}</h2>
            <div class="panel-body">
                <form class="col-md-8"
                      action="{{route('admin.withdrawals.edit',['reference'=>$withdrawal->reference])}}"
                      method="post">
                    {{csrf_field()}}
                    <div class="form-group">
                        <label for="user">User</label>
                        <input class="form-control" id="user" readonly
                               value="{{is_object($withdrawal->user)?$withdrawal->user->name." [{$withdrawal->user->username}]":'User Deleted'}}"/>
                    </div>
                    <div class="form-group">
                        <label for="amount">Amount ($)</label>
                        <input class="form-control" id="amount" name="amount" value="{{$withdrawal->amount}}"/>
                    </div>
                    <div class="form-group">
                        <label for="btc">BTC</label>
                        <input class="form-control" id="btc" name="btc" value="{{$withdrawal->btc}}"/>
                    </div>
                    <div class="form-group">
                        <label for="paid_at">Paid At</label>
                        <input class="form-control" type="date" id="paid_at" name="paid_at"
                               value="{{$withdrawal->paid_at?$withdrawal->paid_at->format('Y-m-d'):null}}"/>
                    </div>
                    <div class="form-group">
                        <label for="approved_at">Approved At</label>
                        <input class="form-control" type="date" id="approved_at" name="approved_at"
                               value="{{$withdrawal->approved_at?$withdrawal->approved_at->format('Y-m-d'):null}}"/>
                    </div>
                    <p class="notify"></p>
                    <div class="form-group">
                        <button type="submit" class="btn btn-primary">Update</button>
                        <a href="{{route('admin.withdrawals.index')}}" class="btn btn-default">Go Back</a>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection

@section('scripts')
    @parent
    <script type="text/javascript">
        $('form').submit(function (e) {
            e.preventDefault();
            ajaxUsingFormURL(this);
        });
    </script>
@endsection