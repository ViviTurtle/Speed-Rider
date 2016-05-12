<?php
require("../includes/common.php");
include("../includes/headerL.php");


$db = new mysqli($host, $username, $password, $dbname);

/* check connection */
if (mysqli_connect_errno()) {
    printf("Connect failed: %s\n", mysqli_connect_error());
    exit();
}

$query = "CALL SP_GET_DRIV_LFJOB;";

$driverL = $db->query($query);


while ($row = mysqli_fetch_object($driverL)) {


    $Driv[] = $row->USERNAME;
    $Lat[] = $row->CURRENT_LATITUDE;
    $Long[] = $row->CURRENT_LONGITUDE;

}


?>

<head>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="">
    <meta name="author" content="">

    <!-- never cache patterns -->
    <meta http-equiv="cache-control" content="max-age=0">
    <meta http-equiv="cache-control" content="no-cache">
    <meta http-equiv="expires" content="0">
    <meta http-equiv="expires" content="Tue, 01 Jan 1980 1:00:00 GMT">
    <meta http-equiv="pragma" content="no-cache">
</head>
<header id="top" class="header1">

    <script>


    </script>

</header>


<section id="about" class="about">



    <div id="map">
    </div>

    <div class="vcenter col-md-10 col-md-offset-1 ">

        <p class="vcenter"><h2>Enter Destination:</h2></p>

        <input id="destination-input" class="controls" type="text" placeholder="Enter a destination location">

        <form>
            <input type="button" value="Request a Driver" onclick="getLocationP()" class="btn-teal"
                   style="height: 100px; width: 300px; font-size: 30px;"/>
        </form>

        
    </div>

</section>


<!-- Footer -->
<footer>
    <section id="contact">
        <div class="container">
            <div class="row">
                <div class="col-lg-10 col-lg-offset-1 text-center">
                    <h4><strong>Speed Rider Team</strong>
                    </h4>
                    <p>1 Washington Square<br>San Jose, CA 95192</p>
                    <ul class="list-unstyled">
                        <li><i class="fa fa-phone fa-fw"></i> (123) 456-7890</li>
                        <li><i class="fa fa-envelope-o fa-fw"></i> <a
                                href="mailto:name@example.com">students@sjsu.edu</a>
                        </li>
                    </ul>
                    <br>
                    <ul class="list-inline">
                        <li>
                            <a href="#"><i class="fa fa-facebook fa-fw fa-3x"></i></a>
                        </li>
                        <li>
                            <a href="#"><i class="fa fa-twitter fa-fw fa-3x"></i></a>
                        </li>
                    </ul>
                    <hr class="small">
                    <p class="text-muted">Copyright &copy; Speed Rider 2016</p>
                </div>
            </div>
        </div>
    </section>
</footer>


<!-- Maps -->

<script>
    var driverLat = <?php echo json_encode($Lat) ?>;
    var driverLong = <?php echo json_encode($Long) ?>;
    var fare = 0;



    function calcFare(distance, time) {
        var costPerMeter = .0005;
        var costPerSec = .005;
        var fare = distance * costPerMeter + time * costPerSec;
        return fare;
    }

    function initMap() {

        driverList = [];
        if (driverLat.length === null) {
            driverList = [
                {lat: 373351420, lng: -121.8811}
            ]
        } else {
            for (i = 0; i < driverLat.length; i++) {
                driverList[i] = {lat: Number(driverLat[i]), lng: Number(driverLong[i])};
            }
        }


        var destination_place_id = null;

        var map = new google.maps.Map(document.getElementById('map'), {
            mapTypeControl: false,
            center: {lat: 37.3351420, lng: -121.8811},
            zoom: 13,
            scrollwheel: false

        });
        var directionsService = new google.maps.DirectionsService;
        var directionsDisplay = new google.maps.DirectionsRenderer;
        var directionDistance = new google.maps.DistanceMatrixService();

        directionsDisplay.setMap(map);


        // get current location
        var infoWindow = new google.maps.InfoWindow({map: map});
        var pos;
        // Try HTML5 geolocation.
        if (navigator.geolocation) {
            navigator.geolocation.getCurrentPosition(function (position) {
                pos = {
                    lat: position.coords.latitude,
                    lng: position.coords.longitude
                };

                GLOBAL_POS = {lat: pos.lat, lng: pos.lng};

                infoWindow.setPosition(pos);
                infoWindow.setContent('YOU ARE HERE');
                map.setCenter(pos);
                map.setZoom(15);

            }, function () {
                handleLocationError(true, infoWindow, map.getCenter());
            });
        } else {
            // Browser doesn't support Geolocation
            handleLocationError(false, infoWindow, map.getCenter());
        }
        // Driver Markers
        for (var i = 0; i < driverList.length; i++) {
            var driverMarker = new google.maps.Marker({
                position: driverList[i],
                map: map,
                draggable: false
            });
            driverMarker.setIcon(({
                url: '/img/VanSpriteSmall.png',
                size: new google.maps.Size(75, 75),
                origin: new google.maps.Point(0, 0),
                anchor: new google.maps.Point(0, 32)
            }));
            driverMarker.setVisible(true);
        }

        // getting the destination. To get lat and long, use document.getElementById('destination-input'). value
        var destination_input = document.getElementById('destination-input');


        //      map.controls[google.maps.ControlPosition.TOP_LEFT].push(pos);
        //      map.controls[google.maps.ControlPosition.TOP_LEFT].push(destination_input);


        var destination_autocomplete =
            new google.maps.places.Autocomplete(destination_input);
        destination_autocomplete.bindTo('bounds', map);

        // Sets a listener on a radio button to change the filter type on Places

        function expandViewportToFitPlace(map, place) {
            if (place.geometry.viewport) {
                map.fitBounds(place.geometry.viewport);
            } else {
                map.setCenter(place.geometry.location);
                map.setZoom(17);
            }
        }


        destination_autocomplete.addListener('place_changed', function () {
            var place = destination_autocomplete.getPlace();
            if (!place.geometry) {
                window.alert("Autocomplete's returned place contains no geometry");
                return;
            }
            expandViewportToFitPlace(map, place);

            // If the place has a geometry, store its place ID and route if we have
            // the other place ID
            destination_place_id = place.place_id;

            //Variable For Latitude/Longitude
            var destination_place_location = place.geometry.location;

            calcDist(pos, destination_place_location, directionDistance);

            route(pos, destination_place_id,
                directionsService, directionsDisplay);

        });


        var trafficLayer = new google.maps.TrafficLayer();
        trafficLayer.setMap(map);
        function route(pos, destination_place_id,
                       directionsService, directionsDisplay) {
            directionsService.route({
                origin: pos,
                destination: {'placeId': destination_place_id},
                travelMode: google.maps.TravelMode.DRIVING
            }, function (response, status) {
                if (status === google.maps.DirectionsStatus.OK) {
                    directionsDisplay.setDirections(response);
                } else {
                    window.alert('Directions request failed due to ' + status);
                }
            });
        }

        function calcDist(pos, destination_place_location,
                          directionDistance) {
            directionDistance.getDistanceMatrix({
                origins: [pos],
                destinations: [destination_place_location],
                travelMode: google.maps.TravelMode.DRIVING,
                unitSystem: google.maps.UnitSystem.IMPERIAL,
                drivingOptions: {
                    departureTime: new Date(Date.now() + 300000)
                }
            }, callback);
        }

        function callback(response, status) {
            var time = "";
            var miles = "";
            if (status == google.maps.DistanceMatrixStatus.OK) {
                var origins = response.originAddresses;
                var destinations = response.destinationAddresses;

                for (var i = 0; i < origins.length; i++) {
                    var results = response.rows[i].elements;
                    for (var j = 0; j < results.length; j++) {
                        var element = results[j];
                        var distance = element.distance.value;
                        miles = element.distance.text;
                        console.log(distance);


                        var duration = element.duration.value;
                        time = element.duration.text;
                        console.log(duration);

                        fare = calcFare(distance, duration);
                        var from = origins[i];
                        var to = destinations[j];
                    }
                }
            }
            window.alert("Your destination is " + miles + " away and will take " + time + ".\n"
                + "The estimated fare for this trip is: $" + parseFloat(Math.round(fare * 100) / 100).toFixed(2)
                + "\nRequest a driver if this is okay!");
        }





    }

    console.log(fare);

    function getLocationP() {
        if (navigator.geolocation) {
            navigator.geolocation.getCurrentPosition(showPositionP);
        }
    }


    function showPositionP(position) {

        var lat = position.coords.latitude;
        var lon = position.coords.longitude;
        window.location.href = "/php/GetDriver.php?lat=" + lat + "&lon=" + lon + "&fare=" + parseFloat(Math.round(fare * 100) / 100).toFixed(2);
    }


</script>
<script
    src="https://maps.googleapis.com/maps/api/js?key=AIzaSyAAo_ycMAjDvH5v14Z595CyEIr5zbHEnFQ&libraries=places&callback=initMap"
    async defer></script>


<!-- jQuery -->
<script src="/js/jquery-1.12.3.min.js"></script>

<!-- Bootstrap Core JavaScript -->
<script src="/js/bootstrap.min.js"></script>

<!-- Custom Theme JavaScript -->
<script>
    // Closes the sidebar menu
    $("#menu-close").click(function (e) {
        e.preventDefault();
        $("#sidebar-wrapper").toggleClass("active");
    });

    // Opens the sidebar menu
    $("#menu-toggle").click(function (e) {
        e.preventDefault();
        $("#sidebar-wrapper").toggleClass("active");
    });


</script>



