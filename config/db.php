<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

try {
    $dsn = "mysql:host=" . getenv("MYSQLHOST") . 
           ";dbname=" . getenv("MYSQLDATABASE") . 
           ";port=" . getenv("MYSQLPORT");

    $conn = new PDO($dsn, getenv("MYSQLUSER"), getenv("MYSQLPASSWORD"));

    // Set mode error ke exception
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

} catch (PDOException $e) {
    die("Koneksi database gagal: " . $e->getMessage());
}
