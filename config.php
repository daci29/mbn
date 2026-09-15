<?php

$request_exec_timeout = null;

/*
|--------------------------------------------------------------------------
| Railway MySQL configuration
|--------------------------------------------------------------------------
*/

$dbhost = getenv('MYSQLHOST') ?: '127.0.0.1';
$dbport = getenv('MYSQLPORT') ?: '3306';
$dbname = getenv('MYSQLDATABASE') ?: 'railway';
$usernamedb = getenv('MYSQLUSER') ?: 'root';
$passworddb = getenv('MYSQLPASSWORD') ?: '';

$options = [
    PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
    PDO::ATTR_EMULATE_PREPARES => false,
    PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES utf8mb4 COLLATE utf8mb4_unicode_ci",
];

$dsn = "mysql:host={$dbhost};port={$dbport};dbname={$dbname};charset=utf8mb4";

try {
    $pdo = new PDO(
        $dsn,
        $usernamedb,
        $passworddb,
        $options
    );
} catch (PDOException $e) {
    error_log("Database connection failed: " . $e->getMessage());
    die("Database connection failed: " . htmlspecialchars($e->getMessage(), ENT_QUOTES, 'UTF-8'));
}

/*
|--------------------------------------------------------------------------
| Telegram configuration
|--------------------------------------------------------------------------
*/

$APIKEY = getenv('API_KEY') ?: '';
$adminnumber = getenv('ADMIN_NUMBER') ?: '';
$domainhosts = getenv('DOMAIN_NAME') ?: '';
$usernamebot = getenv('USERNAME_BOT') ?: '';

/*
|--------------------------------------------------------------------------
| MySQLi connection
|--------------------------------------------------------------------------
*/

$connect = mysqli_init();

if (!$connect->real_connect(
    $dbhost,
    $usernamedb,
    $passworddb,
    $dbname,
    (int) $dbport
)) {
    die("MySQLi connection failed: " . mysqli_connect_error());
}

mysqli_set_charset($connect, "utf8mb4");

?>
