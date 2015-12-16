<?php
    session_start();

    require 'dbconnect.php';
    require 'constants.php';
    require 'utility.php';

    $name = $_SESSION[SESSION_NAME];

    $json = array();

    try {
        // Create a message that says that this user logged in
        $queryStr = 'INSERT INTO messages (name, message)
            VALUES ("SERVER", "'.$name.' logged in.")';
        // Use exec() because no results are returned
        $db->exec($queryStr);

        // Make sure user sees no messages he wasn't there for
        storeHighestId($json, $db);
    }
    catch (PDOException $e) {
        storeError($json, $e);
    }

    echo json_encode($json);