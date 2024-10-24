<?php

namespace Joc4enRatlla\Services;

class Service
{
    public static function loadView($view, $data = [])
    {
        $viewPath = str_replace('.', '/', $view);
        extract($data);

        include  $_SERVER['DOCUMENT_ROOT'] . "/../Views/$viewPath.view.php";

    }

    public static function connect($dbConfig)
    {
        try {
            $dsn = "mysql:host=" . $dbConfig['host'] . ";dbname=" . $dbConfig['dbname'];
            $db = new \PDO($dsn, $dbConfig['username'], $dbConfig['password']);
            $db->setAttribute(\PDO::ATTR_ERRMODE, \PDO::ERRMODE_EXCEPTION);
        } catch (\PDOException $e) {
            die("Error de connexió: " . $e->getMessage());
        }

        return $db;
    }
}