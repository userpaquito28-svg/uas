<?php
session_start();
include "config/db.php";

if ($_SERVER["REQUEST_METHOD"] !== "POST") {
    header("Location: login.php");
    exit;
}

$username = mysqli_real_escape_string($conn, $_POST['username']);
$password = $_POST['password']; // jangan escape password karena nanti dicek dengan password_verify

// cek user
$query = "SELECT * FROM users WHERE username='$username' LIMIT 1";
$result = mysqli_query($conn, $query);

if ($result && mysqli_num_rows($result) === 1) {
    $user = mysqli_fetch_assoc($result);

    // cocokkan password bcrypt
    if (password_verify($password, $user['password'])) {
        $_SESSION['login']    = true;
        $_SESSION['user_id']  = $user['id'];
        $_SESSION['nama']     = $user['nama'];
        $_SESSION['username'] = $user['username'];
        $_SESSION['role']     = $user['role'];

        header("Location: index.php");
        exit;
    }
}

// kalau gagal login
$_SESSION['error_login'] = "Username atau password salah!";
header("Location: login.php");
exit;
