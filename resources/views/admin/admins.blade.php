@extends('layouts.admin')
@section('inner_content')
<div class="container Josefins">
    <form action="{{route('admin.admins.manage_list')}}" method="post" id="manageList" onsubmit="return false;">
        <input name="action" value="" type="hidden">
        <div class="row">
            <div class="col-xs-12">
                <h2 class="pull-left no-margin">Admins</h2>

                <!-- Split button -->
                <div class="btn-group btn-group-sm pull-right">
                    <button type="button" class="btn btn-primary dropdown-toggle" data-toggle="dropdown" aria-haspopup="true"
                            aria-expanded="false">
                        With Selected&nbsp;<span class="caret"></span>
                        <span class="sr-only">Toggle Dropdown</span>
                    </button>
                    <ul class="dropdown-menu">
                        <li>
                            <button type="submit" value="delete" class="btn-link">Delete</button>
                        </li>
                        <li>
                            <button type="submit" value="restore" class="btn-link">Restore</button>
                        </li>
                        <li role="separator" class="divider"></li>
                        <li>
                            <button type="submit" value="discard" class="btn-link">Delete Permanently</button>
                        </li>
                    </ul>
                </div>
            </div>
        </div>
        <div class="text-center padding-1em"><span id="notify"></span></div>
        <div class="table-responsive">
            <table class="table table-hover">
                <thead>
                    <tr>
                        <th>Name</th>
                        <th>Phone</th>
                        <th width="3%"><input type="checkbox" class="toggle-btn" data-toggle="input.togglable"></th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($users as $user)
                    <tr class="{{$user->trashed()?'warning':''}}" >
                        <td>
                            <a class="btn-link" href="{{route('admin.users.view', ['user'=>$user->id])}}">
                                {{$user->name}}
                            </a>
                        </td>
                        <td>{{$user->phone}}</td>
                        <td><input name="id[]" type="checkbox" value="{{$user->id}}" class="togglable"></td>
                    </tr>
                    @endforeach
                    <tr>
                        <td colspan="8" class="text-center">{{$users->links()}}</td>
                    </tr>
                </tbody>
            </table>
        </div>
    </form>
</div>

@endsection

@section('scripts')
@parent
<script type="text/javascript">
    $(function () {
        var $form = $('form:not(#search)');

        $(":submit", $form).click(function () {
            $("form").data("submit-action", this.value);
        });

        $form.submit(function (e) {
            var $this = $(this);
            var NP = $('#notify', $this);
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