@extends('layouts.admin')
@section('inner_content')
    <div class="container">
        <form action="{{route('admin.investments.manage_list')}}" method="post" id="manageList"
              onsubmit="return false;">
            <input name="action" value="" type="hidden">
            <div class="row">
                <div class="col-xs-12">
                    <h2 class="pull-left no-margin">Investments</h2>
                    <span class="pull-right">
                    <div class="btn-group btn-group-sm">
                        <button type="button" class="btn btn-primary dropdown-toggle"
                                data-toggle="dropdown" aria-haspopup="true"
                                aria-expanded="false">
                            Filter
                            &nbsp;<span class="caret"></span>
                            <span class="sr-only">Toggle Dropdown</span>
                        </button>
                        <ul class="dropdown-menu">
                            @foreach($plans as $plan)
                                <li><a href="?plan={{$plan->id}}" class="btn-link">{{$plan->name}} Plan</a></li>
                            @endforeach
                        </ul>
                    </div>
                    <div class="btn-group btn-group-sm">
                        <button type="button" class="btn btn-primary dropdown-toggle" data-toggle="dropdown"
                                aria-haspopup="true"
                                aria-expanded="false">
                            with selected
                            &nbsp;<span class="caret"></span>
                            <span class="sr-only">Toggle Dropdown</span>
                        </button>
                        <ul class="dropdown-menu">
                            <li>
                                <button type="submit" value="verify" class="btn-link">Mark Verified</button>
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
                        <th>Cryto</th>
                        <th>ROI</th>
                        <th>Auto ROI</th>
                        <th>Plan</th>
                        <th>Verified</th>
                        <th>Due</th>
                        <!--<th>Receipt</th>-->
                        <th>Action</th>
                        <th width="3%"><input type="checkbox" class="toggle-btn" data-toggle="input.togglable"></th>
                    </tr>
                    </thead>
                    <tbody>
                    @forelse($investments as $investment)
                        <tr class="{{$investment->trashed()?'grey-text':($investment->status?'':'warning')}}">
                            <td><a href="?q={{$investment->reference}}">{{$investment->reference}}</a></td>
                            <td>
                                <a class="btn-link"
                                   href="{{route('admin.users.view',['user'=>is_object($user = $investment->user)?$investment->user->id:''])}}">
                                    {{is_object($user)?$user->name." [{$user->username}]":'User Deleted'}}
                                </a>
                            </td>
                            <td>{{to_currency($investment->amount)}}</td>
                            <td>{{format_crypto($investment->btc,$investment->exchange_id)}}</td>
                            <td>{{to_currency($investment->roi)}}</td>
                            <td>{{$investment->auto_roi?'Yes':'No'}}</td>
                            <td>
                                <a href="?plan={{$investment->plan->id}}">
                                    {{$investment->plan->name}} Plan
                                </a>
                            </td>
                            <td>{{$investment->verified_at?$investment->verified_at->format('M j, Y - g:i a'):'No'}}</td>
                            <td>{{$investment->due_at?$investment->due_at->diffForHumans():'-'}}</td>
                        <!--                        <td>
                            @foreach($investment->files as $file)
                            <a href="{{getStorageUrl($file->path)}}" target="_blank">View receipt</a><br/>
                            @endforeach
                                </td>-->
                            <td>
                                <a href="{{route('admin.investments.edit',['reference'=>$investment->reference])}}">edit</a>
                            </td>
                            <td>
                                @if(empty($investment->verified_at))
                                    <input name="id[]" type="checkbox" value="{{$investment->id}}" class="togglable">
                                @endif
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="12" class="text-center"><p class="lead">No requests yet</p></td>
                        </tr>
                    @endforelse
                    </tbody>
                    <tfoot>
                    <tr>
                        <td colspan="12">{{$investments->appends($_GET)->links()}}</td>
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