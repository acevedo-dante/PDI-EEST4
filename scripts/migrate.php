<?php

declare(strict_types=1);

use Dotenv\Dotenv;

$projectRoot = dirname(__DIR__);

require $projectRoot . '/vendor/autoload.php';

Dotenv::createImmutable($projectRoot)->safeLoad();

require_once $projectRoot . '/src/database/database.php';

if (PHP_SAPI !== 'cli') {
  fwrite(STDERR, "Este script solo puede ejecutarse desde CLI.\n");
  exit(1);
}

if (in_array('--help', $argv, true) || in_array('-h', $argv, true)) {
  fwrite(STDOUT, "Uso: php scripts/migrate.php\n");
  fwrite(STDOUT, "Aplica los archivos .sql pendientes de la carpeta de migraciones.\n");
  exit(0);
}

final class SqlMigrationRunner
{
  private const MIGRATION_TABLE = 'schema_migrations';

  public function __construct(
    private readonly Database $database,
    private readonly string $projectRoot
  ) {}

  public function run(): int
  {
    $migrationDirectory = $this->resolveMigrationDirectory();

    if ($migrationDirectory === null) {
      fwrite(STDERR, "No se encontró un directorio de migraciones.\n");
      return 1;
    }

    $pdo = $this->database->getConnection();
    $this->ensureMigrationTable($pdo);

    $migrations = $this->loadMigrations($migrationDirectory);

    if ($migrations === []) {
      fwrite(STDOUT, "No se encontraron archivos .sql para aplicar.\n");
      return 0;
    }

    $appliedMigrations = array_fill_keys($this->getAppliedMigrations($pdo), true);
    $pendingMigrations = array_values(array_filter(
      $migrations,
      static fn(array $migration): bool => !isset($appliedMigrations[$migration['filename']])
    ));

    if ($pendingMigrations === []) {
      fwrite(STDOUT, "No hay migraciones pendientes.\n");
      return 0;
    }

    fwrite(STDOUT, "Aplicando migraciones desde: {$migrationDirectory}\n");

    foreach ($pendingMigrations as $migration) {
      $this->applyMigration($pdo, $migration);
    }

    fwrite(STDOUT, "Migraciones completadas correctamente.\n");
    return 0;
  }

  private function resolveMigrationDirectory(): ?string
  {
    $candidates = [
      $this->projectRoot . '/database/migrations',
      $this->projectRoot . '/src/database/migrations',
    ];

    $firstExistingDirectory = null;

    foreach ($candidates as $candidate) {
      if (!is_dir($candidate)) {
        continue;
      }

      if ($firstExistingDirectory === null) {
        $firstExistingDirectory = $candidate;
      }

      $sqlFiles = glob($candidate . '/*.sql');

      if ($sqlFiles !== false && $sqlFiles !== []) {
        return $candidate;
      }
    }

    return $firstExistingDirectory;
  }

  /**
   * @return array<int, array{filename: string, path: string}>
   */
  private function loadMigrations(string $migrationDirectory): array
  {
    $files = glob($migrationDirectory . '/*.sql');

    if ($files === false) {
      return [];
    }

    natsort($files);

    $migrations = [];

    foreach ($files as $filePath) {
      if (!is_file($filePath)) {
        continue;
      }

      $migrations[] = [
        'filename' => basename($filePath),
        'path' => $filePath,
      ];
    }

    return $migrations;
  }

  /**
   * @return array<int, string>
   */
  private function getAppliedMigrations(PDO $pdo): array
  {
    $stmt = $pdo->query(sprintf(
      'SELECT filename FROM %s ORDER BY filename ASC',
      self::MIGRATION_TABLE
    ));

    if ($stmt === false) {
      return [];
    }

    $filenames = $stmt->fetchAll(PDO::FETCH_COLUMN);

    return array_map(static fn($filename): string => (string) $filename, $filenames);
  }

  private function ensureMigrationTable(PDO $pdo): void
  {
    $driver = $pdo->getAttribute(PDO::ATTR_DRIVER_NAME);

    if ($driver === 'pgsql') {
      $pdo->exec(sprintf(
        'CREATE TABLE IF NOT EXISTS %s (
                    filename VARCHAR(255) PRIMARY KEY,
                    applied_at TIMESTAMPTZ NOT NULL DEFAULT NOW()
                )',
        self::MIGRATION_TABLE
      ));

      return;
    }

    $pdo->exec(sprintf(
      'CREATE TABLE IF NOT EXISTS %s (
                filename VARCHAR(255) NOT NULL PRIMARY KEY,
                applied_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP
            )',
      self::MIGRATION_TABLE
    ));
  }

  /**
   * @param array{filename: string, path: string} $migration
   */
  private function applyMigration(PDO $pdo, array $migration): void
  {
    $sql = file_get_contents($migration['path']);

    if ($sql === false || trim($sql) === '') {
      throw new RuntimeException('La migración ' . $migration['filename'] . ' está vacía o no se pudo leer.');
    }

    fwrite(STDOUT, 'Aplicando ' . $migration['filename'] . "...\n");

    $pdo->beginTransaction();

    try {
      $pdo->exec($sql);

      $stmt = $pdo->prepare(sprintf(
        'INSERT INTO %s (filename) VALUES (:filename)',
        self::MIGRATION_TABLE
      ));

      $stmt->execute([
        'filename' => $migration['filename'],
      ]);

      $pdo->commit();

      fwrite(STDOUT, 'Aplicada ' . $migration['filename'] . "\n");
    } catch (Throwable $throwable) {
      if ($pdo->inTransaction()) {
        $pdo->rollBack();
      }

      throw new RuntimeException(
        'Error al aplicar ' . $migration['filename'] . ': ' . $throwable->getMessage(),
        0,
        $throwable
      );
    }
  }
}

try {
  $runner = new SqlMigrationRunner(new Database(), $projectRoot);
  exit($runner->run());
} catch (Throwable $throwable) {
  fwrite(STDERR, $throwable->getMessage() . "\n");
  exit(1);
}
