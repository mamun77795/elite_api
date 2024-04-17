@extends('layout')
@section('content')
    <div class="panel-body">
        <div class="row">
            <form class="form-wizard" action="{{url('rooms/create')}}" enctype="multipart/form-data" method="POST">
                <div class="col-md-12">
                    <section>
                        @if (session()->has('message'))
                            <div class="alert alert-success{{ session('flash_notification.level') }}">
                                <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;
                                </button>
                                {!! session('message') !!}
                            </div>
                        @endif
                        {{ csrf_field() }}
                        <div class="display-no" style="display: block;">
                            <div class="col-md-3 form-group" style="">
                                <label for="input-text-2">HOTEL NAME</label>
                                <select class="col-md-3 form-control" id="select-3" name="hotel_id">
                                    @foreach($hotelData as $hotelDatas)
                                        <option value="{{$hotelDatas->id}}">{{$hotelDatas->hotel_name}}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-md-3 form-group" style="">
                                <label class="control-label" for="input-text-3">ROOM PRICE</label>
                                <input class="form-control" name="room_price" id="input-text-3" placeholder="room price"
                                       type="text">
                            </div>
                            <div class="col-md-3 form-group" style="">
                                <label for="input-text-1">ROOM CATEGORY</label>
                                <select class="form-control" id="select-3" name="room_category_id">
                                    @foreach($roomCategory as $roomCategorys)
                                        <option value="{{$roomCategorys->id}}">{{$roomCategorys->category}}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="col-md-3 form-group" style="">
                            <div class="form-group" style="">
                                <label for="input-text-4">ROOM NUMBER</label>
                                <input class="form-control" name="room_number" id="input-text-4"
                                       placeholder="room number" type="text">
                            </div>
                        </div>
                        <div class="col-md-3 form-group" style="">
                            <label for="select-1">NUMBER OF BED</label>
                            <select class="form-control" id="select-1" name="no_of_beds">
                                <option value="1">BED 1</option>
                                <option value="2">BED 2</option>
                                <option value="3">BED 3</option>
                                <option value="4">BED 4</option>
                                <option value="5">BED 5</option>
                            </select>
                        </div>
                        <div class="col-md-3 form-group" style="">
                            <label for="select-2">NUMBER OF CUSTOMER</label>
                            <select class="form-control" id="select-2" name="no_of_customers">
                                <option value="1">PERSON 1</option>
                                <option value="2">PERSON 2</option>
                                <option value="3">PERSON 3</option>
                                <option value="4">PERSON 4</option>
                                <option value="5">PERSON 5</option>
                                <option value="6">PERSON 6</option>
                            </select>
                        </div>
                        <div class="col-md-3 form-group" style="position: static;">
                            <label for="select-3">STATUS</label>
                            <select class="form-control" id="select-3" name="status">
                                <option value="1">ACTIVE</option>
                                <option value="2">BOOKED</option>
                                <option value="3">BLOCKED</option>
                            </select>
                        </div>
                        <div class="col-md-3 form-group" style="">
                            <label class="col-md-3 control-label" for="input-text-5">DETAILS</label>
                            <input class="form-control" name="details" id="input-text-5" placeholder="Enter Details"
                                   type="text">
                        </div>
                        <div class="col-md-2 pull-left">
                            <label class="col-md-12 control-label" for="AMENITIES">AMENITIES</label>
                        </div>
                        <div class="col-md-10 ">
                            @foreach($allAmenity as $allAmenities)
                                <label class="Checkbox" for="AMENITIES-0">
                                    <input name="check_list[]"  alt="Checkbox" value="{{$allAmenities->id}}"
                                           type="checkbox"  >
                                    {{$allAmenities->title}}
                                </label>
                            @endforeach
                        </div>
                    </section>
                </div>
            </form>
        </div>
    </div>
@stop
