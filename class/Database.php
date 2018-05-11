<?php

    class database
    {

        private $connection;

        public function __construct($DSN, $USER, $PASSWORD)
        {
            $this->connection = new PDO($DSN, $USER, $PASSWORD);
            $this->connection->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            $this->connection->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_OBJ);
        }

        public function query($query, $params = false){
            if ($params){
                $req = $this->connection->prepare($query);
                $req->execute($params);
            }
            else{
                $req = $this->connection->query($query);
            }

            return $req;
        }

        public function lastInsertId(){
            return $this->connection->lastInsertId();
        }
    }

?>