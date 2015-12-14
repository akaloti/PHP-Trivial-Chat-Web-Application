<?php
    session_start();

    require 'dbconnect.php';
    require 'constants.php';

    $name = $_SESSION[SESSION_NAME];

    try {
        // Create a message that says that this user logged in
        $queryStr = 'INSERT INTO messages (name, message, time)
            VALUES ("SERVER", "'.$name.' logged in.", NOW())';
        // Use exec() because no results are returned
        $db->exec($queryStr);
    }
    catch (Exception $e) {
        // Make sure to send the error message back to the webpage
        $json['scriptError'] = true;
        $json['scriptErrorMessage'] = $e->getMessage();
    }