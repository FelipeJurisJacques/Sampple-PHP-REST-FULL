<?php
class Connection
{
    public string $type;
    public string $host;
    public int $port;
    public string $name;
    public string $user;
    public string $password;
    public bool $debug;
    public ?PDO $connection;

    public function __construct()
    {
        $this->type = 'mysql';
        $this->debug = true;
        $this->connection = null;
    }

    public function isConnected(): bool
    {
        return !is_null($this->connection);
    }

    public function connect(): void
    {
        $this->connection = new PDO(
            $this->type . ':host=' . $this->host . ';dbname=' . $this->name,
            $this->user,
            $this->password
        );
        $this->connection->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_OBJ);
        if ($this->debug) {
            $this->connection->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_WARNING);
        }
    }
}
