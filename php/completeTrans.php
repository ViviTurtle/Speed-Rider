<?php

require("../includes/common.php");

$db = new mysqli($host,$username,$password,$dbname);

$driver = $_GET["driver"]; 
$client = $_GET["client"];
$status = $_GET["status"];

/* check connection */
if (mysqli_connect_errno()) {
    printf("Connect failed: %s\n", mysqli_connect_error());
    // echo 'failed';
}
else
{
    $query = "CALL SP_COMPLETE_TRANSANCTION('{$driver}','{$client}', '{$status}')";
    $result = $db->query($query);
    while ($row = $result->fetch_assoc()) {
        $cost = $row["COST"];
    }
    echo $cost;
}

$db->close();

?>
