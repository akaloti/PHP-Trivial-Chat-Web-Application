<?php
    session_start();

    require 'dbconnect.php';

    $name = $_GET['user'];
    $message = $_GET['message'];

    // To return to the caller
    $json = array();

    try {
        // Use parameters so that message can have characters such
        // as quotation marks in them
        $sql = 'INSERT INTO messages (name, message, time) VALUES ("'
            .$name.'", :message, NOW())';
        $stmt = $db->prepare($sql);
        $stmt->bindParam(':message', $message);
        $stmt->execute();
    }
    catch (Exception $e) {
        // Make sure to send the error message back to the webpage
        $json['scriptError'] = true;
        $json['scriptErrorMessage'] = $e->getMessage();
    }

    echo json_encode($json);