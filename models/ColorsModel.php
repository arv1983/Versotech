<?php
    
    require_once(__DIR__ . '/../config/connection.php');
        
    class ColorsModel extends Connection{
        private $table;

        function __construct()
        {
            parent::__construct();
            $this->table = 'colors';
        }

        function getAll(){
            $sqlSelect = $this->connection->query("SELECT * FROM $this->table");
            return $sqlSelect->fetchAll();
        }
    }
