
function initMap() 
{
  var map = new google.maps.Map(document.getElementById('map'), {
    center: {lat: 37.670591, lng: -122.302173}, // center in Bay Area
    zoom: 6
  });
  
  var infoWindow = new google.maps.InfoWindow({map: map});
  var geocoder = new google.maps.Geocoder;
  var directionsService = new google.maps.DirectionsService;
  var directionsDisplay = new google.maps.DirectionsRenderer({map:map});
  var markers = [];
  var driverWindow = new google.maps.InfoWindow({content: 'Driver'});
    
    
// car image source
  var carIcon = { url: 'http://localhost/Speed-Rider/car_icon.png', size: new google.maps.Size(70, 80), origin: new google.maps.Point(0, 0), anchor: new google.maps.Point(0, 32), scale: 4,
                title: 'Driver'};
    
      var riderIcon = { url: 'http://localhost/Speed-Rider/user_map_icon.png', size: new google.maps.Size(40, 40), origin: new google.maps.Point(0, 0), anchor: new google.maps.Point(0, 32), scale: 4,
                title: 'Driver'};
    
    
  // array of hardcoded driver markers
  var driverCoors = [
      ['Driver 1',37.823791,-122.004341],
      ['Driver 2',37.335204,-121.881029],
      ['Driver 3',37.537590,-122.298748],
      ['Driver 4',37.783325,-122.440221],
      ['Driver 5',37.629494,-122.046862]];
    
  // populate array of markers

    for(i =0; i <driverCoors.length; ++i)
    {
        markers[i] = new google.maps.Marker({
            icon:carIcon,
            draggable: false,
            map: map });
    var coordinate = new google.maps.LatLng(driverCoors[i][1],driverCoors[i][2]);
     markers[i].setPosition(coordinate);
     markers[i].addListener('click',function(){driverWindow.open(map,markers[i])});
    }
    
   // Instantiate an info window to hold step text.
  var stepDisplay = new google.maps.InfoWindow;
    
   // Try HTML5 geolocation.
    if (navigator.geolocation)
    {
        navigator.geolocation.getCurrentPosition(function(position) {
        var pos = {lat: position.coords.latitude,lng: position.coords.longitude};
        var lat1 = position.coords.latitude;
        var lng1 = position.coords.longitude;
      
        // Rider marker
        var riderMarker = new google.maps.Marker({
            icon: riderIcon,
            draggable:false,
            map:map});
            riderMarker.setPosition(pos);
        map.setCenter(pos);
        map.setZoom(10);
            
//        riderMarker.addListener('click',function(){
//            infoWindow.setPosition(pos);
//            calculateAndDisplayRoute(markers,directionsDisplay, directionsService,              stepDisplay, map, lat1, lng1)});
            geocodeLatLng(pos,geocoder,map,infoWindow);
        
            
        }, function() {handleLocationError(true, infoWindow, map.getCenter());});
        
    } 
    else 
    {
        // Browser doesn't support Geolocation
        handleLocationError(false, infoWindow, map.getCenter());
    }
}

function handleLocationError(browserHasGeolocation, infoWindow, pos)
{
  infoWindow.setPosition(pos);
  infoWindow.setContent(browserHasGeolocation ?
                        'Error: The Geolocation service failed.' :
                        'Error: Your browser doesn\'t support geolocation.');
}

function findClosestMarker(markers,lat1, lon1) {    
    var pi = Math.PI;
    var R = 6371; //equatorial radius
    var distances = [];
    var closest = -1;

    for( i=0;i<markers.length; i++ ) {  
        var lat2 = markers[i].position.lat();
        var lon2 = markers[i].position.lng();

        var chLat = lat2-lat1;
        var chLon = lon2-lon1;

        var dLat = chLat*(pi/180);
        var dLon = chLon*(pi/180);

        var rLat1 = lat1*(pi/180);
        var rLat2 = lat2*(pi/180);

        var a = Math.sin(dLat/2) * Math.sin(dLat/2) + 
                    Math.sin(dLon/2) * Math.sin(dLon/2) * Math.cos(rLat1) *                 Math.cos(rLat2); 
        var c = 2 * Math.atan2(Math.sqrt(a), Math.sqrt(1-a)); 
        var d = R * c;

        distances[i] = d;
        if ( closest == -1 || d < distances[closest] ) {
            closest = i;
        }
    }

    // (debug) The closest marker is:
    //console.log(markers[closest]);
    return markers[closest];
}

function calculateAndDisplayRoute(markers, directionsDisplay, directionsService, stepDisplay, map, lat1, lng1) {
  // First, remove any existing markers from the map.
  var origin = findClosestMarker(markers,lat1,lng1);

  // Retrieve the start and end locations and create a DirectionsRequest using
  // WALKING directions.
    
  directionsService.route({
    origin: origin.getPosition(),
    destination: {lat: lat1, lng: lng1},
    travelMode: google.maps.TravelMode.DRIVING
  }, function(response, status) {
    // Route the directions and pass the response to a function to create
    // markers for each step.
    if (status === google.maps.DirectionsStatus.OK) {
      directionsDisplay.setDirections(response);
//      showSteps(response, markerArray, stepDisplay, map);
    } else {
      window.alert('Directions request failed due to ' + status);
    }
  });
}

function attachInstructionText(stepDisplay, marker, text, map) {
  google.maps.event.addListener(marker, 'click', function() {
    // Open an info window when the marker is clicked on, containing the text
    // of the step.
    stepDisplay.setContent(text);
    stepDisplay.open(map, marker);
  });
}

function geocodeLatLng(position, geocoder, map, infowindow) {
  var latlng = position;
  var addressString = '';
  geocoder.geocode({'location': latlng}, function(results, status) {
    if (status === google.maps.GeocoderStatus.OK) {
      if (results[1]) {
      infowindow.setContent('<div style="content">' +
                            '<b>Your Location</b>' +
                                  '<p>' + results[1].formatted_address + '</p>'+
                                   '<br><button onclick="">' +
                                    'Find closet driver</button>' +
                                    '</div>');
      } else {
        infowindow.alert('No results found');
      }
    } else {
      infowindow.alert('Geocoder failed due to: ' + status);
    }
  });
  return addressString;
}