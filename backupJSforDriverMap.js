
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
