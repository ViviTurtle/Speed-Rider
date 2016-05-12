<?php

require("../includes/common.php");
include("../includes/headerL.php");


?>


<style>
    html, body {
        height: 100%;
        margin: 0;
        padding: 0;
    }
    #map {
        height: 100%;
    }

     
    #floating-panel {
        position: absolute;
        top: 10px;
        left: 25%;
        z-index: 5;
        background-color: #fff;
        padding: 5px;
        border: 1px solid #999;
        text-align: center;
        font-family: 'Roboto','sans-serif';
        line-height: 30px;
        padding-left: 5px;
    }
    #right-panel {
        font-family: 'Roboto','sans-serif';
        line-height: 30px;
        padding-left: 10px;
    }

    #right-panel select, #right-panel input {
        font-size: 15px;
    }

    #right-panel select {
        width: 100%;
    }

    #right-panel i {
        font-size: 12px;
    }
    #right-panel {
        height: 100%;
        float: right;
        width: 390px;
        overflow: auto;
    }
    #map {
        margin-right: 400px;
    }
    #floating-panel {
        background: #fff;
        padding: 1px;
        font-size: 22px;
        font-family: Arial;
        border: 1px solid #ccc;
        box-shadow: 0 2px 2px rgba(33, 33, 33, 0.4);
        display: none;
    }
    @media print {
        #map {
            height: 500px;
            margin: 0;
        }
        #right-panel {
            float: none;
            width: auto;
        }
    }

</style>

<header id="top" class="header">

</header>



<!-- About -->
<section id="about" class="about">

    <div id="floating-panel">
        <button id ="click">Get me to my Client!</button>
    </div>
    <div id="right-panel"></div>

    <div id="map"></div>

    <script>
            function initMap() {
                var directionsService = new google.maps.DirectionsService;
                var directionsDisplay = new google.maps.DirectionsRenderer;
                var map = new google.maps.Map(document.getElementById('map'), {
                    zoom: 10,
                    center: {lat: 37.3351420, lng: -121.8811}
                });
                directionsDisplay.setMap(map);
                directionsDisplay.setPanel(document.getElementById('right-panel'));

                var control = document.getElementById('floating-panel');
                control.style.display = 'block';
                map.controls[google.maps.ControlPosition.TOP_CENTER].push(control);

                var infoWindow = new google.maps.InfoWindow({map: map});

                var pos;
                // Try HTML5 geolocation.
                if (navigator.geolocation) {
                    navigator.geolocation.getCurrentPosition(function(position) {
                        pos = {
                            lat: position.coords.latitude,
                            lng: position.coords.longitude
                        };

                        infoWindow.setPosition(pos);
                        infoWindow.setContent('Location found.');
                        map.setCenter(pos);
                        map.setZoom(15);

                    }, function() {
                        handleLocationError(true, infoWindow, map.getCenter());
                    });
                } else {
                    // Browser doesn't support Geolocation
                    handleLocationError(false, infoWindow, map.getCenter());
                }
                // var customerPosition = 
                var onChangeHandler = function() {
                    calculateAndDisplayRoute(directionsService, directionsDisplay, pos);
                };
                document.getElementById('click').addEventListener('click', onChangeHandler);
                var trafficLayer = new google.maps.TrafficLayer();
                trafficLayer.setMap(map);

            }

        function calculateAndDisplayRoute(directionsService, directionsDisplay, pos) {
            directionsService.route({
                origin: pos,
                destination: {lat: 37.309579, lng: -121.846201},
                travelMode: google.maps.TravelMode.DRIVING
            }, function(response, status) {
                if (status === google.maps.DirectionsStatus.OK) {
                    directionsDisplay.setDirections(response);
                } else {
                    window.alert('Directions request failed due to ' + status);
                }
            });
        }
    </script>


</section>

<!-- Services -->
<!-- The circle icons use Font Awesome's stacked icon classes. For more information, visit http://fontawesome.io/examples/ -->
<section id="services" class="services bg-primary">
    <div class="container">
        <div class="row text-center">
            <div class="col-lg-10 col-lg-offset-1">


                <!-- /.row (nested) -->
            </div>
            <!-- /.col-lg-10 -->
        </div>
        <!-- /.row -->
    </div>
    <!-- /.container -->
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
<script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyCBJjObeyq52A4L2wUzNdUDLS6ohWWtI2c&libraries=places&callback=initMap"
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
