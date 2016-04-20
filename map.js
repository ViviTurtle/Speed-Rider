
function initMap() 
{
  var map = new google.maps.Map(document.getElementById('map'), {
    center: {lat: 37.670591, lng: -122.302173}, // center in Bay Area
    zoom: 6
  });
  
  var infoWindow = new google.maps.InfoWindow({map: map});
    
  // Hardcoded driver coordinates
  var danvilleCoordinates = new google.maps.LatLng(37.823791,-122.004341);
  var sanJoseCoordinates = new google.maps.LatLng(37.335204,-121.881029);
  var sanMateoCoordinates = new google.maps.LatLng(37.537590,-122.298748);
  var sanFranciscoCoordinates = new google.maps.LatLng(37.783325,-122.440221);
  var haywardCoordinates = new google.maps.LatLng(37.629494,-122.046862);
    

// car image source
  var carIcon = { url: 'http://localhost/Speed-Rider/car_icon.png', size: new google.maps.Size(20, 32), origin: new google.maps.Point(0, 0), anchor: new google.maps.Point(0, 32), scale: 4,
                title: 'Driver'};

     // Danville driver
    var danvilleMarker = new google.maps.Marker({
    icon: carIcon,
    draggable: false,
    map: map });
    danvilleMarker.setPosition(danvilleCoordinates);
  //  danvilleMarker.addListener('click',displayInfo("Driver",danvilleMarker,map));
 
    
    // san Jose driver
    var sanJoseMarker = new google.maps.Marker({
    icon: carIcon,
    draggable: false,
    map: map });
    sanJoseMarker.setPosition(sanJoseCoordinates);
 //   sanJoseMarker.addListener('click',displayInfo("Driver",sanJoseMarker,map));
    
    // san Francisco driver
    var sanFranciscoMarker = new google.maps.Marker({
    icon: carIcon,
    draggable: false,
    map: map });
    sanFranciscoMarker.setPosition(sanFranciscoCoordinates);
   // sanFranciscoMarker.addListener('click',displayInfo("Driver",
  //                                                     sanFranciscoMarker,map));
    
    // san Mateo driver
     var sanMateoMarker = new google.maps.Marker({
    icon: carIcon,
    draggable: false,
    map: map });
    sanMateoMarker.setPosition(sanMateoCoordinates);
 //   sanMateoMarker.addListener('click',displayInfo("Driver",sanMateoMarker,map));
    
    // hayward driver
    var haywardMarker = new google.maps.Marker({
    icon: carIcon,
    draggable: false,
    map: map });
    haywardMarker.setPosition(haywardCoordinates);
  //  haywardMarker.addListener('click',displayInfo("Driver",haywardMarker,map));

     // Try HTML5 geolocation.
    if (navigator.geolocation)
    {
        navigator.geolocation.getCurrentPosition(function(position) {
        var pos = {lat: position.coords.latitude,lng: position.coords.longitude};
            
        // Rider marker
        var riderMarker = new google.maps.Marker({
            icon: {path: google.maps.SymbolPath.FORWARD_CLOSED_ARROW, scale: 5},
            draggable:false,
            map:map});
            riderMarker.setPosition(pos);

        infoWindow.setPosition(pos);
        infoWindow.setContent('Location Found');
        map.setCenter(pos);
        
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

function toggleBounce()
{
  if (marker.getAnimation() !== null) 
  {
    marker.setAnimation(null);
  } 
  else 
  {
    marker.setAnimation(google.maps.Animation.BOUNCE);
  }
}

//function displayInfo(var info, var marker, var map)
//{
//      // Driver info window
//    var driverInfo = new google.maps.InfoWindow({
//    content: info,
//    map: map
//    });
//    
//    driverInfo.open(map, marker);
//}
