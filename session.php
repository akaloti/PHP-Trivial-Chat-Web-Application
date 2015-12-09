<?php
    session_start();

    require 'dbconnect.php';
    require 'constants.php';

    // To return to the caller
    $json = array('connected'=>false);

    if (isset($_SESSION[SESSION_NAME])) {
        try {
            $name = $_SESSION[SESSION_NAME];

            $queryStr =
                'SELECT * FROM users WHERE name = "'.$name.'"';
            $query = $db->prepare($queryStr);
            $query->execute();
            $result = $query->fetch();
            if ($result) {
                $json['connected'] = true;
                $json['name'] = $name;
            }
            else {
                session_unset();
                session_destroy();
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