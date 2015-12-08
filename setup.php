<?php
    // File for creating the table; should only have to be called once ever

    include 'dbconnect.php';

    try {
        $queryStr = "CREATE TABLE users (name VARCHAR(40),
            password VARCHAR(100), lastupdate timestamp DEFAULT NULL)";
        $db->query($queryStr);
    }
    catch (PDOException $e) {
        echo $e->getMessage();
    }