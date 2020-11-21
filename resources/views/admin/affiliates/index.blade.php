@extends('layouts.admin')
@section('inner_content')
<div class="container Josefins">
    <form action="{{route('admin.affiliate.manage_list')}}" method="post" id="manageList" onsubmit="return false;">
        <input name="action" value="" type="hidden">
        <div class="row">
            <div class="col-xs-12">
                <h2 class="pull-left no-margin">Affiliates</h2>

                <!-- Split button -->
                <div class="btn-group btn-group-sm pull-right">
                    <button type="button" class="btn btn-primary dropdown-toggle" data-toggle="dropdown" aria-haspopup="true"
                            aria-expanded="false">
                        with selected
                        &nbsp;<span class="caret"></span>
                        <span class="sr-only">Toggle Dropdown</span>
                    </button>
                    <ul class="dropdown-menu">
                        <li>
                            <button type="submit" value="remove" class="btn-link">Remove Affiliate</button>
                        </li>
                    </ul>
                </div>
            </div>
        </div>
        <div class="text-center padding-1em"><span id="notify"></span></div>
        <div class="table-responsive">
            <table class="table table-hover table-striped">
                <thead>
                    <tr>
                        <th>Name</th>
                        <th>Referrals</th>
                        <th>Level</th>
                        <th width="3%"><input type="checkbox" class="toggle-btn" data-toggle="input.togglable"></th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($affiliates as $affiliate)
                    <tr class="">
                        <td><a href="{{route('admin.users.view',['user'=>$affiliate->id])}}">{{$affiliate->name}}</a></td>
                        <td>-</td>
                        <td>{{$affiliate->affiliateLevel->name}}</td>
                        <td><input name="id[]" type="checkbox" value="{{$affiliate->id}}" class="togglable"></td>
                    </tr>
                    @empty
                    <tr><td colspan="7" class="text-center">No affliate found</td></tr>
                    @endforelse
                </tbody>
                <tfoot>
                    <tr>
                        <td colspan="7">{{$affiliates->links()}}</td>
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