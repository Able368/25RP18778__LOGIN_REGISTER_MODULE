<?php
session_start();

// DATABASE CONNECTION
$host = 'localhost';
$db   = '25rp18778_shareide_db';
$user = 'root';
$pass = '';
$charset = 'utf8mb4';

$dsn = "mysql:host=$host;dbname=$db;charset=$charset";
$options = [
    PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
];

try {
    $pdo = new PDO($dsn, $user, $pass, $options);
} catch (Exception $e) {
    die("Database error: " . $e->getMessage());
}

// ✅ LOGIN CHECK FUNCTION
function is_logged_in() {
    return isset($_SESSION['user_id']);
}
















