<?php 
    session_start();
    // First we execute our common code to connection to the database and start the session 
    require("../includes/common.php");
     
    // We remove the user's data from the session 
    session_destroy();
    unset($_SESSION['userLogged']);
    // We redirect them to the login page
    header("Location: ../index.html");
    die("Redirecting to: Home");