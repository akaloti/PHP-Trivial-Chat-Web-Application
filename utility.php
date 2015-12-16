<?php
    /**
     * Contains functions that don't fit well in any one other file
     */

    /**
     * @param $json the JSON object to store the error message in
     * @param $error the instance of Exception
     */
    function storeError(&$json, &$error) {
        $json['scriptError'] = true;
        $json['scriptErrorMessage'] = $error->getMessage();
    }

    /**
     * @param $db instance of PDO; connection to MySQL database
     * @return the highest id in table messages, or 0 if empty table
     */
    function getHighestId(&$db) {
        // Obtain the highest id from the appropriate table
        $queryStr = 'SELECT MAX(id) AS highestId FROM messages';
        $query = $db->prepare($queryStr);
        $query->execute();
        $result = $query->fetch();
        $query->closeCursor();

        $highestId = $result['highestId'];
        if ($highestId == NULL)
            return 0;
        else
            return $highestId;
    }

    /**
     * @param $json the object in which to store highest id in table
     * messages
     * @param $db instance of PDO; connection to MySQL database
     */
    function storeHighestId(&$json, &$db) {
        $json['lastId'] = getHighestId($db);
    }