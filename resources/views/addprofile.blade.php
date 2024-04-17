@extends('layout')


@section('content')
    <aside class="right-side">
        <section class="content-header">
            <h1>Add Profile </h1>
            <ol class="breadcrumb">
                <li>
                    <a href="home">
                        <i class="livicon" data-name="home" data-size="16" data-color="#000"></i>
                        Home
                    </a>
                </li>
                <li><a href="profile">Profile</a></li>
            </ol>
        </section>
        <section class="content">
            <div class="row">
                <div class="col-md-12">
                    <div class="panel panel-primary">
                        <div class="panel-heading">
                            <h3 class="panel-title">
                                <i class="livicon" data-name="user-add" data-size="18" data-c="#fff" data-hc="#fff" data-loop="true"></i>
                                Add Profile
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
                                    <form class="form-wizard" action="{{url('addprofile/store')}}" enctype="multipart/form-data" method="POST">
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
                                                <input id="user_id" name="users_id" placeholder="Enter Users Id"type="text" class="form-control required" required>
                                                {{--<select class="form-control" name="users_id">--}}
                                                    {{--@foreach($prdata as $item)--}}
                                                        {{--<option value="{{$item->users_id}}">{{$item->users_id}}</option>--}}
                                                    {{--@endforeach--}}
                                                {{--</select>--}}
                                            </div>
                                            <div class="form-group">
                                                <label for="surname">SSc Passing Year *</label>
                                                <input id="ssc_year" name="ssc_year" type="text" placeholder=" Enter ssc passing year" class="form-control required" required>
                                            </div>
                                            <div class="form-group">
                                                <label for="name">HSc Passing Year</label>
                                                <input id="hsc_year" name="hsc_year" placeholder="Enter hsc passing year"type="text" class="form-control required" required>
                                            </div>
                                            <div class="form-group">
                                                <label for="surname">Graduation Passing year *</label>
                                                <input id="grad_year" name="grad_year" type="text" placeholder=" Enter graduation passing year" class="form-control required" required>
                                            </div>
                                            <div class="form-group">
                                                <label for="name">Master Passing year</label>
                                                <input id="msc_year" name="msc_year" placeholder="Enter masters passing year"type="text" class="form-control">
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
                                                {{--<label for="surname" class="" >Picture</label>--}}
                                                {{--<div class="">--}}
                                                    {{--<input type="file" id="picture" name="picture" required >--}}
                                                {{--</div>--}}
                                            {{--</div>--}}
                                            <div class="form-actions">
                                                <label class="col-md-3 control-label" for="example-file-input"></label>
                                                <button type="submit" class="btn btn-primary">Submit</button>
                                                &nbsp;
                                                <input type="reset" class="btn btn-default hidden-xs" value="Reset">
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
