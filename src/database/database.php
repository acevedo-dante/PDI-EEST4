<?php

declare(strict_types=1);

class Database
{
  private const ALLOWED_DRIVERS = ['mysql', 'pgsql'];

  private string $driver;
  private string $host;
  private string $dbName;
  private string $username;
  private string $password;
  private ?PDO $connection = null;

  public function __construct()
  {
    $this->driver = $_ENV['DB_DRIVER'] ?? 'mysql';

    if (!in_array($this->driver, self::ALLOWED_DRIVERS, true)) {
      throw new \InvalidArgumentException("Driver de base de datos no soportado");
    }

    $this->host = $_ENV['DB_HOST'] ?? 'localhost';
    $this->dbName = $_ENV['DB_NAME'] ?? 'slim_php';
    $this->username = $_ENV['DB_USER'] ?? 'root';
    $this->password = $_ENV['DB_PASS'] ?? 'root';
  }

  public function getConnection(): PDO
  {
    if ($this->connection !== null) {
      return $this->connection;
    }

    try {
      $port = $_ENV['DB_PORT'] ?? ($this->driver === 'pgsql' ? '5432' : '3306');
      $dsn = $this->buildDsn($port);

      $this->connection = new PDO($dsn, $this->username, $this->password, [
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
        PDO::ATTR_EMULATE_PREPARES => false,
      ]);

      return $this->connection;
    } catch (PDOException $e) {
      throw new \Exception("Fallo en la conexión a la base de datos");
    }
  }

  private function buildDsn(string $port): string
  {
    if ($this->driver === 'pgsql') {
      return "pgsql:host={$this->host};port={$port};dbname={$this->dbName}";
    }

    return "mysql:host={$this->host};port={$port};dbname={$this->dbName};charset=utf8mb4";
  }
}
