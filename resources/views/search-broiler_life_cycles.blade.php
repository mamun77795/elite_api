<?php
  use App\Http\Controllers\BroilerLifeCycleFunctionController;
  $Function 	= new BroilerLifeCycleFunctionController();

  $endUsers = $Function->getAllEndUsers();
  $farms = $Function->getAllFarms();

  // exit(var_dump($endUsers));
?>

<section class="content">
    <div class="row">
        <div class="col-md-12">
            <div class="panel panel-primary">
                <div class="panel-heading">
                    <h3 class="panel-title">
                        <i class="livicon" data-name="user-add" data-size="18" data-c="#fff" data-hc="#fff"
                           data-loop="true"></i>
                        Search By User
                    </h3>
                </div>

                <div class="panel-body">
                    <form class = "form-inline" method="POST" action="{{ route('postSearchBroilerLifeCycles') }}">
                      {{ csrf_field() }}
                       <div class = "form-group">
                          <h3 class="panel-title"><label  for = "name">ENDUSER</label></h3><br>
                          <select class="form-control" id="enduser" name="enduser">
                            @foreach($endUsers as $endUser)
                              <option value="{{ $endUser->id }}">{{ $endUser->name }}</option>
                            @endforeach
                          </select>
                       </div>

                       <!-- <div class = "form-group">
                          <label class = "sr-only" for = "name">End User</label>
                          <select class="form-control" id="enduser" name="enduser">

                          </select>
                       </div> -->
                       <div class = "form-group">
                         <h3 class="panel-title"><label  for = "fromDate">From Date</label></h3><br>

                          <input type="date" class="form-control" name="from" id="fromDate">
                       </div>

                       <div class = "form-group">
                         <h3 class="panel-title"><label  for = "toDate">To Date</label></h3><br>

                          <input type="date" class="form-control" name="to" id="toDate">
                       </div>

                       <div class = "form-group">
                          <!-- <button type="submit" class="btn btn-info" name="button">Search</button> -->
                          <br><br><input type="submit" class="form-control" value="Search">
                       </div>
                       <!-- <button type = "submit" class = "btn btn-default">Submit</button> -->
                    </form>
                </div>
            </div>
            <div class="panel panel-primary">
                <div class="panel-heading">
                    <h3 class="panel-title">
                        <i class="livicon" data-name="user-add" data-size="18" data-c="#fff" data-hc="#fff"
                           data-loop="true"></i>
                        Search By Date
                    </h3>
                </div>

                <div class="panel-body">
                    <form class = "form-inline" method="POST" action="{{ route('postSearchBroilerLifeCycledate') }}">
                      {{ csrf_field() }}
                       <div class = "form-group">
                         <h3 class="panel-title"><label  for = "fromDate">From Date</label></h3><br>

                          <input type="date" class="form-control" name="from" id="fromDate">
                       </div>

                       <div class = "form-group">
                         <h3 class="panel-title"><label  for = "toDate">To Date</label></h3><br>

                          <input type="date" class="form-control" name="to" id="toDate">
                       </div>

                       <div class = "form-group">
                          <!-- <button type="submit" class="btn btn-info" name="button">Search</button> -->
                          <br><br><input type="submit" class="form-control" value="Search">
                       </div>
                       <!-- <button type = "submit" class = "btn btn-default">Submit</button> -->
                    </form>
                </div>
            </div>

            <div class="panel panel-primary">
                <div class="panel-heading">
                    <h3 class="panel-title">
                        <i class="livicon" data-name="user-add" data-size="18" data-c="#fff" data-hc="#fff"
                           data-loop="true"></i>
                        Search By Feed Mill
                    </h3>
                </div>

                <div class="panel-body">
                    <form class = "form-inline" method="POST" action="{{ route('postSearchBroilerLifeCyclefarm') }}">
                      {{ csrf_field() }}
                       <div class = "form-group">
                          <h3 class="panel-title"><label  for = "name">Farm</label></h3><br>
                          <select class="form-control" id="farm" name="farm">
                            @foreach($farms as $farm)
                              <option value="{{ $farm->id }}">{{ $farm->name }}</option>
                            @endforeach
                          </select>
                       </div>
                       <div class = "form-group">
                         <h3 class="panel-title"><label  for = "fromDate">From Date</label></h3><br>

                          <input type="date" class="form-control" name="from" id="fromDate">
                       </div>

                       <div class = "form-group">
                         <h3 class="panel-title"><label  for = "toDate">To Date</label></h3><br>

                          <input type="date" class="form-control" name="to" id="toDate">
                       </div>

                       <div class = "form-group">
                          <!-- <button type="submit" class="btn btn-info" name="button">Search</button> -->
                          <br><br><input type="submit" class="form-control" value="Search">
                       </div>
                       <!-- <button type = "submit" class = "btn btn-default">Submit</button> -->
                    </form>
                </div>
        </div>
    </div>
    <!--row end-->
</section>

<script type="text/javascript">
$(document).ready(function(){
    $('#enduser').select2();
  });
  $(document).ready(function(){
      $('#farm').select2();
    });


</script>
