@extends('layout')
@section('content')

    <aside class="right-side">
        <section class="content-header">
            <h1>
                User Profile
            </h1>
            <ol class="breadcrumb">
                <li>
                    <a href="home">
                        <i class="livicon" data-name="home" data-size="16" data-color="#000"></i>
                        Home
                    </a>
                </li>
                <li>user</li>
                <li class="active">
                    User Profile
                </li>
            </ol>
        </section>

        <section class="content">
            <div class="row ">
                <div class="col-md-12">
                    <div class="row ">
                        <div class="col-md-4">
                            <div class="form-group">
                                <div class="text-center">
                                    <div class="fileinput fileinput-new" data-provides="fileinput">
                                        @foreach($udata as $info)
                                        <div class="fileinput-new thumbnail">

                                                <img src="{{url('/')}}/../storage/app/{{$info->picture}}" class="img-responsive"
                                                     width="366px" height="218px"/>

                                            {{--<img data-src="holder.js/366x218/#fff:#000" class="img-responsive"--}}
                                                 {{--width="366px" height="218px"/>--}}
                                        </div>
                                            @endforeach
                                    </div>
                                </div>
                            </div>
                            <div class="table-responsive">
                                <table class="table  table-striped" id="user">
                                    @foreach($udata as $info)
                                    <tr>
                                        <td>User Name</td>
                                        <td>
                                            <a href="#" data-pk="1" class="editable"
                                               data-title="Edit User Name">{{$info->name}}</a>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td>Email</td>
                                        <td>
                                            <a href="#" data-pk="1" class="editable" data-title="Edit E-mail">
                                                {{$info->email}}
                                            </a>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td>
                                            User Status
                                        </td>
                                        <td>
                                            <a href="#" data-pk="1" class="editable" data-title="Edit Phone Number">
                                                @if($info->status==0)
                                                    Inactive
                                                @else
                                                    Active
                                                @endif

                                            </a>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td>Designation</td>
                                        <td>
                                            <a href="#" data-pk="1" class="editable" data-title="Edit Address">
                                                Sydney, Australia

                                            </a>
                                        </td>
                                    </tr>
                                        <tr>
                                            <td>Type</td>
                                            <td>
                                                <a href="#" data-pk="1" class="editable" data-title="Edit Address">
                                                    Sydney, Australia

                                                </a>
                                            </td>
                                        </tr>
                                    {{--<tr>--}}
                                        {{--<td>Status</td>--}}
                                        {{--<td>--}}
                                            {{--<a href="#" id="status" data-type="select" data-pk="1" data-value="1"--}}
                                               {{--data-title="Status"></a>--}}
                                        {{--</td>--}}
                                    {{--</tr>--}}
                                    <tr>
                                        <td>Joining Date</td>
                                        <td>
                                            {{$info->created_at}}
                                        </td>
                                    </tr>
                                    {{--<tr>--}}
                                        {{--<td>City</td>--}}
                                        {{--<td>--}}
                                            {{--<a href="#" data-pk="1" class="editable" data-title="Edit City">Nakia</a>--}}
                                        {{--</td>--}}
                                    {{--</tr>--}}
                                </table>
                            </div>


                        </div>

                        @endforeach
                        <div class="col-md-8">
                            <ul class="nav nav-tabs ul-edit responsive">
                                <li class="active">
                                    <a href="#tab-activity" data-toggle="tab">
                                        <i class="livicon" data-name="comments" data-size="16" data-c="#01BC8C"
                                           data-hc="#01BC8C" data-loop="true"></i> Activity
                                    </a>
                                </li>

                                <li>
                                    <a href="#tab-change-pwd" data-toggle="tab">
                                        <i class="livicon" data-name="key" data-size="16" data-c="#01BC8C"
                                           data-hc="#01BC8C" data-loop="true"></i> Change Password
                                    </a>
                                </li>
                            </ul>
                            <div class="tab-content">
                                <div class="col-md-12">
                                <div id="tab-activity" class="tab-pane fade in active">
                                    <div class="activity">
                                        <div class="table-responsive">
                                            <table class="table  table-striped" id="user">
                                                @if (session()->has('message'))
                                                    <div class="alert alert-info{{ session('flash_notification.level') }}">
                                                        <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>

                                                        {!! session('message') !!}
                                                    </div>
                                                @endif
                                                    @foreach($prdata as $info)

                                                <tr>
                                                    <td>User ID</td>
                                                    <td>
                                                        <a href="#" data-pk="1" class="editable"
                                                           data-title="Edit User ID">{{$info->id}}</a>
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td>SSc Year</td>
                                                    <td>
                                                        <a href="#" data-pk="1" class="editable" data-title="Edit E-mail">
                                                            {{$info->ssc_year}}
                                                        </a>
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td>
                                                        Hsc Year
                                                    </td>
                                                    <td>
                                                        <a href="#" data-pk="1" class="editable" data-title="Edit Phone Number">
                                                            {{$info->hsc_year}}
                                                        </a>
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td>Graduation Year</td>
                                                    <td>
                                                        <a href="#" data-pk="1" class="editable" data-title="Edit Address">
                                                            {{$info->grad_year}}
                                                        </a>
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td>Masters Year</td>
                                                    <td>
                                                        <a href="#" id="status" data-type="select" data-pk="1" data-value="1"
                                                           data-title="Status">{{$info->msc_year}}</a>
                                                    </td>
                                                </tr>
                                                    @endforeach
                                                        <tr>
                                                            <td><h3>All Users Profile</h3></td>
                                                            <td>

                                                            </td>
                                                        </tr>
                                                        @foreach($adata as $info)


                                                <tr>
                                                    <td>Street Address</td>
                                                    <td>
                                                        {{$info->street_address}}
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td>City</td>
                                                    <td>
                                                        <a href="#" data-pk="1" class="editable" data-title="Edit City">{{$info->city}}</a>
                                                    </td>
                                                </tr>
                                                        <tr>
                                                            <td>Street Address</td>
                                                            <td>
                                                                {{$info->street_address}}
                                                            </td>
                                                        </tr>
                                                        <tr>
                                                            <td>City</td>
                                                            <td>
                                                                <a href="#" data-pk="1" class="editable" data-title="Edit City">{{$info->city}}</a>
                                                            </td>
                                                        </tr>
                                                        <tr>
                                                            <td>State</td>
                                                            <td>
                                                                {{$info->state}}
                                                            </td>
                                                        </tr>
                                                        <tr>
                                                            <td>Postal Code</td>
                                                            <td>
                                                                <a href="#" data-pk="1" class="editable" data-title="Edit City">{{$info->postal_code}}</a>
                                                            </td>
                                                        </tr>
                                                        <tr>
                                                            <td>Country</td>
                                                            <td>
                                                                @if($info->country==0)
                                                                    Bangladesh
                                                                @elseif($info->country==1)
                                                                    India
                                                                @elseif($info->country==2)
                                                                    Japan
                                                                @elseif($info->country==3)
                                                                    China
                                                                    @endif

                                                            </td>
                                                        </tr>
                                                        <tr>
                                                            <td>Phone Number</td>
                                                            <td>
                                                                <a href="#" data-pk="1" class="editable" data-title="Edit City">{{$info->phone_no}}</a>
                                                            </td>
                                                        </tr>
                                                        <tr>
                                                            <td>Request Phone Number</td>
                                                            <td>
                                                                {{$info->req_phone_no}}
                                                            </td>
                                                        </tr>
                                                        <tr>
                                                            <td>Request Person Name</td>
                                                            <td>
                                                                <a href="#" data-pk="1" class="editable" data-title="Edit City">{{$info->req_person_name}}</a>
                                                            </td>
                                                        </tr>
                                                        <tr>
                                                            <td>Request Person Relation</td>
                                                            <td>
                                                                {{$info->req_person_relation}}
                                                            </td>
                                                        </tr>

                                                        @endforeach
                                            </table>
                                        </div>

                                    </div>
                                </div>
                                <div id="tab-change-pwd" class="tab-pane fade">
                                    <div class="row">
                                        <div class="col-md-12 pd-top">
                                            <form action="#" class="form-horizontal">
                                                <div class="form-body">
                                                    <div class="form-group">
                                                        <label for="inputpassword" class="col-md-3 control-label">
                                                            Password
                                                            <span class='require'>*</span>
                                                        </label>

                                                        <div class="col-md-9">
                                                            <div class="input-group">
                                                                    <span class="input-group-addon">
                                                                        <i class="livicon" data-name="key"
                                                                           data-size="16" data-loop="true" data-c="#000"
                                                                           data-hc="#000"></i>
                                                                    </span>
                                                                <input type="password" placeholder="Password"
                                                                       class="form-control"/>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="form-group">
                                                        <label for="inputnumber" class="col-md-3 control-label">
                                                            Confirm Password
                                                            <span class='require'>*</span>
                                                        </label>

                                                        <div class="col-md-9">
                                                            <div class="input-group">
                                                                    <span class="input-group-addon">
                                                                        <i class="livicon" data-name="key"
                                                                           data-size="16" data-loop="true" data-c="#000"
                                                                           data-hc="#000"></i>
                                                                    </span>
                                                                <input type="password" placeholder="Password"
                                                                       class="form-control"/>
                                                            </div>
                                                        </div>
                                                    </div>

                                                </div>
                                                <div class="form-actions">
                                                    <div class="col-md-offset-3 col-md-9">
                                                        <button type="submit" class="btn btn-primary">Submit</button>
                                                        &nbsp;
                                                        <button type="button" class="btn btn-danger">Cancel</button>
                                                        &nbsp;
                                                        <input type="reset" class="btn btn-default hidden-xs"
                                                               value="Reset"></div>
                                                </div>
                                            </form>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            </div>
        </section>

    </aside>
@stop
