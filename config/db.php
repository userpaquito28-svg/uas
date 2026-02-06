<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

try {
    // Buat DSN (Data Source Name)
    $dsn = "mysql:host=" . getenv("MYSQLHOST") . 
           ";dbname=" . getenv("MYSQLDATABASE") . 
           ";port=" . getenv("MYSQLPORT");

    // Buat koneksi PDO
    $conn = new PDO($dsn, getenv("MYSQLUSER"), getenv("MYSQLPASSWORD"));

    // Set error mode ke exception biar mudah debug
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

} catch (PDOException $e) {
    die("Koneksi database gagal: " . $e->getMessage());
}
?>
