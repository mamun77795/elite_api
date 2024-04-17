@extends('layout')


@section('content')
    <aside class="right-side">
        <section class="content-header">
            <h1>Profile Update</h1>
            <ol class="breadcrumb">
                <li>
                    <a href="index.html">
                        <i class="livicon" data-name="home" data-size="16" data-color="#000"></i>
                        Dashboard
                    </a>
                </li>
                <li>Employee Profile</li>
                <li class="active">Employee Profile Update</li>
            </ol>
        </section>
        <section class="content">
            <div class="row">
                <div class="col-md-12">
                    <div class="panel panel-primary">
                        <div class="panel-heading">
                            <h3 class="panel-title">
                                <i class="livicon" data-name="user-add" data-size="18" data-c="#fff" data-hc="#fff" data-loop="true"></i>
                                Employee Profile Update
                            </h3>
                                <span class="pull-right clickable">
                                    <i class="glyphicon glyphicon-chevron-up"></i>
                                </span>
                        </div>
                        <div class="panel-body">
                            <!--main content-->
                            <div class="row">

                                <div class="col-md-12">

                                    <!-- BEGIN FORM WIZARD WITH VALIDATION -->
                                    <form method="POST" action="{{ url('profile/update', ['id' => $pdata->id]) }}" enctype="multipart/form-data">
                                        <input type="hidden" name="_method" value="PUT">
                                        @if (session()->has('message'))
                                            <div class="alert alert-success{{ session('flash_notification.level') }}">
                                                <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>

                                                {!! session('message') !!}
                                            </div>
                                        @endif
                                        {{ csrf_field() }}

                                        <section>
                                            <div class="form-group">
                                                <label for="name">User ID</label>
                                                <input id="user_id" name="users_id" value={{$pdata->id}} type="text" class="form-control required" required>
                                            </div>
                                            <div class="form-group">
                                                <label for="surname">SSc Passing Year *</label>
                                                <input id="ssc_year" name="ssc_year" type="text" value={{$pdata->ssc_year}} class="form-control required" required>
                                            </div>
                                            <div class="form-group">
                                                <label for="name">HSc Passing Year</label>
                                                <input id="hsc_year" name="hsc_year" value={{$pdata->hsc_year}} type="text" class="form-control required" required>
                                            </div>
                                            <div class="form-group">
                                                <label for="surname">Graduation Passing year *</label>
                                                <input id="grad_year" name="grad_year" type="text" value={{$pdata->grad_year}} class="form-control required" required>
                                            </div>
                                            <div class="form-group">
                                                <label for="name">Master Passing year</label>
                                                <input id="msc_year" name="msc_year" value={{$pdata->msc_year}} type="text" class="form-control">
                                            </div>
                                            <div class="form-group">
                                                <label for="platform">Platform *</label>
                                                <select class="form-control" name="platform_id">
                                                    @foreach($items as $item)
                                                        <option value="{{$item->id}}">{{$item->platform}}</option>
                                                    @endforeach
                                                </select>
                                            </div>
                                            <div class="form-group">
                                                <label for="type_id">Type *</label>
                                                <select class="form-control" name="type_id">
                                                    @foreach($typedata as $info)
                                                        <option value="{{$info->id}}">{{$info->type}}</option>
                                                    @endforeach
                                                </select>
                                            </div>
                                            {{--<div class="form-group">--}}
                                                {{--<label for="avatar" class="col-md-4 control-label" >Picture</label>--}}
                                                {{--<div class="col-md-6">--}}
                                                    {{--<img src="{{url('/')}}/../storage/app/{{$pdata->picture}}" width="150px" height="150px"/>--}}
                                                    {{--<input type="file" id="picture" name="picture" />--}}
                                                {{--</div>--}}
                                            {{--</div>--}}


                                            </br></br></br>
                                            <div class="form-actions">
                                                <label class="col-md-3 control-label" for="example-file-input"></label>
                                                <div class="col-md-6">
                                                <button type="submit" class="btn btn-primary">Submit</button>
                                                &nbsp;
                                                <input type="reset" class="btn btn-default hidden-xs" value="Reset">
                                                </div>
                                            </div>


                                        </section>
                                    </form>
                                    <!-- END FORM WIZARD WITH VALIDATION -->
                                </div>
                            </div>
                            <!--main content end-->
                        </div>
                    </div>
                </div>
            </div>
            <!--row end-->
        </section>
    </aside>
@stop
