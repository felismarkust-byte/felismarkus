<?php
include 'koneksi.php';
session_start();

$pesan = '';

if (isset($_POST['login'])) {
    $username = mysqli_real_escape_string($conn, $_POST['username']);
    $password = mysqli_real_escape_string($conn, $_POST['password']);

    $sql = "SELECT * FROM akun_mahasiswa WHERE username = '$username'";
    $query = mysqli_query($conn, $sql);

    if ($query && mysqli_num_rows($query) > 0) {
        $data = mysqli_fetch_assoc($query);

        if ($password == $data['password']) {
            unset($_SESSION['admin_id']);
            unset($_SESSION['admin_username']);

            $_SESSION['id_mahasiswa'] = $data['id_mahasiswa'];
            $_SESSION['username_mahasiswa'] = $data['username'];

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
<link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700&display=swap" rel="stylesheet">

<style>
body {
    font-family: 'Poppins', sans-serif;
    background: linear-gradient(135deg, #000000, #1a1a1a, #222, #3a3a3a);
    height: 100vh;
    display: flex;
    align-items: center;
    justify-content: center;
    padding: 20px;
}

/* Card glass effect */
.card {
    width: 420px;
    border: none;
    border-radius: 16px;
    padding: 25px;
    background: rgba(255, 255, 255, 0.08);
    backdrop-filter: blur(12px);
    -webkit-backdrop-filter: blur(12px);
    box-shadow: 0 10px 30px rgba(0,0,0,0.3);
    color: #fff;
}

/* Title */
.card h4 {
    color: #ffdd59;
    font-weight: 700;
    text-shadow: 1px 1px 8px rgba(0,0,0,0.4);
}

/* Input fields */
.form-control {
    background: rgba(255,255,255,0.15);
    border: none;
    padding: 11px 14px;
    color: #fff;
    border-radius: 10px;
}

.form-control::placeholder {
    color: #ddd;
}

.form-control:focus {
    background: rgba(255,255,255,0.2);
    color: #fff;
    border: 1px solid #ffdd59;
    box-shadow: none;
}

/* Buttons */
.btn-primary {
    background: #ffdd59;
    border: none;
    color: #000;
    padding: 10px;
    font-weight: 600;
    border-radius: 10px;
}

.btn-primary:hover {
    background: #e6c84e;
}

.btn-secondary {
    border-radius: 10px;
}

.btn-register {
    background: #28a745;
    color: white;
    border-radius: 10px;
}

.btn-register:hover {
    background: #1f7c38;
}

/* Alert */
.alert {
    border-radius: 10px;
    font-size: 14px;
}

label {
    font-weight: 500;
}
</style>
</head>
<body>

<div class="card" data-aos="zoom-in">
    <div class="card-body">
        <h4 class="text-center mb-4">Login Mahasiswa</h4>

        <?php if (!empty($pesan)) echo $pesan; ?>

        <form method="POST" action="">
            <div class="mb-3">
                <label>Username</label>
                <input type="text" name="username" class="form-control" placeholder="Masukkan username" required>
            </div>

            <div class="mb-3">
                <label>Password</label>
                <input type="password" name="password" class="form-control" placeholder="Masukkan password" required>
            </div>

            <button type="submit" name="login" class="btn btn-primary w-100 mt-2">Masuk</button>

            <a href="home.php" class="btn btn-secondary w-100 mt-3">Kembali ke Beranda</a>

            <a href="register_akun.php" class="btn btn-register w-100 mt-2">Daftar Akun Baru</a>
        </form>
    </div>
</div>

</body>
</html>
