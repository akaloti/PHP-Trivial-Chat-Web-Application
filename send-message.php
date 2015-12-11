<?php
    session_start();

    require 'dbconnect.php';

    $name = $_GET['user'];

    // To return to the caller
    $json = array();

    // Update the user's "last message sent" timestamp
    try {
        $sql = 'UPDATE users SET lastupdate = NOW() WHERE name="'.$name.'"';
        $stmt = $db->prepare($sql);
        $stmt->execute();

        if ($stmt->rowCount() != 1) {
            $stmt->closeCursor();
            throw new Exception("Less or more than 1 row was updated");
        }

        $stmt->closeCursor();
    }
    catch (PDOException $e) {
        // Make sure to send the error message back to the webpage
        $json['scriptError'] = true;
        $json['scriptErrorMessage'] = $e->getMessage();
    }
    catch (Exception $e) {
        // Make sure to send the error message back to the webpage
        $json['scriptError'] = true;
        $json['scriptErrorMessage'] = $e->getMessage();
    }

    echo json_encode($json);