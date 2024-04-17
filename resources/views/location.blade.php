@extends('layout')
@section('content')

    <div class="panel-body">
        <form class="form-wizard" action="{{url('locations')}}" id="locations" enctype="multipart/form-data" method="POST">
            {{ csrf_field() }}
            <script type="text/javascript"
                    src="https://maps.googleapis.com/maps/api/js?key= AIzaSyDCiOlaNF3_tYeKOK0y-4d0PJvbcltTjGM&sensor=false&libraries=places"></script>
            <input id="searchInput" class="input-controls" type="text" placeholder="Enter a location">
            <div class="map" id="map" style="width: 100%; height: 300px;"></div>
            <div class="form_area">
                <input type="text" name="location" id="location">
                <input type="text" name="lat" id="lat">
                <input type="text" name="lng" id="lng">
            </div>

            <div class="form-actions">
                <label class="col-md-3 control-label" for="example-file-input"></label>
                <button type="submit" class="btn btn-primary">Submit</button>
                &nbsp;
                <input type="reset" class="btn btn-default hidden-xs" value="Reset">
            </div>
        </form>
        <script>
            /* script */
            function initialize() {
                var latlng = new google.maps.LatLng(23.7000,90.3750);
                var map = new google.maps.Map(document.getElementById('map'), {
                    center: latlng,
                    zoom: 13
                });
                var marker = new google.maps.Marker({
                    map: map,
                    position: latlng,
                    draggable: true,
                    anchorPoint: new google.maps.Point(0, -29)
                });
                var input = document.getElementById('searchInput');
                map.controls[google.maps.ControlPosition.TOP_LEFT].push(input);
                var geocoder = new google.maps.Geocoder();
                var autocomplete = new google.maps.places.Autocomplete(input);
                autocomplete.bindTo('bounds', map);
                var infowindow = new google.maps.InfoWindow();
                autocomplete.addListener('place_changed', function() {
                    infowindow.close();
                    marker.setVisible(false);
                    var place = autocomplete.getPlace();
                    if (!place.geometry) {
                        window.alert("Autocomplete's returned place contains no geometry");
                        return;
                    }

                    // If the place has a geometry, then present it on a map.
                    if (place.geometry.viewport) {
                        map.fitBounds(place.geometry.viewport);
                    } else {
                        map.setCenter(place.geometry.location);
                        map.setZoom(17);
                    }

                    marker.setPosition(place.geometry.location);
                    marker.setVisible(true);

                    bindDataToForm(place.formatted_address,place.geometry.location.lat(),place.geometry.location.lng());
                    infowindow.setContent(place.formatted_address);
                    infowindow.open(map, marker);

                });
                // this function will work on marker move event into map
                google.maps.event.addListener(marker, 'dragend', function() {
                    geocoder.geocode({'latLng': marker.getPosition()}, function(results, status) {
                        if (status == google.maps.GeocoderStatus.OK) {
                            if (results[0]) {
                                bindDataToForm(results[0].formatted_address,marker.getPosition().lat(),marker.getPosition().lng());
                                infowindow.setContent(results[0].formatted_address);
                                infowindow.open(map, marker);
                            }
                        }
                    });
                });
            }
            function bindDataToForm(address,lat,lng){
                document.getElementById('location').value = address;
                document.getElementById('lat').value = lat;
                document.getElementById('lng').value = lng;
            }
            google.maps.event.addDomListener(window, 'load', initialize);
        </script>

        {{--<script>--}}
            {{--$('#locations').on('submit', function(e) {--}}
{{--//                console.log('test');--}}
                {{--e.preventDefault();--}}
                {{--var token = '{{csrf_token()}}'--}}
                {{--var location = $('#location').val();--}}
                {{--var lat = $('#lat').val();--}}
                {{--var lng = $('#lng').val();--}}
                {{--$.ajax({--}}
                    {{--type: "POST",--}}
                    {{--url: '{{ url('locations') }}',--}}
                    {{--data: {address:location, latitude:lat, longitude:lng, _token: token},--}}
                    {{--success: function( msg ) {--}}
                        {{--alert( msg );--}}
                    {{--}--}}
                {{--});--}}
            {{--});--}}
        {{--</script>--}}
    </div> <!-- container / end -->
@stop
