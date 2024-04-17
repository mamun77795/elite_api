@extends('layout')
@section('content')
    <section class="content">
        <div class="row">
            <div class="col-md-12">
                <div class="panel panel-primary">
                    <div class="panel-heading">
                        <h3 class="panel-title">
                            <i class="livicon" data-name="user-add" data-size="18" data-c="#fff" data-hc="#fff"
                               data-loop="true"></i>
                            Connection Search By ID
                        </h3>

                    </div>
                    <input type="text" pachholder="search">
                    {{--<div class="panel-body">--}}
                        {{--<div class="col-md-4">--}}
                            {{--<form class="form-wizard" action="{{url('search')}}" enctype="multipart/form-data"--}}
                                  {{--method="GET">--}}
                                {{--<div class="form-group input-group">--}}

                                    {{--<input type="text" name="text" class="form-control">--}}
                                    {{--<span class="input-group-btn">--}}
                                                {{--<button class="btn btn-default" type="submit">--}}
                                                    {{--<i class="fa fa-search"></i>--}}
                                                {{--</button>--}}
                                     {{--</span>--}}
                                {{--</div>--}}
                            {{--</form>--}}
                        {{--</div>--}}
                        {{--<table class="table table-bordered " id="table">--}}
                            {{--<thead>--}}
                            {{--<tr class="filters">--}}
                                {{--<th>ID</th>--}}
                                {{--<th>PATIENTS NAME</th>--}}
                                {{--<th>MARCHENT NAME</th>--}}
                                {{--<th>TYPE</th>--}}
                                {{--<th>REQUEST</th>--}}
                                {{--<th>TIME</th>--}}
                            {{--</tr>--}}
                            {{--</thead>--}}
                            {{--<tbody>--}}
                            @foreach($users['user'] as $user)
                                <tr class="filters">
                                    <th>ID</th><th>{{$user->id}}</th>
                                    <th>name</th><th>{{$user->name}}</th>
                                    <th>status</th><th>{{$user->status}}</th>
                                         <br>
                                </tr>

                            @endforeach
                            @foreach($users['amenities'] as $user)
                            <tr class="filters">
                                <th>ID</th><th>{{$user->id}}</th>
                                <th>title</th><th>{{$user->title}}</th>
                            </tr>

                    @endforeach
                            {{--</tbody>--}}
                        {{--</table>--}}

                    {{--</div>--}}
                </div>
            </div>
        </div>
        <!--row end-->
    </section>
    {{--</aside>--}}
@stop
