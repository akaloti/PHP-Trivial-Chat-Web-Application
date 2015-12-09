<?php
    session_start();

    include 'dbconnect.php';

    $name = $_GET['name'];
    $pw = $_GET['pw'];

    // JSON object to be returned to $.getJSON calling this file
    $json = array('success'=>false, 'scriptError'=>false);

    if (isset($name) && isset($pw)) {
        try {
            $hash = hash('md5', $pw);
            $queryStr = 'SELECT * FROM users WHERE name = "'.$name.
                '" AND password = "'.$hash.'"';
            $query = $db->prepare($queryStr);
            $query->execute();
            $result = $query->fetch();
            if ($result) {
                $json['success'] = true;

                $_SESSION['name'] = $name;
            }

            $query->closeCursor();
        }
        catch (PDOException $e) {
            // Send the error message back to the webpage
            $json['scriptError'] = true;
            $json['scriptErrorMessage'] = $e->getMessage();
        }
    }

    echo json_encode($json);