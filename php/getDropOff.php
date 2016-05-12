<?php

require("../includes/common.php");

$db = new mysqli($host,$username,$password,$dbname);

$User = $_GET["username"]; 

/* check connection */
if (mysqli_connect_errno()) {
    printf("Connect failed: %s\n", mysqli_connect_error());
    // echo 'failed';
}
else
{
    $query = "CALL SP_GET_DROP_OFF_LOC('{$User}')";
    $result = $db->query($query);
    while ($row = $result->fetch_assoc()) {
        $long = $row["DROP_OFF_LONGITUDE"];
        $lat = $row["DROP_OFF_LATITUDE"];
    }
    echo "{$long}|{$lat}";
}

$db->close();

?>
