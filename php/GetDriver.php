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
$fare = $_GET["fare"];


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

if (intval($clTimes[0]['time'])<30) {
    $closestDriver = $clTimes[0]['uname'];

    $db->close();
    $db = new mysqli($host, $username, $password, $dbname);
    $query = "CALL SP_GET_USER_LOC('$closestDriver')";
    $driverLoc = $db->query($query);


    $row = mysqli_fetch_object($driverLoc);
    $driverLat = $row->CURRENT_LATITUDE;
    $driverLong = $row->CURRENT_LONGITUDE;


    $db->close();
    $db = new mysqli($host, $username, $password, $dbname);
    /*$query="CALL SP_LINK_USER('$closestDriver','$ClName', $lat, $long, $driverLat , $driverLong)";
    $db->query($query);*/
//echo $query;
} else {

    echo "<script>window.alert(\"We failed you. All our drivers are not within 30 minutes of you. Our drivers will be appropriately flogged and punished. You will be redirected to logout. We suck! \");</script>";
    header('Location:./logout');
    exit;

}
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
</header>



<script type="text/javascript" src="https://maps.googleapis.com/maps/api/js?key=AIzaSyAAo_ycMAjDvH5v14Z595CyEIr5zbHEnFQ&libraries=geometry"></script>




<!-- Maps -->
<script>
    var passLat = parseFloat(<?php echo json_encode($lat) ?>);
    var passLong = parseFloat(<?php echo json_encode($long) ?>);

    var driverLat = parseFloat(<?php echo json_encode($driverLat) ?>);
    var driverLong = parseFloat(<?php echo json_encode($driverLong) ?>);
    var map;
    var directionDisplay;
    var directionsService;
    var stepDisplay;
    var markerArray = [];
    var position;
    var marker = null;
    var polyline = null;
    var poly2 = null;
    var speed = 0.000005,
        wait = 1;
    var infowindow = null;

    var myPano;
    var panoClient;
    var nextPanoId;
    var timerHandle = null;

    function createMarker(latlng, label, html) {
// alert("createMarker("+latlng+","+label+","+html+","+color+")");
        var contentString = '<b>' + label + '</b><br>' + html;
        var marker = new google.maps.Marker({
            position: latlng,
            map: map,
            title: label,
            zIndex: Math.round(latlng.lat() * -100000) << 5
        });
        marker.setIcon(({
            url: '/img/VanSpriteSmall.png',
            
        }));

        
        marker.myname = label;
// gmarkers.push(marker);

        google.maps.event.addListener(marker, 'click', function() {
            infowindow.setContent(contentString);
            infowindow.open(map, marker);
        });
        return marker;
    }


    function initialize() {
        infowindow = new google.maps.InfoWindow({
            size: new google.maps.Size(150, 50)
        });
// Instantiate a directions service.
        directionsService = new google.maps.DirectionsService();

// Create a map and center it on Manhattan.
        var myOptions = {
            zoom: 13,
            mapTypeId: google.maps.MapTypeId.ROADMAP,
            scrollwheel: false,
            scaleControl: false,
            streetViewControl: false
        }
        map = new google.maps.Map(document.getElementById("map"), myOptions);

        address = 'san jose';
        geocoder = new google.maps.Geocoder();
        geocoder.geocode({
            'address': address
        }, function(results, status) {
            map.setCenter(results[0].geometry.location);
        });

// Create a renderer for directions and bind it to the map.
        var rendererOptions = {
            map: map,
            suppressMarkers: true
        }
        directionsDisplay = new google.maps.DirectionsRenderer(rendererOptions);

// Instantiate an info window to hold step text.
        stepDisplay = new google.maps.InfoWindow();

        polyline = new google.maps.Polyline({
            path: [],
            strokeColor: '#FF0000',
            strokeWeight: 3
        });
        poly2 = new google.maps.Polyline({
            path: [],
            strokeColor: '#FF0000',
            strokeWeight: 3
        });

        calcRoute();
    }



    var steps = [];

    function calcRoute() {

        if (timerHandle) {
            clearTimeout(timerHandle);
        }
        if (marker) {
            marker.setMap(null);
        }
        polyline.setMap(null);
        poly2.setMap(null);
        directionsDisplay.setMap(null);
        polyline = new google.maps.Polyline({
            path: [],
            strokeColor: '#FF0000',
            strokeWeight: 3
        });
        poly2 = new google.maps.Polyline({
            path: [],
            strokeColor: '#FF0000',
            strokeWeight: 3
        });
// Create a renderer for directions and bind it to the map.
        var rendererOptions = {
            map: map,
            suppressMarkers: true
        }

        directionsDisplay = new google.maps.DirectionsRenderer(rendererOptions);



        var start = {lat: driverLat, lng: driverLong};
        var end = {lat: passLat, lng: passLong};
        var travelMode = google.maps.DirectionsTravelMode.DRIVING


        var passMarker = new google.maps.Marker({
            position: end,
            map: map,
            draggable: false
        });
        passMarker.setIcon(({
            url: '/img/PassM.png',
            size: new google.maps.Size(75, 75),
            origin: new google.maps.Point(0, 0),
            anchor: new google.maps.Point(10, 10)
        }));
        passMarker.setVisible(true);

        var infoWindow = new google.maps.InfoWindow({map: map});
        infoWindow.setPosition(end);
        infoWindow.setContent('THIS IS YOU!');

        var request = {
            origin: start,
            destination: end,
            travelMode: travelMode
        };

// Route the directions and pass the response to a
// function to create markers for each step.
        directionsService.route(request, function(response, status) {
            if (status == google.maps.DirectionsStatus.OK) {
                directionsDisplay.setDirections(response);

                var bounds = new google.maps.LatLngBounds();
                var route = response.routes[0];
                startLocation = new Object();
                endLocation = new Object();

// For each route, display summary information.
                var path = response.routes[0].overview_path;
                var legs = response.routes[0].legs;
                for (i = 0; i < legs.length; i++) {
                    if (i == 0) {
                        startLocation.latlng = legs[i].start_location;
                        startLocation.address = legs[i].start_address;
                        marker = createMarker(legs[i].start_location, "The ROUTE", legs[i].start_address, "green");
                    }
                    endLocation.latlng = legs[i].end_location;
                    endLocation.address = legs[i].end_address;
                    var steps = legs[i].steps;
                    for (j = 0; j < steps.length; j++) {
                        var nextSegment = steps[j].path;
                        for (k = 0; k < nextSegment.length; k++) {
                            polyline.getPath().push(nextSegment[k]);
                            bounds.extend(nextSegment[k]);



                        }
                    }
                }

                polyline.setMap(map);
                map.fitBounds(bounds);
//        createMarker(endLocation.latlng,"end",endLocation.address,"red");
                map.setZoom(18);
                startAnimation();
            }
        });
    }



    var step = 50; // 5; // metres
    var tick = 100; // milliseconds
    var eol;
    var k = 0;
    var stepnum = 0;
    var speed = "";
    var lastVertex = 1;


    //=============== animation functions ======================
    function updatePoly(d) {
// Spawn a new polyline every 20 vertices, because updating a 100-vertex poly is too slow
        if (poly2.getPath().getLength() > 20) {
            poly2 = new google.maps.Polyline([polyline.getPath().getAt(lastVertex - 1)]);
// map.addOverlay(poly2)
        }

        if (polyline.GetIndexAtDistance(d) < lastVertex + 2) {
            if (poly2.getPath().getLength() > 1) {
                poly2.getPath().removeAt(poly2.getPath().getLength() - 1)
            }
            poly2.getPath().insertAt(poly2.getPath().getLength(), polyline.GetPointAtDistance(d));
        } else {
            poly2.getPath().insertAt(poly2.getPath().getLength(), endLocation.latlng);
        }
    }


    function animate(d) {
// alert("animate("+d+")");
        if (d > eol) {
            map.panTo(endLocation.latlng);
            return;
        }
        var p = polyline.GetPointAtDistance(d);
        map.panTo(p);
        marker.setPosition(p);
        updatePoly(d);
        timerHandle = setTimeout("animate(" + (d + step) + ")", tick);
    }


    function startAnimation() {
        eol = google.maps.geometry.spherical.computeLength(polyline.getPath());
        map.setCenter(polyline.getPath().getAt(0));
        poly2 = new google.maps.Polyline({
            path: [polyline.getPath().getAt(0)],
            strokeColor: "#0000FF",
            strokeWeight: 10
        });
// map.addOverlay(poly2);
        setTimeout("animate(50)", 2000); // Allow time for the initial map display
    }


    google.maps.event.addDomListener(window, "load", initialize);

    google.maps.LatLng.prototype.latRadians = function() {
        return this.lat() * Math.PI / 180;
    }

    google.maps.LatLng.prototype.lngRadians = function() {
        return this.lng() * Math.PI / 180;
    }


    // === A method which returns a GLatLng of a point a given distance along the path ===
    // === Returns null if the path is shorter than the specified distance ===
    google.maps.Polyline.prototype.GetPointAtDistance = function(metres) {
// some awkward special cases
        if (metres == 0) return this.getPath().getAt(0);
        if (metres < 0) return null;
        if (this.getPath().getLength() < 2) return null;
        var dist = 0;
        var olddist = 0;
        for (var i = 1;
             (i < this.getPath().getLength() && dist < metres); i++) {
            olddist = dist;
            dist += google.maps.geometry.spherical.computeDistanceBetween(this.getPath().getAt(i), this.getPath().getAt(i - 1));
        }
        if (dist < metres) {
            return null;
        }
        var p1 = this.getPath().getAt(i - 2);
        var p2 = this.getPath().getAt(i - 1);
        var m = (metres - olddist) / (dist - olddist);
        return new google.maps.LatLng(p1.lat() + (p2.lat() - p1.lat()) * m, p1.lng() + (p2.lng() - p1.lng()) * m);
    }

    // === A method which returns the Vertex number at a given distance along the path ===
    // === Returns null if the path is shorter than the specified distance ===
    google.maps.Polyline.prototype.GetIndexAtDistance = function(metres) {
// some awkward special cases
        if (metres == 0) return this.getPath().getAt(0);
        if (metres < 0) return null;
        var dist = 0;
        var olddist = 0;
        for (var i = 1;
             (i < this.getPath().getLength() && dist < metres); i++) {
            olddist = dist;
            dist += google.maps.geometry.spherical.computeDistanceBetween(this.getPath().getAt(i), this.getPath().getAt(i - 1));
        }
        if (dist < metres) {
            return null;
        }
        return i;
    }
</script>

<!-- About -->
<section class=about >
    <div id="map">
    </div>
    <div class="vcenter col-md-10 col-md-offset-1 ">

    <p class="vcenter" id="Crappola">Your Driver is on the way! Our drivers take the fastest route
        possible and the approximate route the driver will be taking is shown above. Please be patient and our driver
        will arrive within 30 minutes. <h2 style="background:#4c4c79;color:#bcbca5;font-size:2em;">Remember your fare is $<?php echo $fare?></h2></p>

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
                    <p><h5>1 Washington Square<br>San Jose, CA 95192</h5></p>
                    <ul class="list-unstyled">
                        <li><h5><i class="fa fa-phone fa-fw"></i>(123) 456-7890</h5> </li>
                        <li><h5><i class="fa fa-envelope-o fa-fw"></i></h5> <a href="mailto:fakeemail@speedrider.ninja">students@sjsu.edu</a>
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


<!-- end snippet -->


<script type ="text/javascript" src="/js/epoly.js"></script><!-- jQuery -->
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


</script>