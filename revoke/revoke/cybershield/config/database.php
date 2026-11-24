<?php
/**
 * CyberShield - Configuration & connexion à la base de données
 * Siwar Hbibi & Marwa Mejri - 2TSGI 2
 */
session_start();
date_default_timezone_set('Africa/Tunis');

define('APP_NAME', 'CyberShield');
define('DB_PASS', getenv('DB_PASS') !== false ? getenv('DB_PASS') : ''); // XAMPP : vide par défaut

/* Deux modes :
   - sqlite : fichier database.sqlite fourni (Render / démo / tests, zéro configuration)
   - mysql  : base MySQL classique (XAMPP) — variables d'environnement possibles */
$sqliteFile = __DIR__ . '/../database.sqlite';
$envDriver = getenv('DB_DRIVER');
define('DB_DRIVER', $envDriver ?: (file_exists($sqliteFile) ? 'sqlite' : 'mysql'));
define('DB_SQLITE_FILE', $sqliteFile);

if (DB_DRIVER === 'mysql') {
    $host = getenv('DB_HOST');
    $name = getenv('DB_NAME');
    $user = getenv('DB_USER');
    define('DB_HOST', $host ?: 'localhost');
    define('DB_NAME', $name ?: 'cybershield_db');
    define('DB_USER', $user ?: 'root');
}

/* Base URL calculée automatiquement (fonctionne même si le dossier est renommé) */
$appRoot = realpath(dirname(__DIR__));
$docRoot = realpath($_SERVER['DOCUMENT_ROOT'] ?? '');
$rel = ($docRoot && strpos($appRoot, $docRoot) === 0)
    ? str_replace('\\', '/', substr($appRoot, strlen($docRoot)))
    : '';
define('BASE_URL', rtrim($rel, '/'));

class DB
{
    private static ?PDO $pdo = null;

    private static array $tables = ['users', 'clients', 'employees', 'devices', 'interventions', 'alerts', 'reports', 'services'];

    public static function pdo(): PDO
    {
        if (self::$pdo === null) {
            try {
                $options = [PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION, PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC];
                if (DB_DRIVER === 'sqlite') {
                    self::$pdo = new PDO('sqlite:' . DB_SQLITE_FILE, null, null, $options);
                } else {
                    self::$pdo = new PDO(
                        'mysql:host=' . DB_HOST . ';dbname=' . DB_NAME . ';charset=utf8mb4',
                        DB_USER,
                        DB_PASS,
                        $options
                    );
                }
            } catch (PDOException $e) {
                http_response_code(500);
                echo '<!doctype html><html lang="fr"><head><meta charset="utf-8"><title>CyberShield - Erreur BDD</title>
                <style>body{background:#070d1a;color:#e6f0ff;font-family:system-ui;display:grid;place-items:center;min-height:100vh;margin:0}
                .box{background:#0f1b33;border:1px solid #1e3257;padding:32px;border-radius:16px;max-width:640px}
                code{color:#22d3ee}</style></head><body><div class="box"><h2>Connexion à la base de données impossible</h2>
                <p>Vérifiez la configuration : avec XAMPP, importez <code>database.sql</code> dans phpMyAdmin. Sur Render, le fichier <code>database.sqlite</code> doit être présent.</p>
                <p style="color:#8fa6c8;font-size:13px">' . htmlspecialchars($e->getMessage()) . '</p></div></body></html>';
                exit;
            }
        }
        return self::$pdo;
    }

    public static function driver(): string
    {
        return DB_DRIVER;
    }

    public static function all(string $sql, array $params = []): array
    {
        $st = self::pdo()->prepare($sql);
        $st->execute($params);
        return $st->fetchAll();
    }

    public static function one(string $sql, array $params = []): ?array
    {
        $st = self::pdo()->prepare($sql);
        $st->execute($params);
        $r = $st->fetch();
        return $r === false ? null : $r;
    }

    public static function value(string $sql, array $params = [])
    {
        $st = self::pdo()->prepare($sql);
        $st->execute($params);
        return $st->fetchColumn();
    }

    public static function exec(string $sql, array $params = []): int
    {
        $st = self::pdo()->prepare($sql);
        $st->execute($params);
        return $st->rowCount();
    }

    private static function checkTable(string $table): void
    {
        if (!in_array($table, self::$tables, true)) {
            throw new InvalidArgumentException("Table non autorisée : $table");
        }
    }

    public static function insert(string $table, array $data): int
    {
        self::checkTable($table);
        $cols = array_keys($data);
        $sql = 'INSERT INTO ' . $table . ' (' . implode(',', $cols) . ') VALUES (' . implode(',', array_map(fn($c) => ':' . $c, $cols)) . ')';
        self::exec($sql, $data);
        return (int) self::pdo()->lastInsertId();
    }

    public static function update(string $table, int $id, array $data): int
    {
        self::checkTable($table);
        if (!$data) return 0;
        $sets = implode(',', array_map(fn($c) => $c . '=:' . $c, array_keys($data)));
        $data['__id'] = $id;
        return self::exec("UPDATE $table SET $sets WHERE id=:__id", $data);
    }

    public static function delete(string $table, int $id): int
    {
        self::checkTable($table);
        return self::exec("DELETE FROM $table WHERE id=?", [$id]);
    }

    /** Liste d'options pour un champ <select> */
    public static function options(string $table, string $labelCol, string $valueCol = 'id', string $where = '1=1', array $params = []): array
    {
        self::checkTable($table);
        $rows = self::all("SELECT $valueCol AS v, $labelCol AS l FROM $table WHERE $where ORDER BY $labelCol", $params);
        $out = [];
        foreach ($rows as $r) $out[$r['v']] = $r['l'];
        return $out;
    }
}

require_once __DIR__ . '/../includes/functions.php';
require_once __DIR__ . '/../includes/auth.php';
