<?php
// koneksi.php
$host = "localhost";       // biasanya "localhost"
$user = "root";            // username default XAMPP
$pass = "";                // kosong jika belum diubah
$db   = "db_pendaftaran_amikuniversal";  // nama database kamu

// Membuat koneksi
$conn = mysqli_connect($host, $user, $pass, $db);

// Cek koneksi
if (!$conn) {
    die("Koneksi gagal: " . mysqli_connect_error());
}
// echo "Koneksi berhasil"; // bisa diaktifkan untuk tes
?>
