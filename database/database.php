<?php
// database.php
// This file handles database connection and disconnection functions.
require_once('db_credentials.php'); 

/**
 * Establishes a connection to the database using credentials from db_credentials.php.
 *
 * @return mysqli $connection The database connection resource
 */
function db_connect()
{ 
    $connection = mysqli_connect(DB_SERVER, DB_USER, DB_PASS, DB_NAME, DB_PORT);
    if (!$connection) {
        die("Connection failed: " . mysqli_connect_error());
    }

    return $connection;
}

/**
 * Closes the database connection.
 *
 * @param mysqli $connection The database connection resource to close
 */
function db_disconnect($connection)
{ 
    if (isset($connection)) { 
        mysqli_close($connection);
    }
}

/**
 * Confirms that the result set from a database query is valid.
 *
 * @param mysqli_result $result_set The result set from a database query
 */
function confirm_result_set($result_set)
{  
    if (!$result_set) {
        exit("Database query failed: " . mysqli_error($GLOBALS['connection']));
    }
}
?>
