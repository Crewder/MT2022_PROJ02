<?php

namespace App\Model;

use PDO;

class DatabaseConnection
{

    private ?PDO $connection = null;

    public function __construct()
    {
        if ($this->connection === null) {
            $this->initalize();
        }
    }

    private function initalize()
    {
        $this->connection = new PDO("mysql:dbname=forum;host=mysql", "root", "root");
    }

    public function getConnection(): PDO
    {
        return $this->connection;
    }
}