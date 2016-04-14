
function initMap() {
  var map = new google.maps.Map(document.getElementById('map'), {
    center: {lat: 37.670591, lng: -122.302173}, // center in Bay Area
    zoom: 6
  });
  
  var infoWindow = new google.maps.InfoWindow({map: map});
    
  // Hardcoded driver coordinates
  var sanRamonCoordinates = new google.maps.LatLng(37.729057,-121.934515);
  var sanJoseCoordinates = new google.maps.LatLng(37.335204,-121.881029);
  var sanMateoCoordinates = new google.maps.LatLng(37.537590,-122.298748);
  var sanFranciscoCoordinates = new google.maps.LatLng(37.799308,-122.397434);
  var haywardCoordinates = new google.maps.LatLng(37.629494,-122.046862);

  // san ramon driver
  var sanRamonMarker = new google.maps.Marker({
    icon: { path: google.maps.SymbolPath.FORWARD_CLOSED_ARROW, scale: 4 },
    draggable: true,
    map: map });
  sanRamonMarker.setPosition(sanRamonCoordinates);
    
    // san Jose driver
  var sanJoseMarker = new google.maps.Marker({
    icon: { path: google.maps.SymbolPath.FORWARD_CLOSED_ARROW, scale: 4 },
    draggable: true,
    map: map });
  sanJoseMarker.setPosition(sanJoseCoordinates);
    
    // san Francisco driver
 var sanFranciscoMarker = new google.maps.Marker({
    icon: { path: google.maps.SymbolPath.FORWARD_CLOSED_ARROW, scale: 4 },
    draggable: true,
    map: map });
  sanFranciscoMarker.setPosition(sanFranciscoCoordinates);
    
    // san Mateo
     var sanMateoMarker = new google.maps.Marker({
    icon: { path: google.maps.SymbolPath.FORWARD_CLOSED_ARROW, scale: 4 },
    draggable: true,
    map: map });
  sanMateoMarker.setPosition(sanMateoCoordinates);


    
    
    

  // Try HTML5 geolocation.
  if (navigator.geolocation) {
    navigator.geolocation.getCurrentPosition(function(position) {
      var pos = {
        lat: position.coords.latitude,
        lng: position.coords.longitude
      };

      infoWindow.setPosition(pos);
      infoWindow.setContent('Location found.');
      map.setCenter(pos);
    }, function() {
      handleLocationError(true, infoWindow, map.getCenter());
    });
  } else {
    // Browser doesn't support Geolocation
    handleLocationError(false, infoWindow, map.getCenter());
  }
}



function handleLocationError(browserHasGeolocation, infoWindow, pos) {
  infoWindow.setPosition(pos);
  infoWindow.setContent(browserHasGeolocation ?
                        'Error: The Geolocation service failed.' :
                        'Error: Your browser doesn\'t support geolocation.');
}