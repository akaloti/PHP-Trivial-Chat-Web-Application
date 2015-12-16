<?php
    /**
     * Contains functions that don't fit well in any one other file
     */

    /**
     * @param $json the JSON object to store the error message in
     * @param $error the instance of Exception
     */
    function storeError(&$json, $error) {
        $json['scriptError'] = true;
        $json['scriptErrorMessage'] = $error->getMessage();
    }