<?php

require("/includes/common.php");
include("/includes/headerL.php");


?>




<header id="top" class="header">

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
    <div id="map"></div>

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
                url: '/img/VanSpriteSmall.png',
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
                    url: '/img/PassM.png',
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
