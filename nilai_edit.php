<?php
include 'config/db.php';

if (!isset($_SESSION['login'])) {
    header("Location: login.php");
    exit;
}

$id_nilai = $_POST['id_nilai'] ?? '';
$tugas    = ($_POST['tugas'] === '') ? null : (int)$_POST['tugas'];
$uts      = ($_POST['uts'] === '') ? null : (int)$_POST['uts'];
$uas      = ($_POST['uas'] === '') ? null : (int)$_POST['uas'];
$status   = $_POST['status_ujian'] ?? 'Belum Ujian';

if ($id_nilai == '') {
    header("Location: laporan.php");
    exit;
}

// hitung nilai akhir
$nilai_akhir = null;
if ($tugas !== null && $uts !== null && $uas !== null) {
    $nilai_akhir = (0.3 * $tugas) + (0.3 * $uts) + (0.4 * $uas);
}

// ambil id_mhs dari tabel nilai (buat update status ujian di mahasiswa)
$get = mysqli_query($conn, "SELECT id_mhs FROM nilai WHERE id_nilai='$id_nilai'");
$row = mysqli_fetch_assoc($get);
$id_mhs = $row['id_mhs'] ?? null;

// update tabel nilai (TANPA status_ujian)
$q = "UPDATE nilai SET
        tugas=" . ($tugas === null ? "NULL" : $tugas) . ",
        uts=" . ($uts === null ? "NULL" : $uts) . ",
        uas=" . ($uas === null ? "NULL" : $uas) . ",
        nilai_akhir=" . ($nilai_akhir === null ? "NULL" : $nilai_akhir) . "
      WHERE id_nilai='$id_nilai'";

mysqli_query($conn, $q);

// update status ujian di tabel mahasiswa
if ($id_mhs) {
    mysqli_query($conn, "UPDATE mahasiswa SET status_ujian='$status' WHERE id_mhs='$id_mhs'");
}

header("Location: laporan.php");
exit;
