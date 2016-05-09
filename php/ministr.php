
<html>

<?php
require("./includes/common.php");
include("./includes/headerL.php");



?>



<body>
<br>
<br>
<br>
<br>

<?php

/* show tables */
$query = "select table_name from information_schema.tables where table_schema='Speed_Rider';";
$result = $db->query($query) or die('cannot show tables');



while($tableName = mysqli_fetch_assoc($result)) {

    $table = $tableName['table_name'];
echo '<h3>',$table,'</h3>';

    $result2 = $db->query('SHOW COLUMNS FROM '.$table) or die('cannot show columns from '.$table);

    if(mysqli_num_rows($result2)) {
        echo '<table cellpadding="0" cellspacing="0" class="db-table">';
        echo '<tr><th>Field</th><th>Type</th><th>Null</th><th>Key</th><th>Default<th>Extra</th></tr>';
        while($row2 = mysqli_fetch_assoc($result2)) {
            echo '<tr>';
                foreach($row2 as $key=>$value) {
                    echo '<td>',$value,'</td>';
                }
                echo '</tr>';
            }
            echo '</table><br />';
    }
}



$sql = "SELECT * FROM T_USER";
$result = $db->query($sql);

if ($result->num_rows > 0) {
// output data of each row
while($row = $result->fetch_assoc()) {
echo "UserName: " . $row["USERNAME"]. " - Name: " . $row["FNAME"]. " " . $row["LNAME"]."- User Type: " . $row["USER_TYPE"]." ". $row["CURRENT_LATITUDE"]." ". $row["CURRENT_LONGITUDE"]."<br>";
}
} else {
echo "0 results";
}

?>

</body>

</html>