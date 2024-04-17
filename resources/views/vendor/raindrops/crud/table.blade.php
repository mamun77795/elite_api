

@extends(config('raindrops.crud.layout'))

@section('raindrops-header')
    @include('raindrops::styles.styles')
    <link href="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.7/css/select2.min.css" rel="stylesheet" />
@stop

@section('raindrops')

    <div class="row" style="margin: 15px 0;">
        <div class="col-md-4">
            @if(config('raindrops.crud.show_title'))
                <h2 class="tabletitle" style="margin-top: 10px;">{{$title or ''}}</h2>
            @endif
        </div>
        <div class="col-md-8">
            <div class="pull-right " style="margin-top: 10px;">
                {!! $buttons !!}
                @isset($back_button)
                    <a href="{{ $back_button['url'] }}"
                       class="{{ $back_button['class'] }}">{{ $back_button['text'] }}</a>
                @endisset
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-md-12">
            @php
              $urlSegment   = basename(Request::url());
              $searchView   = 'search-'.$urlSegment;
            @endphp
            @if(view()->exists($searchView))
              @include($searchView)
            @endif


        </div>
    </div>
    <div class="row">
        <div class="col-md-12">
            {!! $table->render() !!}
        </div>
    </div>

@stop

@section('raindrops-footer')
    @include('raindrops::scripts.php-to-js')
    @include('raindrops::scripts.dropdown')
    @include('raindrops::scripts.delete')
    <script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.7/js/select2.min.js"></script>
@stop

@if(isset($include_view))
    @includeIf($include_view)
@endif
