@extends('layouts.admin')
@section('inner_content')
<div class="container Josefins">
    <div class="row">
        <div class="col-xs-12 padding-btm-1em">
            <h2>
                {{$user->name}} Referrals
                <div class="pull-right">
                    <a class="btn btn-default btn-sm" href="{{route('admin.users.view',['user'=>$user->id])}}">
                        &lt; Back
                    </a>
                </div>
                <div class="pull-right">
                    <form action="{{route('admin.users.manage_list')}}" method="post" id="user-actions" onsubmit="return false;">
                        <input name="action" value="" type="hidden">
                        <input name="id[]" value="{{$user->id}}" type="hidden">
                        <span class="pull-right">
                            <!-- Split button -->
                            <div class="btn-group btn-group-sm">
                                <button type="button" class="btn btn-primary dropdown-toggle" data-toggle="dropdown" aria-haspopup="true"
                                        aria-expanded="false">
                                    Affiliate Actions
                                    &nbsp;<span class="caret"></span>
                                    <span class="sr-only">Toggle Dropdown</span>
                                </button>
                                <ul class="dropdown-menu">
                                    @if($user->super_affiliate)
                                    <li><button type="submit" value="remove_affiliate" class="btn-link">Remove Affiliate</button></li>
                                    @else
                                    <li><button type="submit" value="add_affiliate" class="btn-link">Make Affiliate</button></li>
                                    @endif
                                </ul>
                            </div>
                            <!-- Split button -->
                        </span>
                    </form>
                </div>
            </h2>
        </div>
    </div>
    <p class="text-center padding-1em" hidden id="notify"></p>
    <div class="row">
        <div class="col-sm-12">
            <div class="title">
                <h2>Downliners <small>(total {{$matrices->total()}})</small></h2>
                <div class="decor-line"></div>
                <div class="form-group">
                    <label for="level">Levels</label>
                    <select autocomplete="off" class="form-control" id="level" onchange="window.location = '?level=' + this.value">
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
                                <td>{{$matrix->user->name}}</td>
                                <td>{{$matrix->referral->user->name}}</td>
                                <td>{{$matrix->level->name}}</td>
                                <td>{{$matrix->position}}</td>
                                <td>{{to_currency($matrix->amount)}}</td>
                                <td>{{$matrix->percentage}}%</td>
                                <td>{{$matrix->released? 'Yes' : 'No'}}</td>
                            </tr>
                            @empty
                            <tr><td colspan="7" class="text-center">You have no referrals yet</td></tr>
                            @endforelse
                        </tbody>
                        <tfoot>
                            <tr><td colspan="7">{{$matrices->appends($_GET)->links()}}</td></tr>
                        </tfoot>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

@endsection

@section('head')
@parent
<style>
    div.tab{
        cursor: pointer;
    }
    div.scrollable{
        max-height: 400px;
        overflow-x: hidden;
        overflow-y: scroll;
    }
    a:hover{
        text-decoration: none;
    }
    .list .list-item{
        border-bottom: lightgrey thin solid;
        padding: 10px;
    }
</style>
@endsection

@section('scripts')
@parent
<script type="text/javascript">

    $(function () {
        var NP = $('#notify');

        var ref_form = $('#user-actions');
        $(":submit", ref_form).click(function () {
            $("form").data("submit-action", this.value);
        });
        ref_form.submit(function (e) {
            e.preventDefault();
            $('input[name=action]', ref_form).val(button = $(this).data("submit-action"));
            $('button[type=submit]', ref_form).attr('disabled', true);
            iAjax({
                url: ref_form.attr('action'),
                method: "POST",
                data: ref_form.serialize(),
                onSuccess: function (response) {
                    notify(NP, response);
                    if (response.status == true) {
                        setTimeout(function () {
                            window.location.reload();
                        }, 1000);
                    }
                },
                onFailure: function (xhr) {
                    handleHttpErrors(xhr, ref_form, '#notify');
                },
                onComplete: function () {
                    $('button[type=submit]', ref_form).removeAttr('disabled');
                }
            });
        });


    });

</script>
@endsection