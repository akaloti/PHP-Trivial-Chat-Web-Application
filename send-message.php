<?php
    session_start();

    require 'dbconnect.php';

    $name = $_GET['user'];
    $message = $_GET['message'];

    // To return to the caller
    $json = array();

    try {
        $sql = 'INSERT INTO messages (name, message, time) VALUES ("'
            .$name.'", "'.$message.'", NOW())';
        // Use exec() because no results are returned
        $db->exec($sql);
    }
    catch (Exception $e) {
        // Make sure to send the error message back to the webpage
        $json['scriptError'] = true;
        $json['scriptErrorMessage'] = $e->getMessage();
    }

    echo json_encode($json);