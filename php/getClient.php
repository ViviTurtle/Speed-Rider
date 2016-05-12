<?php

require("../includes/common.php");

$db = new mysqli($host,$username,$password,$dbname);

$User = $_GET["username"]; 
$lat =  $_GET["lat"];
$long =  $_GET["lon"];

/* check connection */
if (mysqli_connect_errno()) {
    printf("Connect failed: %s\n", mysqli_connect_error());
    // echo 'failed';
}
else
{
    $query = "CALL SP_CHANGE_2_LFCLT('{$User}',{$lat}, {$long})";
    $result = $db->query($query);
     // echo $query;
}

$db->close();

?>
