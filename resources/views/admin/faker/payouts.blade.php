@extends('layouts.admin')
@section('inner_content')
<div class="container Josefins">
    <div class="row">
        <div class="col-xs-12">
            <h2 class="pull-left no-margin">Payouts</h2>
            <button class="btn btn-primary pull-right btn-add">
                Add
            </button>
        </div>
    </div>
    @if(old('status') !== null)
    <p class='{{old('status')?'bg-success green-text':'bg-danger red-text'}} 
       text-center padding-1em'>{{old('message')}}</p>
    @endif
    <div class="row margin-btm-4em">
        <form class="table-responsive col-xs-12" method="post" action="{{route('admin.faker.payouts.update')}}">
            <div class="pull-left text-left">
                Page {{$payouts->currentpage() }} 
                of {{ceil($payouts->total() / $payouts->perpage())}}
            </div>
            <p class="notify text-center"></p>
            {{ csrf_field() }}
            <table class="table table-hover table-striped">
                @php
                $columns = [
                ['name'=>'username','required'=>true,'type'=>'text'],
                ['name'=>'address','required'=>true,'type'=>'text'],
                ['name'=>'amount','required'=>true,'type'=>'number'],
                ['name'=>'country','required'=>true,'type'=>'text'],
                ['name'=>'TXID','required'=>false,'type'=>'text'],
                ['name'=>'confirmations','required'=>false,'type'=>'number'],
                ];
                @endphp
                <thead>
                    <tr>
                        <th>#</th>
                        @foreach($columns as $column)
                        <th>{{ucfirst($column['name'])}}</th>
                        @endforeach
                        <th></th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($payouts as $payout)
                    <tr>
                        <td>{{getLoopNumber($payouts, $loop)}}</td>
                        @foreach($columns as $column)
                        <td>
                            <div class="form-group{{ $errors->has($payout->id.'::'.$column['name']) ? ' has-error' : '' }}">
                                @php
                                $key = strtolower($column['name']);
                                @endphp
                                <input id="{{$payout->id}}_username" 
                                       type="{{$column['type']}}" 
                                       class="form-control" 
                                       name="{{$payout->id.'::'.$key}}" 
                                       value="{{ old($payout->id.'::'.$column['name'],$payout->{$key}) }}" 
                                       {{$column['required']?'required':''}} autofocus>
                                @if ($errors->has($payout->id.'::'.$column['name']))
                                <span class="help-block">
                                    <strong>{{ $errors->first($payout->id.'::'.$column['name']) }}</strong>
                                </span>
                                @endif
                            </div>
                        </td>
                        @endforeach
                        <td><button type="button" id="{{$payout->id}}" class="btn btn-danger btn-delete">Delete</button></td>
                    </tr>
                    @endforeach
                </tbody>
                <tfoot>
                    <tr>
                        <td colspan="8">{{$payouts->links()}}</td>
                    </tr>
                </tfoot>
            </table>
            <div class="btn-save-container">
                <button type="submit" class="col-md-offset-2 btn btn-primary">Save Changes</button>
            </div>
        </form>
    </div>
</div>
<!--Create Modal-->
<div class="modal fade" tabindex="-1" id="createModal" role="dialog">
    <div class="modal-dialog" role="document">
        <form class="modal-content" method="post" action="{{route('admin.faker.payouts.create')}}">
            <div class="modal-body">
                <h2 class="modal-title">Create Payout</h2>
                {{ csrf_field() }}

                @foreach($columns as $column)
                <div class="control-group form-group{{ $errors->has($column['name']) ? ' has-error' : '' }}">
                    <div class="form-group{{ $errors->has($column['name']) ? ' has-error' : '' }} floating-label-form-group controls">
                        <label for="{{$column['name']}}">
                            {{ucfirst($column['name'])}}
                            {{$column['required']?'':'(Optional)'}} 
                        </label>
                        <input class="form-control" 
                               id="{{$column['name']}}" 
                               type="{{$column['type']}}" 
                               placeholder="{{$column['name']}}" 
                               name="{{strtolower($column['name'])}}" 
                               value="{{ old($column['name']) }}" 
                               {{$column['required']?'required':''}} 
                               autofocus data-validation-required-message="Enter {{$column['name']}}">
                        <p class="help-block text-danger"></p>
                        @if ($errors->has($column['name']))
                        <span class="help-block">
                            <strong>{{ $errors->first($column['name']) }}</strong>
                        </span>
                        @endif
                    </div>
                </div>
                @endforeach
                <p class="notify"></p>
            </div>
            <div class="modal-footer">
                <button type="submit" class="btn btn-primary ok">Create</button>
                <button type="button" class="btn btn-default" data-dismiss="modal">Cancel</button>
            </div>
        </form><!-- /.modal-content -->
    </div><!-- /.modal-dialog -->
</div><!-- /.modal -->
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
    var deleteurl = "{{route('admin.faker.payouts.delete')}}";
    $('form .btn-delete').click(function (e) {
        e.preventDefault();
        var btn = $(this);
        var ans = confirm('Are you sure?');
        if (ans) {
            iAjax({
                url: deleteurl,
                method: 'post',
                data: {id: btn.attr('id')},
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

    $('.btn-add').click(function () {
        $('#createModal').modal({backdrop: 'static', show: true, keyboard: false});
    });

    $('form').submit(function (e) {
        e.preventDefault();
        var form = $(this);
        var data = form.serialize();
        $('button, input, textarea', form).prop('disabled', true);
        iAjax({
            url: form.attr('action'),
            data: data,
            method: 'post',
            onSuccess: function (xhr) {
                notify($('.notify', form), xhr);
                window.location.reload();
            },
            onFailure: function (xhr) {
                handleHttpErrors(xhr, form);
            },
            onComplete: function () {
                $('button, input, textarea', form).prop('disabled', false);
            }
        });
    });
</script>
@endsection