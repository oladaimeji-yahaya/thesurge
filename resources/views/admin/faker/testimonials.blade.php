@extends('layouts.admin')
@section('inner_content')
<div class="container Josefins">
    <div class="row">
        <div class="col-xs-12">
            <h2 class="pull-left no-margin">Testimonials</h2>
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
        <form class="table-responsive col-xs-12" method="post" action="{{route('admin.faker.testimonials.update')}}">
            <div class="pull-left text-left">
                Page {{$testimonials->currentpage() }} 
                of {{ceil($testimonials->total() / $testimonials->perpage())}}
            </div>
            <p class="notify text-center"></p>
            {{ csrf_field() }}
            <table class="table table-hover table-striped">
                @php
                $columns = [
                ['name'=>'video','required'=>true,'type'=>'url'],
                ['name'=>'description','required'=>false,'type'=>'text'],
                ];
                @endphp
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Image</th>
                        @foreach($columns as $column)
                        <th>{{ucfirst($column['name'])}}</th>
                        @endforeach
                        <th></th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($testimonials as $testimonial)
                    <tr>
                        <td>{{getLoopNumber($testimonials, $loop)}}</td>
                        <td>
                            @if($testimonial->image)
                            <img src="{{asset($testimonial->image)}}" style="max-height: 100px;max-width: 100px;"/>
                            @else
                            no image
                            @endif
                        </td>
                        @foreach($columns as $column)
                        <td>
                            <div class="form-group{{ $errors->has($testimonial->id.'::'.$column['name']) ? ' has-error' : '' }}">
                                <input id="{{$testimonial->id}}_username" 
                                       type="{{$column['type']}}" 
                                       class="form-control" 
                                       name="{{$testimonial->id.'::'.strtolower($column['name'])}}" 
                                       value="{{ old($testimonial->id.'::'.$column['name'],$testimonial->{$column['name']}) }}" 
                                       {{$column['required']?'required':''}} autofocus>
                                @if ($errors->has($testimonial->id.'::'.$column['name']))
                                <span class="help-block">
                                    <strong>{{ $errors->first($testimonial->id.'::'.$column['name']) }}</strong>
                                </span>
                                @endif
                            </div>
                        </td>
                        @endforeach
                        <td><button type="button" id="{{$testimonial->id}}" class="btn btn-danger btn-delete">Delete</button></td>
                    </tr>
                    @endforeach
                </tbody>
                <tfoot>
                    <tr>
                        <td colspan="5">{{$testimonials->links()}}</td>
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
        <form class="modal-content" method="post" action="{{route('admin.faker.testimonials.create')}}" enctype="multipart/form-data">
            <div class="modal-body">
                <h2 class="modal-title">Create Payout</h2>
                {{ csrf_field() }}
                @php
                $columns['image'] = ['name'=>'image','required'=>false,'type'=>'file'];
                @endphp

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
    var deleteurl = "{{route('admin.faker.testimonials.delete')}}";
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
        var data;
        var extraConfig = {};
        if (form.attr('enctype') === 'multipart/form-data') {
            var formItem = form[0];
            data = new FormData(formItem);
            extraConfig = {
                contentType: false,
                processData: false
            };
        } else {
            data = form.serialize();
        }

        $('button, input, textarea', form).prop('disabled', true);
        iAjax($.extend({
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
        }, extraConfig));
    });
</script>
@endsection