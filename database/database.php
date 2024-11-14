<?php
// database.php
// This code was reused and refactored from PAULO's project for Web Development in Level 2
// AUTHOR: Paulo Ricardo Gomes Granjeiro - 041118057
// Collaborators: Craig, Kyla, Krish, Leonardo, Yazid

require_once('db_credentials.php'); // getting credentials from file db_credentials.php

function db_connect()
{ 
    // Enable error reporting for debugging
    error_reporting(E_ALL);
    ini_set('display_errors', 1);

    // Function to connect form to database
    $connection = mysqli_connect(DB_SERVER, DB_USER, DB_PASS, DB_NAME, DB_PORT);
    
    // Check if the connection is successful
    if (!$connection) {
        die("Connection failed: " . mysqli_connect_error());
    }

    return $connection;
}

function db_disconnect($connection)
{ 
    // Function to disconnect from database
    if (isset($connection)) { 
        mysqli_close($connection);
    }
}

function confirm_result_set($result_set)
{  
    // Check query result
    if (!$result_set) {
        exit("Database query failed: " . mysqli_error($GLOBALS['connection']));
    }
}
?>
