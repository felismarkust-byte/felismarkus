<?php
include 'koneksi.php';
session_start();

// Cek login mahasiswa
if (!isset($_SESSION['id_mahasiswa'])) {
    header("Location: login_mahasiswa.php");
    exit;
}

$id = intval($_SESSION['id_mahasiswa']);
$pesan = '';

// === PROSES SIMPAN EDIT BIODATA ===
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['simpan_edit'])) {
    $nama    = mysqli_real_escape_string($conn, isset($_POST['nama_lengkap']) ? $_POST['nama_lengkap'] : '');
    $tempat  = mysqli_real_escape_string($conn, isset($_POST['tempat_lahir']) ? $_POST['tempat_lahir'] : '');
    $tanggal = mysqli_real_escape_string($conn, isset($_POST['tanggal_lahir']) ? $_POST['tanggal_lahir'] : '');
    $jk      = mysqli_real_escape_string($conn, isset($_POST['jenis_kelamin']) ? $_POST['jenis_kelamin'] : '');
    $alamat  = mysqli_real_escape_string($conn, isset($_POST['alamat']) ? $_POST['alamat'] : '');
    $nohp    = mysqli_real_escape_string($conn, isset($_POST['no_hp']) ? $_POST['no_hp'] : '');
    $email   = mysqli_real_escape_string($conn, isset($_POST['email']) ? $_POST['email'] : '');
    $prodi   = mysqli_real_escape_string($conn, isset($_POST['id_prodi']) ? $_POST['id_prodi'] : '');

    $folder = "upload_dokumen/";
    $berkas_sql = "";

    function uploadFile($file_input, $folder, $conn) {
        if(isset($_FILES[$file_input]) && $_FILES[$file_input]['error'] == 0){
            $nama_file = time().'_'.basename($_FILES[$file_input]['name']);
            if(move_uploaded_file($_FILES[$file_input]['tmp_name'], $folder.$nama_file)){
                return mysqli_real_escape_string($conn, $nama_file);
            }
        }
        return false;
    }

    if($ktp = uploadFile('file_ktp', $folder, $conn)) $berkas_sql .= " , file_ktp='$ktp' ";
    if($kk  = uploadFile('file_kk', $folder, $conn)) $berkas_sql .= " , file_kk='$kk' ";
    if($ijazah = uploadFile('file_ijazah', $folder, $conn)) $berkas_sql .= " , file_ijazah='$ijazah' ";
    if($foto = uploadFile('file_foto', $folder, $conn)) $berkas_sql .= " , file_foto='$foto' ";

    $update_sql = "UPDATE mahasiswa SET 
                    nama_lengkap='$nama',
                    tempat_lahir='$tempat',
                    tanggal_lahir='$tanggal',
                    jenis_kelamin='$jk',
                    alamat='$alamat',
                    no_hp='$nohp',
                    email='$email',
                    id_prodi='$prodi'
                    $berkas_sql
                    WHERE id_mahasiswa='$id'";

    if(mysqli_query($conn, $update_sql)){
        $pesan = "<div class='alert alert-success mt-3'>✅ Data berhasil disimpan!</div>";
    } else {
        $pesan = "<div class='alert alert-danger mt-3'>❌ Gagal simpan data: ".htmlspecialchars(mysqli_error($conn))."</div>";
    }
}

// Ambil data mahasiswa
$query = "SELECT m.*, p.nama_prodi, p.jenjang 
          FROM mahasiswa m
          LEFT JOIN program_studi p ON m.id_prodi = p.id_prodi
          WHERE m.id_mahasiswa='$id'";
$res = mysqli_query($conn, $query);
$data = $res ? mysqli_fetch_assoc($res) : array();

// Ambil daftar prodi
$prodi_list = mysqli_query($conn, "SELECT id_prodi, nama_prodi, jenjang FROM program_studi ORDER BY nama_prodi ASC");
?>

<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Dashboard Mahasiswa | AMIK Universal</title>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
<link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css" rel="stylesheet">
<style>
body { font-family:'Poppins', sans-serif; background:#f5f7fa; }
.sidebar { height:100vh; background:#007bff; color:#fff; position:fixed; width:220px; top:0; left:0; padding-top:1rem; }
.sidebar a { display:block; color:#fff; padding:12px 20px; text-decoration:none; font-weight:500; margin-bottom:4px; border-radius:6px; transition:0.3s; }
.sidebar a:hover, .sidebar a.active { background:#0056b3; }
.main { margin-left:230px; padding:2rem; }
.card { border-radius:12px; box-shadow:0 6px 25px rgba(0,0,0,0.08); }
.card-header { background:#007bff; color:#fff; font-weight:600; border-top-left-radius:12px; border-top-right-radius:12px; font-size:1.2rem; }
.card-body { padding:2rem; }
.form-label { font-weight:500; }
.img-preview-landscape { width:100%; max-width:160px; height:90px; object-fit:cover; margin-top:5px; border-radius:6px; border:1px solid #ddd; padding:3px; background:#fff; }
.img-preview-portrait { width:100%; max-width:100px; height:120px; object-fit:cover; margin-top:5px; border-radius:6px; border:1px solid #ddd; padding:3px; background:#fff; }
.btn-primary { background:#007bff; border:none; font-weight:500; transition:0.3s; }
.btn-primary:hover { background:#0056b3; }
.alert { font-size:0.95rem; }
</style>
</head>
<body>

<div class="sidebar">
    <h4 class="text-center mb-4">AMIK Universal</h4>
    <a href="home.php" class="active"><i class="fa fa-home me-2"></i> Dashboard</a>
    <a href="dashboard_mahasiswa.php"><i class="fa fa-user me-2"></i> Edit Profil</a>
    <a href="logout.php"><i class="fa fa-sign-out-alt me-2"></i> Logout</a>
</div>

<div class="main">
<div class="card">
  <div class="card-header text-center">
    Edit Identitas & Upload Berkas
  </div>
  <div class="card-body">

    <?php if (!empty($pesan)) echo $pesan; ?>

    <?php if (empty($data)) { ?>
    <div class='alert alert-warning'>Data pendaftar tidak ditemukan.</div>
    <?php } else { ?>
    <form method="POST" action="" enctype="multipart/form-data">

      <div class="row g-3">
        <div class="col-md-6">
          <label class="form-label">Nama Lengkap</label>
          <input type="text" name="nama_lengkap" class="form-control" value="<?php echo isset($data['nama_lengkap']) ? htmlspecialchars($data['nama_lengkap']) : ''; ?>" required>
        </div>
        <div class="col-md-6">
          <label class="form-label">Tempat Lahir</label>
          <input type="text" name="tempat_lahir" class="form-control" value="<?php echo isset($data['tempat_lahir']) ? htmlspecialchars($data['tempat_lahir']) : ''; ?>" required>
        </div>
      </div>

      <div class="row g-3 mt-2">
        <div class="col-md-6">
          <label class="form-label">Tanggal Lahir</label>
          <input type="date" name="tanggal_lahir" class="form-control" value="<?php echo isset($data['tanggal_lahir']) ? htmlspecialchars($data['tanggal_lahir']) : ''; ?>" required>
        </div>
        <div class="col-md-6">
          <label class="form-label">Jenis Kelamin</label>
          <select name="jenis_kelamin" class="form-select" required>
            <option value="Laki-laki" <?php echo (isset($data['jenis_kelamin']) && $data['jenis_kelamin']=="Laki-laki") ? "selected" : ""; ?>>Laki-laki</option>
            <option value="Perempuan" <?php echo (isset($data['jenis_kelamin']) && $data['jenis_kelamin']=="Perempuan") ? "selected" : ""; ?>>Perempuan</option>
          </select>
        </div>
      </div>

      <div class="mt-3">
        <label class="form-label">Alamat</label>
        <textarea name="alamat" class="form-control" rows="2" required><?php echo isset($data['alamat']) ? htmlspecialchars($data['alamat']) : ''; ?></textarea>
      </div>

      <div class="row g-3 mt-2">
        <div class="col-md-6">
          <label class="form-label">No HP</label>
          <input type="text" name="no_hp" class="form-control" value="<?php echo isset($data['no_hp']) ? htmlspecialchars($data['no_hp']) : ''; ?>" required>
        </div>
        <div class="col-md-6">
          <label class="form-label">Email</label>
          <input type="email" name="email" class="form-control" value="<?php echo isset($data['email']) ? htmlspecialchars($data['email']) : ''; ?>" required>
        </div>
      </div>

      <div class="mt-3">
        <label class="form-label">Program Studi</label>
        <select name="id_prodi" class="form-select" required>
          <option value="">-- Pilih Program Studi --</option>
          <?php
          if($prodi_list){
            while($p = mysqli_fetch_assoc($prodi_list)){
              $sel = (isset($data['id_prodi']) && $data['id_prodi']==$p['id_prodi']) ? 'selected' : '';
              echo '<option value="'.htmlspecialchars($p['id_prodi']).'" '.$sel.'>'.htmlspecialchars($p['jenjang']).' - '.htmlspecialchars($p['nama_prodi']).'</option>';
            }
          }
          ?>
        </select>
      </div>

      <div class="row g-3 mt-3 text-center">
        <div class="col-md-3">
          <label class="form-label">KTP</label>
          <?php if(!empty($data['file_ktp'])) { ?>
          <img src="upload_dokumen/<?php echo htmlspecialchars($data['file_ktp']); ?>" class="img-preview-landscape">
          <?php } ?>
          <input type="file" name="file_ktp" class="form-control mt-1">
        </div>
        <div class="col-md-3">
          <label class="form-label">KK</label>
          <?php if(!empty($data['file_kk'])) { ?>
          <img src="upload_dokumen/<?php echo htmlspecialchars($data['file_kk']); ?>" class="img-preview-landscape">
          <?php } ?>
          <input type="file" name="file_kk" class="form-control mt-1">
        </div>
        <div class="col-md-3">
          <label class="form-label">Ijazah</label>
          <?php if(!empty($data['file_ijazah'])) { ?>
          <img src="upload_dokumen/<?php echo htmlspecialchars($data['file_ijazah']); ?>" class="img-preview-portrait">
          <?php } ?>
          <input type="file" name="file_ijazah" class="form-control mt-1">
        </div>
        <div class="col-md-3">
          <label class="form-label">Foto</label>
          <?php if(!empty($data['file_foto'])) { ?>
          <img src="upload_dokumen/<?php echo htmlspecialchars($data['file_foto']); ?>" class="img-preview-portrait">
          <?php } ?>
          <input type="file" name="file_foto" class="form-control mt-1">
        </div>
      </div>

      <button type="submit" name="simpan_edit" class="btn btn-primary w-100 mt-4">Simpan Perubahan</button>

    </form>
    <?php } ?>

  </div>
</div>
</div>

</body>
</html>
