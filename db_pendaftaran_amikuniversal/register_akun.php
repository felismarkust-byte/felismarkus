<?php
include 'koneksi.php';
session_start();

// Inisialisasi variabel pesan
$pesan = "";

// Jika tombol register ditekan
if (isset($_POST['register'])) {
    $nama_lengkap = mysqli_real_escape_string($conn, $_POST['nama_lengkap']);
    $username     = mysqli_real_escape_string($conn, $_POST['username']);
    $password     = mysqli_real_escape_string($conn, $_POST['password']);
    $email        = mysqli_real_escape_string($conn, $_POST['email']);

    // Cek apakah username sudah dipakai
    $cek = mysqli_query($conn, "SELECT * FROM akun_mahasiswa WHERE username = '$username'");

    if (mysqli_num_rows($cek) > 0) {
        $pesan = "<div class='alert alert-danger mt-3'>❌ Username sudah digunakan! Silakan login <a href='login_mahasiswa.php'>di sini</a>.</div>";
    } else {
        // Simpan data mahasiswa terlebih dahulu
        $insert_mhs = mysqli_query($conn, "INSERT INTO mahasiswa (nama_lengkap, email, status_pendaftaran, tanggal_daftar)
                                           VALUES ('$nama_lengkap', '$email', 'Belum Diverifikasi', NOW())");

        if ($insert_mhs) {
            // Ambil id_mahasiswa terakhir
            $id_mahasiswa = mysqli_insert_id($conn);

            // Simpan akun login (tanpa hash agar sesuai XAMPP lama)
            $insert_akun = mysqli_query($conn, "INSERT INTO akun_mahasiswa (id_mahasiswa, username, password, terakhir_login)
                                                VALUES ('$id_mahasiswa', '$username', '$password', NULL)");

            if ($insert_akun) {
                $pesan = "<div class='alert alert-success mt-3'>✅ Registrasi berhasil! Silakan <a href='login_mahasiswa.php'>Login di sini</a>.</div>";
            } else {
                $pesan = "<div class='alert alert-danger mt-3'>❌ Terjadi kesalahan saat menyimpan akun mahasiswa.</div>";
            }
        } else {
            $pesan = "<div class='alert alert-danger mt-3'>❌ Terjadi kesalahan saat menyimpan data mahasiswa.</div>";
        }
    }
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Registrasi Mahasiswa | AMIK Universal</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">

  <style>
    body {
      font-family: 'Poppins', sans-serif;
      background: linear-gradient(135deg, #6f42c1, #0d6efd);
      height: 100vh;
      margin: 0;
      display: flex;
      align-items: center;
      justify-content: center;
    }

    .card {
      width: 430px;
      border-radius: 20px;
      padding: 2rem;
      background: rgba(255, 255, 255, 0.15);
      backdrop-filter: blur(12px);
      border: 1px solid rgba(255, 255, 255, 0.25);
      box-shadow: 0 8px 25px rgba(0, 0, 0, 0.25);
      color: #fff;
      animation: fadeIn 0.9s ease-in-out;
    }

    h4 {
      font-weight: 700;
      text-shadow: 0 2px 4px rgba(0,0,0,0.3);
      margin-bottom: 1.5rem;
    }

    label {
      font-weight: 500;
    }

    .form-control {
      border-radius: 10px;
      padding: 0.75rem 1rem;
      border: none;
      transition: 0.3s;
    }

    .form-control:focus {
      box-shadow: 0 0 0 3px rgba(255,255,255,0.5);
      background-color: #f8f9fa;
    }

    .btn-success, .btn-secondary {
      padding: 0.75rem;
      border-radius: 12px;
      font-weight: 600;
      transition: 0.3s;
    }

    .btn-success:hover {
      transform: scale(1.03);
      background: #198754;
    }

    .btn-secondary:hover {
      transform: scale(1.03);
    }

    .alert {
      border-radius: 12px;
      font-size: 0.9rem;
      padding: 0.75rem 1rem;
    }

    @keyframes fadeIn {
      from { opacity: 0; transform: translateY(20px); }
      to   { opacity: 1; transform: translateY(0); }
    }
  </style>
</head>
<body>

<div class="card">
  <h4 class="text-center">Registrasi Mahasiswa Baru</h4>

  <?php if (!empty($pesan)) echo $pesan; ?>

  <form method="POST" action="">
    <div class="mb-3">
      <label>Nama Lengkap</label>
      <input type="text" name="nama_lengkap" class="form-control" required>
    </div>

    <div class="mb-3">
      <label>Alamat Email</label>
      <input type="email" name="email" class="form-control" required>
    </div>

    <div class="mb-3">
      <label>Username</label>
      <input type="text" name="username" class="form-control" required>
    </div>

    <div class="mb-3">
      <label>Password</label>
      <input type="password" name="password" class="form-control" required>
    </div>

    <button type="submit" name="register" class="btn btn-success w-100">Daftar Sekarang</button>
    <a href="login_mahasiswa.php" class="btn btn-secondary w-100 mt-2">Sudah punya akun? Login</a>
  </form>
</div>

</body>
</html>
