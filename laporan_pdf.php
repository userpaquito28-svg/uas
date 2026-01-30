<?php
include 'config/db.php';
require('fpdf/fpdf.php');

// ambil filter sama kayak laporan.php
$filter_kelas  = isset($_GET['kelas']) ? $_GET['kelas'] : '';
$filter_status = isset($_GET['status']) ? $_GET['status'] : '';
$search        = isset($_GET['search']) ? $_GET['search'] : '';

$where = " WHERE 1=1 ";

if ($filter_kelas != '') {
    $kelas = mysqli_real_escape_string($conn, $filter_kelas);
    $where .= " AND m.kelas = '$kelas' ";
}

if ($filter_status != '') {
    $status = mysqli_real_escape_string($conn, $filter_status);
    $where .= " AND m.status_ujian = '$status' ";
}

if ($search != '') {
    $s = mysqli_real_escape_string($conn, $search);
    $where .= " AND (m.npm LIKE '%$s%' OR m.nama LIKE '%$s%') ";
}

// Query fix: JOIN nilai n ke mahasiswa m
$sql = "SELECT 
            m.npm, m.nama, m.kelas,
            n.tugas, n.uts, n.uas, n.nilai_akhir,
            m.status_ujian
        FROM mahasiswa m
        LEFT JOIN nilai n ON n.id_mhs = m.id_mhs
        $where
        ORDER BY m.kelas ASC, m.nama ASC";

$res = mysqli_query($conn, $sql);

if (!$res) {
    die("Query error: " . mysqli_error($conn));
}

// =========================
// PDF
// =========================
$pdf = new FPDF('L', 'mm', 'A4');
$pdf->AddPage();

$pdf->SetFont('Arial', 'B', 14);
$pdf->Cell(0, 10, 'LAPORAN PENILAIAN MAHASISWA', 0, 1, 'C');

$pdf->SetFont('Arial', '', 10);
$pdf->Cell(0, 6, 'Dicetak: ' . date('d-m-Y H:i'), 0, 1, 'C');

$pdf->Ln(4);

// header table
$pdf->SetFont('Arial', 'B', 10);
$pdf->Cell(35, 8, 'NPM', 1, 0, 'C');
$pdf->Cell(65, 8, 'Nama', 1, 0, 'C');
$pdf->Cell(25, 8, 'Kelas', 1, 0, 'C');
$pdf->Cell(20, 8, 'Tugas', 1, 0, 'C');
$pdf->Cell(20, 8, 'UTS', 1, 0, 'C');
$pdf->Cell(20, 8, 'UAS', 1, 0, 'C');
$pdf->Cell(25, 8, 'Akhir', 1, 0, 'C');
$pdf->Cell(35, 8, 'Status', 1, 1, 'C');

// body
$pdf->SetFont('Arial', '', 10);

while ($row = mysqli_fetch_assoc($res)) {
    $pdf->Cell(35, 8, $row['npm'], 1, 0);
    $pdf->Cell(65, 8, $row['nama'], 1, 0);
    $pdf->Cell(25, 8, $row['kelas'], 1, 0, 'C');

    $pdf->Cell(20, 8, ($row['tugas'] === null ? '-' : $row['tugas']), 1, 0, 'C');
    $pdf->Cell(20, 8, ($row['uts'] === null ? '-' : $row['uts']), 1, 0, 'C');
    $pdf->Cell(20, 8, ($row['uas'] === null ? '-' : $row['uas']), 1, 0, 'C');
    $pdf->Cell(25, 8, ($row['nilai_akhir'] === null ? '-' : $row['nilai_akhir']), 1, 0, 'C');

    // normalisasi status
    $status = strtoupper(trim($row['status_ujian']));
    if ($status !== 'SUDAH') $status = 'BELUM';

    $pdf->Cell(35, 8, $status, 1, 1, 'C');
}

$pdf->Output();
