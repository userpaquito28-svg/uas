<?php
include 'config/db.php';

if (!isset($_SESSION['login'])) {
    header("Location: login.php");
    exit;
}

$id_mhs = $_POST['id_mhs'] ?? '';
$tugas  = ($_POST['tugas'] === '') ? null : (int)$_POST['tugas'];
$uts    = ($_POST['uts'] === '') ? null : (int)$_POST['uts'];
$uas    = ($_POST['uas'] === '') ? null : (int)$_POST['uas'];
$status = $_POST['status_ujian'] ?? 'Belum Ujian';

if ($id_mhs == '') {
    header("Location: laporan.php");
    exit;
}

// hitung nilai akhir
$nilai_akhir = null;
if ($tugas !== null && $uts !== null && $uas !== null) {
    $nilai_akhir = (0.3 * $tugas) + (0.3 * $uts) + (0.4 * $uas);
}

// kalau mahasiswa sudah punya nilai, update aja biar ga double
$cek = mysqli_query($conn, "SELECT id_nilai FROM nilai WHERE id_mhs='$id_mhs'");
if (mysqli_num_rows($cek) > 0) {
    $row = mysqli_fetch_assoc($cek);
    $id_nilai = $row['id_nilai'];

    // UPDATE nilai (tanpa status_ujian)
    $q = "UPDATE nilai SET 
            tugas=" . ($tugas === null ? "NULL" : $tugas) . ",
            uts=" . ($uts === null ? "NULL" : $uts) . ",
            uas=" . ($uas === null ? "NULL" : $uas) . ",
            nilai_akhir=" . ($nilai_akhir === null ? "NULL" : $nilai_akhir) . "
          WHERE id_nilai='$id_nilai'";
} else {

    // INSERT nilai (tanpa status_ujian)
    $q = "INSERT INTO nilai (id_mhs, tugas, uts, uas, nilai_akhir)
          VALUES (
            '$id_mhs',
            " . ($tugas === null ? "NULL" : $tugas) . ",
            " . ($uts === null ? "NULL" : $uts) . ",
            " . ($uas === null ? "NULL" : $uas) . ",
            " . ($nilai_akhir === null ? "NULL" : $nilai_akhir) . "
          )";
}

mysqli_query($conn, $q);

// update status ujian di tabel mahasiswa
mysqli_query($conn, "UPDATE mahasiswa SET status_ujian='$status' WHERE id_mhs='$id_mhs'");

header("Location: laporan.php");
exit;
