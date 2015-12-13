<?php
    session_start();

    require 'dbconnect.php';
    require 'constants.php';

    $name = $_SESSION[SESSION_NAME];

    // JSON object to be returned to caller
    $newMessages = array();

    try {
        // Get the time that this client last received messages
        $query = $db->prepare('SELECT * FROM users WHERE name="'.$name.'"');
        $query->execute();
        $lastUpdate = $query->fetch()['lastupdate'];
        $query->closeCursor();

        // Obtain the messages that this user hasn't seen
        $query = $db->prepare('SELECT * FROM messages WHERE
            time > ?');
        $params = array($lastUpdate);
        $query->execute($params);
        for ($i = 0; $row = $query->fetch(); $i++)
            $newMessages[] = array('name'=>$row['name'],
                'message'=>$row['message']);
        $query->closeCursor();

        // Update this user's lastupdate timestamp
        $query = $db->prepare('UPDATE users SET lastupdate=NOW()
            WHERE name="'.$name.'"');
        $query->execute();
        $query->closeCursor();
    }
    catch (Exception $e) {
        // Make sure to send the error message back to the webpage
        $newMessages['scriptError'] = true;
        $newMessages['scriptErrorMessage'] = $e->getMessage();
    }

    $query->closeCursor();
    $db = null;

    echo json_encode($newMessages);