<?php
    session_start();
    
    include 'dbconnect.php';
    
    // JSON object to be returned to $.getJSON calling this file
    $json = array('success'=>false);
    
    $name = $_GET['name'];
    $pw = $_GET['pw'];
    
    if (isset($name) && isset($pw)) {
        try {
            $hash = hash('md5', $pw);
            $queryStr = 'SELECT * FROM users WHERE name = "'.$name.'"';
            $query = $db->prepare($queryStr);
            $query->execute();
            $result = $query->fetch();
            if (!$result) {
                $queryStr = 'INSERT INTO users (name, password)
                    VALUES("'.$name.'", "'.$hash.'")';
                $db->query($queryStr);
                    
                $json['success'] = true;
            }
            
            $query->closeCursor();
        }
        catch (PDOException $e) {
            echo $e->getMessage();
        }
    }
    
    echo json_encode($json);
?>