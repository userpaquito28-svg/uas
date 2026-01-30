<?php
session_start();
include "config/db.php";

// ===================
// HIDDEN ACCESS KEY
// ===================
$access_key = $_GET['key'] ?? '';
if ($access_key !== 'uas2026') { // ganti key sesukamu
    header("Location: login.php");
    exit;
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Register Admin</title>
    <link rel="stylesheet" href="assets/css/bootstrap.min.css">
</head>
<body>
<div class="container mt-5">
    <h3 class="mb-4">Register Admin/User</h3>

    <?php if (isset($_SESSION['error_register'])): ?>
        <div class="alert alert-danger"><?= $_SESSION['error_register']; ?></div>
        <?php unset($_SESSION['error_register']); ?>
    <?php endif; ?>

    <?php if (isset($_SESSION['success_register'])): ?>
        <div class="alert alert-success"><?= $_SESSION['success_register']; ?></div>
        <?php unset($_SESSION['success_register']); ?>
    <?php endif; ?>

    <form action="register_proses.php" method="POST">
        <div class="mb-3">
            <label class="form-label">Nama Lengkap</label>
            <input type="text" name="nama" class="form-control" required>
        </div>

        <div class="mb-3">
            <label class="form-label">Username</label>
            <input type="text" name="username" class="form-control" required>
        </div>

        <div class="mb-3">
            <label class="form-label">Password</label>
            <input type="password" name="password" class="form-control" required>
        </div>

        <div class="mb-3">
            <label class="form-label">Role</label>
            <select name="role" class="form-select" required>
                <option value="admin">Admin</option>
                <option value="user">User</option>
            </select>
        </div>

        <button type="submit" class="btn btn-primary">Register</button>
    </form>
</div>
</body>
</html>
