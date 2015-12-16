<?php
    /**
     * File for creating the messages table
     */

    include 'dbconnect.php';
    include 'constants.php';

    try {
        /**
         * Table: messages
         *
         * Columns:
         * @id auto-incremented, unique id of the message
         * @name the user who sent the message
         * @message the content of the message
         */
        $queryStr = "CREATE TABLE messages (
            id int NOT NULL AUTO_INCREMENT,
            name varchar(".MAX_USERNAME.") NOT NULL,
            message mediumtext NOT NULL,
            PRIMARY KEY (id)
            )";
        $db->query($queryStr);
    }
    catch (PDOException $e) {
        echo $e->getMessage();
    }