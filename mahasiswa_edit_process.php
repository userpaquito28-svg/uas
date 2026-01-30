<?php
session_start();
include 'config/db.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id_mhs     = intval($_POST['id_mhs']);
    $npm        = mysqli_real_escape_string($conn, $_POST['npm']);
    $nama       = mysqli_real_escape_string($conn, $_POST['nama']);
    $kelas      = mysqli_real_escape_string($conn, $_POST['kelas']);
    $status_ujian = strtoupper(trim($_POST['status_ujian']));

    $update = "UPDATE mahasiswa SET 
                npm='$npm', 
                nama='$nama', 
                kelas='$kelas', 
                status_ujian='$status_ujian'
               WHERE id_mhs=$id_mhs";

    if (mysqli_query($conn, $update)) {
        $_SESSION['success'] = "Mahasiswa berhasil diperbarui!";
    } else {
        $_SESSION['error'] = "Gagal memperbarui mahasiswa: " . mysqli_error($conn);
    }
}

header("Location: mahasiswa.php");
exit;
