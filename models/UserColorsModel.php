<?php
    
    require_once(__DIR__ . '/../config/connection.php');
        
    class UserColorsModel extends Connection{
        private $table;

        function __construct()
        {
            parent::__construct();
            $this->table = 'user_colors';
        }

        function getColorsUser($userId){
            $sqlSelect = $this->connection->query("SELECT * FROM $this->table WHERE user_id = '$userId'");
            return $sqlSelect->fetchAll();
        }

        function deleteColorsUser($userId){
            $sqlDelete = "DELETE FROM $this->table WHERE user_id = '$userId'";
        }

    }
