<?php


if (isset($_POST['lat']) || isset($_POST['lng'])) {
    
    $query = "CALL SP_GET_DRIV_LFJOB";

}


require_once("../includes/common.php");
