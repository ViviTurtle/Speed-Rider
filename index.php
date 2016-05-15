<!DOCTYPE html>
<html lang="en">
<head>

    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="">
    <meta name="author" content="">
    <meta name="theme-color" content="#1e1e35">

    <!-- never cache patterns -->
    <meta http-equiv="cache-control" content="max-age=0">
    <meta http-equiv="cache-control" content="no-cache">
    <meta http-equiv="expires" content="0">
    <meta http-equiv="expires" content="Tue, 01 Jan 1980 1:00:00 GMT">
    <meta http-equiv="pragma" content="no-cache">


    <title>Speed Rider</title>

    <!-- Bootstrap Core CSS -->
    <link href="./css/bootstrap.min.css" rel="stylesheet">

    <!-- Custom CSS -->
    <link href="./css/stylish-portfolio.css" rel="stylesheet">

    <!-- Custom Fonts -->
    <link href="./font-awesome/css/font-awesome.min.css" rel="stylesheet" type="text/css">
    <link href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,700,300italic,400italic,700italic"
          rel="stylesheet" type="text/css">

    <!-- jQuery -->
    <script src="./js/jquery-1.12.3.min.js"></script>
    <script src="./js/header.js"></script>
    <script type="text/javascript" src="js/modernizr.custom.86080.js"></script>


    <?php

    require("./includes/common.php");

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


    <!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
    <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
    <!--[if lt IE 9]>
    <script src="https://oss.maxcdn.com/libs/html5shiv/3.7.0/html5shiv.js"></script>
    <script src="https://oss.maxcdn.com/libs/respond.js/1.4.2/respond.min.js"></script>
    <![endif]-->

</head>

<body>


<!-- Navigation Bar -->
<div class="navbartop">

    <a href=""><img src="./img/logowhite.png" class="logo" alt="SpeedRider"></a>
    <ul class="navattop">
        <li><a href="">Home</a></li>
        <li><a href="#about">About</a></li>
        <li><a id="login-trigger" href="./php/login.php">Login</a>
        </li>

        <li><a href="#contact">Contact</a></li>
    </ul>
    <div class="nav-toggle nav-toggle-menu">
        <a id="menu-toggle" href="#" class="btn btn-teal btn-lg toggle"><i class="fa fa-bars"></i></a>
        <nav id="sidebar-wrapper">
            <ul class="sidebar-nav">
                <a id="menu-close" href="#" class="btn btn-light btn-lg pull-right toggle"><i
                        class="fa fa-times"></i></a>
                <li class="sidebar-brand">
                    <a href="#top" onclick=$("#menu-close").click();>Speed Rider</a>
                </li>
                <li>
                    <a href="./php/login.php" onclick=$("#login-content").click();>Login</a>
                </li>
                <li>
                    <a href="#about" onclick=$("#menu-close").click();>About</a>
                </li>
                <li>
                    <a href="#callout" onclick=$("#menu-close").click();>Start Now</a>
                </li>
                <li>
                    <a href="#contact" onclick=$("#menu-close").click();>Contact</a>
                </li>
            </ul>
        </nav>
    </div>
    <!-- ./nav-toggle...-->
</div>
<!-- ./navbartop...-->

<script>$('.navbartop').lockedheader({scrollPoint: 32});</script>


<!-- Header -->
<header id="top" class="header">


    <div class="cb-slideshow">

        <!--picture 1-->
        <li>
            <span class="text-vertical-center">
                    <h1>SPEED RIDER</h1>
                    <h3 class="picComments">We're obscenely fast and we'll get you to your destination in no time!*</h3>
                    <h3 class="disclaimer">*SpeedRider is not responsible for safety.</h3>
                    <a href="#about" class="btn btn-dark btn-lg">Get Started Now!</a>
        </span></li>

        <!--picture 2-->
        <li>
            <span class="text-vertical-center">
                <h1>SPEED RIDER</h1>
                <h3 class="picComments">So fast you'll scream!</h3>
                <br>
                <a href="#about" class="btn btn-dark btn-lg">Get Started Now!</a>

        </span></li>

        <!--picture 3-->
        <li>
            <span class="text-vertical-center">
                <h1>SPEED RIDER</h1>
                <h3 class="picComments">Customers are always happy even at our worst.*</h3>
                <h3 class="disclaimer">*Customer experiences may differ.</h3>
                <a href="#about" class="btn btn-dark btn-lg">Get Started Now!</a>

        </span></li>

        <!--picture 4-->
        <li>
            <span class="text-vertical-center">
                <h1>SPEED RIDER</h1>
                <h3 class="picComments">Share a ride with celebrities...</h3>
                <h3 class="disclaimer">*Customer experiences may differ.</h3>
                <a href="#about" class="btn btn-dark btn-lg">Get Started Now!</a>

        </span></li>

        <!--picture 5-->
        <li>
            <span class="text-vertical-center">
                <h1>SPEED RIDER</h1>
                <h3 class="picComments">Upon request, we have our hypersonic buses.</h3>
                <br>
                <br>
                <a href="#about" class="btn btn-dark btn-lg">Get Started Now!</a>
        </span></li>

        <!--picture 6-->
        <li>
            <span class="text-vertical-center">
                <h1>SPEED RIDER</h1>
                <h3 class="picComments">Friendly and Free Trauma/Accident Response from Our Heroic Staff.*</h3>
                <h3 class="disclaimer">*Staff not responsible for severed limbs or death.</h3>
                <a href="#about" class="btn btn-dark btn-lg">Get Started Now!</a>

        </span></li>


    </div>


</header>


<!-- About -->
<section id="about" class="about">
    <div class="container">
        <div class="row">
            <div class="col-lg-12 text-center">
                <h2>San Jose State University CS 160 Project</h2>
            </div>
        </div>
        <!-- /.row -->
    </div>
    <!-- /.container -->
</section>

<!-- Services -->
<!-- The circle icons use Font Awesome's stacked icon classes. For more information, visit http://fontawesome.io/examples/ -->
<section id="services" class="services bg-primary">
    <div class="container">
        <div class="row text-center">
            <div class="col-lg-10 col-lg-offset-1">
                <h2>How It Works</h2>
                <hr class="small">
                <div class="row">
                    <div class="col-md-3 col-sm-6">
                        <div class="service-item">
                                <span class="fa-stack fa-4x">
                                <i class="fa fa-circle fa-stack-2x"></i>
                                <i class="fa fa-cloud fa-stack-1x text-primary"></i>
                            </span>
                            <h4>
                                <strong>Our Website</strong>
                            </h4>
                            <p>Navigate your browser to www.SpeedRider.ninja.</p>
                        </div>
                    </div>
                    <div class="col-md-3 col-sm-6">
                        <div class="service-item">
                                <span class="fa-stack fa-4x">
                                <i class="fa fa-circle fa-stack-2x"></i>
                                <i class="fa fa-compass fa-stack-1x text-primary"></i>
                            </span>
                            <h4>
                                <strong>Your Destination</strong>
                            </h4>
                            <p>Pick where you want us to take you.</p>
                        </div>
                    </div>
                    <div class="col-md-3 col-sm-6">
                        <div class="service-item">
                                <span class="fa-stack fa-4x">
                                <i class="fa fa-circle fa-stack-2x"></i>
                                <i class="fa fa-flask fa-stack-1x text-primary"></i>
                            </span>
                            <h4>
                                <strong>Our Drivers</strong>
                            </h4>
                            <p>We'll send our nearest available driver to pick you up.</p>
                        </div>
                    </div>
                    <div class="col-md-3 col-sm-6">
                        <div class="service-item">
                                <span class="fa-stack fa-4x">
                                <i class="fa fa-circle fa-stack-2x"></i>
                                <i class="fa fa-shield fa-stack-1x text-primary"></i>
                            </span>
                            <h4>
                                <strong>The Ride</strong>
                            </h4>
                            <p>Sit tight and enjoy your trip!</p>
                        </div>
                    </div>
                </div>
                <!-- /.row (nested) -->
            </div>
            <!-- /.col-lg-10 -->
        </div>
        <!-- /.row -->
    </div>
    <!-- /.container -->
</section>

<!-- Callout -->
<aside id="callout" class="callout">
    <div class="text-vertical-center">
        <h1>Interested?</h1>
    </div>
</aside>

<!-- Call to Action -->
<aside class="call-to-action bg-primary">
    <div class="container">
        <div class="row">
            <div class="col-lg-12 text-center">
                <h3>GET STARTED NOW!</h3>
                <a href="./php/login.php#toregister" class="btn btn-lg btn-dark">SIGN UP</a>
                <a href="./php/login.php" class="btn btn-lg btn-dark">LOGIN</a>
            </div>
        </div>
    </div>
</aside>

<!-- Map -->
<section class="map">
    <div id="map"></div>

    <script>
        var driverLat = <?php echo json_encode($Lat) ?>;
        var driverLong = <?php echo json_encode($Long) ?>;




        function initMap() {
		driverList = [];
            	
            if (driverList[0] != null) {
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
                    map.setZoom(10);

                }, function () {
                    handleLocationError(true, infoWindow, map.getCenter());
                });
            } else {
                // Browser doesn't support Geolocation
                handleLocationError(false, infoWindow, map.getCenter());
            }
            // Driver Markers
        if (driverList.length !== 0) {    
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
	}
            


            
            // Sets a listener on a radio button to change the filter type on Places

            function expandViewportToFitPlace(map, place) {
                if (place.geometry.viewport) {
                    map.fitBounds(place.geometry.viewport);
                } else {
                    map.setCenter(place.geometry.location);
                    map.setZoom(0);
                }
            }


            

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
<script
    src="https://maps.googleapis.com/maps/api/js?key=AIzaSyCBJjObeyq52A4L2wUzNdUDLS6ohWWtI2c&libraries=places&callback=initMap"
    async defer></script>


<!-- jQuery -->
<script src="./js/jquery-1.12.3.min.js"></script>

<!-- Bootstrap Core JavaScript -->
<script src="./js/bootstrap.min.js"></script>

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

    // Scrolls to the selected menu item on the page
    $(function () {
        $('a[href*=#]:not([href=#])').click(function () {
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

</body>

</html>
