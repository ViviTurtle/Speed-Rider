<?php 

    // These variables define the connection information for MySQL database 
    $username = "root"; 
    $password = ""; 
    $host = "localhost"; 
    $dbname = "speedrider"; 

    // UTF-8 is a character encoding scheme that allows you to conveniently store 
    // a wide varienty of special characters, like ¢ or €, in your database. 
    // By passing the following $options array to the database connection code we 
    // are telling the MySQL server that we want to communicate with it using UTF-8 
    // See Wikipedia for more information on UTF-8: 
    // http://en.wikipedia.org/wiki/UTF-8 
    $options = array(PDO::MYSQL_ATTR_INIT_COMMAND => 'SET NAMES utf8');
     

    try 
    { 
        // This statement opens a connection to database
        $db = new PDO("mysql:host={$host};dbname={$dbname};charset=utf8", $username, $password, $options); 
    } 
    catch(PDOException $ex) 
    { 
        // If an error occurs while opening a connection to your database, it will be traped here
        die("Failed to connect to the database: " . $ex->getMessage());
    } 
     
    // throw exception when encounters an error.  
    $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION); 
     
    // return database rows from your database using an associative array.  The array have string indexes, where the string value 
    // represents the name of the column in database. 
    $db->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC); 
     
    // This tells the web browser that your content is encoded using UTF-8 
    // and that it should submit content back to you using UTF-8 
    header('Content-Type: text/html; charset=utf-8'); 
     
    session_start(); 
?>