<?php
session_start();
include 'config/db.php';

if (isset($_GET['id_mhs'])) {
    $id_mhs = intval($_GET['id_mhs']);

    $delete = "DELETE FROM mahasiswa WHERE id_mhs=$id_mhs";

    if (mysqli_query($conn, $delete)) {
        $_SESSION['success'] = "Mahasiswa berhasil dihapus!";
    } else {
        $_SESSION['error'] = "Gagal menghapus mahasiswa: " . mysqli_error($conn);
    }
}

header("Location: mahasiswa.php");
exit;
