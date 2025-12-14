<?php
// create_admin.php
// Pastikan file koneksi 'koneksi.php' ada dan mendefinisikan $conn (mysqli)
include 'koneksi.php';
session_start();

$msg = '';

// pastikan koneksi tersedia
if (!isset($conn) || !$conn) {
    die('<div style="color:red;padding:20px;">Database connection not found. Periksa file koneksi.php.</div>');
}

// buat tabel admin jika belum ada (charset utf8 agar kompatibel MySQL lama)
$create_table_sql = "
CREATE TABLE IF NOT EXISTS admin (
  id_admin INT AUTO_INCREMENT PRIMARY KEY,
  username VARCHAR(100) NOT NULL UNIQUE,
  password VARCHAR(255) NOT NULL,
  nama VARCHAR(150) DEFAULT NULL,
  terakhir_login DATETIME DEFAULT NULL,
  created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
";
if (!mysqli_query($conn, $create_table_sql)) {
    die('<div class="alert alert-danger">Gagal membuat/cek tabel admin: ' . htmlspecialchars(mysqli_error($conn)) . '</div>');
}

// proses form submit
if (isset($_POST['create'])) {
    $username = trim(mysqli_real_escape_string($conn, $_POST['username']));
    $nama = trim(mysqli_real_escape_string($conn, $_POST['nama']));
    $password_raw = $_POST['password'];

    if ($username === '' || $password_raw === '') {
        $msg = '<div class="alert alert-danger">Isi username & password.</div>';
    } else {
        // gunakan password_hash bila tersedia (PHP >= 5.5). Jika tidak ada, fallback ke md5 (kurang aman).
        if (function_exists('password_hash')) {
            $hash = password_hash($password_raw, PASSWORD_DEFAULT);
        } else {
            // fallback (tidak direkomendasikan — segera upgrade PHP)
            $hash = md5($password_raw);
        }

        // prepared statement untuk insert
        $stmt = mysqli_prepare($conn, "INSERT INTO admin (username, password, nama) VALUES (?, ?, ?)");
        if ($stmt === false) {
            $msg = '<div class="alert alert-danger">Gagal menyiapkan query: ' . htmlspecialchars(mysqli_error($conn)) . '</div>';
        } else {
            mysqli_stmt_bind_param($stmt, "sss", $username, $hash, $nama);
            $ok = mysqli_stmt_execute($stmt);

            if ($ok) {
                $msg = '<div class="alert alert-success">Admin berhasil dibuat. Username: <b>' . htmlspecialchars($username) . '</b></div>';
                // opsional: redirect setelah beberapa detik (uncomment jika mau)
                // header("Refresh:2; url=login_admin.php");
            } else {
                // cek apakah duplicate username
                $errno = mysqli_stmt_errno($stmt);
                if ($errno == 1062) { // duplicate entry
                    $msg = '<div class="alert alert-danger">Gagal: username sudah ada.</div>';
                } else {
                    $msg = '<div class="alert alert-danger">Gagal membuat admin: ' . htmlspecialchars(mysqli_stmt_error($stmt)) . '</div>';
                }
            }
            mysqli_stmt_close($stmt);
        }
    }
}
?>
<!doctype html>
<html>
<head>
  <meta charset="utf-8">
  <title>Create Admin</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
  <style>
    body { background:#f8f9fa; font-family:Poppins, sans-serif; }
    .card { max-width:640px; margin:50px auto; border-radius:12px; box-shadow:0 8px 30px rgba(0,0,0,0.06); }
  </style>
</head>
<body>
  <div class="card p-4">
    <h3>Create Admin Account</h3>
    <?php if($msg) echo $msg; ?>
    <form method="POST" action="">
      <div class="row">
        <div class="col-md-6 mb-3">
          <label class="form-label">Username</label>
          <input name="username" class="form-control" required>
        </div>
        <div class="col-md-6 mb-3">
          <label class="form-label">Nama (optional)</label>
          <input name="nama" class="form-control">
        </div>
      </div>
      <div class="mb-3">
        <label class="form-label">Password</label>
        <input name="password" type="password" class="form-control" required>
      </div>
      <div class="d-flex">
        <button name="create" class="btn btn-primary">Create Admin</button>
        <a href="home.php" class="btn btn-secondary ms-2">Back</a>
      </div>
    </form>
    <hr>
    <p class="text-muted small">Setelah admin dibuat, <strong>hapus</strong> file ini dari server untuk keamanan. Jika Anda menggunakan PHP lama dan mendapatkan hash md5 (fallback), segera <em>upgrade PHP</em> dan buat ulang admin menggunakan password_hash.</p>
  </div>
</body>
</html>
