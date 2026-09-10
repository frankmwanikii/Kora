<?php

declare(strict_types=1);

final class Auth
{
    private const SESSION_NAME = 'kora_admin_sess';

    public static function startSession(): void
    {
        if (session_status() === PHP_SESSION_ACTIVE) {
            return;
        }

        $sessionDir = dirname(__DIR__) . '/data/sessions';

        if (!is_dir($sessionDir)) {
            @mkdir($sessionDir, 0775, true);
        }

        if (is_dir($sessionDir)) {
            @chmod($sessionDir, 0775);
        }

        session_name(self::SESSION_NAME);

        if (is_dir($sessionDir) && is_writable($sessionDir)) {
            session_save_path($sessionDir);
        }

        session_set_cookie_params([
            'lifetime' => 0,
            'path' => '/',
            'secure' => !empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off',
            'httponly' => true,
            'samesite' => 'Lax',
        ]);

        if (!@session_start()) {
            // Last resort: default PHP session path so login form can still render.
            session_save_path('');
            session_start();
        }
    }

    public static function loginAdmin(PDO $pdo, string $username, string $password): bool
    {
        $stmt = $pdo->prepare(
            'SELECT id, username, password_hash, name, email FROM admin_users WHERE username = :username LIMIT 1'
        );
        $stmt->execute(['username' => $username]);
        $user = $stmt->fetch();

        if (!$user || !password_verify($password, (string) $user['password_hash'])) {
            return false;
        }

        session_regenerate_id(true);
        $_SESSION['admin_id'] = (int) $user['id'];
        $_SESSION['admin_user'] = [
            'id' => (int) $user['id'],
            'username' => (string) $user['username'],
            'name' => (string) $user['name'],
            'email' => (string) $user['email'],
        ];

        return true;
    }

    public static function logout(): void
    {
        $_SESSION = [];

        if (ini_get('session.use_cookies')) {
            $params = session_get_cookie_params();
            setcookie(
                session_name(),
                '',
                time() - 42000,
                $params['path'],
                $params['domain'],
                (bool) $params['secure'],
                (bool) $params['httponly']
            );
        }

        session_destroy();
    }

    public static function check(): bool
    {
        return isset($_SESSION['admin_id']) && (int) $_SESSION['admin_id'] > 0;
    }

    public static function requireLogin(): void
    {
        if (!self::check()) {
            redirect('/index.php');
        }
    }

    public static function admin(): ?array
    {
        if (!self::check()) {
            return null;
        }

        $user = $_SESSION['admin_user'] ?? null;

        return is_array($user) ? $user : null;
    }

    public static function id(): ?int
    {
        if (!self::check()) {
            return null;
        }

        return (int) $_SESSION['admin_id'];
    }
}
