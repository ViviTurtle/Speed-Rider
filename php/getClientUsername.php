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
    $query = "CALL SP_GET_CLIENT_USERNAME('{$User}')";
    $result = $db->query($query);
    while ($row = $result->fetch_assoc()) {
        $C_USER = $row["CLIENT_USERNAME"];
    }
    echo $C_USER;
}

$db->close();

?>
