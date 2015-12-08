<?php
// File for creating the table; should only have to be called once ever

$db = new PDO("mysql:host=localhost;dbname=trivial_chat",
    "matoro260", "matoro261");
$db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
try {
    $queryStr = "CREATE TABLE users (Name VARCHAR(40), Password VARCHAR(100),
        LastUpdate timestamp DEFAULT NULL)";
    $db->query($queryStr);
} catch (PDOException $e) {
    echo $e->getMessage();
}