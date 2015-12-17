<?php
    // Database connection
    $db = new PDO("mysql:host=localhost;dbname=trivial_chat",
        "matoro260", "matoro261");
    $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);