<?php

    class Validator{

        private $data;
        private $errors = [];

        public function __construct($data){
            $this->data = $data;
        }

        private function getField($field){
            if (!isset($this->data[$field]))
                return NULL;
            return $this->data[$field];
        }

        public function isAlpha($field, $errorMsg){
            if (!isset($this->data[$field]) || !preg_match('/^[a-z0-9A-Z_]+$/', $this->getField($field))){
                $this->errors[$field] = $errorMsg;
            }
        }

        public function isName($field, $errorMsg){
            if (!isset($this->data[$field]) || !preg_match('/^[a-zA-Z]+ ?[a-zA-Z]*$/', $this->getField($field))){
                $this->errors[$field] = $errorMsg;
            }
        }

        public function isDate($field, $errorMsg){
            if (!isset($this->data[$field]) || !preg_match('/^(0[1-9]|[12][0-9]|3[01])\/(0[1-9]|1[012])\/(19|20)\d\d$/', $this->getField($field))){
                $this->errors[$field] = $errorMsg;
            }
        }

        public function isUniq($field, $db, $table, $errorMsg){
            $record = $db->query("SELECT id FROM $table WHERE $field = ?", [$this->getField($field)])->fetch();
            if ($record){
                $this->errors[$field] = $errorMsg;
            }
        }

        public function isEmail($field, $errorMsg){
            if(!filter_var($this->getField($field), FILTER_VALIDATE_EMAIL)){
                $this->errors[$field] = $errorMsg;
            }
        }

        public function isConfirmed($field, $errorMsg = ''){
            $value = $this->getField($field);
            if(empty($value) || $value != $this->getField($field . '_confirm')){
                $this->errors[$field] = $errorMsg;
            }
        }

        public function isValid(){
            return empty($this->errors);
        }

        public function getErrors(){
            return $this->errors;
        }

    }

?>