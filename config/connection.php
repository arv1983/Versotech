<?php

class Connection {

    private $databaseFile;
    public $connection;

    public function __construct()
    {
        $this->databaseFile = realpath(__DIR__ . "/../database/db.sqlite");
        $connection = $this->getConnection();
    }

    private function connect()
    {
        return $this->connection = new PDO("sqlite:{$this->databaseFile}");
    }

    public function getConnection()
    {
        return $this->connection ?: $this->connection = $this->connect();
    }

    public function query($query)
    {
        $result = $this->getConnection()->query($query);
        $result->setFetchMode(PDO::FETCH_OBJ);
        return $result;
    }
}