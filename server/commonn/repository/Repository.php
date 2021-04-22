<?php
class Repository
{
    private Connection $_connection;

    public function __construct(int $index = 0)
    {
        if (isset($GLOBALS['_repositories'])) {
            $rep = $GLOBALS['_repositories'];
            $l = count($rep);
            if ($l > 0 && $index < $l) {
                $connection = $rep[$index];
                if (!$connection->isConnected()) {
                    $connection->connect();
                }
                $this->_connection = $connection;
            } else {
                throw new Exception('0 connections founds');
            }
        } else {
            throw new Exception('0 connections founds');
        }
    }

    public function query(string $sql): Query
    {
        return new Query($this->_connection, $sql);
    }

    public static function add(Connection $connection): void
    {
        if (!isset($GLOBALS['_repositories'])) {
            $GLOBALS['_repositories'] = [$connection];
        } else {
            $GLOBALS['_repositories'][] = $connection;
        }
    }
}

class Query
{
    private Connection $_connection;
    private PDOStatement $_statement;

    public function __construct(Connection $connection, string $sql)
    {
        $this->_connection = $connection;
        $pdo = $this->_connection->connection;
        $statement = null;
        if (str_contains($sql, ':')) {
            $statement = $pdo->prepare($sql);
        } else {
            $statement = $pdo->query($sql);
        }
        if ($statement === false) {
            throw new Exception('failed statement');
        } else {
            $this->_statement = $statement;
        }
    }

    public function prepare(string $name, $value): Query
    {
        $bind = false;
        if (is_string($value)) {
            $bind = $this->_statement->bindParam($name, $value, PDO::PARAM_STR);
        } else if (is_numeric($value)) {
            if (is_int($value)) {
                $bind = $this->_statement->bindParam($name, $value, PDO::PARAM_INT);
            } else if (is_float($value)) {
                $bind = $this->_statement->bindParam($name, $value, PDO::PARAM_STR);
            } else {
                throw new Exception('invalid parameter');
            }
        } else if (is_bool($value)) {
            $bind = $this->_statement->bindParam($name, $value, PDO::PARAM_BOOL);
        } else if (is_null($value)) {
            $bind = $this->_statement->bindParam($name, $value, PDO::PARAM_NULL);
        } else {
            throw new Exception('invalid parameter');
        }
        if (!$bind) {
            throw new Exception('failed bind parameter');
        } else {
            return $this;
        }
    }

    public function fetch(): array
    {
        $value = $this->_statement->fetchAll();
        if (is_array($value)) {
            return $value;
        } else {
            throw new Exception('failed fetch');
        }
    }

    public function row(): ?object
    {
        $value = $this->_statement->fetch();
        if (is_object($value)) {
            return $value;
        } else {
            return null;
        }
    }

    public function execute(): bool
    {
        return $this->_statement->execute();
    }

    public function insert(): ?int
    {
        if ($this->_statement->execute()) {
            return $this->_connection->connection->lastInsertId();
        } else {
            return null;
        }
    }
}
