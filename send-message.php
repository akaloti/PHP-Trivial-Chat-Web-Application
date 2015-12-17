<?php
    session_start();

    require 'dbconnect.php';
    require 'constants.php';
    require 'utility.php';

    $name = $_SESSION[SESSION_NAME];
    $message = $_GET['message'];

    // To return to the caller
    $json = array();

    try {
        // Use parameters so that message can have characters such
        // as quotation marks in them
        $sql = 'INSERT INTO messages (name, message) VALUES ("'
            .$name.'", :message)';
        $stmt = $db->prepare($sql);
        $stmt->bindParam(':message', $message);
        $stmt->execute();
    }
    catch (Exception $e) {
        storeError($json, $e);
    }

    echo json_encode($json);