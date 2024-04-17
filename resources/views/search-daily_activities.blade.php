<?php
  use App\Http\Controllers\FunctionController;
  $Function 	= new FunctionController();

  // $endUsers = $Function->getAllEndUsers();

  $endUsers = $Function->roleBasedEnduser();

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
                        Search
                    </h3>
                </div>

                <div class="panel-body">
                    <form class = "form-inline" method="POST" action="{{ route('dailyactivitySearch') }}">
                      {{ csrf_field() }}
                       <div class = "form-group">
                          <label class = "sr-only" for = "name">Supervisor</label>
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
                          <label class = "sr-only" for = "fromDate">From</label>
                          <input type="date" class="form-control" name="from" id="fromDate">
                       </div>

                       <div class = "form-group">
                          <label class = "sr-only" for = "toDate">To</label>
                          <input type="date" class="form-control" name="to" id="toDate">
                       </div>

                       <div class = "form-group">
                          <!-- <button type="submit" class="btn btn-info" name="button">Search</button> -->
                          <input type="submit" class="form-control" value="Search">
                       </div>
                       <!-- <button type = "submit" class = "btn btn-default">Submit</button> -->
                    </form>
                </div>
            </div>
        </div>
    </div>
    <!--row end-->
</section>

<script type="text/javascript">
$(document).ready(function(){
    $('#enduser').select2();
  });


</script>
