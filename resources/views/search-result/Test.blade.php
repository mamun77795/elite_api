<!DOCTYPE html>
<html lang="en">
<head>
  <title>Bootstrap Example</title>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css">
  <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.4.0/jquery.min.js"></script>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.7/umd/popper.min.js"></script>
  <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js"></script>
</head>
<body>

<div class="container">
  <div class="row">
    <!-- <div class="col-sm-12"> -->
        <h3>Daily Activity</h3>
        <table class="table table-bordered">
            <thead>
                <tr>
                    <th>Date</th>
                    <th>Region</th>
                    <th>Area</th>
                    <th>Duration</th>
                    <th>Purpose</th>

                    <th>Name</th>
                    <th>Address</th>
                    <th>Phone</th>
                    <th>Capacity</th>
                    <th>Farm Type</th>
                    <th>Comments</th>

                    <th>Name</th>
                    <th>Agent Type</th>
                    <th>Address</th>
                    <th>Phone</th>
                    <th>Comments</th>
                </tr>
            </thead>

            <tbody>
                @foreach($dates as $date)
                    @php
                        $activityCount   = 0;
                        $farmCount       = 0;
                        $agentCount      = 0;
                        $numbers         = array();
                        $maxRow          = 1;
                        if(isset($activities[$date])){
                            $activityCount      = sizeof($activities[$date]);
                            array_push($numbers, $activityCount);
                        }
                        if(isset($farmersArr[$date])){
                            $farmCount      = sizeof($farmersArr[$date]);
                            array_push($numbers, $farmCount);
                        }

                        if(isset($agentsArr[$date])){
                            $agentCount         = sizeof($agentsArr[$date]);
                            array_push($numbers, $agentCount);
                        }
                        $maxRow =  max($numbers); // Number of rows in a date
                        unset($numbers); // Empty this array for this Loop.
                    @endphp
                    @for($i = 0; $i < $maxRow; $i++)
                        <tr>
                            <td>{{ $i === 0 ? $date : " " }}</td>
                            @if(isset($activities[$date]))
                                @php
                                    $activityDataArr    = $activities[$date];
                                @endphp
                                @if(isset($activityDataArr[$i]))
                                    @php
                                        $activity = $activityDataArr[$i];
                                    @endphp
                                    <td>{{ $activity['region'] }}</td>
                                    <td>{{ $activity['name_of_area'] }}</td>
                                    <td>{{ $activity['working_duration'] }}</td>
                                    <td>{{ $activity['purpose'] }}</td>
                                @else
                                    <td> </td>
                                    <td> </td>
                                    <td> </td>
                                    <td> </td>
                                @endif
                            @endif

                            <!-- Farmers -->
                            @if(isset($farmersArr[$date]))
                                @php
                                    $farmerdataArr =  $farmersArr[$date];
                                @endphp
                                @if(isset($farmerdataArr[$i]))
                                    @php
                                        $farmerdata = $farmerdataArr[$i];
                                    @endphp
                                    <td>{{ $farmerdata['name'] }}</td>
                                    <td>{{ $farmerdata['address'] }}</td>
                                    <td>{{ $farmerdata['phone'] }}</td>
                                    <td>{{ $farmerdata['farm_capacity'] }}</td>
                                    <td>{{ $farmerdata['farm_type'] }}</td>
                                    <td>{{ $farmerdata['comments'] }}</td>
                                @else
                                    <td> </td>
                                    <td> </td>
                                    <td> </td>
                                    <td> </td>
                                    <td> </td>
                                    <td> </td>
                                @endif
                            @else
                                <td> </td>
                                <td> </td>
                                <td> </td>
                                <td> </td>
                                <td> </td>
                                <td> </td>
                            @endif

                            <!-- Agents -->
                            @if(isset($agentsArr[$date]))
                                @php
                                    $agentDataArr =  $agentsArr[$date];
                                @endphp
                                @if(isset($agentDataArr[$i]))
                                    @php
                                        $agentdata = $agentDataArr[$i];
                                    @endphp
                                    <td>{{ $agentdata['name'] }}</td>
                                    <td>{{ $agentdata['agent_type'] }}</td>
                                    <td>{{ $agentdata['address'] }}</td>
                                    <td>{{ $agentdata['phone'] }}</td>
                                    <td>{{ $agentdata['comments'] }}</td>
                                @else
                                    <td> </td>
                                    <td> </td>
                                    <td> </td>
                                    <td> </td>
                                    <td> </td>
                                @endif
                            @else
                                <td> </td>
                                <td> </td>
                                <td> </td>
                                <td> </td>
                                <td> </td>
                            @endif


                        </tr>

                    @endfor

                @endforeach
            </tbody>
        </table>
  </div>
</div>

</body>
</html>
