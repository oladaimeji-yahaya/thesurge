@extends('layouts.dashboard')

@section('dashboard_content')
    <div class="text-center">
        <h1 class="">Your account has been suspended :(</h1>
        <p class="">Contact
            <a class="font-bold light-blue-text" href="{{route('support')}}">support</a>
            to resolve this.
        </p>
    </div>
@endsection

@section('scripts')
    <!-- IE10 viewport hack for Surface/desktop Windows 8 bug -->
    <script src="{{asset('js/ie10-viewport-bug-workaround.js')}}"></script>
@endsection