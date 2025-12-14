<?php
include 'koneksi.php';
session_start();
?>

<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="utf-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1" />
  <title>Home | AMIK Universal Student Registration</title>

  <!-- Bootstrap & fonts & icons -->
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
  <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700&display=swap" rel="stylesheet">
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css">

  <style>
  :root{
    --sidebar-width:230px;
    --sidebar-collapsed:70px;
    --accent: #ffdd59;
    --glass-bg: rgba(255,255,255,0.04);
  }
  html,body{height:100%;}
  body{
    font-family:'Poppins',sans-serif;
    margin:0;
    background:#f0f2f5;
    -webkit-font-smoothing:antialiased;
    -moz-osx-font-smoothing:grayscale;
    transition: margin-left .45s ease;
    margin-left: var(--sidebar-width);
  }

  /* ================= SIDEBAR ================= */
  .sidebar-left{
    position:fixed;
    top:0; left:0;
    height:100vh;
    width:var(--sidebar-width);
    padding:28px 16px;
    display:flex;
    flex-direction:column;
    align-items:center;
    gap:12px;
    background: rgba(0,0,0,0.6);
    backdrop-filter: blur(8px);
    color:#fff;
    z-index:1200;
    box-shadow: 0 8px 30px rgba(2,6,23,0.12);
    transform: translateZ(0);
  }
  .sidebar-left.collapsed{ width: var(--sidebar-collapsed); }

  .sidebar-logo{ width:64px; height:64px; border-radius:50%; object-fit:cover; border:2px solid rgba(255,255,255,0.12); transition:all .32s ease; }
  .sidebar-left.collapsed .sidebar-logo{ width:44px; height:44px; }

  .sidebar-text{ font-weight:700; letter-spacing:.3px; transition:opacity .25s, transform .25s; }
  .sidebar-left.collapsed .sidebar-text{ opacity:0; transform:translateX(-6px); pointer-events:none; }

  .nav-link{ color:#fff !important; display:flex; align-items:center; gap:10px; padding:10px 12px; border-radius:8px; transition:all .22s ease; }
  .nav-link i{ min-width:22px; text-align:center; font-size:18px; }
  .nav-link:hover{ transform:translateX(6px); background: rgba(255,255,255,0.02); color:var(--accent) !important; }

  .btn-register{ margin-top:auto; border-radius:999px; padding:10px 18px; border:1px solid rgba(255,255,255,0.14); color:#fff; background:transparent; transition:all .25s; }
  .btn-register:hover{ background:#fff; color:#000; }

  .toggle-btn{
    position:absolute; right:-44px; top:18px; background:#000; color:#fff; padding:8px 10px; border-radius:8px; box-shadow:0 8px 20px rgba(2,6,23,0.15); cursor:pointer;
  }

  /* ====== HERO multi-layer parallax ====== */
  .hero{
    position:relative;
    height:80vh;
    min-height:520px;
    overflow:hidden;
    display:flex;
    align-items:center;
    justify-content:center;
    color:#111418;
    background:#F5F7FA;
  }
  /* layers absolute */
  .hero .layer{
    position:absolute; inset:0; background-position:center; background-size:cover; will-change:transform, opacity;
  }
  .layer.layer-back{ transform:scale(1.12); opacity:0.55; filter:blur(2px); }
  .layer.layer-mid{ transform:scale(1.03); opacity:0.75; }
  .layer.layer-front{ opacity:0.95; }

  .hero-overlay{ position:absolute; inset:0; background:linear-gradient(180deg, rgba(0,0,0,0.35), rgba(0,0,0,0.5)); z-index:1; }
  .hero-inner{ position:relative; z-index:2; text-align:center; padding:0 20px; max-width:980px; }

  .hero h1{ font-size:clamp(2rem, 4.6vw, 3.2rem); font-weight:800; line-height:1.02; margin-bottom:12px; text-shadow:0 10px 30px rgba(0,0,0,0.6); }
  .hero p{ margin-bottom:18px; font-size:1.05rem; opacity:0.95; }

  /* magnetic button */
  .btn-magnetic{ display:inline-block; padding:12px 20px; border-radius:12px; background:linear-gradient(90deg,#111827,var(--accent)); color:#fff; border:none; cursor:pointer; box-shadow:0 12px 30px rgba(12,17,23,0.28); transform:translate3d(0,0,0); }

  /* ====== PROGRAM CARDS ====== */
  .program-card{ border-radius:12px; overflow:hidden; transition:transform .25s ease, box-shadow .28s ease; transform-style:preserve-3d; will-change:transform; background:#fff; }
  .program-card .card-body{ min-height:170px; }
  .program-card:hover{ transform:translateY(-12px) rotateX(0.5deg); box-shadow:0 28px 60px rgba(8,15,35,0.12); }

  /* tilt container */
  .tilt-wrap{ perspective:1200px; }

  /* fade-premium base */
  .fade-premium{ opacity:0; transform:translateY(40px); }

  /* footer */
  footer{ background:#000; color:#fff; padding:48px 0; opacity:0; transform:translateY(20px); }

  /* responsive */
  @media (max-width: 992px){
    body{ margin-left:0; }
    .sidebar-left{ position:relative; width:100%; height:auto; flex-direction:row; padding:12px; gap:10px; }
    .sidebar-left.collapsed{ width:100%; }
    .toggle-btn{ right:12px; top:12px; }
    .hero{ min-height:480px; }
  }

  /* accessibility */
  @media (prefers-reduced-motion: reduce){
    .btn-magnetic, .tilt-wrap, .program-card, .hero .layer { transition:none !important; animation:none !important; }
  }
  </style>
</head>

<body>

<!-- PRELOADER -->
<div id="preloader" aria-hidden="false" style="position:fixed;inset:0;background:#000;display:flex;align-items:center;justify-content:center;z-index:9999;">
  <div style="display:flex;flex-direction:column;align-items:center;gap:14px;color:#fff;">
    <img src="logo.jpg" alt="logo" style="width:68px;height:68px;border-radius:50%;object-fit:cover;border:2px solid rgba(255,255,255,0.12)">
    <div class="loader-ring" style="width:56px;height:56px;border-radius:50%;border:5px solid rgba(255,255,255,0.12);border-top-color:transparent;animation:spin 1s linear infinite;"></div>
  </div>
</div>

<!-- SIDEBAR -->
<div id="sidebar" class="sidebar-left" role="navigation" aria-label="Main sidebar">
  <div id="toggleBtn" class="toggle-btn" aria-hidden="false"><i class="bi bi-list" style="font-size:18px;"></i></div>

  <img src="logo.jpg" alt="Logo AMIK" class="sidebar-logo">

  <div class="sidebar-text">AMIK UNIVERSAL</div>

  <ul class="nav flex-column w-100 mt-2">
    <li class="nav-item"><a class="nav-link" href="home.php"><i class="bi bi-house"></i> <span class="sidebar-text">Home</span></a></li>
    <li class="nav-item"><a class="nav-link" href="informasi.php"><i class="bi bi-info-circle"></i> <span class="sidebar-text">Information</span></a></li>
    <li class="nav-item"><a class="nav-link" href="pendaftaran.php"><i class="bi bi-journal-text"></i> <span class="sidebar-text">Registration</span></a></li>
    <li class="nav-item"><a class="nav-link" href="login_mahasiswa.php"><i class="bi bi-person"></i> <span class="sidebar-text">Student Login</span></a></li>
    <li class="nav-item"><a class="nav-link" href="login_admin.php"><i class="bi bi-shield-lock"></i> <span class="sidebar-text">Admin</span></a></li>
  </ul>

  <a href="register_akun.php" class="btn btn-register btn-magnetic mt-3">Register Account</a>
</div>

<!-- HERO (Enhanced Multi-Layer Parallax) -->
<section class="hero" id="heroSection" aria-label="Hero">

  <!-- BACKGROUND LAYERS -->
  <div class="layer layer-white" style="background-image:url('assets/img/hero-back.jpg');"></div>
  <div class="layer layer-mid" style="background-image:url('assets/img/hero-mid.jpg');"></div>

  <!-- Soft campus silhouette -->
  <div class="layer layer-campus" style="background-image:url('assets/img/campus-soft.png'); opacity:0.35;"></div>

  <!-- Front texture -->
  <div class="layer layer-front" style="background-image:url('assets/img/hero-front.png'); mix-blend-mode:overlay; opacity:0.55;"></div>

  <!-- Gradient Overlay -->
  <div class="hero-overlay"></div>

  <!-- HERO CONTENT -->
  <div class="hero-inner">
    <h1 class="hero-title fade-stay">Selamat Datang di AMIK Universal</h1>

    <p class="hero-subtitle fade-stay">
      Inovasi, Teknologi, dan Pendidikan — Bersama membangun masa depan profesional.
    </p>

    <div class="d-flex justify-content-center gap-3 mt-3">

      <!-- Button: Mulai Pendaftaran -->
      <a href="pendaftaran.php"
         class="btn-magnetic btn-magnetic--primary btn-magnetic-primary fade-stay"
         style="text-decoration:none;">
        <span class="btn-magnetic-inner">Mulai Pendaftaran</span>
      </a>

      <!-- Button: Informasi -->
      <a href="informasi.php"
         class="btn btn-outline-light btn-magnetic fade-stay"
         style="border-radius:12px;">
        Informasi
      </a>

    </div>
  </div>

</section>




<!-- PROGRAM STUDI -->
<section class="container my-5" aria-label="Program Studi">
  <div class="text-center mb-5">
    <h2 class="fw-bold">Available Study Programs</h2>
    <p class="text-muted">Pilih program studi yang sesuai dengan bakat dan cita-cita Anda.</p>
  </div>

  <div class="row g-4">
    <?php
    // Pastikan koneksi di koneksi.php memberi variable $conn
    $query = "SELECT * FROM program_studi";
    $result = mysqli_query($conn, $query);

    if($result && mysqli_num_rows($result) > 0) {
      while ($row = mysqli_fetch_assoc($result)) {
        $id = intval($row['id_prodi']);
        $link = "detail_prodi.php?id_prodi={$id}";
        $nama = htmlspecialchars($row['nama_prodi']);
        $jenjang = htmlspecialchars($row['jenjang']);
        $deskripsi = htmlspecialchars($row['deskripsi']);
        echo "
        <div class='col-md-4'>
          <div class='tilt-wrap'>
            <a href='{$link}' class='card program-card fade-premium' style='text-decoration:none;'>
              <div class='card-body'>
                <h5 class='card-title'>{$nama}</h5>
                <p class='mb-1'><strong>Degree:</strong> {$jenjang}</p>
                <p class='text-muted'>{$deskripsi}</p>
              </div>
            </a>
          </div>
        </div>";
      }
    } else {
      echo "<div class='col-12'><p class='text-center text-muted'>No study program data available.</p></div>";
    }
    ?>
  </div>
</section>
<!-- Wave Footer -->
<div class="wave-footer">
  <svg viewBox="0 0 1200 120" preserveAspectRatio="none">
    <path class="wave-layer" d="M0,0 C300,110 900,-40 1200,60 L1200,120 L0,120 Z" fill="#000"></path>
    <path class="wave-layer" d="M0,20 C300,130 900,-20 1200,80 L1200,120 L0,120 Z" fill="#111"></path>
    <path class="wave-layer" d="M0,40 C300,150 900,0 1200,100 L1200,120 L0,120 Z" fill="#222"></path>
  </svg>
</div>

<!-- FOOTER -->
<footer role="contentinfo">
  <div class="container">
    <div class="row align-items-center">
      <div class="col-md-4 text-center text-md-start mb-3 mb-md-0">
        <img src="logo.jpg" alt="logo" style="width:60px;height:60px;border-radius:50%;object-fit:cover;">
        <div class="fw-bold mt-2">AMIK UNIVERSAL MEDAN</div>
		
      </div>
     <div class="col-md-8 text-md-end">
    <div class="fw-bold mb-2">Ikuti Kami</div>

    <!-- Facebook -->
    <a href="https://web.facebook.com/universalmedan" target="_blank" class="text-white me-3">
        <i class="bi bi-facebook"></i>
    </a>

    <!-- Instagram -->
    <a href="https://www.instagram.com/amikuniversalmedan/?hl=en" target="_blank" class="text-white me-3">
        <i class="bi bi-instagram"></i>
    </a>

    <!-- TikTok -->
    <a href="https://www.tiktok.com/@fellllllllllllll5/photo/7539918609221553413?lang=id-ID" target="_blank" class="text-white">
        <i class="bi bi-tiktok"></i>
    </a>
</div>

    </div>
    <div class="text-center mt-4">&copy; <?php echo date('Y'); ?> AMIK Universal Medan</div>
  </div>
</footer>

<!-- SCRIPTS: GSAP -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/gsap/3.12.2/gsap.min.js" integrity="" crossorigin="anonymous"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/gsap/3.12.2/ScrollTrigger.min.js" integrity="" crossorigin="anonymous"></script>

<script>
/* Utility: respect reduced motion */
const prefersReduced = window.matchMedia('(prefers-reduced-motion: reduce)').matches;

/* Preloader hide */
window.addEventListener('load', () => {
  // small delay for aesthetic
  setTimeout(() => {
    gsap.to('#preloader', { opacity:0, duration:0.7, onComplete: () => {
      const p = document.getElementById('preloader');
      if (p) p.style.display = 'none';
    }});
  }, 420);
});

/* GSAP registration */
if (!prefersReduced) {
  gsap.registerPlugin(ScrollTrigger);

  /* SIDEBAR ENTRY */
  gsap.from("#sidebar", { x:-80, opacity:0, duration:1.1, ease:"elastic.out(1,0.6)" });

  /* HERO: layered parallax subtle entrance */
  const heroTimeline = gsap.timeline();
  heroTimeline
    .from(".layer.layer-back", { y:40, scale:1.08, opacity:0.6, duration:1.2, ease:"power3.out" }, 0)
    .from(".layer.layer-mid", { y:30, scale:1.03, opacity:0.8, duration:1.1, ease:"power3.out" }, 0.08)
    .from(".layer.layer-front", { y:18, opacity:0.9, duration:1.0, ease:"power3.out" }, 0.14)
    .from(".hero-inner h1", { y:24, opacity:0, duration:0.95, ease:"power3.out" }, 0.28)
    .from(".hero-inner p", { y:18, opacity:0, duration:0.85, ease:"power3.out" }, 0.38)
    .from(".btn-magnetic", { y:18, opacity:0, duration:0.8, ease:"power3.out" }, 0.46);

  /* Parallax effect: mouse move (depth) */
  const hero = document.getElementById('heroSection');
  hero.addEventListener('mousemove', (e) => {
    const rect = hero.getBoundingClientRect();
    const px = (e.clientX - rect.left) / rect.width - 0.5;
    const py = (e.clientY - rect.top) / rect.height - 0.5;
    // subtle transform values
    gsap.to('.layer.layer-back', { x: px * 20, y: py * 10, duration: 1.6, ease: "power2.out" });
    gsap.to('.layer.layer-mid', { x: px * 36, y: py * 16, duration: 1.4, ease: "power2.out" });
    gsap.to('.layer.layer-front', { x: px * 56, y: py * 24, duration: 1.2, ease: "power2.out" });
  });

  /* Parallax on scroll: background position shift */
  window.addEventListener('scroll', () => {
    const sc = window.scrollY || window.pageYOffset;
    gsap.to('.layer.layer-back', { y: sc * 0.03, duration:0.8, ease:"none" });
    gsap.to('.layer.layer-mid', { y: sc * 0.06, duration:0.8, ease:"none" });
  });

  /* Magnetic button: follow cursor inside button */
  document.querySelectorAll('.btn-magnetic').forEach(btn => {
    btn.addEventListener('mousemove', (e) => {
      const rect = btn.getBoundingClientRect();
      const relX = e.clientX - rect.left - rect.width/2;
      const relY = e.clientY - rect.top - rect.height/2;
      gsap.to(btn, { x: relX * 0.15, y: relY * 0.12, scale:1.02, duration:0.35, ease:"power3.out" });
    });
    btn.addEventListener('mouseleave', () => {
      gsap.to(btn, { x:0, y:0, scale:1, duration:0.45, ease:"power3.out" });
    });
  });

  /* 3D Tilt on cards */
  document.querySelectorAll('.tilt-wrap').forEach((wrap) => {
    const card = wrap.querySelector('.program-card');
    wrap.addEventListener('mousemove', (e) => {
      const rect = wrap.getBoundingClientRect();
      const x = (e.clientX - rect.left) / rect.width * 2 - 1; // -1 .. 1
      const y = (e.clientY - rect.top) / rect.height * 2 - 1;
      gsap.to(card, { rotationY: x * 8, rotationX: -y * 8, transformPerspective:800, transformOrigin:"center", duration:0.4, ease:"power3.out" });
    });
    wrap.addEventListener('mouseleave', () => {
      gsap.to(card, { rotationY:0, rotationX:0, duration:0.6, ease:"elastic.out(1,0.6)" });
    });
  });

  /* Cinematic stagger reveal for fade-premium elements */
  gsap.utils.toArray('.fade-premium').forEach((el, i) => {
    gsap.to(el, {
      scrollTrigger: {
        trigger: el,
        start: "top 90%",
        toggleActions: "play none none none"
      },
      opacity:1,
      y:0,
      duration:1.05,
      delay: i * 0.06,
      ease:"power3.out"
    });
  });

  /* Reveal program cards stagger to create cinematic flow */
  gsap.utils.toArray('.program-card').forEach((card, idx) => {
    gsap.to(card, {
      scrollTrigger: {
        trigger: card,
        start: "top 88%",
        toggleActions: "play none none none"
      },
      opacity:1,
      y:0,
      duration:0.9,
      delay: idx * 0.04,
      ease:"power3.out"
    });
  });

  /* Footer reveal */
  gsap.to('footer', {
    scrollTrigger: { trigger: 'footer', start: 'top 95%' },
    opacity:1,
    y:0,
    duration:1.0,
    ease:"power2.out"
  });

  /* Sidebar toggle elastic */
  const sidebar = document.getElementById('sidebar');
  const toggle = document.getElementById('toggleBtn');
  let collapsed = false;
  toggle.addEventListener('click', () => {
    collapsed = !collapsed;
    sidebar.classList.toggle('collapsed', collapsed);
    const targetWidth = collapsed ? parseInt(getComputedStyle(document.documentElement).getPropertyValue('--sidebar-collapsed')) : parseInt(getComputedStyle(document.documentElement).getPropertyValue('--sidebar-width'));
    gsap.to(sidebar, { width: targetWidth + 'px', duration:0.6, ease:"elastic.out(1,0.6)" });
    gsap.to(document.body, { marginLeft: (collapsed ? getComputedStyle(document.documentElement).getPropertyValue('--sidebar-collapsed') : getComputedStyle(document.documentElement).getPropertyValue('--sidebar-width')), duration:0.45, ease:"power3.out" });
  });

  /* micro hover nav links */
  document.querySelectorAll('.nav-link').forEach(link => {
    link.addEventListener('mouseenter', () => gsap.to(link, { x:6, duration:0.18, ease:"power1.out" }));
    link.addEventListener('mouseleave', () => gsap.to(link, { x:0, duration:0.18, ease:"power1.out" }));
  });

} else {
  // Reduced motion: simply show content
  document.getElementById('sidebar').style.opacity = 1;
  document.querySelectorAll('.layer').forEach(l => l.style.opacity = 1);
  document.querySelectorAll('.fade-premium').forEach(f => { f.style.opacity = 1; f.style.transform = 'none'; });
  document.querySelectorAll('.program-card').forEach(c => c.style.opacity = 1);
  document.querySelector('footer').style.opacity = 1;
}

/* simple spin keyframe for preloader */
(function addSpinKeyframe(){
  const s = document.createElement('style');
  s.innerHTML = '@keyframes spin{from{transform:rotate(0)}to{transform:rotate(360deg)}}';
  document.head.appendChild(s);
})();
</script>

</body>
</html>
