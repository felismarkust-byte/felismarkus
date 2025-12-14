<?php
include 'koneksi.php';
session_start();
?>
<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Information | AMIK Universal</title>

<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
<link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700&display=swap" rel="stylesheet">
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css">
<link href="https://unpkg.com/aos@2.3.1/dist/aos.css" rel="stylesheet">

<style>
  body {
    font-family: 'Poppins', sans-serif;
    background: #f0f2f5;
    transition: 0.5s;
  }

  body.dark-mode {
      background: #1a1a1a;
      color: #eaeaea;
  }

  /* DARK MODE CARD */
  body.dark-mode .info-card {
      background: #2b2b2b;
      color: #fff;
      box-shadow: 0 6px 20px rgba(255,255,255,0.05);
  }

  body.dark-mode .info-title {
      color: #ffdd59 !important;
  }

  /* HEADER / HERO */
  .info-hero {
      position: relative;
      background: url('assets/img/kampus_bg.jpg') center/cover no-repeat;
      padding: 100px 0;
      text-align: center;
      color: #fff;
      border-radius: 16px;
      overflow: hidden;
  }

  .info-hero::after {
      content: "";
      position: absolute;
      top:0; left:0;
      width:100%; height:100%;
      background: rgba(0,0,0,0.6);
  }

  .info-hero .content {
      position: relative;
      z-index: 2;
  }

  .info-hero h1 {
      font-size: 2.8rem;
      font-weight: 700;
      text-shadow: 3px 3px 18px rgba(0,0,0,0.7);
  }

  /* CARD MODERN */
  .info-card {
    border: 0;
    border-radius: 16px;
    padding: 20px;
    background: #fff;
    box-shadow: 0 6px 24px rgba(0,0,0,0.10);
    transition: 0.3s;
  }

  .info-card:hover {
    transform: translateY(-8px);
    box-shadow: 0 14px 32px rgba(0,0,0,0.16);
  }

  .text-primary {
    color: #ffdd59 !important;
  }

  /* DARK MODE TOGGLE */
  .toggle-btn {
      position: fixed;
      bottom: 20px;
      right: 20px;
      background: #000;
      color: #fff;
      border-radius: 50%;
      width: 55px;
      height: 55px;
      display: flex;
      justify-content: center;
      align-items: center;
      cursor: pointer;
      font-size: 22px;
      z-index: 999;
      transition: .3s;
  }

  body.dark-mode .toggle-btn {
      background: #ffdd59;
      color: #000;
  }

</style>
</head>
<body>

<!-- DARK MODE BUTTON -->
<div class="toggle-btn" onclick="toggleDarkMode()">
    <i class="bi bi-moon-stars"></i>
</div>

<div class="container mt-4 mb-5">

    <!-- HEADER -->
    <div class="info-hero mb-5" data-aos="fade-up">
        <div class="content">
            <h1>Informasi Kampus</h1>
            <p class="mt-2">Informasi Resmi – AMIK Universal Medan</p>
        </div>
    </div>

    <!-- CONTENT -->
    <div class="row g-4">

        <div class="col-md-6" data-aos="fade-right">
            <div class="info-card h-100">
                <h4 class="info-title text-primary"><i class="bi bi-building"></i> Tentang Kampus</h4>
                <p>
                    AMIK Universal adalah perguruan tinggi vokasi di bidang Teknologi Informasi 
                    yang membentuk lulusan profesional dan siap kerja.
                </p>
            </div>
        </div>

        <div class="col-md-6" data-aos="fade-left">
            <div class="info-card h-100">
                <h4 class="info-title text-primary"><i class="bi bi-journal-text"></i> Info Pendaftaran</h4>
                <ul>
                    <li>Pendaftaran dilakukan setelah login mahasiswa.</li>
                    <li>Menyiapkan email, nomor HP, alamat, dan pilihan prodi.</li>
                    <li>Status diverifikasi oleh admin.</li>
                    <li>Jika data kurang, status “Belum Lengkap”.</li>
                </ul>
            </div>
        </div>

        <div class="col-md-4" data-aos="zoom-in">
            <div class="info-card h-100">
                <h5 class="info-title text-primary"><i class="bi bi-star"></i> Visi Kampus</h5>
                <p>Menjadi institusi pendidikan IT unggul, inovatif, dan profesional.</p>
            </div>
        </div>

        <div class="col-md-4" data-aos="zoom-in" data-aos-delay="150">
            <div class="info-card h-100">
                <h5 class="info-title text-primary"><i class="bi bi-lightbulb"></i> Misi Kampus</h5>
                <p>Menyelenggarakan pendidikan berbasis praktik dan kreativitas teknologi.</p>
            </div>
        </div>

        <div class="col-md-4" data-aos="zoom-in" data-aos-delay="300">
            <div class="info-card h-100">
                <h5 class="info-title text-primary"><i class="bi bi-gear"></i> Fasilitas</h5>
                <ul>
                    <li>Lab Komputer Modern</li>
                    <li>Program Sertifikasi</li>
                    <li>Magang Industri</li>
                </ul>
            </div>
        </div>

    </div>

    <!-- BUTTON BACK -->
    <div class="text-center mt-5" data-aos="fade-up">
        <a href="home.php" class="btn btn-dark btn-lg px-4">
            <i class="bi bi-arrow-left"></i> Kembali ke Home
        </a>
    </div>

</div>

<!-- AOS + DARK MODE -->
<script src="https://unpkg.com/aos@2.3.1/dist/aos.js"></script>
<script>
AOS.init({
    duration: 900,
    once: true,
    offset: 120,
    easing: 'ease-out'
});

function toggleDarkMode() {
    document.body.classList.toggle('dark-mode');
}
</script>

</body>
</html>
