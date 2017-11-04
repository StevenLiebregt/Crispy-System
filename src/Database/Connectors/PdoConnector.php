<?php

namespace StevenLiebregt\CrispySystem\Database\Connectors;

use StevenLiebregt\CrispySystem\Helpers\Config;

class PdoConnector implements IConnector
{
    private $connection;

    public function __construct()
    {
        $dsn = sprintf('%s:host=%s;dbname=%s;port=%s',
            Config::get('database.driver'),
            Config::get('database.host'),
            Config::get('database.dbname'),
            Config::get('database.port')
        );
        try {
            $pdo = new \PDO($dsn, Config::get('database.user'), Config::get('database.pass'));
            $pdo->setAttribute(\PDO::ATTR_ERRMODE, \PDO::ERRMODE_EXCEPTION);
            $pdo->setAttribute(\PDO::ATTR_EMULATE_PREPARES, false);
            $this->connection = $pdo;
        } catch (\PDOException $e) {
            exit($e->getMessage());
        }
    }

    public function getConnection()
    {
        return $this->connection;
    }
}