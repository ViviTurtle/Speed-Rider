<?php

    require("../includes/common.php");
    // Check if we have parameters w1 and w2 being passed to the script through the URL
    if (isset($_GET["lat"]) && isset($_GET["lon"])) {

        // Put the two words together with a space in the middle to form "hello world"
        $lat = $_GET["lat"];
        $lon = $_GET["lon"];

        $query = "CALL SP_UPDATE_DRIVER_LOC('Driver1',$lon,$lat)";
        echo $query;
        $db->query($query);
        header('Location:../trycoordinates.php?run=true');

    }



?>

