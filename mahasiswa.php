<?php
session_start();
include 'config/db.php';

if (!isset($_SESSION['login'])) {
    header("Location: login.php");
    exit;
}

// Search
$search = $_GET['search'] ?? '';
$search_safe = mysqli_real_escape_string($conn, $search);

$where = "";
if ($search !== '') {
    $where = "WHERE npm LIKE '%$search_safe%' OR nama LIKE '%$search_safe%'";
}

// Statistik
$qTotal = mysqli_query($conn, "SELECT COUNT(*) AS total FROM mahasiswa");
$totalMahasiswa = mysqli_fetch_assoc($qTotal)['total'] ?? 0;

$qSudah = mysqli_query($conn, "SELECT COUNT(*) AS total FROM mahasiswa WHERE status_ujian='SUDAH'");
$totalSudah = mysqli_fetch_assoc($qSudah)['total'] ?? 0;

$qBelum = mysqli_query($conn, "SELECT COUNT(*) AS total FROM mahasiswa WHERE status_ujian='BELUM'");
$totalBelum = mysqli_fetch_assoc($qBelum)['total'] ?? 0;

// Data mahasiswa
$qMhs = mysqli_query($conn, "SELECT * FROM mahasiswa $where ORDER BY kelas ASC, nama ASC");
?>
<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="utf-8" />
    <meta http-equiv="x-ua-compatible" content="IE=edge" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />

    <title>Mahasiswa</title>

    <link rel="shortcut icon" type="image/x-icon" href="assets/images/favicon.ico" />
    <link rel="stylesheet" type="text/css" href="assets/css/bootstrap.min.css" />
    <link rel="stylesheet" type="text/css" href="assets/vendors/css/vendors.min.css" />
    <link rel="stylesheet" type="text/css" href="assets/css/theme.min.css" />
</head>

<body>

    <?php include 'navigation.php'; ?>
    <?php include 'header.php'; ?>

    <main class="nxl-container">
        <div class="nxl-content">

            <div class="main-content">
                <div class="row">

                    <!-- Statistik Ringkas -->
                    <div class="col-12">
                        <div class="row g-4 mb-4">
                            <div class="col-md-4">
                                <div class="card stretch stretch-full">
                                    <div class="card-body">
                                        <h6 class="text-muted mb-2">Total Mahasiswa</h6>
                                        <h3 class="mb-0"><?= $totalMahasiswa; ?></h3>
                                    </div>
                                </div>
                            </div>

                            <div class="col-md-4">
                                <div class="card stretch stretch-full">
                                    <div class="card-body">
                                        <h6 class="text-muted mb-2">Sudah Ujian</h6>
                                        <h3 class="mb-0"><?= $totalSudah; ?></h3>
                                    </div>
                                </div>
                            </div>

                            <div class="col-md-4">
                                <div class="card stretch stretch-full">
                                    <div class="card-body">
                                        <h6 class="text-muted mb-2">Belum Ujian</h6>
                                        <h3 class="mb-0"><?= $totalBelum; ?></h3>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Tabel Mahasiswa -->
                    <div class="col-12">
                        <div class="card stretch stretch-full">
                            <div class="card-header d-flex justify-content-between align-items-center">
                                <div>
                                    <h5 class="card-title mb-0">Data Mahasiswa</h5>
                                    <small class="text-muted">Kelola data mahasiswa</small>
                                </div>

                                <div class="card-header-action d-flex gap-2">
                                    <form method="GET" class="d-flex gap-2">
                                        <input type="text" name="search" value="<?= htmlspecialchars($search); ?>"
                                            class="form-control form-control-sm" style="width: 240px;"
                                            placeholder="Cari NPM / Nama">
                                        <button class="btn btn-dark btn-sm" type="submit">
                                            <i class="feather-search me-1"></i>Cari
                                        </button>
                                        <?php if ($search !== ''): ?>
                                            <a href="mahasiswa.php" class="btn btn-light btn-sm">Reset</a>
                                        <?php endif; ?>
                                    </form>

                                    <button class="btn btn-primary btn-sm" data-bs-toggle="modal" data-bs-target="#modalTambahMahasiswa">
                                        <i class="feather-plus me-1"></i>Tambah
                                    </button>
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
                                                <th style="width: 180px;">Status Ujian</th>
                                                <th class="text-end" style="width: 180px;">Aksi</th>
                                            </tr>
                                        </thead>

                                        <tbody>
                                            <?php if (mysqli_num_rows($qMhs) > 0): ?>
                                                <?php while($m = mysqli_fetch_assoc($qMhs)): ?>
                                                    <?php
                                                    $status = strtoupper(trim($m['status_ujian']));
                                                    if ($status !== 'SUDAH') $status = 'BELUM';
                                                    ?>
                                                    <tr>
                                                        <td class="fw-semibold"><?= htmlspecialchars($m['npm']); ?></td>
                                                        <td><?= htmlspecialchars($m['nama']); ?></td>
                                                        <td><?= htmlspecialchars($m['kelas']); ?></td>
                                                        <td>
                                                            <?php if ($status === 'SUDAH'): ?>
                                                                <span class="badge bg-soft-success text-success">
                                                                    <i class="feather-check-circle me-1"></i>Sudah Ujian
                                                                </span>
                                                            <?php else: ?>
                                                                <span class="badge bg-soft-warning text-warning">
                                                                    <i class="feather-alert-circle me-1"></i>Belum Ujian
                                                                </span>
                                                            <?php endif; ?>
                                                        </td>
                                                        <td class="text-end d-flex justify-content-end gap-2">
                                                            <button class="btn btn-sm btn-warning btn-edit" 
                                                                data-id="<?= $m['id_mhs']; ?>" 
                                                                data-npm="<?= htmlspecialchars($m['npm']); ?>" 
                                                                data-nama="<?= htmlspecialchars($m['nama']); ?>" 
                                                                data-kelas="<?= htmlspecialchars($m['kelas']); ?>" 
                                                                data-status="<?= $status; ?>">
                                                                <i class="feather-edit-2 me-1"></i>Edit
                                                            </button>

                                                            <a href="mahasiswa_hapus.php?id_mhs=<?= $m['id_mhs']; ?>" 
                                                                class="btn btn-sm btn-danger"
                                                                onclick="return confirm('Yakin hapus mahasiswa ini?')">
                                                                <i class="feather-trash-2 me-1"></i>Hapus
                                                            </a>
                                                        </td>
                                                    </tr>
                                                <?php endwhile; ?>
                                            <?php else: ?>
                                                <tr>
                                                    <td colspan="5" class="text-center py-4 text-muted">
                                                        Belum ada data mahasiswa
                                                    </td>
                                                </tr>
                                            <?php endif; ?>
                                        </tbody>
                                    </table>
                                </div>
                            </div>

                            <div class="card-footer">
                                <small class="text-muted">* Pagination bisa ditambah belakangan (optional)</small>
                            </div>
                        </div>
                    </div>

                </div>
            </div>

        </div>

        <?php include 'footer.php'; ?>
    </main>

    <!-- Modal Tambah Mahasiswa -->
    <div class="modal fade" id="modalTambahMahasiswa" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-lg modal-dialog-centered">
            <div class="modal-content">
                <form action="mahasiswa_tambah_process.php" method="POST">
                    <div class="modal-header">
                        <h5 class="modal-title">Tambah Mahasiswa</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                    </div>

                    <div class="modal-body">
                        <div class="row g-3">
                            <div class="col-md-6">
                                <label class="form-label">NPM</label>
                                <input type="text" name="npm" class="form-control" required>
                            </div>

                            <div class="col-md-6">
                                <label class="form-label">Nama</label>
                                <input type="text" name="nama" class="form-control" required>
                            </div>

                            <div class="col-md-6">
                                <label class="form-label">Kelas</label>
                                <input type="text" name="kelas" class="form-control" required>
                            </div>

                            <div class="col-md-6">
                                <label class="form-label">Status Ujian</label>
                                <select class="form-select" name="status_ujian" required>
                                    <option value="BELUM" selected>Belum Ujian</option>
                                    <option value="SUDAH">Sudah Ujian</option>
                                </select>
                            </div>
                        </div>
                    </div>

                    <div class="modal-footer">
                        <button class="btn btn-light" type="button" data-bs-dismiss="modal">Batal</button>
                        <button class="btn btn-primary" type="submit">
                            <i class="feather-save me-1"></i>Simpan
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Modal Edit Mahasiswa -->
    <div class="modal fade" id="modalEditMahasiswa" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-lg modal-dialog-centered">
            <div class="modal-content">
                <form id="formEditMahasiswa" method="POST" action="mahasiswa_edit_process.php">
                    <input type="hidden" name="id_mhs" id="edit_id_mhs">
                    <div class="modal-header">
                        <h5 class="modal-title">Edit Mahasiswa</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                    </div>

                    <div class="modal-body">
                        <div class="row g-3">
                            <div class="col-md-6">
                                <label class="form-label">NPM</label>
                                <input type="text" name="npm" id="edit_npm" class="form-control" required>
                            </div>

                            <div class="col-md-6">
                                <label class="form-label">Nama</label>
                                <input type="text" name="nama" id="edit_nama" class="form-control" required>
                            </div>

                            <div class="col-md-6">
                                <label class="form-label">Kelas</label>
                                <input type="text" name="kelas" id="edit_kelas" class="form-control" required>
                            </div>

                            <div class="col-md-6">
                                <label class="form-label">Status Ujian</label>
                                <select class="form-select" name="status_ujian" id="edit_status_ujian" required>
                                    <option value="BELUM">Belum Ujian</option>
                                    <option value="SUDAH">Sudah Ujian</option>
                                </select>
                            </div>
                        </div>
                    </div>

                    <div class="modal-footer">
                        <button class="btn btn-light" type="button" data-bs-dismiss="modal">Batal</button>
                        <button class="btn btn-warning" type="submit">
                            <i class="feather-save me-1"></i>Simpan Perubahan
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script src="assets/vendors/js/vendors.min.js"></script>
    <script src="assets/js/common-init.min.js"></script>
    <script src="assets/js/theme-customizer-init.min.js"></script>

    <script>
        // Tombol Edit
        const editButtons = document.querySelectorAll('.btn-edit');
        editButtons.forEach(btn => {
            btn.addEventListener('click', function() {
                document.getElementById('edit_id_mhs').value = this.dataset.id;
                document.getElementById('edit_npm').value = this.dataset.npm;
                document.getElementById('edit_nama').value = this.dataset.nama;
                document.getElementById('edit_kelas').value = this.dataset.kelas;
                document.getElementById('edit_status_ujian').value = this.dataset.status;

                var myModal = new bootstrap.Modal(document.getElementById('modalEditMahasiswa'));
                myModal.show();
            });
        });
    </script>

</body>
</html>
