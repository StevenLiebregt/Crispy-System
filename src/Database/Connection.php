<?php

namespace StevenLiebregt\CrispySystem\Database;

use PDO;
use PDOException;
use StevenLiebregt\CrispySystem\Helpers\Config;

/**
 * Class Connection
 * @package StevenLiebregt\CrispySystem\Database
 * @author Steven Liebregt <stevenliebregt@outlook.com>
 * @since 1.1.3
 */
class Connection
{
    /**
     * @var \PDO Contains the actual PDO connection
     */
    private $pdo;

    /**
     * Connection constructor.
     * Set the PDO connection with the data from the /config/database.php file
     * @since 1.1.3
     */
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
            showPlainError($e->getMessage());
        }
    }

    /**
     * Returns PDO instance
     * @return PDO the PDO instance
     * @since 1.1.3
     */
    public function getPdo() : \PDO
    {
        return $this->pdo;
    }
}