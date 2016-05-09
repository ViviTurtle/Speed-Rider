<?php
require("../includes/common.php");
include("../includes/headerL.php");


$db = new mysqli($host,$username,$password,$dbname);


/* check connection */
if (mysqli_connect_errno()) {
    printf("Connect failed: %s\n", mysqli_connect_error());
    exit();
}

$query="CALL SP_GET_DRIV_LFJOB;";



$driverL = $db->query($query);




    //Client $test = $_POST['position'];
    $ClName = $UserInf->USERNAME;
    $lat = $_GET["lat"];
    $long =  $_GET["lon"];


/**
 * Takes one driver at a time. Eventually it will assign.
 */
while ($row = mysqli_fetch_object($driverL)) {


    $Driv[] = $row->USERNAME;
    $Lat[] = $row->CURRENT_LATITUDE;
    $Long[] = $row->CURRENT_LONGITUDE;
}

foreach($Driv as $key=>$value) {

    $url='http://216.58.194.202/maps/api/distancematrix/json?units=imperial&origins='.$lat.','.$long.'&destinations='.$Lat[$key].','.$Long[$key].'&mode=driving&traffic_model';

    $json = file_get_contents($url);
    $data = json_decode($json);

    $time = $data->rows[0]->elements[0]->duration->text;
    $time = intval(preg_replace('/[^0-9]+/', '', $time), 10);
    $clTimes[] = array('uname'=>$Driv[$key], 'time'=>$time);


}

// print_r($clTimes);

usort($clTimes, function($a, $b) {
    return $a['time'] - $b['time'];
});

// echo '<br><br>Sorted Array<br>';
// print_r($clTimes);

// echo '<br><br>'.$Driv[0].' is paired with '.$ClName.'<br>';



$db->close();


$db = new mysqli($host,$username,$password,$dbname);
$query="CALL SP_GET_USER_LOC('$Driv[0]')";

$driverLoc = $db->query($query);



$row = mysqli_fetch_object($driverLoc);
$driverLat = $row->CURRENT_LATITUDE;
$driverLong = $row->CURRENT_LONGITUDE;


$db->close();
$db = new mysqli($host,$username,$password,$dbname);
$query="CALL SP_LINK_USER('$Driv[0]','$ClName', $lat, $long, $driverLat , $driverLong)";
$db->query($query);
echo $query;

unset($clTimes);

unset($Driv);
$db->close();

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
<style>
.vcenter {
    position: relative; 
    top: 5%;
    text-align: center;
}
</style>
</header>



<!-- About -->
<section id="about" class="about">

    <input id="pac-input" class="controls" type="text"
           placeholder="Enter a location">
    <div id="type-selector" class="controls">
        <input type="radio" name="type" id="changetype-all" checked="checked">
        <label for="changetype-all">All</label>

        <input type="radio" name="type" id="changetype-establishment">
        <label for="changetype-establishment">Establishments</label>

        <input type="radio" name="type" id="changetype-address">
        <label for="changetype-address">Addresses</label>

        <input type="radio" name="type" id="changetype-geocode">
        <label for="changetype-geocode">Geocodes</label>
    </div>
    <br/>
    <br/>


    <div id="map" class="col-md-8 col-md-offset-2" style="height: 75%;">
        
    </div>

    <script>


        /**
         * Initialization of Grabbing Geolocation
         */
        function initCoords() {
            var bayarea = new google.maps.LatLng(37.547841, -122.003326);
            var browserSupportFlag = Boolean();
            /*
             Check if geolocation is allowed.
             */
            if (navigator.geolocation) {
                browserSupportFlag = true;
                navigator.geolocation.getCurrentPosition(initMap, function () {
                    noGeolocation(browserSupportFlag);
                });
            } else {
                browserSupportFlag = false;
                noGeolocation(browserSupportFlag);
            }
            function noGeolocation(errorFlag) {
                if (errorFlag == true) {
                    alert("Geolocation service failed.");
                    initMap(bayarea);
                } else {
                    alert("Your browser doesn't support geolocation. We've placed you in Siberia.");
                    initMap(bayarea);
                }
            }
        }
        function initMap(position) {
            // This Variable is to convert position into a LatLng object if it isn't already.
            var convert;
            var driverList = [
                {lat: 37.20422, lng: -121.84769},
                {lat: 37.36742, lng: -121.98267},
                {lat: 37.33413, lng: -121.88052},
            ];

            var curPos = {
                "lat" : position.coords.latitude,
                "lon" : position.coords.longitude
            };

            $.ajax ({
                url:   '/includes/test.php',
                type:  "POST",
                data: curPos,
                success: function(data){
                    console.log(data);
                }
            });
            /**
             * Noticed that the geolocation callback function returns an object of position where
             * there is this position.location.latitude and position.location.longitude. This type of
             * object seems to not always work and gives me errors. Changed all variables instead to
             * google.maps.LatLng() to keep consistent.
             * */
            if (position instanceof google.maps.LatLng) {
                var map = new google.maps.Map(document.getElementById('map'), {
                    center: {lat: position.lat(), lng: position.lng()},
                    zoom: 10
                });
                convert = position;
            } else {
                var map = new google.maps.Map(document.getElementById('map'), {
                    center: {lat: position.coords.latitude, lng: position.coords.longitude},
                    zoom: 10
                });
                convert = new google.maps.LatLng(position.coords.latitude, position.coords.longitude);
            }
            // Add layer of Traffic
            var traffic = new google.maps.TrafficLayer();
            traffic.setMap(map);
            var input = /** @type {!HTMLInputElement} */(
                document.getElementById('pac-input'));
            var types = document.getElementById('type-selector');
            map.controls[google.maps.ControlPosition.TOP_LEFT].push(input);
            map.controls[google.maps.ControlPosition.TOP_LEFT].push(types);
            var autocomplete = new google.maps.places.Autocomplete(input);
            autocomplete.bindTo('bounds', map);
            var infowindow = new google.maps.InfoWindow();
            // User Marker
            var marker = new google.maps.Marker({
                position: {lat: convert.lat(), lng: convert.lng()},
                map: map,
                anchorPoint: new google.maps.Point(0, -29),
                draggable: false
            });
            marker.setIcon(({
                url: '/img/PassM.png',
                size: new google.maps.Size(75, 75),
                origin: new google.maps.Point(0, 0),
                anchor: new google.maps.Point(0, 32)
            }));
            // Driver Markers
            for (var i = 0; i < driverList.length; i++){
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
            autocomplete.addListener('place_changed', function () {
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
                    map.setZoom(17);  // Why 17? Because it looks good.
                }
                marker.setPosition(place.geometry.location);
                marker.setVisible(true);
                var address = '';
                if (place.address_components) {
                    address = [
                        (place.address_components[0] && place.address_components[0].short_name || ''),
                        (place.address_components[1] && place.address_components[1].short_name || ''),
                        (place.address_components[2] && place.address_components[2].short_name || '')
                    ].join(' ');
                }
                infowindow.setContent('<div><strong>' + place.name + '</strong><br>' + address);
                infowindow.open(map, marker);
            });
            // Sets a listener on a radio button to change the filter type on Places
            // Auto-complete
            function setupClickListener(id, types) {
                var radioButton = document.getElementById(id);
                radioButton.addEventListener('click', function () {
                    autocomplete.setTypes(types);
                });
            }



            setupClickListener('changetype-all', []);
            setupClickListener('changetype-address', ['address']);
            setupClickListener('changetype-establishment', ['establishment']);
            setupClickListener('changetype-geocode', ['geocode']);
        }
    </script>


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
                        <li><i class="fa fa-envelope-o fa-fw"></i>  <a href="mailto:name@example.com">students@sjsu.edu</a>
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
<script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyCBJjObeyq52A4L2wUzNdUDLS6ohWWtI2c&libraries=places&callback=initCoords"
        async defer></script>


<!-- jQuery -->
<script src="/js/jquery-1.12.3.min.js"></script>

<!-- Bootstrap Core JavaScript -->
<script src="/js/bootstrap.min.js"></script>

<!-- Custom Theme JavaScript -->
<script>
    // Closes the sidebar menu
    $("#menu-close").click(function(e) {
        e.preventDefault();
        $("#sidebar-wrapper").toggleClass("active");
    });

    // Opens the sidebar menu
    $("#menu-toggle").click(function(e) {
        e.preventDefault();
        $("#sidebar-wrapper").toggleClass("active");
    });

    // Scrolls to the selected menu item on the page
    $(function() {
        $('a[href*=#]:not([href=#])').click(function() {
            if (location.pathname.replace(/^\//, '') == this.pathname.replace(/^\//, '') || location.hostname == this.hostname) {

                var target = $(this.hash);
                target = target.length ? target : $('[name=' + this.hash.slice(1) + ']');
                if (target.length) {
                    $('html,body').animate({
                        scrollTop: target.offset().top
                    }, 1000);
                    return false;
                }
            }
        });
    });
</script>
