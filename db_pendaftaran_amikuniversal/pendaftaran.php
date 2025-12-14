<?php
include 'koneksi.php';
session_start();

// Pastikan mahasiswa sudah login
if (!isset($_SESSION['id_mahasiswa'])) {
    header("Location: login_mahasiswa.php?pesan=login_dulu");
    exit();
}

$pesan = "";

// Proses jika form disubmit
if (isset($_POST['daftar'])) {
    // Ambil dan sanitasi data input
    $nama     = trim(mysqli_real_escape_string($conn, $_POST['nama_lengkap']));
    $tempat   = trim(mysqli_real_escape_string($conn, $_POST['tempat_lahir']));
    $tanggal  = trim($_POST['tanggal_lahir']);
    $jk       = trim($_POST['jenis_kelamin']);
    $alamat   = trim(mysqli_real_escape_string($conn, $_POST['alamat']));
    $nohp     = trim(mysqli_real_escape_string($conn, $_POST['no_hp']));
    $email    = trim(mysqli_real_escape_string($conn, $_POST['email']));
    $prodi    = trim(mysqli_real_escape_string($conn, $_POST['id_prodi']));

    if ($nama != "" && $tempat != "" && $tanggal != "" && $jk != "" && $alamat != "" && $nohp != "" && $email != "" && $prodi != "") {
        $sql = "INSERT INTO mahasiswa 
                (nama_lengkap, tempat_lahir, tanggal_lahir, jenis_kelamin, alamat, no_hp, email, id_prodi, tanggal_daftar, status_pendaftaran)
                VALUES ('$nama', '$tempat', '$tanggal', '$jk', '$alamat', '$nohp', '$email', '$prodi', NOW(), 'Menunggu')";
        $query = mysqli_query($conn, $sql);

        if ($query) {
            $pesan = "<div class='alert alert-success mt-3'>✅ Pendaftaran berhasil! Tunggu verifikasi admin.</div>";
        } else {
            $pesan = "<div class='alert alert-danger mt-3'>❌ Gagal menyimpan ke database: " . htmlspecialchars(mysqli_error($conn)) . "</div>";
        }
    } else {
        $pesan = "<div class='alert alert-warning mt-3'>⚠️ Harap isi semua data dengan lengkap!</div>";
    }
}

// Ambil data program studi
$prodi_list = mysqli_query($conn, "SELECT id_prodi, nama_prodi, jenjang FROM program_studi ORDER BY nama_prodi ASC");
?>
<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Pendaftaran Mahasiswa Baru | AMIK Universal Medan</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
  <style>
    body { background-color: #f4f6f9; font-family: 'Poppins', sans-serif; }
    .card { max-width: 600px; margin: 50px auto; border-radius: 10px; box-shadow: 0 4px 20px rgba(0,0,0,0.1); }
    .btn-primary { background-color: #007bff; border: none; }
    .btn-primary:hover { background-color: #0056b3; }
  </style>
</head>
<body>

<div class="container">
  <div class="card p-4">
    <h3 class="text-center text-primary mb-3">Formulir Pendaftaran Mahasiswa Baru</h3>

    <?php if ($pesan != "") echo $pesan; ?>

    <form method="POST" action="">
      <div class="mb-3">
        <label>Nama Lengkap</label>
        <input type="text" name="nama_lengkap" class="form-control" required>
      </div>

      <div class="mb-3">
        <label>Tempat Lahir</label>
        <input type="text" name="tempat_lahir" class="form-control" required>
      </div>

      <div class="mb-3">
        <label>Tanggal Lahir</label>
        <input type="date" name="tanggal_lahir" class="form-control" required>
      </div>

      <div class="mb-3">
        <label>Jenis Kelamin</label>
        <select name="jenis_kelamin" class="form-select" required>
          <option value="">-- Pilih --</option>
          <option value="Laki-laki">Laki-laki</option>
          <option value="Perempuan">Perempuan</option>
        </select>
      </div>

      <div class="mb-3">
        <label>Alamat</label>
        <textarea name="alamat" class="form-control" rows="3" required></textarea>
      </div>

      <div class="mb-3">
        <label>No HP</label>
        <input type="text" name="no_hp" class="form-control" required>
      </div>

      <div class="mb-3">
        <label>Email</label>
        <input type="email" name="email" class="form-control" required>
      </div>

      <div class="mb-3">
        <label>Program Studi</label>
        <select name="id_prodi" class="form-select" required>
          <option value="">-- Pilih Program Studi --</option>
          <?php while ($p = mysqli_fetch_assoc($prodi_list)) { ?>
            <option value="<?php echo $p['id_prodi']; ?>"><?php echo $p['jenjang'] . " - " . $p['nama_prodi']; ?></option>
          <?php } ?>
        </select>
      </div>

      <button type="submit" name="daftar" class="btn btn-primary w-100">Daftar Sekarang</button>
    </form>
  </div>
</div>

</body>
</html>
