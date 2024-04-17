@extends('layout')
@section('content')

    <aside class="right-side">
        <!-- Content Header (Page header) -->
        <section class="content-header">
            <h1>Profile</h1>
            <ol class="breadcrumb">
                <li>
                    <a href="home">
                        <i class="livicon" data-name="home" data-size="18" data-loop="true"></i>
                        Home
                    </a>
                </li>
                <li>
                    <a href="#">Profile</a>
                </li>
            </ol>
        </section>

        <div class="row">
            <div class="col-md-12">
                <div class="portlet-title">
                    <div class="table-toolbar">
                        <div class="btn-group pull-right">
                            <a href="{{ url('addprofile')}}" class="btn btn-labeled btn-primary" role="button">
                                <span class="btn-label">
                                    Add Profile
                                </span></a>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <section class="content paddingleft_right15">

            <div class="table-toolbar">
                <div class="row">
                    <div id="sample_editable_1_wrapper" class="">
                        @if (session()->has('message'))
                            <div class="alert alert-info{{ session('flash_notification.level') }}">
                                <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>

                                {!! session('message') !!}
                            </div>
                        @endif
                        <table class="table table-striped table-bordered table-hover dataTable no-footer" id="sample_editable_1" role="grid">
                            <thead class="dark">
                            <tr role="row">
                                {{--<th class="sorting_asc" tabindex="0" aria-controls="sample_editable_1" rowspan="1" colspan="1">ID</th>--}}
                                <th class="sorting" tabindex="0" aria-controls="sample_editable_1" rowspan="1" colspan="1" aria-label="
                                                 Full Name
                                            : activate to sort column ascending" style="width: 10px;">ID</th>
                                <th class="sorting" tabindex="0" aria-controls="sample_editable_1" rowspan="1" colspan="1" aria-label="
                                                 Full Name
                                            : activate to sort column ascending" style="width: 30px;">SSC</th>
                                <th class="sorting" tabindex="0" aria-controls="sample_editable_1" rowspan="1" colspan="1" aria-label="
                                                 Delete
                                            : activate to sort column ascending" style="width: 30px;">HSC</th>
                                <th class="sorting" tabindex="0" aria-controls="sample_editable_1" rowspan="1" colspan="1" aria-label="
                                                 Delete
                                            : activate to sort column ascending" style="width: 30px;">Graduation</th>
                                <th class="sorting" tabindex="0" aria-controls="sample_editable_1" rowspan="1" colspan="1" aria-label="
                                                 Delete
                                            : activate to sort column ascending" style="width: 30px;">Masters</th>
                                <th class="sorting" tabindex="0" aria-controls="sample_editable_1" rowspan="1" colspan="1" aria-label="
                                                 Delete
                                            : activate to sort column ascending" style="width: 50px;">Platform</th>
                                <th class="sorting" tabindex="0" aria-controls="sample_editable_1" rowspan="1" colspan="1" aria-label="
                                                 Delete
                                            : activate to sort column ascending" style="width: 50px;">Designation</th>
                                {{--<th class="sorting" tabindex="0" aria-controls="sample_editable_1" rowspan="1" colspan="1" aria-label="--}}
                                                 {{--Delete--}}
                                            {{--: activate to sort column ascending" style="width: 80px;">Picture</th>--}}
                                <th class="sorting" tabindex="0" aria-controls="sample_editable_1" rowspan="1" colspan="1" aria-label="
                                                 Delete
                                            : activate to sort column ascending" style="width: 125px;">Action</th>

                            </tr>
                            </thead>
                            <tbody>
                            @foreach($alldata as $info)
                                <tr role="row" class="odd">
                                    <td class="sorting_1">{{$info->users_id}}</td>

                                    <td>
                                        {{$info->ssc_year}}
                                    </td>
                                    <td>
                                        {{$info->hsc_year}}
                                    </td>
                                    <td>
                                        {{$info->grad_year}}
                                    </td>
                                    <td>
                                        {{$info->msc_year}}
                                    </td>
                                    <td>
                                        {{$info->platform_id}}
                                    </td>
                                    <td>
                                        {{$info->type_id}}
                                    </td>
                                    {{--<td>--}}
                                        {{--<img src="{{url('/')}}/../storage/app/--}}{{--$info->picture--}}{{--" width="50px" height="50px"/>--}}
                                    {{--</td>--}}

                                    <td>
                                        {{--<a href="{{url('profile/'. $info->id.'/edit')}}" class="btn btn-primary">Edit</a>--}}
                                        {{--<a href="{{url('profile/delete/'. $info->id)}}" class="btn btn-danger" onclick="return checkDelete()">Delete</a>--}}
                                        <div class="ui-group-buttons">
                                            <a href="{{url('profile/'. $info->id.'/edit')}}" class="btn btn-success btn-xs" role="button"  >
                                                <span class="glyphicon glyphicon-ok"></span>
                                                Edit
                                            </a>
                                            <div class="or or-xs"></div>
                                            <a href="{{url('profile/delete/'. $info->id)}}" class="btn btn-danger btn-xs" onclick="return checkDelete()" role="button">
                                                <span class="glyphicon glyphicon-remove"></span>
                                                Delete
                                            </a>
                                        </div>
                                    </td>
                                </tr>
                            @endforeach

                            </tbody>
                        </table>
                        <!-- Modal for showing delete confirmation -->
                        <div class="modal fade" id="delete_confirm" tabindex="-1" role="dialog" aria-labelledby="user_delete_confirm_title" aria-hidden="true">
                            <div class="modal-dialog">
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">�</button>
                                        <h4 class="modal-title" id="user_delete_confirm_title">
                                            Delete User
                                        </h4>
                                    </div>
                                    <div class="modal-body">
                                        Are you sure to delete this user? This operation is irreversible.
                                    </div>
                                    <div class="modal-footer">
                                        <button type="button" class="btn btn-default" data-dismiss="modal">Cancel</button>
                                        <a href="#" type="button" class="btn btn-danger">Delete</a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <!-- row--> </section>
    </aside>
@stop
