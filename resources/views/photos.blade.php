@extends('layout')
@section('content')

    <form action="{{ url('photos') }}" class="form-image-upload" method="POST" enctype="multipart/form-data">

        {!! csrf_field() !!}

        @if (count($errors) > 0)
            <div class="alert alert-danger">
                <strong>Whoops!</strong> There were some problems with your input.<br><br>
                <ul>
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        @if ($message = Session::get('success'))
            <div class="alert alert-success alert-block">
                <button type="button" class="close" data-dismiss="alert">×</button>
                <strong>{{ $message }}</strong>
            </div>
        @endif

        <div class="row">
                        <div class="fileinput fileinput-new"
                 data-provides="fileinput">
                <div class="fileinput-new thumbnail">
                    <p>
                        <img src="{{url('/')}}/storage/{{$uid=Auth::user()->avatar}}"
                             width="200"
                             class="img-circle img-responsive center-block "
                             height="240" alt=""></p>
                                </div>
                                <div class="fileinput-preview fileinput-exists thumbnail"></div>
                                <div>
                                    <span class="btn btn-default btn-file">
                                        <span class="fileinput-new">
                                            Select image
                                        </span>
                                            <span class="fileinput-exists">Change</span>

                                            <input type="file" name="url[]"></span>
                    <a href="#" class="btn btn-default fileinput-exists"
                       data-dismiss="fileinput">Remove</a>
                </div>
            </div>
            <div class="fileinput fileinput-new"
                 data-provides="fileinput">
                <div class="fileinput-new thumbnail">
                    <p>
                        <img src="{{url('/')}}/storage/{{$uid=Auth::user()->avatar}}"
                             width="200"
                             class="img-circle img-responsive center-block "
                             height="240" alt=""></p>
                </div>
                <div class="fileinput-preview fileinput-exists thumbnail"></div>
                <div>
                                    <span class="btn btn-default btn-file">
                                        <span class="fileinput-new">
                                            Select image
                                        </span>
                                            <span class="fileinput-exists">Change</span>

                                            <input type="file" name="url[]"></span>
                    <a href="#" class="btn btn-default fileinput-exists"
                       data-dismiss="fileinput">Remove</a>
                </div>
            </div>
            <div class="fileinput fileinput-new"
                 data-provides="fileinput">
                <div class="fileinput-new thumbnail">
                    <p>
                        <img src="{{url('/')}}/storage/{{$uid=Auth::user()->avatar}}"
                             width="200"
                             class="img-circle img-responsive center-block "
                             height="240" alt=""></p>
                </div>
                <div class="fileinput-preview fileinput-exists thumbnail"></div>
                <div>
                                    <span class="btn btn-default btn-file">
                                        <span class="fileinput-new">
                                            Select image
                                        </span>
                                            <span class="fileinput-exists">Change</span>

                                            <input type="file" name="url[]"></span>
                    <a href="#" class="btn btn-default fileinput-exists"
                       data-dismiss="fileinput">Remove</a>
                </div>
            </div>
            <div class="col-md-2">
                <br/>
                <button type="submit" class="btn btn-success">Upload</button>
            </div>
        </div>

    </form>
    {{-------------------------------------------------------}}
    {{--<div class="row">--}}
    {{--<div class="col-lg-12">--}}
    {{--<div class="panel panel-primary">--}}
    {{--<div class="panel-heading">--}}
    {{--<h4 class="panel-title">--}}
    {{--<i class="livicon" data-name="clock" data-size="16" data-loop="true" data-c="#fff"--}}
    {{--data-hc="white"></i>--}}
    {{--Multiple File Uplaod--}}
    {{--</h4>--}}
    {{--</div>--}}
    {{--<div class="panel-body">--}}
    {{--<div class="row">--}}
    {{--<form action="{{ url('photos') }}"  class="form-image-upload" method="POST" enctype="multipart/form-data">--}}
    {{--<form action="{{ url('photos') }}" id="fileupload" method="POST" enctype="multipart/form-data">--}}
    {{--{!! csrf_field() !!}--}}
    {{--<!-- Redirect browsers with JavaScript disabled to the origin page -->--}}
    {{--<!-- The fileupload-buttonbar contains buttons to add/delete files and start/cancel the upload -->--}}
    {{--<div class="row fileupload-buttonbar">--}}
    {{--<div class="col-lg-7">--}}
    {{--<!-- The fileinput-button span is used to style the file input field as button -->--}}
    {{--<span class="btn btn-success fileinput-button">--}}
    {{--<i class="glyphicon glyphicon-plus"></i>--}}
    {{--<span>Add files...</span>--}}
    {{--<input type="file" name="url[]" multiple>--}}
    {{--</span>--}}
    {{--<button type="submit" class="btn btn-primary start">--}}
    {{--<i class="glyphicon glyphicon-upload"></i>--}}
    {{--<span>Start upload</span>--}}
    {{--</button>--}}
    {{--<button type="reset" class="btn btn-warning cancel">--}}
    {{--<i class="glyphicon glyphicon-ban-circle"></i>--}}
    {{--<span>Cancel upload</span>--}}
    {{--</button>--}}
    {{--<button type="button" class="btn btn-danger delete">--}}
    {{--<i class="glyphicon glyphicon-trash"></i>--}}
    {{--<span>Delete</span>--}}
    {{--</button>--}}
    {{--<!-- The global file processing state -->--}}
    {{--<span class="fileupload-process"></span>--}}
    {{--<!-- The global progress state -->--}}
    {{--<div class="col-lg-5 fileupload-progress fade">--}}
    {{--<!-- The global progress bar -->--}}
    {{--<div class="progress progress-striped active" role="progressbar"--}}
    {{--aria-valuemin="0" aria-valuemax="100">--}}
    {{--<div class="progress-bar progress-bar-success" style="width:0%;"></div>--}}
    {{--</div>--}}
    {{--<!-- The extended global progress state -->--}}
    {{--<div class="progress-extended">&nbsp;</div>--}}
    {{--</div>--}}
    {{--</div>--}}
    {{--</div>--}}
    {{--<!-- The table listing the files available for upload/download -->--}}
    {{--<table role="presentation" class="table table-striped">--}}
    {{--<tbody class="files"></tbody>--}}
    {{--</table>--}}
    {{--<div id="blueimp-gallery" class="blueimp-gallery blueimp-gallery-controls"--}}
    {{--data-filter=":even">--}}
    {{--<div class="slides"></div>--}}
    {{--<h3 class="title"></h3>--}}
    {{--<a class="prev">‹</a>--}}
    {{--<a class="next">›</a>--}}
    {{--<a class="close">×</a>--}}
    {{--<a class="play-pause"></a>--}}
    {{--<ol class="indicator"></ol>--}}
    {{--</div>--}}
    {{--<div class="col-md-2">--}}
    {{--<br/>--}}
    {{--<button type="submit" class="btn btn-success">Upload</button>--}}
    {{--</div>--}}
    {{--</form>--}}
    {{--</div>--}}
    {{--</div>--}}
    {{--</div>--}}
    {{--</div>--}}
    {{--</div>--}}
    {{-----------------------------------------------------}}
@stop