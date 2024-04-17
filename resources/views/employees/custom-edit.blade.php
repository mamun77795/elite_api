@extends(config('raindrops.crud.layout'))

@section('raindrops-header')
    @include('raindrops::styles.styles')
@stop

@section('raindrops')

    <div class="row" style="margin: 15px 0;">
        <div class="col-md-4">

            {{--{{ $my_name }}--}}
            @if(config('raindrops.crud.show_title'))
                <h2 style="margin-top: 10px;">{{$title or ''}}</h2>
            @endif
        </div>
        <div class="col-md-8">
            <div class="pull-right " style="margin-top: 10px;">
                {{--{!! $buttons !!}--}}
                @isset($back_button)
                    <a href="{{ $back_button['url'] }}" class="{{ $back_button['class'] }}">{{ $back_button['text'] }}</a>
                @endisset
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-md-12">
            {{--{!! $form->render() !!}--}}
            <form class="form-wizard" action="{{url('employees/update/'.$id)}}" enctype="multipart/form-data" method="POST">
                @if (session()->has('message'))
                    <div class="alert alert-success{{ session('flash_notification.level') }}">
                        <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>

                        {!! session('message') !!}
                    </div>
                @endif
                {{ csrf_field() }}
            <section>
                @if (session()->has('message'))
                    <div class="alert alert-success{{ session('flash_notification.level') }}">
                        <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;
                        </button>
                        {!! session('message') !!}
                    </div>
                @endif

                <div class="display-no" style="display: block;">
                    <div class="col-md-4 form-group" style="">
                        <label for="input-text-2">NAME</label>
                        <input class="form-control" name="name" id="input-text-3" value={{$name}} type="text">
                    </div>
                    <div class="col-md-4 form-group" style="">
                        <label for="input-text-2">EMAIL</label>
                        <input class="form-control" name="email" id="input-text-3" value={{$email}} type="text">
                    </div>
                    <div class="col-md-4 form-group" style="">
                        <label for="input-text-2">PASSWORD</label>
                        <input class="form-control" name="password" id="input-text-3" value={{$password}} type="password">
                    </div>
                    <div class="col-md-4 form-group" style="">
                        <label for="input-text-2">NUMBER</label>
                        <input class="form-control" name="number" id="input-text-3" value={{$number}} type="text">
                    </div>
                    <div class="col-md-4 form-group" style="">
                        <label for="input-text-2">DATE OF BIRTH</label>
                        <input class="form-control" name="dob" id="input-text-3" value={{$dob}} type="text">
                    </div>
                    {{--<div class="col-md-4 form-group" style="">--}}
                        {{--<label for="input-text-2">CODE</label>--}}
                        {{--<input class="form-control" name="code" id="input-text-3" value={{$code}} type="text">--}}
                    {{--</div>--}}
                    <div class="col-md-4 form-group" style="">
                        <label for="input-text-2">DESIGNATION</label>
                        <input class="form-control" name="designation" id="input-text-3" value={{$designation}} type="text">
                    </div>

                    <div class="col-md-4 form-group" style="">
                        <label for="input-text-2">ADDRESS</label>
                        <input class="form-control" name="address" id="input-text-3" value={{$address}} type="text">
                    </div>
                    <div class="col-md-4 form-group" style="">
                        <label for="input-text-2">ROLE</label>
                        {{--<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.2.3/jquery.min.js"></script>--}}
                        <select class="col-md-4 form-control" id="YourID" name="role_id">
                            <option value="3">EMPLOYEE</option>
                            {{--<option value="4">MODERATOR</option>--}}
                            <option value="5">VIEWER</option>
                        </select>
                    </div>
                </div>
            </section>
            <div class="form-actions">
                <div class="col-md-offset-5 col-md-10">
                    <button type="submit" class="btn btn-primary">Submit</button>
                </div>
            </div>
            </form>
        </div>
    </div>
    <script>
        $(document).ready(function() {
            $('#YourID option[value={{$role_id}}]').attr("selected", "selected");
        });
    </script>

@stop

@section('raindrops-footer')
    @include('raindrops::scripts.php-to-js')
    @include('raindrops::scripts.dropdown')
    @include('raindrops::scripts.delete')
@stop


@if(isset($include_view))
    @includeIf($include_view)
@endif
