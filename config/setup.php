<?php

    require_once "database.php";
    require_once __DIR__ . "/../class/Database.php";

    try {
        $db = new database($DB_DSN, $DB_USER, $DB_PASSWORD);
        $db->query($CREATE_DB);
        $db->query($DB_MEMBERS);
        $db->query($DB_PHOTO);
        $db->query($DB_COMMENT);
        $db->query($DB_VOTE);

    } catch (PDOException $e) {
        die($e->getMessage());
    }
?>