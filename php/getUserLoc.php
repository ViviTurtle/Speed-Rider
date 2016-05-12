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
    $query = "CALL SP_GET_USER_LOC('{$User}')";
    $result = $db->query($query);
    while ($row = $result->fetch_assoc()) {
        $long = $row["CURRENT_LONGITUDE"];
        $lat = $row["CURRENT_LATITUDE"];
    }
    echo "{$long}|{$lat}";
}

$db->close();

?>
