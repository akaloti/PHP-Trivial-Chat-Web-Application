<?php
    session_start();

    require 'dbconnect.php';
    require 'constants.php';

    // JSON object to be returned to $.getJSON calling this file
    $json = array('success'=>false);

    $name = $_GET['name'];
    $pw = $_GET['pw'];

    if (isset($name) && isset($pw)) {
        try {
            // Check if the given username already exists
            $hash = hash('md5', $pw);
            $queryStr = 'SELECT * FROM users WHERE name = "'.$name.'"';
            $query = $db->prepare($queryStr);
            $query->execute();
            $result = $query->fetch();

            if (!$result) {
                // the user doesn't already exist; create it
                $queryStr = 'INSERT INTO users (name, password, lastupdate)
                    VALUES("'.$name.'", "'.$hash.'", NOW())';
                $db->query($queryStr);

                $_SESSION[SESSION_NAME] = $name;
                $json['success'] = true;

                // Create a message that says that this user logged in
                $queryStr = 'INSERT INTO messages (name, message, time)
                    VALUES ("SERVER", "New user '.$name.' logged in.", NOW())';
                // Use exec() because no results are returned
                $db->exec($queryStr);
            }

            $query->closeCursor();
        }
        catch (PDOException $e) {
            echo $e->getMessage();
        }
    }

    echo json_encode($json);