@extends('layouts.admin')
@section('inner_content')
<div class="container Josefins">
    <div class="row">
        <div class="col-xs-12">
            <h2 class="pull-left no-margin">FAQs</h2>
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
        <form class="table-responsive col-xs-12" method="post" action="{{route('admin.faker.faqs.update')}}">
            <div class="pull-left text-left">
                Page {{$faqs->currentpage() }} 
                of {{ceil($faqs->total() / $faqs->perpage())}}
            </div>
            <p class="notify text-center"></p>
            {{ csrf_field() }}
            <table class="table table-hover table-striped">
                <thead>
                    <tr>
                        <th width="3%">#</th>
                        <th width="40%">Question</th>
                        <th width="50%">Answer</th>
                        <th width="7%"></th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($faqs as $faq)
                    <tr>
                        <td>{{getLoopNumber($faqs, $loop)}}</td>
                        <td>
                            <div class="form-group{{ $errors->has($faq->id.'::question') ? ' has-error' : '' }}">
                                <input id="{{$faq->id}}_question" type="text" class="form-control" 
                                       name="{{$faq->id.'::question'}}" 
                                       value="{{ old($faq->id.'::question',$faq->question) }}" required autofocus>
                                @if ($errors->has($faq->id.'::question'))
                                <span class="help-block">
                                    <strong>{{ $errors->first($faq->id.'::question') }}</strong>
                                </span>
                                @endif
                            </div>
                        </td>
                        <td>
                            <div class="form-group{{ $errors->has($faq->id.'::answer') ? ' has-error' : '' }}">
                                <textarea id="{{$faq->id}}_answer" type="text" class="form-control" 
                                          name="{{$faq->id.'::answer'}}" 
                                          required autofocus>{{ old($faq->id.'::answer',$faq->answer) }}</textarea>
                                @if ($errors->has($faq->id.'::answer'))
                                <span class="help-block">
                                    <strong>{{ $errors->first($faq->id.'::answer') }}</strong>
                                </span>
                                @endif
                            </div>
                        </td>
                        <td><button type="button" id="{{$faq->id}}" class="btn btn-danger btn-delete">Delete</button></td>
                    </tr>
                    @endforeach
                </tbody>
                <tfoot>
                    <tr>
                        <td colspan="4">{{$faqs->links()}}</td>
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
        <form class="modal-content" method="post" action="{{route('admin.faker.faqs.create')}}">
            <div class="modal-body">
                <h2 class="modal-title">Create FAQ</h2>
                {{ csrf_field() }}

                <div class="control-group form-group{{ $errors->has('question') ? ' has-error' : '' }}">
                    <div class="form-group{{ $errors->has('question') ? ' has-error' : '' }} floating-label-form-group controls">
                        <label for="question">Question</label>
                        <input class="form-control" id="question" type="text" placeholder="Question" name="question" value="{{ old('question') }}" 
                               required autofocus data-validation-required-message="Enter question.">
                        <p class="help-block text-danger"></p>
                        @if ($errors->has('question'))
                        <span class="help-block">
                            <strong>{{ $errors->first('question') }}</strong>
                        </span>
                        @endif
                    </div>
                </div>
                <div class="control-group form-group{{ $errors->has('answer') ? ' has-error' : '' }}">
                    <div class="form-group{{ $errors->has('answer') ? ' has-error' : '' }} floating-label-form-group controls">
                        <label for="answer">Answer</label>
                        <textarea class="form-control" id="answer" type="answer" placeholder="Answer" name="answer" 
                                  required data-validation-required-message="Enter answer."></textarea>
                        <p class="help-block text-danger"></p>
                        @if ($errors->has('answer'))
                        <span class="help-block">
                            <strong>{{ $errors->first('answer') }}</strong>
                        </span>
                        @endif
                    </div>
                </div>
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
    var deleteurl = "{{route('admin.faker.faqs.delete')}}";
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