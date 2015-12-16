<?php
    session_start();

    require 'dbconnect.php';
    require 'constants.php';
    require 'utility.php';

    $name = $_SESSION[SESSION_NAME];
    $lastId = $_GET['lastId'];

    // JSON object to be returned to caller
    $json = array('messages'=>array());

    try {
        // Obtain the messages that this user hasn't seen
        $query = $db->prepare('SELECT * FROM messages WHERE
            id > ?');
        $params = array($lastId);
        $query->execute($params);
        for ($i = 0; $row = $query->fetch(); $i++) {
            $json['messages'][] = array('name'=>$row['name'],
                'message'=>$row['message']);

            // Make sure client is told the most recent message's id;
            // assume highest id is in last row
            $json['lastId'] = $row['id'];
        }
        $query->closeCursor();
    }
    catch (Exception $e) {
        storeError($json, $e);
    }

    $db = null;

    echo json_encode($json);