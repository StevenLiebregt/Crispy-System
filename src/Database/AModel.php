<?php

namespace StevenLiebregt\CrispySystem\Database;

abstract class AModel
{
    /**
     * @var \PDO
     */
    protected $connection;

    /**
     * @var
     */
    protected $table;

    /**
     * @var array|string
     */
    protected $fields = [];

    /**
     * AModel constructor.
     * @param PdoConnector $pdoConnector
     */
    public function __construct(PdoConnector $pdoConnector)
    {
        if (is_null($this->table)) {
            showPlainError('Model can`t have an empty name: ' . static::class);
        }

        if (is_null($this->fields) || $this->fields === []) {
            showPlainError('Model needs fields: ' . static::class);
        }

        $this->connection = $pdoConnector->getConnection();

        $this->fields = implode(', ', $this->fields);
    }

    /**
     * Runs a SQL DESCRIBE {TABLE} query
     * @return array
     */
    public function describe()
    {
        $sql = 'DESCRIBE ' . $this->table;
        $query = $this->connection->prepare($sql);
        $query->execute();

        return $query->fetchAll(\PDO::FETCH_ASSOC);
    }

    /**
     * @return array
     */
    public function getAll()
    {
        $sql = 'SELECT ' . $this->fields
            . ' FROM ' . $this->table;
        $query = $this->connection->prepare($sql);
        $query->execute();

        return $query->fetchAll(\PDO::FETCH_ASSOC);
    }

    public function getOneById($id)
    {
        $id = (string)$id;
        $sql = 'SELECT ' . $this->fields
            . ' FROM ' . $this->table
            . ' WHERE ' . $this->table . '.id = :id';
        $query = $this->connection->prepare($sql);
        $query->bindParam(':id', $id);
        $query->execute();

        return $query->fetchAll(\PDO::FETCH_ASSOC);
    }

    public function insert(array $values)
    {

    }

    public function updateById($id, array $values)
    {
        $id = (string)$id;
    }

    public function deleteById($id)
    {
        $id = (string)$id;
    }
}