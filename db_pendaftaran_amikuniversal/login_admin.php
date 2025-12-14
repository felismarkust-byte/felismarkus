<?php
include 'koneksi.php';
session_start();

$pesan = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['login'])) {
    $username = isset($_POST['username']) ? trim($_POST['username']) : '';
    $password = isset($_POST['password']) ? $_POST['password'] : '';

    if ($username === '' || $password === '') {
        $pesan = '<div class="alert alert-danger text-center">Isi username & password.</div>';
    } else {
        $stmt = mysqli_prepare($conn, "SELECT id_admin, username, password, nama FROM admin WHERE username = ?");
        if ($stmt) {
            mysqli_stmt_bind_param($stmt, "s", $username);
            mysqli_stmt_execute($stmt);
            mysqli_stmt_store_result($stmt);

            if (mysqli_stmt_num_rows($stmt) === 0) {
                $pesan = '<div class="alert alert-danger text-center">Username tidak ditemukan.</div>';
                mysqli_stmt_close($stmt);
            } else {
                mysqli_stmt_bind_result($stmt, $id_admin, $db_username, $db_password, $db_nama);
                mysqli_stmt_fetch($stmt);

                $login_ok = false;
                if (function_exists('password_verify')) {
                    if (password_verify($password, $db_password)) $login_ok = true;
                }
                if (!$login_ok) {
                    if ($password === $db_password || md5($password) === $db_password) $login_ok = true;
                }

                if ($login_ok) {
                    $_SESSION['admin_id'] = $id_admin;
                    $_SESSION['admin_username'] = $db_username;
                    $_SESSION['admin_nama'] = $db_nama;

                    $upd = mysqli_prepare($conn, "UPDATE admin SET terakhir_login = NOW() WHERE id_admin = ?");
                    if ($upd) {
                        mysqli_stmt_bind_param($upd, "i", $id_admin);
                        mysqli_stmt_execute($upd);
                        mysqli_stmt_close($upd);
                    }

                    mysqli_stmt_close($stmt);
                    header("Location: admin_dashboard.php");
                    exit;
                } else {
                    $pesan = '<div class="alert alert-danger text-center">Password salah.</div>';
                    mysqli_stmt_close($stmt);
                }
            }
        } else {
            $pesan = '<div class="alert alert-danger">Kesalahan query: ' . htmlspecialchars(mysqli_error($conn)) . '</div>';
        }
    }
}
?>
<!doctype html>
<html lang="id">
<head>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<title>Login Admin | AMIK Universal</title>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
<link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet">

<style>
    body {
        height: 100vh;
        margin: 0;
        font-family: 'Poppins', sans-serif;
        display: flex;
        justify-content: center;
        align-items: center;
        background: radial-gradient(circle at top, #4c8cff, #001f4d);
        background-size: cover;
    }

    /* Glass Card */
    .login-card {
        width: 380px;
        padding: 35px;
        border-radius: 18px;
        backdrop-filter: blur(18px);
        background: rgba(255, 255, 255, 0.15);
        border: 1px solid rgba(255, 255, 255, 0.3);
        box-shadow: 0 8px 40px rgba(0,0,0,0.25);
        animation: zoomIn 0.7s ease;
        color: white;
    }

    @keyframes zoomIn {
        from { opacity: 0; transform: scale(0.85); }
        to { opacity: 1; transform: scale(1); }
    }

    .login-card h4 {
        font-weight: 700;
        text-align: center;
        color: #fff;
        margin-bottom: 25px;
    }

    /* Input Glow */
    .form-control {
        background: rgba(255,255,255,0.8);
        border: none;
        border-radius: 12px;
        padding: 12px;
        transition: 0.3s;
    }

    .form-control:focus {
        box-shadow: 0 0 12px #82b1ff;
        border: none;
    }

    /* Button */
    .btn-login {
        background: #005eff;
        color: white;
        border-radius: 12px;
        padding: 10px;
        font-weight: 600;
        transition: 0.3s;
    }

    .btn-login:hover {
        background: #003b99;
        transform: scale(1.03);
    }

    .btn-home {
        border-radius: 12px;
        background: rgba(255,255,255,0.3);
        color: white !important;
        transition: 0.3s;
    }

    .btn-home:hover {
        background: rgba(255,255,255,0.5);
    }

    .admin-icon {
        font-size: 45px;
        display: block;
        text-align: center;
        margin-bottom: 10px;
        color: #fff;
    }
</style>
</head>

<body>

<div class="login-card">

    <i class="bi bi-person-lock admin-icon"></i>

    <h4>Login Administrator</h4>

    <div class="text-center mb-3">
        <a href="home.php" class="btn btn-home px-4 py-2">
            <i class="bi bi-house"></i> Home
        </a>
    </div>

    <?php if ($pesan) echo $pesan; ?>

    <form method="POST">
        <div class="mb-3">
            <label>Username</label>
            <input type="text" name="username" value="<?php echo isset($username)?htmlspecialchars($username):''; ?>" class="form-control" required>
        </div>

        <div class="mb-3">
            <label>Password</label>
            <input type="password" name="password" class="form-control" required>
        </div>

        <button name="login" class="btn btn-login w-100 mt-2">
            <i class="bi bi-box-arrow-in-right"></i> Masuk
        </button>
    </form>

</div>

</body>
</html>
