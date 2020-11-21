@extends('layouts.dashboard')
@section('dashboard_content')
    <div class="card">
        <div class="card-body">
            <table class="table table-striped">
                <tbody>
                @if(count($notifications) > 0)
                    @foreach($notifications as $n)
                        <tr class="{{$n['status']}}" id="<?= $n['id'] ?>">
                            <td>
                                <a href="{{$n['link']}}">
                                    <div class="row" style="margin-bottom: 0; margin-top: 3px;margin-bottom: 0px;">
                                        <div class="col-xs-12">{{$n['msg']}}</div>
                                    </div>
                                </a>
                            </td>
                            <td>
                                <div>
                                    <a href="#" onclick="event.preventDefault();deleteNf(this)"
                                       title="Delete"><i class="glyphicon glyphicon-trash"></i></a>
                                    <a href="#" onclick="event.preventDefault();toggleMark(this)"
                                       title="Mark as {{$n['status'] === 'read'?'un':''}}read" class="markbtn">
                                        <i class="glyphicon glyphicon-eye-open"></i>
                                    </a>
                                </div>
                                <p class="font-sm no-margin align-m-left align-s-centre">{{$n['created_at']}}</p>
                            </td>
                        </tr>
                    @endforeach
                    <tr>
                        <td colspan="2">{{$links}}</td>
                    </tr>
                @else
                    <tr>
                        <td colspan="">No new notifications</td>
                    </tr>
                @endif
                </tbody>
            </table>
        </div>
    </div>
@endsection

@section('head')
    @parent
    <style>
        tr > td, tr > td .material-icons {
            font-size: 1em;
            padding: 1px 5px;
        }

        tr > td:first-child {
            width: 90%;
        }

        tr > td:last-child {
            width: 10%;
            min-width: 60px;
        }

        tr > td:last-child a {
            margin: 0 3px;
        }

        tr.unread {
            background-color: rgba(194, 225, 241, 0.4) !important;
        }

        tr.unread .markbtn {
            color: #039be5;
        }

        tr.read {
            background-color: inherit;
        }

        tr.read .markbtn {
            color: lightgray;
        }

        tr {
            border-radius: 10px;
        }
    </style>
@endsection

@section('scripts')
    @parent
    <script>

        function deleteNf(caller) {
            iAjax({
                url: '<?= $dlink ?>',
                data: {id: $(caller).parents('tr').attr('id')},
                onSuccess: function (data) {
                    if (typeof data.status !== 'undefined' && data.status == '1') {
                        $(caller).parents('tr').remove();
                        $('.badge.nft').html(data.count);
                        if ($('tbody').children('tr').length === 0) {
                            page -= 1;
                            loadMore();
                        }
                    }
                }
            });
        }

        function toggleMark(caller) {
            iAjax({
                url: '<?= $mlink ?>',
                data: {id: $(caller).parents('tr').attr('id')},
                onSuccess: function (data) {
                    if (typeof data.status !== 'undefined') {
                        $(caller).parents('tr').removeClass('read unread').addClass(data.status);
                        $(caller).attr('title', 'Mark as ' + (data.status === 'read' ? 'un' : '')
                            + 'read');
                        $('.badge.nft').html(data.count);
                    }
                }
            });
        }
    </script>
@endsection