<?php
    /**
     * File for creating the users table
     */

    include 'dbconnect.php';
    include 'constants.php';

    try {
        /**
         * Table: users
         *
         * Columns:
         * @name the user's name
         * @password the user's password
         */
        $queryStr = "CREATE TABLE users (
            name varchar(".MAX_USERNAME.") NOT NULL,
            password varchar(100) NOT NULL,
            UNIQUE (name)
            )";
        $db->query($queryStr);
    }
    catch (PDOException $e) {
        echo $e->getMessage();
    }