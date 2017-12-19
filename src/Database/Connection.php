<?php

namespace StevenLiebregt\CrispySystem\Database;

use PDO;
use PDOException;
use StevenLiebregt\CrispySystem\Helpers\Config;

/**
 * Class Connection
 *
 * Expects a file /config/database.php with the following keys
 *  driver: 'mysql' // or another type
 *  host: '172.17.0.2' // or another host
 *  dbname: 'name_of_your_database' // or another name
 *  port: '3306' // or another port
 *  user: 'username' // or another username
 *  pass: 'password' // or another password
 *
 * @package StevenLiebregt\CrispySystem\Database
 */
class Connection
{
    /**
     * @var \PDO Contains the actual PDO connection
     */
    private $pdo;

    public function __construct()
    {
        $dsn = sprintf('%s:host=%s;dbname=%s;port=%s',
            Config::get('database.driver'),
            Config::get('database.host'),
            Config::get('database.dbname'),
            Config::get('database.port')
        );

        try {
            $pdo = new PDO($dsn, Config::get('database.user'), Config::get('database.pass'));
            $pdo->setAttribute(\PDO::ATTR_ERRMODE, \PDO::ERRMODE_EXCEPTION);
            $pdo->setAttribute(\PDO::ATTR_EMULATE_PREPARES, false);
            $this->pdo = $pdo;
        } catch (PDOException $e) {
            exit($e->getMessage());
        }
    }

    public function getPdo() : \PDO
    {
        return $this->pdo;
    }
}