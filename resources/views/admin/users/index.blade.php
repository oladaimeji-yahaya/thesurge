@extends('layouts.admin')
@section('inner_content')
<div class="container Josefins">
    <form action="{{route('admin.users.manage_list')}}" method="post" id="manageList" onsubmit="return false;">
        <input name="action" value="" type="hidden">
        <div class="row">
            <div class="col-xs-12">
                <h2 class="pull-left no-margin">Users</h2>

                <!-- Split button -->
                <div class="btn-group btn-group-sm pull-right">
                    <a href="?filter=" class="btn btn-primary">All</a>
                    <a href="?filter=suspended" class="btn btn-primary">Suspended</a>
                    <a href="?filter=deleted" class="btn btn-primary">Deleted</a>
                    <button type="button" class="btn btn-primary dropdown-toggle" data-toggle="dropdown" aria-haspopup="true"
                            aria-expanded="false">
                        with selected
                        &nbsp;<span class="caret"></span>
                        <span class="sr-only">Toggle Dropdown</span>
                    </button>
                    <ul class="dropdown-menu">
                        <!--                        <li>
                                                    <button type="submit" value="add_affiliate" class="btn-link">Make Affiliate</button>
                        </li>
                        <li>
                            <button type="submit" value="remove_affiliate" class="btn-link">Remove Affiliate</button>
                        </li>
                                                <li role="separator" class="divider"></li>-->
<li>
                            <button type="submit" value="freeze_withdrawal" class="btn-link">Freeze Withdrawal</button>
                        </li>
                        <li>
                            <button type="submit" value="activate_withdrawal" class="btn-link">Activate Withdrawal</button>
                        </li>
                        <li role="separator" class="divider"></li>
                        <li>
                            <button type="submit" value="block" class="btn-link">Suspend</button>
                        </li>
                        <li>
                            <button type="submit" value="unblock" class="btn-link">Activate</button>
                        </li>
                        <li role="separator" class="divider"></li>
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
            <table class="table table-hover table-striped">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Name</th>
                        <th>Username</th>
                        <th>Email</th>
                        <th>Phone</th>
                        <th width="3%"><input type="checkbox" class="toggle-btn" data-toggle="input.togglable"></th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($users as $user)
                    <tr class="{{$user->trashed()?'grey-text':($user->status?'':'warning')}}">
                        <td>{{$user->id}}</td>
                        <td>
                            <a class="btn-link" href="{{route('admin.users.view',['user'=>$user->id])}}">
                                {{$user->name}}
                            </a>
                        </td>
                        <td>{{$user->username}}</td>
                        <td>{{$user->email}}</td>
                        <td>{{$user->phone}}</td>
                        <td><input name="id[]" type="checkbox" value="{{$user->id}}" class="togglable"></td>
                    </tr>
                    @empty
                    <tr><td colspan="5" class="text-center">No user found</td></tr>
                    @endforelse
                </tbody>
                <tfoot>
                    <tr>
                        <td colspan="8">{{$users->links()}}</td>
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