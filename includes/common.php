

<?php
$username = "speeqhyw_who";
$password = "Blah!ThisSUck@#123";
$host = "localhost";
$dbname = "speeqhyw_Speed_Rider";

// This statement opens a connection to database

$db = new mysqli($host,$username,$password,$dbname);


/* check connection */
if (mysqli_connect_errno()) {
    printf("Connect failed: %s\n", mysqli_connect_error());
    exit();
}

?>

