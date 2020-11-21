@extends('layouts.admin')
@section('inner_content')
    <div class="container">
        <div class="panel">
            <h2 class="panel-heading">Investment {{$investment->reference}}</h2>
            <div class="panel-body">
                <form class="col-md-8"
                      action="{{route('admin.investments.edit',['reference'=>$investment->reference])}}"
                      method="post">
                    {{csrf_field()}}
                    <div class="form-group">
                        <label for="user">User</label>
                        <input class="form-control" id="user" readonly
                               value="{{is_object($investment->user)?$investment->user->name." [{$investment->user->username}]":'User Deleted'}}"/>
                    </div>
                    <div class="form-group">
                        <label for="user">Plan</label>
                        <input class="form-control" id="user" readonly value="{{$investment->plan->name}}"/>
                    </div>
                    <div class="row">
                        <div class="form-group col-md-6">
                            <label for="amount">Amount ($)</label>
                            <input class="form-control" id="amount" name="amount" value="{{$investment->amount}}"/>
                        </div>
                        <div class="form-group col-md-6">
                            <label for="btc">Crypto ({{$investment->exchange->name}})</label>
                            <input class="form-control" id="btc" name="btc" value="{{$investment->btc}}"/>
                        </div>
                    </div>
                    <div class="row">
                        <div class="form-group col-md-6">
                            <label for="roi">ROI ($)</label>
                            <input class="form-control" {{$investment->auto_roi?'readonly':''}} id="roi" name="roi"
                                   value="{{$investment->roi}}"/>
                        </div>
                        <div class="form-group col-md-6">
                            <label for="auto_roi">Auto ROI</label>
                            <select name="auto_roi" class="form-control" id="auto_roi">
                                <option value="0" {{$investment->auto_roi?'':'selected'}}>No</option>
                                <option value="1" {{$investment->auto_roi?'selected':''}}>Yes</option>
                            </select>
                        </div>
                    </div>
                    <div class="row">
                        <div class="form-group col-md-6">
                            <label for="paid_at">Paid At</label>
                            <input class="form-control" type="date" id="paid_at" name="paid_at"
                                   value="{{$investment->paid_at?$investment->paid_at->format('Y-m-d'):null}}"/>
                        </div>
                        <div class="form-group col-md-6">
                            <label for="verified_at">Verified At</label>
                            <input class="form-control" type="date" id="verified_at" name="verified_at"
                                   value="{{$investment->verified_at?$investment->verified_at->format('Y-m-d'):null}}"/>
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="due_at">Due At</label>
                        <input class="form-control" type="date" id="due_at" readonly
                               value="{{$investment->due_at?$investment->due_at->format('Y-m-d'):null}}"/>
                    </div>
                    <p class="notify"></p>
                    <div class="form-group">
                        <button type="submit" class="btn btn-primary">Update</button>
                        <a href="{{route('admin.investments.index')}}" class="btn btn-default">Go Back</a>
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