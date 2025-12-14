<?php
include 'koneksi.php';
session_start();

/* ======================
   CEK LOGIN ADMIN
   ====================== */
if (!isset($_SESSION['admin_id'])) {
    header("Location: login_admin.php");
    exit;
}

$pesan = "";

/* ======================
   AUTO FIX KOLOM BERKAS
   ====================== */
$required_cols = array(
    'file_ktp'    => "VARCHAR(255) DEFAULT NULL",
    'file_kk'     => "VARCHAR(255) DEFAULT NULL",
    'file_ijazah' => "VARCHAR(255) DEFAULT NULL",
    'file_foto'   => "VARCHAR(255) DEFAULT NULL"
);

foreach ($required_cols as $col => $type) {
    $check = mysqli_query($conn, "SHOW COLUMNS FROM mahasiswa LIKE '$col'");
    if ($check && mysqli_num_rows($check) == 0) {
        mysqli_query($conn, "ALTER TABLE mahasiswa ADD $col $type");
    }
}

/* ======================
   UPDATE STATUS
   ====================== */
if (isset($_POST['update_status'])) {
    $id = intval($_POST['id_mahasiswa']);
    $status = mysqli_real_escape_string($conn, $_POST['status_pendaftaran']);

    $sql = "UPDATE mahasiswa SET status_pendaftaran='$status' WHERE id_mahasiswa='$id'";
    mysqli_query($conn, $sql);

    $pesan = "<div class='alert alert-success'>Status pendaftaran berhasil diperbarui</div>";
}

/* ======================
   PENCARIAN
   ====================== */
$keyword = "";
if (isset($_GET['keyword'])) {
    $keyword = mysqli_real_escape_string($conn, $_GET['keyword']);
}

$where = "";
if ($keyword != "") {
    $where = "WHERE m.nama_lengkap LIKE '%$keyword%'
              OR m.email LIKE '%$keyword%'
              OR m.no_hp LIKE '%$keyword%'";
}

/* ======================
   AMBIL DATA MAHASISWA
   ====================== */
$query = "
SELECT m.id_mahasiswa, m.nama_lengkap, m.email, m.no_hp, m.status_pendaftaran,
       p.nama_prodi, p.jenjang
FROM mahasiswa m
LEFT JOIN program_studi p ON m.id_prodi = p.id_prodi
$where
ORDER BY m.tanggal_daftar DESC
";

$result = mysqli_query($conn, $query);
?>
<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Dashboard Admin | AMIK Universal</title>

<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
<link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css" rel="stylesheet">
<link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600&display=swap" rel="stylesheet">

<style>
body{
  background:#f1f4f9;
  font-family:'Poppins',sans-serif;
}
.sidebar{
  position:fixed;
  top:0;left:0;
  width:230px;
  height:100vh;
  background:#020617;
  padding-top:20px;
  color:#fff;
}
.sidebar h4{text-align:center;margin-bottom:30px;}
.sidebar a{
  display:block;
  color:#cbd5f5;
  padding:12px 18px;
  margin:5px 10px;
  border-radius:8px;
  text-decoration:none;
}
.sidebar a:hover,.sidebar a.active{
  background:#2563eb;
  color:#fff;
}
.main{margin-left:250px;padding:30px;}
.card{border-radius:15px;}
table th{
  background:#020617!important;
  color:#fff;
  text-align:center;
}
.badge{border-radius:20px;padding:6px 14px;}
@media(max-width:768px){
  .sidebar{position:relative;width:100%;height:auto;}
  .main{margin-left:0;}
}
</style>
</head>
<body>

<!-- SIDEBAR -->
<div class="sidebar">
  <h4>Admin Panel</h4>
  <a href="#" class="active"><i class="fa fa-home"></i> Dashboard</a>
  <a href="logout_admin.php"><i class="fa fa-sign-out-alt"></i> Logout</a>
</div>

<!-- MAIN -->
<div class="main">
<div class="card p-4">

<h3 class="text-center fw-bold mb-3">
📋 Daftar Calon Mahasiswa Baru
</h3>

<?= $pesan ?>

<!-- FORM PENCARIAN -->
<form method="GET" class="row mb-3">
  <div class="col-md-6">
    <input type="text" name="keyword" class="form-control"
           placeholder="Cari nama / email / no HP"
           value="<?= htmlspecialchars($keyword) ?>">
  </div>
  <div class="col-md-2">
    <button class="btn btn-primary w-100">Cari</button>
  </div>
  <div class="col-md-2">
    <a href="admin_dashboard.php" class="btn btn-secondary w-100">Reset</a>
  </div>
  <a href="home.php" class="btn btn-primary mt-3">Back to Home</a>
  </div>
  
</form>

<div class="table-responsive">
<table class="table table-bordered table-hover">
<thead>
<tr>
<th>No</th>
<th>Nama</th>
<th>Program Studi</th>
<th>Email</th>
<th>No HP</th>
<th>Status</th>
<th>Aksi</th>
</tr>
</thead>
<tbody>

<?php
$no = 1;
while ($row = mysqli_fetch_assoc($result)) {
    $status = $row['status_pendaftaran'];
    if ($status == 'Diterima') $warna = 'success';
    elseif ($status == 'Belum Lengkap') $warna = 'warning';
    elseif ($status == 'Ditolak') $warna = 'danger';
    else $warna = 'secondary';
?>
<tr>
<td><?= $no++ ?></td>
<td><?= htmlspecialchars($row['nama_lengkap']) ?></td>
<td><?= $row['nama_prodi']." (".$row['jenjang'].")" ?></td>
<td><?= htmlspecialchars($row['email']) ?></td>
<td><?= htmlspecialchars($row['no_hp']) ?></td>
<td><span class="badge bg-<?= $warna ?>"><?= $status ?></span></td>
<td>
<form method="POST" class="d-flex mb-1">
<input type="hidden" name="id_mahasiswa" value="<?= $row['id_mahasiswa'] ?>">
<select name="status_pendaftaran" class="form-select form-select-sm me-2">
<option <?= ($status=='Belum Diverifikasi')?'selected':'' ?>>Belum Diverifikasi</option>
<option <?= ($status=='Belum Lengkap')?'selected':'' ?>>Belum Lengkap</option>
<option <?= ($status=='Diterima')?'selected':'' ?>>Diterima</option>
<option <?= ($status=='Ditolak')?'selected':'' ?>>Ditolak</option>
</select>
<button name="update_status" class="btn btn-success btn-sm">Simpan</button>
</form>

<form method="GET" action="lihat_berkas.php" target="_blank">
<input type="hidden" name="id" value="<?= $row['id_mahasiswa'] ?>">
<button class="btn btn-info btn-sm w-100">
<i class="fa fa-eye"></i> Lihat Berkas
</button>
</form>
</td>
</tr>
<?php } ?>

</tbody>
</table>
</div>

</div>
</div>

</body>
</html>
