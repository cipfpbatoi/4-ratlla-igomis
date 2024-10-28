<?php

namespace Joc4enRatlla\Services;


class Connect
{
    private \PDO $connection ;
    public function __construct($dbConfig)
    {
        if (isset($_SESSION['connection'])) {
            $this->connection = unserialize($_SESSION['connection'],[Connect::class]);
            return;
        }
        try {
            $dsn = "mysql:host=" . $dbConfig['host'] . ";dbname=" . $dbConfig['dbname'];
            $db = new \PDO($dsn, $dbConfig['username'], $dbConfig['password']);
            $db->setAttribute(\PDO::ATTR_ERRMODE, \PDO::ERRMODE_EXCEPTION);
        } catch (\PDOException $e) {
            die("Error de connexió: " . $e->getMessage());
        }

        $this->connection = $db;
         
    }

    public function getConnection(): \PDO
    {
        return $this->connection;
    }




}