@extends('layouts.directory')
@section('content')
<section>
    <div class="container">
        <div>
            <h1>Testimonials</h1>
        </div>
    </div>
</section>
<section class="about-section">
    <div class="container padding-top-1em padding-btm-1em">
        <h2 class="text-center">You are with good company. Hear from some of our happy partners around the world.</h2>
        @forelse($testimonials->chunk(3) as $testimonial_row)
        <div class="row">
            @foreach($testimonial_row as $testimonial)
            <div class="col-md-4">
                <div class="section section--bg video"  style="min-height: 300px; margin-bottom: 2px" data-bg="{{$testimonial->image?url($testimonial->image):''}}">
                    <a href="{{$testimonial->video}}" class="video__btn animate" data-animate="fadeIn" data-duration="1.0s" data-delay="0.2s"><i class="fa fa-play-circle"></i></a>
                    <h4 class="video__title animate" data-animate="fadeIn" data-duration="1.0s" data-delay="0.4s">Watch Video</h4>
<!--                    <p class="video__text animate tiny-padding" data-animate="fadeIn" data-duration="1.0s" data-delay="0.6s">
                        {{$testimonial->description}}
                    </p>-->
                </div>
            </div>
            @endforeach
        </div>
        @empty
        <h2 class="text-center">No testimonials yet</h2>
        @endforelse
        <div class="row text-center">{{$testimonials->links()}}</div>
    </div>
</section>
@endsection
