<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Ambil environment variables
$host = getenv("MYSQLHOST");
$db   = getenv("MYSQLDATABASE");
$user = getenv("MYSQLUSER");
$pass = getenv("MYSQLPASSWORD");
$port = getenv("MYSQLPORT");

// Buat koneksi
$conn = new mysqli($host, $user, $pass, $db, $port);

// Cek koneksi
if ($conn->connect_error) {
    die("Koneksi database gagal: " . $conn->connect_error);
}

// Contoh query
$result = $conn->query("SELECT NOW() AS currentTime");
if ($result) {
    $row = $result->fetch_assoc();
    echo "Waktu sekarang: " . $row['currentTime'];
} else {
    echo "Query gagal: " . $conn->error;
}

// Tutup koneksi jika sudah selesai
$conn->close();
