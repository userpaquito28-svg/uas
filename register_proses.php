<?php
session_start();
include "config/db.php";

$nama     = trim($_POST['nama'] ?? '');
$username = trim($_POST['username'] ?? '');
$password = $_POST['password'] ?? '';
$role     = $_POST['role'] ?? 'user';

if ($nama === '' || $username === '' || $password === '') {
    $_SESSION['error_register'] = "Semua field harus diisi!";
    header("Location: register.php?key=uas2026");
    exit;
}

// cek username sudah ada atau belum
$qCheck = mysqli_query($conn, "SELECT * FROM users WHERE username='$username'");
if (mysqli_num_rows($qCheck) > 0) {
    $_SESSION['error_register'] = "Username sudah digunakan!";
    header("Location: register.php?key=uas2026");
    exit;
}

// hash password
$hashPassword = password_hash($password, PASSWORD_BCRYPT);

// simpan ke database
$qInsert = mysqli_query($conn, "INSERT INTO users (nama, username, password, role, created_at) 
                                VALUES ('$nama', '$username', '$hashPassword', '$role', NOW())");

if ($qInsert) {
    $_SESSION['success_register'] = "Registrasi berhasil! Silakan login.";
    header("Location: login.php");
    exit;
} else {
    $_SESSION['error_register'] = "Gagal registrasi: " . mysqli_error($conn);
    header("Location: register.php?key=uas2026");
    exit;
}
