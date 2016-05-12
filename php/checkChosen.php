<?php

require("../includes/common.php");

$is_chosen = 0;
$User = $_GET["username"]; 

$db = new mysqli($host,$username,$password,$dbname);
/* check connection */
if (mysqli_connect_errno()) {
    printf("Connect failed: %s\n", mysqli_connect_error());
    // echo 'failed';
}
else
{
    $query = "CALL SP_CHECK_IF_CHOSEN('{$User}')";
    $result = $db->query($query);
    while ($row = $result->fetch_assoc()) {
        $is_chosen = $row["IS_CHOSEN"];
    }
    echo $is_chosen;
}
$db->close();

?>
