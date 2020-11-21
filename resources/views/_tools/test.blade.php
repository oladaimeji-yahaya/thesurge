@extends('layouts.app')
@section('content')
<div class="container">
    <button onclick="run()" style="margin: 200px auto;">Test</button>
</div>
<!--Alert Modal-->
<div class="modal fade" tabindex="-1" id="alertModal" role="dialog">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header" style="border-bottom: none;padding-bottom: 0">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
            </div>
            <div class="modal-body text-center">
                <img/>
                <h2 class="modal-title"></h2>
                <div class="modal-text"></div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal"></button>
                <button type="button" class="btn btn-primary ok"></button>
            </div>
        </div><!-- /.modal-content -->
    </div><!-- /.modal-dialog -->
</div><!-- /.modal -->
@endsection

@section('scripts')
<script src="https://js.pusher.com/3.2/pusher.min.js"></script>
<script src="{{asset('js/utilities.js')}}" type="text/javascript"></script>
<script>

//    // Enable pusher logging - don't include this in production
//    Pusher.logToConsole = true;
//
//    var pusher = new Pusher('9c8ca64c1c4494fc1ea2', {
//        cluster: 'eu',
//        encrypted: true
//    });
//
//    var channel = pusher.subscribe('my-channel');
//    channel.bind('my-event', function (data) {
//        alert(data.message);
//    });
</script>
<script>
    function run() {
        showAlertModal({title: 'Hi, Test', text: 'It just has to work'});
    }
</script>
@endsection