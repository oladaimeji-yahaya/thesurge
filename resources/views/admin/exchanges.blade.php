@extends('layouts.admin')
@section('inner_content')
<div class="container">
    @if(old('status') !== null)
    <p class='{{old('status')?'bg-success green-text':'bg-danger red-text'}} text-center padding-1em'>{{old('message')}}</p>
    @endif
    <div class="row margin-btm-4em">
        <form class="table-responsive col-xs-12" role="form" method="POST" action="{{route('admin.exchanges.update')}}">
            <div class="pull-left text-left">
                Page {{$exchanges->currentpage() }} 
                of {{ceil($exchanges->total() / $exchanges->perpage())}}
            </div>
            <p class="notify text-center"></p>
            <table class="table table-hover table-striped">
                <thead>
                    <tr>
                        <th>Rank</th>
                        <th>Name</th>
                        <th>Address</th>
                        <th>QR Code</th>
                        <th>Enabled</th>
                    </tr>
                </thead>
                <tbody>
                    {{ csrf_field() }}
                    @forelse($exchanges as $exchange)
                    <tr>
                        <td>{{$exchange->rank}}</td>
                        <td>
                            {{$exchange->name}} ({{$exchange->symbol}})
                        </td>
                        <td>
                            <input id="{{$exchange->id}}-pay_to" type="text" 
                                   class="form-control" name="{{$exchange->id.'-pay_to'}}" 
                                   value="{{ old($exchange->id.'-pay_to',$exchange->pay_to) }}" autofocus>
                            @if ($errors->has($exchange->id.'-pay_to'))
                            <span class="help-block">
                                <strong>{{ $errors->first($exchange->id.'-pay_to') }}</strong>
                            </span>
                            @endif
                        </td>
                        <td>
                            <div class="row">
                                <div class="col-sm-6">
                                    @if($exchange->qr)
                                    <img src="{{getStorageUrl($exchange->qr)}}" style="max-height: 100px;max-width: 100px;"/>
                                    @else
                                    <div >no image</div>
                                    @endif
                                </div>
                                <div class="col-sm-6">
                                    @if($exchange->qr)
                                    <button type="button" data-id="{{$exchange->id}}" 
                                            class="btn btn-danger btn-sm btn-qr-delete">Delete QR Code</button><br/>
                                    @endif
                                    <button type="button" data-id="{{$exchange->id}}" 
                                            class="btn btn-default btn-sm btn-qr-add">Change QR code</button>
                                </div>
                            </div>        
                        </td>
                        <td><input name="{{$exchange->id}}-enabled" type="checkbox"
                                   {{ old($exchange->id.'-enabled',$exchange->enabled)?'checked':''}}/></td>
                    </tr>
                    @empty
                    <tr><td colspan="5" class="text-center">No exchange rate available</td></tr>
                    @endforelse
                </tbody>
                <tfoot>
                    <tr>
                        <td colspan="8">{{$exchanges->links()}}</td>
                    </tr>
                </tfoot>
            </table>
            <div class="btn-save-container">
                <button type="submit" class="col-md-offset-2 btn btn-primary">Update</button>
            </div>
        </form>
    </div>
</div>
<form hidden id="qrform" enctype="multipart/form-data" 
      action="{{route('admin.exchanges.uploadqr')}}">
    <input type="text" name="id"/>
    <input type="file" name="qr_file"/>
</form>
@endsection


@section('head')
@parent
<style>
    .btn-save-container{
        position: fixed;
        width: 100%;
        bottom: 0;
        left: 0;
        padding: 10px;
        background-color: rgba(0,0,0,.9);
    }
</style>
@endsection

@section('scripts')
@parent
<script>
    var deleteurl = "{{route('admin.exchanges.deleteqr')}}";
    $('form .btn-qr-delete').click(function (e) {
        e.preventDefault();
        var btn = $(this);
        var ans = confirm('Are you sure you want to delete this QR code?');
        if (ans) {
            iAjax({
                url: deleteurl,
                method: 'post',
                data: {id: btn.data('id')},
                onSuccess: function (xhr) {
                    notify($('form .notify'), xhr);
                    window.location.reload();
                },
                onFailure: function (xhr) {
                    handleHttpErrors(xhr);
                }
            });
        }
    });

    $('form .btn-qr-add').click(function (e) {
        $('#qrform input[name=id]').val($(this).data('id'));
        $('#qrform input[name=qr_file]').click();
    });

    $('#qrform input[name=qr_file]').change(function (e) {
        $('#qrform').submit();
    });

    $('#qrform').submit(function (e) {
        e.preventDefault();
        var form = $(this);
        var data;
        var formItem = form[0];
        data = new FormData(formItem);

        $('button, input, textarea', $('form')).prop('disabled', true);
        iAjax({
            url: form.attr('action'),
            data: data,
            contentType: false,
            processData: false,
            method: 'post',
            onSuccess: function (xhr) {
                notify($('.notify'), xhr);
                window.location.reload();
            },
            onFailure: function (xhr) {
                handleHttpErrors(xhr, $('form'));
            },
            onComplete: function () {
                $('button, input, textarea', $('form')).prop('disabled', false);
            }
        });
    });
</script>
@endsection