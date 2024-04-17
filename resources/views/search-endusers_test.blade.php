<?php
  use App\Http\Controllers\FunctionController;
  $Function 	= new FunctionController();

  $supervisors = $Function->getSupervisor();

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
                    <form class = "form-inline" method="POST" action="{{ route('postSearch') }}">
                      {{ csrf_field() }}
                       <div class = "form-group">
                          <label class = "sr-only" for = "name">Supervisor</label>
                          <select class="form-control" id="supervisor" name="supervisor">
                            <option value="NULL">Admin</option>
                            @foreach($supervisors as $supervisor)
                              <option value="{{ $supervisor->id }}">{{ $supervisor->name }}</option>
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
    $('#supervisor').select2();
    // Ajax Starts
    $("#supervisor").change(function(){ // On Change Event
      var id  = $("#supervisor").val(); // Read the value of Dropdown
      $("#enduser").empty();
      console.log(id);
      $.ajaxSetup({
          headers: {
              'X-CSRF-TOKEN': '<?= csrf_token() ?>'
          }
      });
      $.ajax({
          url: "{{ route('getEndUsers') }}", // Rout to get End User info
          type: 'GET',
          datatype: 'JSON',
          data: { id } // Value sent to Route
      })
      .done(function(response) {
          console.log(response);
          $.each(response, function(i, d) { // For each value
            var $option = $("<option/>", { // Creating options Dynamically
              value: d.id,
              text: d.name
            });
            $("#enduser").append($option); // Adding Option to End User Dropdown
            $("#enduser").select2();
          });

      })
      .fail(function() {
          console.log("error");
      })
    });
    // Ajax End

  });


</script>
