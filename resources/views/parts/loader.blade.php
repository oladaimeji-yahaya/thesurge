<div id="{{$loader_id or 'loader'}}" style="display: none" 
     class="text-center {{$loader_classes or 'padding-2em'}} Josefins">
    <img src="{{url('images/loader.gif')}}" alt=""/>
    <p class="text-center {{$loader_font or 'grey-text'}} Josefins">
        {!!$loader_text or 'Loading...'!!}
    </p>
</div>
