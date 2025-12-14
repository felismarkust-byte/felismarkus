<?php
include 'koneksi.php';
session_start();

$pesan = '';

if (isset($_POST['login'])) {
    $username = mysqli_real_escape_string($conn, $_POST['username']);
    $password = mysqli_real_escape_string($conn, $_POST['password']); // ambil password input

    $sql = "SELECT * FROM akun_mahasiswa WHERE username = '$username'";
    $query = mysqli_query($conn, $sql);

    if (mysqli_num_rows($query) > 0) {
        $data = mysqli_fetch_assoc($query);
        
        // Perbandingan password biasa
        if ($password == $data['password']) {
            $_SESSION['id_mahasiswa'] = $data['id_mahasiswa'];
            $_SESSION['username'] = $data['username'];

            // update waktu login terakhir
            mysqli_query($conn, "UPDATE akun_mahasiswa SET terakhir_login = NOW() WHERE id_akun = {$data['id_akun']}");

            header("Location: dashboard_mahasiswa.php");
            exit;
        } else {
            $pesan = "<div class='alert alert-danger mt-3'>Password salah!</div>";
        }
    } else {
        $pesan = "<div class='alert alert-danger mt-3'>Username tidak ditemukan!</div>";
    }
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Login Mahasiswa | AMIK Universal</title>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
<style>
    body {
        background: linear-gradient(135deg, #007bff, #6610f2);
        height: 100vh;
        display: flex;
        align-items: center;
        justify-content: center;
        font-family: 'Poppins', sans-serif;
    }
    .card {
        width: 400px;
        border: none;
        border-radius: 15px;
        box-shadow: 0 6px 20px rgba(0,0,0,0.2);
    }
    .card-body {
        padding: 2rem;
    }
    .btn-register {
        background-color: #28a745;
        color: white;
        border: none;
    }
    .btn-register:hover {
        background-color: #218838;
    }
    .btn-home {
        margin-bottom: 10px;
    }
</style>
</head>
<body>

<div class="card">
  <div class="card-body">
    <h4 class="text-center text-primary fw-bold mb-4">Login Mahasiswa</h4>

    <?php if (!empty($pesan)) echo $pesan; ?>

    <form method="POST" action="">
      <div class="mb-3">
        <label>Username</label>
        <input type="text" name="username" class="form-control" required>
      </div>
      <div class="mb-3">
        <label>Password</label>
        <input type="password" name="password" class="form-control" required>
      </div>
      <button type="submit" name="login" class="btn btn-primary w-100 mb-2">Masuk</button>
    </form>

    <a href="home.php" class="btn btn-secondary w-100 btn-home"><i class="bi bi-house-door"></i> Kembali ke Beranda</a>
    <a href="register_mahasiswa.php" class="btn btn-register w-100"><i class="bi bi-person-plus-fill"></i> Daftar Akun Baru</a>
  </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
