<?php
    session_start();

    require 'dbconnect.php';
    require 'constants.php';
    require 'utility.php';

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
        catch (Exception $e) {
            storeError($json, $e);
        }
    }

    echo json_encode($json);