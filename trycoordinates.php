



<script>

    getLocation();
    function getLocation() {
        if (navigator.geolocation) {
            navigator.geolocation.getCurrentPosition(showPosition);
        }
    }

    function showPosition(position) {

        var lat = position.coords.latitude;
        var lon = position.coords.longitude;

    }


    function pushCoordinates() {
        push = false;
        window.location.href = "/includes/passenger.php?lat=" + lat + "&lon=" + lon;

    }
</script>

