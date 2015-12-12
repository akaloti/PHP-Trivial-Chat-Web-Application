<?php
    /**
     * File for creating the table;
     * should only have to be called once ever
     */

    include 'dbconnect.php';

    $usernameSize = 40;

    try {
        /**
         * Table: users
         *
         * Columns:
         * @name the user's name
         * @password the user's password
         * @lastupdate the last time that the user received others' messages
         */
        $queryStr = "CREATE TABLE users (
            name VARCHAR(".$usernameSize."),
            password VARCHAR(100),
            lastupdate TIMESTAMP DEFAULT NULL)";
        $db->query($queryStr);

        /**
         * Table: messages
         *
         * Columns:
         * @name the user who sent the message
         * @message the content of the message
         * @time the time at which the message was sent
         */
        $queryStr = "CREATE TABLE messages (
            name VARCHAR(".$usernameSize."),
            message MEDIUMTEXT,
            time TIMESTAMP DEFAULT NULL)";
        $db->query($queryStr);
    }
    catch (PDOException $e) {
        echo $e->getMessage();
    }