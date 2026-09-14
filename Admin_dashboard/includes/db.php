<?php

declare(strict_types=1);

final class Database
{
    private static ?PDO $pdo = null;

    public static function configPath(): string
    {
        return dirname(__DIR__, 2) . '/data/db.local.php';
    }

    /**
     * @return array{
     *     host: string,
     *     port: int,
     *     database: string,
     *     username: string,
     *     password: string,
     *     charset: string
     * }
     */
    public static function config(): array
    {
        $path = self::configPath();

        if (!is_file($path)) {
            throw new RuntimeException(
                'MySQL is not configured. Copy data/db.local.php.example to data/db.local.php and fill in credentials.'
            );
        }

        $local = require $path;
        if (!is_array($local)) {
            throw new RuntimeException('Invalid MySQL configuration in data/db.local.php.');
        }

        $host = trim((string) ($local['host'] ?? '127.0.0.1'));
        $database = trim((string) ($local['database'] ?? ''));
        $username = trim((string) ($local['username'] ?? ''));
        $password = (string) ($local['password'] ?? '');
        $charset = trim((string) ($local['charset'] ?? 'utf8mb4'));
        $port = (int) ($local['port'] ?? 3306);

        if ($host === '' || $database === '' || $username === '') {
            throw new RuntimeException('MySQL host, database, and username are required in data/db.local.php.');
        }

        if ($port < 1 || $port > 65535) {
            $port = 3306;
        }

        if ($charset === '') {
            $charset = 'utf8mb4';
        }

        return [
            'host' => $host,
            'port' => $port,
            'database' => $database,
            'username' => $username,
            'password' => $password,
            'charset' => $charset,
        ];
    }

    public static function isConfigured(): bool
    {
        try {
            $config = self::config();
        } catch (Throwable) {
            return false;
        }

        return $config['host'] !== '' && $config['database'] !== '' && $config['username'] !== '';
    }

    /**
     * @return array{
     *     host: string,
     *     port: int,
     *     database: string,
     *     username: string,
     *     password: string,
     *     charset: string,
     *     password_set: bool
     * }
     */
    public static function formDefaults(): array
    {
        $defaults = [
            'host' => '127.0.0.1',
            'port' => 3306,
            'database' => 'kora_admin',
            'username' => 'kora_admin',
            'password' => '',
            'charset' => 'utf8mb4',
            'password_set' => false,
        ];

        try {
            $config = self::config();
        } catch (Throwable) {
            return $defaults;
        }

        return [
            'host' => $config['host'],
            'port' => $config['port'],
            'database' => $config['database'],
            'username' => $config['username'],
            'password' => '',
            'charset' => $config['charset'],
            'password_set' => $config['password'] !== '',
        ];
    }

    /**
     * @param array<string, mixed> $input
     * @return array{ok: bool, error?: string}
     */
    public static function saveConfig(array $input, ?string $existingPassword = null): array
    {
        $host = trim((string) ($input['host'] ?? '127.0.0.1'));
        $database = trim((string) ($input['database'] ?? ''));
        $username = trim((string) ($input['username'] ?? ''));
        $password = (string) ($input['password'] ?? '');
        $charset = trim((string) ($input['charset'] ?? 'utf8mb4'));
        $port = (int) ($input['port'] ?? 3306);

        if ($host === '') {
            return ['ok' => false, 'error' => 'Database host is required.'];
        }

        if ($database === '' || !preg_match('/^[A-Za-z0-9_]+$/', $database)) {
            return ['ok' => false, 'error' => 'Enter a valid database name (letters, numbers, underscore).'];
        }

        if ($username === '') {
            return ['ok' => false, 'error' => 'Database username is required.'];
        }

        if ($password === '' && $existingPassword !== null) {
            $password = $existingPassword;
        }

        if ($port < 1 || $port > 65535) {
            $port = 3306;
        }

        if ($charset === '') {
            $charset = 'utf8mb4';
        }

        $config = [
            'host' => $host,
            'port' => $port,
            'database' => $database,
            'username' => $username,
            'password' => $password,
            'charset' => $charset,
        ];

        $path = self::configPath();
        $dir = dirname($path);

        if (!is_dir($dir) && !@mkdir($dir, 0775, true) && !is_dir($dir)) {
            return ['ok' => false, 'error' => 'Unable to create the data directory for database settings.'];
        }

        $exported = var_export($config, true);
        $php = <<<PHP
<?php

declare(strict_types=1);

/**
 * Local MySQL credentials for the KORA admin CMS.
 * Created by /Admin_dashboard/setup.php. Do not commit this file.
 */
return {$exported};

PHP;

        $tmp = $dir . '/.db.local.php.' . bin2hex(random_bytes(4)) . '.tmp';
        if (@file_put_contents($tmp, $php, LOCK_EX) === false) {
            @unlink($tmp);

            return ['ok' => false, 'error' => 'Unable to write data/db.local.php. Make sure the data/ folder is writable.'];
        }

        @chmod($tmp, 0660);
        if (!@rename($tmp, $path) && !@copy($tmp, $path)) {
            @unlink($tmp);

            return ['ok' => false, 'error' => 'Unable to save data/db.local.php. Make sure the data/ folder is writable.'];
        }

        @unlink($tmp);
        @chmod($path, 0660);
        self::$pdo = null;

        return ['ok' => true];
    }

    /**
     * Test credentials, optionally create the database, then connect.
     *
     * @param array<string, mixed> $input
     * @return array{ok: bool, error?: string, pdo?: PDO}
     */
    public static function provision(array $input, ?string $existingPassword = null): array
    {
        $saved = self::saveConfig($input, $existingPassword);
        if (!$saved['ok']) {
            return $saved;
        }

        $config = self::config();
        $create = !empty($input['create_database']);

        try {
            if ($create) {
                $server = new PDO(
                    sprintf('mysql:host=%s;port=%d;charset=%s', $config['host'], $config['port'], $config['charset']),
                    $config['username'],
                    $config['password'],
                    [
                        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
                        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
                    ]
                );
                $name = str_replace('`', '``', $config['database']);
                $server->exec(
                    "CREATE DATABASE IF NOT EXISTS `{$name}` CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci"
                );
            }

            $pdo = self::connect();
        } catch (PDOException $e) {
            $message = $e->getMessage();
            if (str_contains($message, 'Unknown database') && !$create) {
                $message .= ' Tick “Create database if it does not exist” or create it in MySQL first.';
            }

            return ['ok' => false, 'error' => 'Could not connect to MySQL: ' . $message];
        } catch (Throwable $e) {
            return ['ok' => false, 'error' => $e->getMessage()];
        }

        return ['ok' => true, 'pdo' => $pdo];
    }

    public static function connect(): PDO
    {
        if (self::$pdo instanceof PDO) {
            return self::$pdo;
        }

        if (!in_array('mysql', PDO::getAvailableDrivers(), true)) {
            throw new RuntimeException('PHP PDO MySQL extension is required.');
        }

        $config = self::config();
        $dsn = sprintf(
            'mysql:host=%s;port=%d;dbname=%s;charset=%s',
            $config['host'],
            $config['port'],
            $config['database'],
            $config['charset']
        );

        $pdo = new PDO($dsn, $config['username'], $config['password'], [
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
            PDO::ATTR_EMULATE_PREPARES => false,
        ]);
        $pdo->exec("SET NAMES utf8mb4 COLLATE utf8mb4_unicode_ci");

        self::$pdo = $pdo;

        return self::$pdo;
    }
}
