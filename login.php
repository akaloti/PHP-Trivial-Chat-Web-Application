<?php
    session_start();

    require 'dbconnect.php';
    require 'constants.php';
    require 'utility.php';

    $name = $_GET['name'];
    $pw = $_GET['pw'];

    // JSON object to be returned to $.getJSON calling this file
    $json = array('success'=>false);

    if (isset($name) && isset($pw)) {
        try {
            // Search for username-password combination
            $hash = hash('md5', $pw);
            $queryStr = 'SELECT * FROM users WHERE name = "'.$name.
                '" AND password = "'.$hash.'"';
            $query = $db->prepare($queryStr);
            $query->execute();
            $result = $query->fetch();
            $query->closeCursor();

            if ($result) {
                // Create a message that says that this user logged in
                $queryStr = 'INSERT INTO messages (name, message)
                    VALUES ("SERVER", "'.$name.' logged in.")';
                // Use exec() because no results are returned
                $db->exec($queryStr);

                // Make sure user sees no messages he wasn't there for
                storeHighestId($json, $db);

                // Successful login
                $json['success'] = true;
                $_SESSION[SESSION_NAME] = $name;
            }
        }
        catch (Exception $e) {
            storeError($json, $e);
        }
    }

    echo json_encode($json);