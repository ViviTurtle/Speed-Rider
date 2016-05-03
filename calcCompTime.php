
<!DOCTYPE html>
<html lang="en">

<head>

    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="">
    <meta name="author" content="">

    <!-- never cache patterns -->
    <meta http-equiv="cache-control" content="max-age=0">
    <meta http-equiv="cache-control" content="no-cache">
    <meta http-equiv="expires" content="0">
    <meta http-equiv="expires" content="Tue, 01 Jan 1980 1:00:00 GMT">
    <meta http-equiv="pragma" content="no-cache">


    <title>Speed Rider</title>

<?php



$username = "root";
$password = "testtest";
$host = "localhost";
$dbname = "Speed_Rider";

    // This statement opens a connection to database

$db = new mysqli($host,$username,$password,$dbname);


/* check connection */
if (mysqli_connect_errno()) {
    printf("Connect failed: %s\n", mysqli_connect_error());
    exit();
}

$query="CALL SP_GET_DRIV_LFJOB;"."CALL SP_GET_CLIENTS;";



$db->multi_query($query);
$driverL = $db->store_result();
$db->next_result(); //Skips through
$db->next_result(); //Twice for some reason.
$clientL = $db->store_result();


/**
 * Populate arrays with all the Clients that need a ride.
 */
while ($row = mysqli_fetch_object($clientL)) {
    $ClName[] = $row->USERNAME;
    $lat[] = $row->CURRENT_LATITUDE;
    $long[] = $row->CURRENT_LONGITUDE;
}

/**
 * Takes one driver at a time. Eventually it will assign.
 */
while ($row = mysqli_fetch_object($driverL)) {


    $Driv[] = $row->USERNAME;
    $Driv[] = $row->CURRENT_LATITUDE;
    $Driv[] = $row->CURRENT_LONGITUDE;


    echo '<br><br>'.$Driv[0].' ';
    foreach($ClName as $key=>$value) {
        $url='http://216.58.194.202/maps/api/distancematrix/json?units=imperial&origins='.$Driv[1].','.$Driv[2].'&destinations='.$lat[$key].','.$long[$key].'&mode=driving';
        $json = file_get_contents($url);
        $data = json_decode($json);

        $time = $data->rows[0]->elements[0]->duration->text;
        $time = intval(preg_replace('/[^0-9]+/', '', $time), 10);
        $clTimes[] = array('uname'=>$ClName[$key], 'time'=>$time);


    }

    print_r($clTimes);

    usort($clTimes, function($a, $b) {
        return $a['time'] - $b['time'];
    });

    echo '<br><br>Sorted Array<br>';
    print_r($clTimes);

    echo '<br><br>'.$Driv[0].' is paired with '.$clTimes['0']['uname'].'<br>';

    unset($clTimes);
    unset($Driv);

}




/*
$url='http://216.58.194.202/maps/api/distancematrix/json?units=imperial&origins='.$Driv[1].','.$Driv[2].'&destinations='.$lat[0].','.$long[0].'&mode=driving';

echo $data->rows[0]->elements[0]->duration->text;
*/


$db->close();



/*https://216.58.194.202/maps/api/distancematrix/json?units=imperial&origins=(LAT),(LONG)&
    destinations=(LAT)%2C(LONG)%7C&key=AIzaSyBLK1e0lp9TvoE2wGtQxEsCg5eoTTfJeGM    */

?>


</head>

<body>


</body>

</html>