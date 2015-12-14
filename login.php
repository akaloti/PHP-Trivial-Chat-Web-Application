<?php
    session_start();

    require 'dbconnect.php';
    require 'constants.php';

    $name = $_GET['name'];
    $pw = $_GET['pw'];

    // JSON object to be returned to $.getJSON calling this file
    $json = array('success'=>false, 'scriptError'=>false);

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
                // Successful login
                $json['success'] = true;
                $_SESSION[SESSION_NAME] = $name;

                // Update user's "lastupdate" timestamp, so he/she
                // doesn't get messages he/she wasn't present for
                $queryStr = 'UPDATE users SET lastupdate=NOW()
                    WHERE name="'.$name.'"';
                $query = $db->prepare($queryStr);
                $query->execute();
                $query->closeCursor();

                // Create a message that says that this user logged in
                $queryStr = 'INSERT INTO messages (name, message, time)
                    VALUES ("SERVER", "'.$name.' logged in.", NOW())';
                // Use exec() because no results are returned
                $db->exec($queryStr);
            }
        }
        catch (Exception $e) {
            // Send the error message back to the webpage
            $json['scriptError'] = true;
            $json['scriptErrorMessage'] = $e->getMessage();
        }
    }

    echo json_encode($json);