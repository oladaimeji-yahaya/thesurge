@extends('layouts.admin')
@section('inner_content')
<div class="container Josefins">
    <form action="{{route('admin.withdrawals.manage_list')}}" method="post" id="manageList" onsubmit="return false;">
        <input name="action" value="" type="hidden">
        <div class="row">
            <div class="col-xs-12">
                <h2 class="pull-left no-margin">Withdrawals</h2>
                <span class="pull-right">
                    <div class="btn-group btn-group-sm">
                        <button type="button" class="btn btn-primary dropdown-toggle" data-toggle="dropdown" aria-haspopup="true"
                                aria-expanded="false">
                            with selected
                            &nbsp;<span class="caret"></span>
                            <span class="sr-only">Toggle Dropdown</span>
                        </button>
                        <ul class="dropdown-menu">
                            <li>
                                <button type="submit" value="approve" class="btn-link">Approve</button>
                            </li>
                            <li>
                                <button type="submit" value="paid" class="btn-link">Mark Paid</button>
                            </li>
                            <li role="separator" class="divider"></li>
                            <li>
                                <button type="submit" value="delete" class="btn-link">Delete</button>
                            </li>
                        </ul>
                    </div>
                </span>
            </div>
        </div>
        <div class="text-center padding-1em"><span id="notify"></span></div>
        <div class="table-responsive">
            <table class="table table-hover table-striped">
                <thead>
                    <tr>
                        <th>Reference</th>
                        <th>User</th>
                        <th>Amount</th>
                        <th>BTC</th>
                        <th>Approved</th>
                        <th>Paid</th>
                        <th>Action</th>
                        <th width="3%"><input type="checkbox" class="toggle-btn" data-toggle="input.togglable"></th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($withdrawals as $withdrawal)
                    <tr class="{{$withdrawal->trashed()?'grey-text':($withdrawal->status?'':'warning')}}" >
                        <td><a href="?q={{$withdrawal->reference}}">{{$withdrawal->reference}}</a></td>
                        <td>
                            <a class="btn-link" href="{{route('admin.users.view',['user'=>is_object($user = $withdrawal->user)?$withdrawal->user->id:''])}}">
                                {{is_object($user)?$user->name:'User Deleted'}}
                            </a>
                        </td>
                        <td>{{to_currency($withdrawal->amount)}}</td>
                        <td>{{format_bitcoin($withdrawal->btc)}}</td>
                        <td>{{$withdrawal->approved_at?$withdrawal->approved_at->format('M j, Y - g:i a'):'No'}}</td>
                        <td>{{$withdrawal->paid_at?$withdrawal->paid_at->format('M j, Y - g:i a'):'No'}}</td>
                        <td><a href="{{route('admin.withdrawals.edit',['reference'=>$withdrawal->reference])}}">edit</a> </td>
                        <td>
                            @if(empty($withdrawal->verified_at))
                            <input name="id[]" type="checkbox" value="{{$withdrawal->id}}" class="togglable">
                            @endif
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="8" class="text-center"><p class="lead">No requests yet</p></td>
                    </tr>
                    @endforelse
                </tbody>
                <tfoot>
                    <tr>
                        <td colspan="8">{{$withdrawals->appends($_GET)->links()}}</td>
                    </tr>
                </tfoot>
            </table>
        </div>
    </form>
</div>
@endsection

@section('scripts')
@parent
<script type="text/javascript">
    $(function () {
        var $this = $('#manageList');
        var NP = $('#notify');

        $(":submit", $this).click(function () {
            $("form").data("submit-action", this.value);
        });

        $this.submit(function (e) {
            e.preventDefault();
            $('input[name=action]', $this).val(button = $(this).data("submit-action"));
            $('button[type=submit]', $this).attr('disabled', true);
            showPageLoader();
            iAjax({
                url: $this.attr('action'),
                method: "POST",
                data: $this.serialize(),
                onSuccess: function (response) {
                    notify(NP, response);
                    if (response.status == true) {
                        setTimeout(function () {
                            window.location.reload();
                        }, 1000);
                    }
                },
                onFailure: function (xhr) {
                    handleHttpErrors(xhr, $this, '#notify');
                },
                onComplete: function () {
                    hidePageLoader();
                    $('button[type=submit]', $this).removeAttr('disabled');
                }
            });
        });
    });
</script>
@endsection