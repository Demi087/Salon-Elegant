<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

$scriptDirectory = str_replace('\\', '/', dirname($_SERVER['SCRIPT_NAME'] ?? '/'));
if (basename($scriptDirectory) === 'admin') {
    $scriptDirectory = dirname($scriptDirectory);
}
$APP_BASE_PATH = rtrim($scriptDirectory, '/');

define('DB_HOST', 'localhost');
define('DB_NAME', 'salon_db');
define('DB_USER', 'root');
define('DB_PASS', '');

try {
    $pdo = new PDO(
        'mysql:host=' . DB_HOST . ';dbname=' . DB_NAME . ';charset=utf8mb4',
        DB_USER,
        DB_PASS,
        [
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
            PDO::ATTR_EMULATE_PREPARES => false,
        ]
    );
} catch (PDOException $e) {
    die('Ошибка подключения к базе данных: ' . htmlspecialchars($e->getMessage(), ENT_QUOTES, 'UTF-8'));
}

if (isset($_GET['accessibility'])) {
    $mode = $_GET['accessibility'];
    if ($mode === 'toggle') {
        $_SESSION['accessible'] = empty($_SESSION['accessible']);
    } elseif ($mode === 'on') {
        $_SESSION['accessible'] = true;
    } elseif ($mode === 'off') {
        $_SESSION['accessible'] = false;
    }
    $returnUrl = $_GET['return'] ?? '';
    $back = is_string($returnUrl) && str_starts_with($returnUrl, '/') && !str_starts_with($returnUrl, '//')
        ? $returnUrl
        : ($_SERVER['HTTP_REFERER'] ?? '/index.php');
    header('Location: ' . $back);
    exit;
}

$ACCESSIBLE = !empty($_SESSION['accessible']);