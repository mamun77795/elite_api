<?php
  use App\Http\Controllers\CartFunctionController;
  $Function 	= new CartFunctionController();
?>
@extends('layout')

@section('content')


    {{-------------------------------------------}}
    @role(['admin','developer'])
    <div class="row">
        <div class="col-md-12">


                <div class="portlet-title">
                  <div class="caption">
                      <i class="livicon" data-name="lab" data-size="16" data-loop="true" data-c="#fff" data-hc="white"></i>
                      <h3>Sales Order</h3>
                  </div>
                </div>
                <div class="portlet-body">
                    <div class="scrollmenu">
                        <table class="table table-bordered table-hover" id="searchResult">
                          <thead>
                          <tr>
                              <th>ORDER<br /> CODE</th>
                              <th>USER <br />NAME</th>
                              <th>NAME</th>
                              <th>CODE</th>
                              <th>ADDRESS</th>
                              <th>PRODUCT <br />TYPE</th>
                              <th>PRODUCT <br />NAME</th>
                              <th>QUANTITY</th>
                              <th>PRICE</th>
                              <th>PAYMENT <br />TYPE</th>
                              <th>DATE</th>

                          </tr>
                          </thead>
                          <tbody>

                            @foreach($endUsers as $eu)
                              <tr>
                                <td>{{ $Function->getordertrackingcode($eu->id) }}</td>
                                <td>{{ $Function->getEndUserName($eu->enduser_id) }}</td>
                                <td>{{ $Function->getadvancedealer($eu->advance_dealer_id,$eu->sub_agent_id) }}</td>
                                <td>{{ $Function->getdealercode($eu->advance_dealer_id,$eu->sub_agent_id) }}</td>
                                <td>{{ $Function->getdealeraddress($eu->advance_dealer_id,$eu->sub_agent_id) }}</td>
                                <td>{{ $Function->getproduct_id($eu->product_id) }}</td>
                                <td>{{ $Function->getproduct_name($eu->product_id) }}</td>
                                <td>{{ $Function->getquantity($eu->id) }}</td>
                                <td>{{ $Function->getprice($eu->id,$eu->product_id) }}</td>
                                <td>{{ $Function->getadvance_payment_ids($eu->id) }}</td>
                                <td>{{ $Function->getEndUserDateTime($eu->id) }}</td>
                              </tr>
                            @endforeach
                          </tbody>
                      </table>
                    </div>
                </div>


        </div>
    </div>
    @endrole

    @role(['client','bdthai','daimond','amrit'])
    <div class="row">
        <div class="col-md-12">


          <div class="portlet-title">
            <div class="caption">
                <i class="livicon" data-name="lab" data-size="16" data-loop="true" data-c="#fff" data-hc="white"></i>
                <h3>Sales Order</h3>
            </div>
          </div>
          <div class="portlet-body">
              <div class="scrollmenu">
                  <table class="table table-bordered table-hover" id="searchResult">
                    <thead>
                    <tr>
                        <th>ORDER<br /> CODE</th>
                        <th>USER <br />NAME</th>
                        <th>NAME</th>
                        <th>CODE</th>
                        <th>ADDRESS</th>
                        <th>PRODUCT <br />TYPE</th>
                        <th>PRODUCT <br />NAME</th>
                        <th>QUANTITY</th>
                        <th>PRICE</th>
                        <th>PAYMENT <br />TYPE</th>
                        <th>DATE</th>

                    </tr>
                    </thead>
                    <tbody>

                      @foreach($endUsers as $eu)
                        <tr>
                          <td>{{ $Function->getordertrackingcode($eu->id) }}</td>
                          <td>{{ $Function->getEndUserName($eu->enduser_id) }}</td>
                          <td>{{ $Function->getadvancedealer($eu->advance_dealer_id,$eu->sub_agent_id) }}</td>
                          <td>{{ $Function->getdealercode($eu->advance_dealer_id,$eu->sub_agent_id) }}</td>
                          <td>{{ $Function->getdealeraddress($eu->advance_dealer_id,$eu->sub_agent_id) }}</td>
                          <td>{{ $Function->getproduct_id($eu->product_id) }}</td>
                          <td>{{ $Function->getproduct_name($eu->product_id) }}</td>
                          <td>{{ $Function->getquantity($eu->id) }}</td>
                          <td>{{ $Function->getprice($eu->id,$eu->product_id) }}</td>
                          <td>{{ $Function->getadvance_payment_ids($eu->id) }}</td>
                          <td>{{ $Function->getEndUserDateTime($eu->id) }}</td>
                        </tr>
                      @endforeach
                    </tbody>
                </table>
              </div>
          </div>


        </div>
    </div>
    @endrole

@stop
