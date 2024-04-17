@extends('layout')
@section('content')
    <div class="panel-body">
        <div class="row">

            <div class="col-lg-12">
                <ul class="nav  nav-tabs ">
                    <li class="active">
                        <a href="#tab1" data-toggle="tab">
                            <i class="fa fa-user"></i>
                            My Profile</a>
                    </li>
                    <li>
                        <a href="#tab2" data-toggle="tab">
                            <i class="fa fa-pencil"></i>
                            Edit Profile</a>
                    </li>
                </ul>
                <div class="tab-content mar-top">
                    <div id="tab1" class="tab-pane fade active in">
                        <div class="row">
                            <div class="col-lg-12">
                                <div class="panel">
                                    <div class="panel-body">
                                        <div class="col-md-4">
                                            <div class="form-group">
                                                <div class="text-center">
                                                    <div class="panel-heading">
                                                        <h3 class="panel-title "> MY PHOTO</h3>
                                                    </div>
                                                    <div class="fileinput fileinput-new" data-provides="fileinput">
                                                        <div class="fileinput-new thumbnail">
                                                            <p>
                                                                <img src="{{url('/')}}/storage/{{$uid=Auth::user()->picture}}"
                                                                     width="200" height="240"
                                                                     class="img-circle img-responsive center-block "
                                                                     alt=""></p>
                                                        </div>
                                                        <div class="fileinput-preview fileinput-exists thumbnail"></div>

                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-8">
                                            <div class="panel-body">
                                                <div class="table-responsive">
                                                    <table class="table table-bordered table-striped" id="users">
                                                        <tr>
                                                            <td>User Name</td>
                                                            <td>
                                                                <a href="#" data-pk="1" class="editable"
                                                                   data-title="Edit User Name">{{ Auth::user()->name }}</a>
                                                            </td>
                                                        </tr>
                                                        <tr>
                                                            <td>Hotel ID</td>
                                                            <td>
                                                                <a href="#" data-pk="1" class="editable"
                                                                   data-title="Edit E-mail">{{ Auth::user()->email }}</a>
                                                            </td>
                                                        </tr>
                                                        <tr>
                                                            <td>Status</td>
                                                            <td>
                                                                @if(Auth::user()->status==0)
                                                                    Inactive
                                                                @elseif(Auth::user()->status==1)
                                                                    Actives
                                                                @elseif(Auth::user()->status==2)
                                                                    Suspended
                                                                @endif
                                                            </td>
                                                        </tr>
                                                        <tr>
                                                            <td>Joining Date</td>
                                                            <td>
                                                                {{Carbon::createFromTimeStamp(strtotime(Auth::user()->created_at))->diffForHumans()}}
                                                            </td>
                                                        </tr>
                                                    </table>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div id="tab2" class="tab-pane fade">
                        <div class="row">
                            <div class="col-md-12 pd-top">
                                {{--@foreach($mydata as $info)--}}
                                <form method="POST" action="{{ url('myprofile/update', ['id' => Auth::user()->id]) }}"
                                      enctype="multipart/form-data">
                                    <input type="hidden" name="_method" value="PUT">
                                    <input type="hidden" name="_token" value="{{ csrf_token() }}">
                                    <section>
                                        <div class="panel-body">
                                            <div class="col-md-4">
                                                <div class="form-group">
                                                    <div class="text-center">
                                                        <div class="fileinput fileinput-new"
                                                             data-provides="fileinput">
                                                            <div class="fileinput-new thumbnail">
                                                                <p>
                                                                    <img src="{{url('/')}}/storage/{{$uid=Auth::user()->picture}}"
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

                                                                    <input type="file" name="picture"></span>
                                                                <a href="#" class="btn btn-default fileinput-exists"
                                                                   data-dismiss="fileinput">Remove</a>
                                                            </div>
                                                            {{-------------------}}
                                                            <div class="alert alert-success">
                                                                NOTE: Please choose a file format jpeg,jpg,png and max
                                                                image size 1MB.

                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="col-md-8">
                                                <div class="form-body">
                                                    <div class="form-group">
                                                        <label for="text" class="col-md-3 control-label">
                                                            Name
                                                            <span class='require'>*</span>
                                                        </label>
                                                        <div class="col-md-8">
                                                            <div class="input-group">
                                                            <span class="input-group-addon">
                                                                <i class="livicon" data-name="key" data-size="16"
                                                                   data-loop="true" data-c="#000" data-hc="#000"></i>
                                                            </span>
                                                                <input type="text" value="{{Auth::user()->name}}"
                                                                       name="name"
                                                                       class="form-control"/>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="form-group">
                                                        <label for="email" class="col-md-3 control-label">
                                                            Hotel ID
                                                            <span class='require'>*</span>
                                                        </label>
                                                        <div class="col-md-8">
                                                            <div class="input-group">
                                                            <span class="input-group-addon">
                                                                <i class="livicon" data-name="key" data-size="16"
                                                                   data-loop="true" data-c="#000" data-hc="#000"></i>
                                                            </span>
                                                                <input type="email" value="{{Auth::user()->email}}"
                                                                       name="email" class="form-control" disabled/>
                                                            </div>
                                                        </div>
                                                    </div>

                                                    <div class="form-body">
                                                        <div class="form-group">
                                                            <label for="inputpassword"
                                                                   class="col-md-3 control-label">
                                                                Password
                                                                <span class='require'>*</span>
                                                            </label>
                                                            <div class="col-md-8">
                                                                <div class="input-group">
                                                            <span class="input-group-addon">
                                                                <i class="livicon" data-name="key" data-size="16"
                                                                   data-loop="true" data-c="#000" data-hc="#000"></i>
                                                            </span>
                                                                    <input type="password"
                                                                           value=""
                                                                           name="password" class="form-control"/>
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <div class="form-group">
                                                            <label for="inputnumber" class="col-md-3 control-label">
                                                                Confirm Password
                                                                <span class='require'>*</span>
                                                            </label>
                                                            <div class="col-md-8">
                                                                <div class="input-group">
                                                                            <span class="input-group-addon">
                                                                                <i class="livicon" data-name="key"
                                                                                   data-size="16"
                                                                                   data-loop="true" data-c="#000"
                                                                                   data-hc="#000"></i>
                                                                            </span>
                                                                    <input type="password"
                                                                           value="{{--$info->password--}}"
                                                                           name="password" class="form-control"/>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                                </br>
                                                <div class="form-actions">
                                                    <div class="col-md-offset-3 col-md-9">
                                                        <button type="submit" class="btn btn-primary">Submit
                                                        </button>
                                                        &nbsp;
                                                        <input type="reset" class="btn btn-default hidden-xs"
                                                               value="Reset"></div>
                                                </div>
                                                {{--@endforeach--}}
                                            </div>
                                        </div>

                                    </section>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        {{--</section>--}}
        {{--</aside>--}}
    </div>
@stop
