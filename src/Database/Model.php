<?php

namespace StevenLiebregt\CrispySystem\Database;

use PDO;

abstract class Model
{
    protected $table;
    protected $fields;
    protected $connection;

    /**
     * @var \PDO $pdo
     */
    protected $pdo;

    public function __construct(Connection $connection)
    {
        if (!$this->table || !$this->fields) {
            showPlainError('Not all information of the model is set');
        }

        $this->connection = $connection;
        $this->pdo = $connection->getPdo();
    }

    public function showTables()
    {
        $sql = 'SHOW TABLES';
        $query = $this->pdo->prepare($sql);
        $query->execute();
        return $query->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getAll()
    {
        $fields = implode(', ', $this->fields);
        $sql = 'SELECT ' . $fields . ' FROM ' . $this->table;
        $query = $this->pdo->prepare($sql);
        $query->execute();
        return $query->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getOneById(string $id)
    {
        $fields = implode(', ', $this->fields);
        $sql = 'SELECT ' . $fields . ' FROM ' . $this->table . ' WHERE ' . $this->table . '.id = :id';
        $query = $this->pdo->prepare($sql);
        $query->bindParam(':id', $id);
        $query->execute();
        return $query->fetch(PDO::FETCH_ASSOC);
    }
    /**
     * @param array ...$values Insert all values, remove the id column and insert it
     * @return int Return the last inserted id
     */
    public function insert(...$values)
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

    public function updateById($id, ...$values)
    {
        $fields = $this->fields;
        array_shift($fields);
        $sql = 'UPDATE `' . $this->table . '` SET ';
        foreach ($fields as $field) {
            $sql .= $field . '=:' . $field . ', ';
        }
        $sql = substr($sql, 0, -2);
        $sql .= ' WHERE id = :id';
        $query = $this->pdo->prepare($sql);
        foreach ($values as $key => &$value) {
            $query->bindParam(':' . $fields[$key], $value);
        }
        $query->bindParam('id', $id);
        $query->execute();
        return $query->rowCount();
    }

    public function deleteById($id)
    {
        $sql = 'DELETE FROM `' . $this->table . '` WHERE id = :id';
        $query = $this->pdo->prepare($sql);
        $query->bindParam('id', $id);
        $query->execute();
        return $query->rowCount();
    }
}