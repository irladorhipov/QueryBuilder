<?php

namespace App;

use PDO;
use PDOException;
 
class Connection
{
    private object $pdo;

    public function __construct($config)
    {
        $this->open($config['driver'],
                    $config['host'],
                    $config['dbname'],
                    $config['login'],
                    $config['password']);
    }

    private function open(string $driver, 
                          string $host,
                          string $dbname,
                          string $login,
                          string $password): void 
    {    
        $this->pdo = new PDO("$driver:host=$host;dbname=$dbname", $login, $password);
    }

    public function getPDO(): PDO
    {
        return $this->pdo;
    }

    public function isConnect(): string|PDOException
    {
        try {
            if ($this->pdo) return 'success connect';
        } catch (PDOException $exception) {
            echo $exception->getMessage();
        }
    }
}