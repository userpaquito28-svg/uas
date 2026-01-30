<?php
include 'config/db.php';

if (!isset($_SESSION['login'])) {
    header("Location: login.php");
    exit;
}

$id_nilai = $_POST['id_nilai'] ?? '';

if ($id_nilai != '') {

    // ambil id_mhs sebelum hapus
    $get = mysqli_query($conn, "SELECT id_mhs FROM nilai WHERE id_nilai='$id_nilai'");
    $row = mysqli_fetch_assoc($get);
    $id_mhs = $row['id_mhs'] ?? null;

    // hapus nilai
    mysqli_query($conn, "DELETE FROM nilai WHERE id_nilai='$id_nilai'");

    // update status ujian jadi Belum Ujian
    if ($id_mhs) {
        mysqli_query($conn, "UPDATE mahasiswa SET status_ujian='Belum Ujian' WHERE id_mhs='$id_mhs'");
    }
}

header("Location: laporan.php");
exit;
