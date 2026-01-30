<?php
session_start();
include 'config/db.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $npm        = mysqli_real_escape_string($conn, $_POST['npm']);
    $nama       = mysqli_real_escape_string($conn, $_POST['nama']);
    $kelas      = mysqli_real_escape_string($conn, $_POST['kelas']);
    $status_ujian = strtoupper(trim($_POST['status_ujian']));

    $insert = "INSERT INTO mahasiswa (npm, nama, kelas, status_ujian) 
               VALUES ('$npm', '$nama', '$kelas', '$status_ujian')";

    if (mysqli_query($conn, $insert)) {
        $_SESSION['success'] = "Mahasiswa berhasil ditambahkan!";
    } else {
        $_SESSION['error'] = "Gagal menambahkan mahasiswa: " . mysqli_error($conn);
    }
}

header("Location: mahasiswa.php");
exit;
