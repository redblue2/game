<?php
// modules/db.php

$host = "localhost";
$dbname = "mk_oyun";
$username = "root";
$password = ""; // XAMPP kullanıyorsan genelde boş olur

try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8mb4", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die("Veritabanı bağlantı hatası: " . $e->getMessage());
}
?>
	