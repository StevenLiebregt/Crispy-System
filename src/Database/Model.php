<?php

namespace StevenLiebregt\CrispySystem\Database;

use PDO;

/**
 * Class Model
 * @package StevenLiebregt\CrispySystem\Database
 * @author Steven Liebregt <stevenliebregt@outlook.com>
 * @since 1.1.4
 */
abstract class Model
{
    /**
     * @var string Name of the database table, used in queries
     */
    protected $table;

    /**
     * @var array List of fields in database table, used in queries
     */
    protected $fields;

    /**
     * @var Connection Used to retrieve PDO connection
     */
    protected $connection;

    /**
     * @var \PDO $pdo
     */
    protected $pdo;

    /**
     * Model constructor.
     * Set the connection and the PDO
     * @param Connection $connection Contains the PDO connection
     * @since 1.1.4
     */
    public function __construct(Connection $connection)
    {
        if (!$this->table || !$this->fields) {
            showPlainError('Not all information of the model is set');
        }

        $this->connection = $connection;
        $this->pdo = $connection->getPdo();
    }

    /**
     * Run a `SHOW TABLES` query and return the results
     * @return array Result of the `SHOW TABLES` query
     * @since 1.1.4
     */
    public function showTables()
    {
        $sql = 'SHOW TABLES';
        $query = $this->pdo->prepare($sql);
        $query->execute();

        return $query->fetchAll(PDO::FETCH_ASSOC);
    }

    /**
     * Run a SELECT all query
     * @return array Query result
     * @since 1.1.4
     */
    public function getAll()
    {
        $fields = implode(', ', $this->fields);
        $sql = 'SELECT ' . $fields . ' FROM ' . $this->table;
        $query = $this->pdo->prepare($sql);
        $query->execute();

        return $query->fetchAll(PDO::FETCH_ASSOC);
    }

    /**
     * Run a SELECT query by ID
     * @param int|string $id The id of the record you want to retrieve
     * @return mixed Query result
     * @since 1.1.4
     */
    public function getOneById($id)
    {
        $id = (string)$id;

        $fields = implode(', ', $this->fields);
        $sql = 'SELECT ' . $fields . ' FROM ' . $this->table . ' WHERE ' . $this->table . '.id = :id';
        $query = $this->pdo->prepare($sql);
        $query->bindParam(':id', $id);
        $query->execute();

        return $query->fetch(PDO::FETCH_ASSOC);
    }

    /**
     * Insert a new record in the database
     * @param array $values Values to insert, except id, since it is expected to be AUTO_INCREMENT
     * @return int Return the last inserted id
     * @since 1.1.4
     */
    public function insert(array $values) : int
    {
        $fields = $this->fields;
        array_shift($fields);

        $sql = 'INSERT INTO `' . $this->table . '` (' . implode(', ', $fields) . ') VALUES (:' . implode(', :', $fields) . ')';
        $query = $this->pdo->prepare($sql);
        foreach ($values as $key => &$value) {
            $query->bindParam(':' . $fields[$key], $value);
        }
        $query->execute();

        return $this->pdo->lastInsertId();
    }

    /**
     * Update a record
     * @param string|array $id The id(s) to update
     * @param array $values Values to update, key = field, value = value
     * @return int The amount of rows affected by the query
     * @since 1.1.4
     */
    public function updateById($id, array $values) : int
    {
        $sql = 'UPDATE `' . $this->table . '` SET ';

        foreach ($values as $key => $value) {
            $sql .= $key . '=:' . $key . ', ';
        }

        $sql = substr($sql, 0, -2);
        $sql .= ' WHERE ';

        // Assign id(s)
        if (is_array($id)) {
            $i = 0;
            foreach ($id as $_id) {
                if ($i === 0) {
                    $sql .= ' id=:id' . $i;
                } else {
                    $sql .= ' OR id=:id' . $i;
                }
                $i++;
            }
            $query = $this->pdo->prepare($sql);
            for ($j = 0; $j < 0; $j++) {
                $query->bindParam(':id' . $j, $id[$j]);
            }
        } else {
            $sql .= 'id=:id';
            $query = $this->pdo->prepare($sql);
            $query->bindParam(':id', $id);
        }

        // Assign values
        foreach ($values as $key => $value) {
            $query->bindParam(':' . $key, $value);
        }

        $query->execute();

        return $query->rowCount();
    }

    /**
     * Run a DELETE query
     * @param string|array $id Id(s) to delete
     * @return int Return affected rows
     * @since 1.1.4
     */
    public function deleteById($id) : int
    {
        $sql = 'DELETE FROM `' . $this->table . '` WHERE ';

        // Assign ids
        if (is_array($id)) {
            $i = 0;
            foreach ($id as $_id) {
                if ($i === 0) {
                    $sql .= ' id=:id' . $i;
                } else {
                    $sql .= ' OR id=:id' . $i;
                }
                $i++;
            }
            $query = $this->pdo->prepare($sql);
            for ($j = 0; $j < 0; $j++) {
                $query->bindParam(':id' . $j, $id[$j]);
            }
        } else {
            $sql .= 'id=:id';
            $query = $this->pdo->prepare($sql);
            $query->bindParam(':id', $id);
        }

        $query->execute();

        return $query->rowCount();
    }
}