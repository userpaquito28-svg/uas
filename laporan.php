<?php
session_start();
include "config/db.php";

if (!isset($_SESSION['login'])) {
    header("Location: login.php");
    exit;
}

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

// Ambil data nilai mahasiswa
$sql = "SELECT 
            n.id_nilai,
            m.id_mhs,
            m.npm,
            m.nama,
            m.kelas,
            n.tugas,
            n.uts,
            n.uas,
            n.nilai_akhir,
            m.status_ujian
        FROM mahasiswa m
        LEFT JOIN nilai n ON n.id_mhs = m.id_mhs
        $where
        ORDER BY m.kelas ASC, m.nama ASC";
$dataNilai = mysqli_query($conn, $sql);

// Ambil data mahasiswa untuk dropdown
$qMhs = mysqli_query($conn, "SELECT id_mhs, npm, nama, kelas FROM mahasiswa ORDER BY kelas ASC, nama ASC");

// Ambil kelas untuk filter
$qKelas = mysqli_query($conn, "SELECT DISTINCT kelas FROM mahasiswa ORDER BY kelas ASC");

// Simpan semua modal edit & hapus
$modals = [];
?>

<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="utf-8" />
<meta http-equiv="x-ua-compatible" content="IE=edge" />
<meta name="viewport" content="width=device-width, initial-scale=1" />

<title>Laporan Penilaian</title>

<link rel="shortcut icon" type="image/x-icon" href="assets/images/favicon.ico" />
<link rel="stylesheet" type="text/css" href="assets/css/bootstrap.min.css" />
<link rel="stylesheet" type="text/css" href="assets/vendors/css/vendors.min.css" />
<link rel="stylesheet" type="text/css" href="assets/css/theme.min.css" />

<style>
.btn-sm { padding: 0.35rem 0.6rem; font-size: 0.85rem; }
</style>
</head>

<body>

<?php include 'navigation.php'; ?>
<?php include 'header.php'; ?>

<main class="nxl-container">
    <div class="nxl-content">
        <div class="main-content">
            <div class="row">

                <!-- Filter + Tambah -->
                <div class="col-12">
                    <div class="card stretch stretch-full">
                        <div class="card-header d-flex justify-content-between align-items-center">
                            <div>
                                <h5 class="card-title mb-0">Laporan Penilaian</h5>
                                <small class="text-muted">Kelola nilai mahasiswa dan cetak laporan PDF</small>
                            </div>
                            <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#modalTambahNilai">
                                <i class="feather-plus me-2"></i>Tambah Nilai
                            </button>
                        </div>

                        <div class="card-body">
                            <form action="" method="GET">
                                <div class="row g-3">
                                    <div class="col-md-3">
                                        <label class="form-label">Kelas</label>
                                        <select class="form-select" name="kelas">
                                            <option value="">Semua Kelas</option>
                                            <?php while ($k = mysqli_fetch_assoc($qKelas)) : ?>
                                                <option value="<?= $k['kelas']; ?>" <?= ($filter_kelas == $k['kelas']) ? 'selected' : ''; ?>>
                                                    <?= $k['kelas']; ?>
                                                </option>
                                            <?php endwhile; ?>
                                        </select>
                                    </div>

                                    <div class="col-md-3">
                                        <label class="form-label">Status Ujian</label>
                                        <select class="form-select" name="status">
                                            <option value="">Semua Status</option>
                                            <option value="SUDAH" <?= ($filter_status == "SUDAH") ? 'selected' : ''; ?>>Sudah Ujian</option>
                                            <option value="BELUM" <?= ($filter_status == "BELUM") ? 'selected' : ''; ?>>Belum Ujian</option>
                                        </select>
                                    </div>

                                    <div class="col-md-4">
                                        <label class="form-label">Cari (NPM/Nama)</label>
                                        <input type="text" class="form-control" name="search" value="<?= htmlspecialchars($search); ?>" placeholder="Cari mahasiswa...">
                                    </div>

                                    <div class="col-md-2 d-flex align-items-end">
                                        <button type="submit" class="btn btn-dark w-100">
                                            <i class="feather-filter me-2"></i>Tampilkan
                                        </button>
                                    </div>

                                    <div class="col-12 d-flex gap-2 mt-2">
                                        <a href="laporan_pdf.php?kelas=<?= urlencode($filter_kelas); ?>&status=<?= urlencode($filter_status); ?>&search=<?= urlencode($search); ?>" class="btn btn-danger">
                                            <i class="feather-printer me-2"></i>Cetak PDF
                                        </a>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>

                <!-- Data Nilai -->
                <div class="col-12">
                    <div class="card stretch stretch-full">
                        <div class="card-header">
                            <h5 class="card-title mb-0">Data Penilaian</h5>
                        </div>

                        <div class="card-body custom-card-action p-0">
                            <div class="table-responsive">
                                <table class="table table-hover mb-0">
                                    <thead>
                                        <tr class="border-b">
                                            <th style="width: 160px;">NPM</th>
                                            <th>Nama</th>
                                            <th style="width: 120px;">Kelas</th>
                                            <th style="width: 110px;">Tugas</th>
                                            <th style="width: 110px;">UTS</th>
                                            <th style="width: 110px;">UAS</th>
                                            <th style="width: 120px;">Akhir</th>
                                            <th style="width: 180px;">Status</th>
                                            <th class="text-end" style="width: 140px;">Aksi</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php if (mysqli_num_rows($dataNilai) > 0) : ?>
                                            <?php while ($row = mysqli_fetch_assoc($dataNilai)) : ?>
                                                <tr>
                                                    <td class="fw-semibold"><?= htmlspecialchars($row['npm']); ?></td>
                                                    <td><?= htmlspecialchars($row['nama']); ?></td>
                                                    <td><?= htmlspecialchars($row['kelas']); ?></td>
                                                    <td><?= ($row['tugas'] === null) ? '-' : $row['tugas']; ?></td>
                                                    <td><?= ($row['uts'] === null) ? '-' : $row['uts']; ?></td>
                                                    <td><?= ($row['uas'] === null) ? '-' : $row['uas']; ?></td>
                                                    <td class="fw-semibold"><?= ($row['nilai_akhir'] === null) ? '-' : $row['nilai_akhir']; ?></td>
                                                    <td>
                                                        <?php if ($row['status_ujian'] == "SUDAH") : ?>
                                                            <span class="badge bg-soft-success text-success">
                                                                <i class="feather-check-circle me-1"></i>Sudah Ujian
                                                            </span>
                                                        <?php else : ?>
                                                            <span class="badge bg-soft-warning text-warning">
                                                                <i class="feather-alert-circle me-1"></i>Belum Ujian
                                                            </span>
                                                        <?php endif; ?>
                                                    </td>
                                                    <td class="text-end">
                                                        <button class="btn btn-sm btn-warning"
                                                            data-bs-toggle="modal"
                                                            data-bs-target="#modalEditNilai<?= $row['id_nilai']; ?>">
                                                            <i class="feather-edit-2 me-1"></i>Edit
                                                        </button>

                                                        <button class="btn btn-sm btn-danger"
                                                            data-bs-toggle="modal"
                                                            data-bs-target="#modalHapusNilai<?= $row['id_nilai']; ?>">
                                                            <i class="feather-trash-2 me-1"></i>Hapus
                                                        </button>
                                                    </td>
                                                </tr>

                                                <?php
                                                // simpan modal untuk render di akhir body
                                                $modals[] = $row;
                                                ?>
                                            <?php endwhile; ?>
                                        <?php else : ?>
                                            <tr>
                                                <td colspan="9" class="text-center py-4 text-muted">
                                                    Data belum ada.
                                                </td>
                                            </tr>
                                        <?php endif; ?>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </div>
</main>

<!-- Modal Tambah Nilai -->
<div class="modal fade" id="modalTambahNilai" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-content">
            <form action="nilai_tambah.php" method="POST">
                <div class="modal-header">
                    <h5 class="modal-title">Tambah Nilai Mahasiswa</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>

                <div class="modal-body">
                    <div class="row g-3">
                        <div class="col-md-12">
                            <label class="form-label">Mahasiswa</label>
                            <select class="form-select" name="id_mhs" required>
                                <option selected disabled value="">Pilih Mahasiswa</option>
                                <?php
                                mysqli_data_seek($qMhs, 0);
                                while ($m = mysqli_fetch_assoc($qMhs)) :
                                ?>
                                    <option value="<?= $m['id_mhs']; ?>">
                                        <?= htmlspecialchars($m['npm']); ?> - <?= htmlspecialchars($m['nama']); ?> (<?= htmlspecialchars($m['kelas']); ?>)
                                    </option>
                                <?php endwhile; ?>
                            </select>
                        </div>

                        <div class="col-md-4">
                            <label class="form-label">Nilai Tugas</label>
                            <input type="number" min="0" max="100" name="tugas" class="form-control" placeholder="0 - 100">
                        </div>

                        <div class="col-md-4">
                            <label class="form-label">Nilai UTS</label>
                            <input type="number" min="0" max="100" name="uts" class="form-control" placeholder="0 - 100">
                        </div>

                        <div class="col-md-4">
                            <label class="form-label">Nilai UAS</label>
                            <input type="number" min="0" max="100" name="uas" class="form-control" placeholder="0 - 100">
                        </div>

                        <div class="col-md-12">
                            <label class="form-label">Status Ujian</label>
                            <select class="form-select" name="status_ujian" required>
                                <option value="SUDAH">Sudah Ujian</option>
                                <option value="BELUM" selected>Belum Ujian</option>
                            </select>
                        </div>
                    </div>
                </div>

                <div class="modal-footer">
                    <button class="btn btn-light" data-bs-dismiss="modal" type="button">Batal</button>
                    <button class="btn btn-primary" type="submit">
                        <i class="feather-save me-2"></i>Simpan
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Semua Modal Edit & Hapus -->
<?php foreach ($modals as $row): ?>
    <!-- Modal Edit Nilai -->
    <div class="modal fade" id="modalEditNilai<?= $row['id_nilai']; ?>" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-lg modal-dialog-centered">
            <div class="modal-content">
                <form action="nilai_edit.php" method="POST">
                    <div class="modal-header">
                        <h5 class="modal-title">Edit Nilai Mahasiswa</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                    </div>

                    <div class="modal-body">
                        <input type="hidden" name="id_nilai" value="<?= $row['id_nilai']; ?>">
                        <div class="row g-3">
                            <div class="col-md-6">
                                <label class="form-label">NPM</label>
                                <input type="text" class="form-control" value="<?= htmlspecialchars($row['npm']); ?>" disabled>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">Nama</label>
                                <input type="text" class="form-control" value="<?= htmlspecialchars($row['nama']); ?>" disabled>
                            </div>
                            <div class="col-md-4">
                                <label class="form-label">Nilai Tugas</label>
                                <input type="number" min="0" max="100" name="tugas" class="form-control" value="<?= $row['tugas']; ?>">
                            </div>
                            <div class="col-md-4">
                                <label class="form-label">Nilai UTS</label>
                                <input type="number" min="0" max="100" name="uts" class="form-control" value="<?= $row['uts']; ?>">
                            </div>
                            <div class="col-md-4">
                                <label class="form-label">Nilai UAS</label>
                                <input type="number" min="0" max="100" name="uas" class="form-control" value="<?= $row['uas']; ?>">
                            </div>
                            <div class="col-md-12">
                                <label class="form-label">Status Ujian</label>
                                <select class="form-select" name="status_ujian">
                                    <option value="SUDAH" <?= ($row['status_ujian'] == "SUDAH") ? 'selected' : ''; ?>>Sudah Ujian</option>
                                    <option value="BELUM" <?= ($row['status_ujian'] == "BELUM") ? 'selected' : ''; ?>>Belum Ujian</option>
                                </select>
                            </div>
                        </div>
                    </div>

                    <!-- Tombol horizontal sejajar -->
                    <div class="modal-footer d-flex justify-content-end gap-2">
                        <button type="button" class="btn btn-light btn-sm" data-bs-dismiss="modal">Batal</button>
                        <button type="submit" class="btn btn-warning btn-sm">
                            <i class="feather-edit-2 me-1"></i>Update
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Modal Hapus Nilai -->
    <div class="modal fade" id="modalHapusNilai<?= $row['id_nilai']; ?>" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <form action="nilai_hapus.php" method="POST">
                    <div class="modal-header">
                        <h5 class="modal-title">Hapus Nilai Mahasiswa</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                    </div>

                    <div class="modal-body">
                        <input type="hidden" name="id_nilai" value="<?= $row['id_nilai']; ?>">
                        <p>Yakin mau hapus nilai mahasiswa <b><?= htmlspecialchars($row['npm']); ?> - <?= htmlspecialchars($row['nama']); ?></b>?</p>
                    </div>

                    <!-- Tombol horizontal sejajar -->
                    <div class="modal-footer d-flex justify-content-end gap-2">
                        <button type="button" class="btn btn-light btn-sm" data-bs-dismiss="modal">Batal</button>
                        <button type="submit" class="btn btn-danger btn-sm">
                            <i class="feather-trash-2 me-1"></i>Hapus
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
<?php endforeach; ?>

<script src="assets/vendors/js/vendors.min.js"></script>
<script src="assets/js/common-init.min.js"></script>
<script src="assets/js/theme-customizer-init.min.js"></script>
</body>
</html>
