<?php
include 'koneksi.php';
session_start();

// Proteksi admin login
if (!isset($_SESSION['admin_id'])) {
    header("Location: login_admin.php");
    exit();
}

$id = isset($_GET['id']) ? intval($_GET['id']) : 0;

$query = "SELECT m.*, p.nama_prodi, p.jenjang
          FROM mahasiswa m
          LEFT JOIN program_studi p ON m.id_prodi = p.id_prodi
          WHERE m.id_mahasiswa = " . intval($id);

$result = mysqli_query($conn, $query);
$data   = ($result) ? mysqli_fetch_assoc($result) : null;

if (!$result || !$data) {
    die("<div class='alert alert-danger text-center'>❌ Data atau berkas tidak ditemukan!</div>");
}

// Lokasi folder upload
$folder = "upload_dokumen/";

function cekfile($file) {
    global $folder;
    if (!empty($file) && file_exists($folder . $file)) {
        return $folder . $file;
    }
    return "";
}

$ktp  = cekfile(isset($data['file_ktp']) ? $data['file_ktp'] : '');
$kk   = cekfile(isset($data['file_kk']) ? $data['file_kk'] : '');
$ijz  = cekfile(isset($data['file_ijazah']) ? $data['file_ijazah'] : '');
$foto = cekfile(isset($data['file_foto']) ? $data['file_foto'] : '');
?>
<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Berkas Pendaftar | Dashboard Admin</title>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
<link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet">
<style>
body { background:#f7f9fc; font-family:'Poppins',sans-serif; }
.sidebar {
    height: 100vh;
    background: #0d6efd;
    color: #fff;
    padding-top: 20px;
    position: fixed;
    width: 220px;
}
.sidebar a {
    color: #fff;
    text-decoration: none;
    display: block;
    padding: 10px 20px;
    border-radius: 6px;
}
.sidebar a:hover {
    background: rgba(255,255,255,0.2);
}
.main-content {
    margin-left: 240px;
    padding: 20px;
}
.card { border-radius:12px; box-shadow:0 4px 20px rgba(0,0,0,0.1); }
.img-preview { border-radius:10px; max-height:150px; object-fit:contain; }
iframe { border-radius:10px; height:150px; }
.label { font-weight:600; color:#0d6efd; }
.biodata-item { margin-bottom:10px; }
.biodata-item span { font-weight:600; color:#0d6efd; }
</style>
</head>
<body>

<!-- Sidebar Navbar -->
<div class="sidebar">
    <h4 class="text-center fw-bold mb-4">Admin Panel</h4>
    <a href="dashboard.php"><i class="bi bi-house"></i> Dashboard</a>
   
    <a href="logout.php"><i class="bi bi-box-arrow-right"></i> Logout</a>
</div>

<!-- Main Content -->
<div class="main-content">
  <div class="card p-4">
    <h3 class="text-center fw-bold text-dark mb-3"><i class="bi bi-folder"></i> Berkas Pendaftar</h3>
    <h5 class="text-center text-primary mb-4"><?php echo htmlspecialchars($data['nama_lengkap']); ?></h5>

    <!-- Biodata -->
<p class="fw-bold text-primary">🧾 Biodata Pendaftar</p>
<div class="row">
    <div class="col-md-6 biodata-item"><span class="label">Nama:</span> <?php echo htmlspecialchars($data['nama_lengkap']); ?></div>
    <div class="col-md-6 biodata-item"><span class="label">Jenis Kelamin:</span> <?php echo htmlspecialchars($data['jenis_kelamin']); ?></div>
    <div class="col-md-6 biodata-item"><span class="label">Tanggal Lahir:</span> <?php echo htmlspecialchars($data['tanggal_lahir']); ?></div>
    <div class="col-md-6 biodata-item"><span class="label">Agama:</span> <?php echo htmlspecialchars($data['agama']); ?></div>
    <div class="col-md-6 biodata-item"><span class="label">No HP:</span> <?php echo htmlspecialchars($data['no_hp']); ?></div>
    <div class="col-md-6 biodata-item"><span class="label">Prodi:</span> <?php echo htmlspecialchars($data['jenjang'].' - '.$data['nama_prodi']); ?></div>
    <div class="col-md-6 biodata-item"><span class="label">Asal Sekolah:</span> <?php echo htmlspecialchars($data['asal_sekolah']); ?></div>
    <div class="col-md-6 biodata-item"><span class="label">Tahun Lulus:</span> <?php echo htmlspecialchars($data['tahun_lulus']); ?></div>
    <div class="col-md-12 biodata-item"><span class="label">Alamat:</span><br> <?php echo nl2br(htmlspecialchars($data['alamat'])); ?></div>
    <div class="col-md-6 biodata-item"><span class="label">Tanggal Daftar:</span> <?php echo htmlspecialchars($data['tanggal_daftar']); ?></div>
    <div class="col-md-6 biodata-item"><span class="label">Status:</span> <?php echo htmlspecialchars($data['status_pendaftaran']); ?></div>
</div>

    <hr>

    <!-- Preview Berkas -->
    <p class="fw-bold text-primary">🗂 Berkas Pendaftar</p>
    <div class="row g-3">
      <!-- KTP -->
      <div class="col-md-3">
        <div class="card p-2 h-100 text-center">
          <h6 class="fw-bold"><i class="bi bi-person-badge"></i> KTP</h6>
          <?php if ($ktp != "") { ?>
            <img src="<?php echo $ktp; ?>" class="img-preview w-100 mb-2" alt="KTP">
            <a href="<?php echo $ktp; ?>" download class="btn btn-sm btn-success w-100">⬇ Download</a>
          <?php } else { ?>
            <p class="text-muted">Tidak ada file</p>
          <?php } ?>
        </div>
      </div>

      <!-- KK -->
      <div class="col-md-3">
        <div class="card p-2 h-100 text-center">
          <h6 class="fw-bold"><i class="bi bi-people"></i> KK</h6>
          <?php if ($kk != "") { ?>
            <img src="<?php echo $kk; ?>" class="img-preview w-100 mb-2" alt="KK">
            <a href="<?php echo $kk; ?>" download class="btn btn-sm btn-success w-1">⬇ Download</a>
          <?php } else { ?>
            <p class="text-muted">Tidak ada file</p>
          <?php } ?>
        </div>
      </div>

      <!-- Ijazah -->
      <div class="col-md-3">
        <div class="card p-2 h-100 text-center">
          <h6 class="fw-bold"><i class="bi bi-mortarboard"></i> Ijazah</h6>
          <?php if ($ijz != "") { ?>
            <?php if (stripos($ijz, ".pdf") !== false) { ?>
              <iframe src="<?php echo $ijz; ?>" class="w-100 mb-2"></iframe>
              <a href="<?php echo $ijz; ?>" download class="btn btn-sm btn-success w-100">⬇ Download PDF</a>
            <?php } else { ?>
              <img src="<?php echo $ijz; ?>" class="img-preview w-100 mb-2" alt="Ijazah">
              <a href="<?php echo $ijz; ?>" download class="btn btn-sm btn-success w-100">⬇ Download</a>
            <?php } ?>
          <?php } else { ?>
            <p class="text-muted">Tidak ada file</p>
          <?php } ?>
        </div>
      </div>

      <!-- Foto -->
      <div class="col-md-3">
        <div class="card p-2 h-100 text-center">
          <h6 class="fw-bold"><i class="bi bi-image"></i> Foto</h6>
          <?php if ($foto != "") { ?>
            <img src="<?php echo $foto; ?>" class="img-preview w-100 mb-2" alt="Foto">
            <a href="<?php echo $foto; ?>" download class="btn btn-sm btn-success w-100">⬇ Download</a>
          <?php } else { ?>
            <p class="text-muted">Tidak ada file</p>
          <?php } ?>
        </div>
      </div>
    </div> <!-- end row -->

  </div> <!-- end card -->
</div> <!-- end main-content -->

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
