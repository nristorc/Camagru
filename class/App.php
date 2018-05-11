<?php

class App{

    static $db = null;

    static function getDatabase(){

        if (!self::$db) {
            self::$db = new database('mysql:dbname=camagru;host=127.0.0.1', 'root1', 'root00');
        }
        return self::$db;
    }

    static function redirect($page){
        header("Location: $page");
        exit();
    }

    static function getAuth(){
        return new Auth(Session::getInstance(), ['restriction_msg' => 'Vous devez être connecté pour accéder à cette page !']);
    }
}

?>