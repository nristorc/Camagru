<?php

    class Str{

        static function random($length){
            $ascii = "0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ";
            return substr(str_shuffle(str_repeat($ascii, $length)), 0, $length);
        }

    }

?>