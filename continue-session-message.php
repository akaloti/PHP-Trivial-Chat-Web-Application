<?php
    session_start();

    require 'dbconnect.php';
    require 'constants.php';

    $name = $_SESSION[SESSION_NAME];

    try {
        // Create a message that says that this user logged in
        $queryStr = 'INSERT INTO messages (name, message)
            VALUES ("SERVER", "'.$name.' logged in.")';
        // Use exec() because no results are returned
        $db->exec($queryStr);
    }
    catch (Exception $e) {
        echo $e->getMessage();
    }