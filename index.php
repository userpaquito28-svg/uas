<?php
session_start();
include "config/db.php";

if (!isset($_SESSION['login'])) {
    header("Location: login.php");
    exit;
}

// ambil data mahasiswa
$q = "SELECT npm, nama, kelas, status_ujian 
      FROM mahasiswa 
      ORDER BY nama ASC";
$result = mysqli_query($conn, $q);

if (!$result) {
    die("Query error: " . mysqli_error($conn));
}
?>

<!DOCTYPE html>
<html lang="zxx">

<head>
    <meta charset="utf-8" />
    <meta http-equiv="x-ua-compatible" content="IE=edge" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />

    <title>Beranda</title>

    <link rel="shortcut icon" type="image/x-icon" href="assets/images/favicon.ico" />
    <link rel="stylesheet" type="text/css" href="assets/css/bootstrap.min.css" />
    <link rel="stylesheet" type="text/css" href="assets/vendors/css/vendors.min.css" />
    <link rel="stylesheet" type="text/css" href="assets/vendors/css/daterangepicker.min.css" />
    <link rel="stylesheet" type="text/css" href="assets/css/theme.min.css" />
</head>

<body>

    <?php include 'navigation.php'; ?>
    <?php include 'header.php'; ?>

    <main class="nxl-container">
        <div class="nxl-content">

            <div class="card stretch stretch-full">
                <div class="card-header">
                    <div>
                        <h5 class="card-title mb-0">Status Ujian Mahasiswa</h5>
                        <small class="text-muted">Monitoring mahasiswa yang sudah menyelesaikan ujian</small>
                    </div>
                </div>

                <div class="card-body custom-card-action p-0">
                    <div class="table-responsive">
                        <table class="table table-hover mb-0">
                            <thead>
                                <tr class="border-b">
                                    <th style="width: 160px;">NPM</th>
                                    <th>Nama</th>
                                    <th style="width: 120px;">Kelas</th>
                                    <th style="width: 220px;">Status Ujian</th>
                                </tr>
                            </thead>

                            <tbody>
                                <?php if (mysqli_num_rows($result) > 0): ?>
                                    <?php while ($row = mysqli_fetch_assoc($result)): ?>
                                        <tr>
                                            <td class="fw-semibold"><?= htmlspecialchars($row['npm']) ?></td>
                                            <td><?= htmlspecialchars($row['nama']) ?></td>
                                            <td><?= htmlspecialchars($row['kelas']) ?></td>
                                            <td>
                                                <?php if ($row['status_ujian'] === 'SUDAH'): ?>
                                                    <span class="badge bg-soft-success text-success">
                                                        <i class="feather-check-circle me-1"></i>Sudah Ujian
                                                    </span>
                                                <?php else: ?>
                                                    <span class="badge bg-soft-warning text-warning">
                                                        <i class="feather-alert-circle me-1"></i>Belum Ujian
                                                    </span>
                                                <?php endif; ?>
                                            </td>
                                        </tr>
                                    <?php endwhile; ?>
                                <?php else: ?>
                                    <tr>
                                        <td colspan="4" class="text-center py-4 text-muted">
                                            Belum ada data mahasiswa
                                        </td>
                                    </tr>
                                <?php endif; ?>
                            </tbody>

                        </table>
                    </div>
                </div>

                <div class="card-footer">
                    <ul class="list-unstyled d-flex align-items-center gap-2 mb-0 pagination-common-style">
                        <li><a href="javascript:void(0);"><i class="bi bi-arrow-left"></i></a></li>
                        <li><a href="javascript:void(0);" class="active">1</a></li>
                        <li><a href="javascript:void(0);">2</a></li>
                        <li><a href="javascript:void(0);"><i class="bi bi-arrow-right"></i></a></li>
                    </ul>
                </div>
            </div>

        </div>
    </main>

    <script src="assets/vendors/js/vendors.min.js"></script>
    <script src="assets/vendors/js/daterangepicker.min.js"></script>
    <script src="assets/vendors/js/apexcharts.min.js"></script>
    <script src="assets/vendors/js/circle-progress.min.js"></script>
    <script src="assets/js/common-init.min.js"></script>
    <script src="assets/js/theme-customizer-init.min.js"></script>
</body>

</html>
