<?php
include 'koneksi.php';
session_start();

if(!isset($_GET['id_prodi'])) {
    header("Location: home.php");
    exit;
}

$id_prodi = $_GET['id_prodi'];
$query = mysqli_query($conn, "SELECT * FROM program_studi WHERE id_prodi='$id_prodi'");
if(mysqli_num_rows($query) == 0) {
    echo "<p class='text-center mt-5'>Program not found.</p>";
    exit;
}

$prodi = mysqli_fetch_assoc($query);
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8" />
<meta name="viewport" content="width=device-width, initial-scale=1.0"/>

<!-- Bootstrap -->
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">

<!-- Fonts -->
<link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">

<!-- GSAP for smooth animations -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/gsap/3.12.2/gsap.min.js"></script>

<style>
:root{
  --bg:#eef1f7;
  --card-bg: rgba(255,255,255,0.85);
  --muted:#6b7280;
  --primary-1: #667eea;
  --primary-2: #764ba2;
  --glass-border: rgba(255,255,255,0.45);
  --accent: #36C9FF;
  --nav-bg: rgba(27,42,78,0.82);
  --gradient-anim: linear-gradient(90deg,var(--primary-1),var(--primary-2));
}

/* DARK MODE variables */
body.dark {
  --bg: #0b1020;
  --card-bg: rgba(18,24,38,0.6);
  --muted: #b6c0d9;
  --nav-bg: rgba(6,10,22,0.85);
  --glass-border: rgba(255,255,255,0.06);
}

/* Base */
body {
  font-family: 'Poppins', sans-serif;
  background-color: var(--bg);
  margin:0;
  transition: background-color .35s ease, color .35s ease;
  -webkit-font-smoothing:antialiased;
  -moz-osx-font-smoothing:grayscale;
}

/* NAVBAR: animated hover + dark toggle */
.navbar {
  padding: 1rem 1.25rem;
  background: var(--nav-bg) !important;
  backdrop-filter: blur(12px);
  border-bottom: 1px solid rgba(255,255,255,0.06);
  box-shadow: 0 8px 30px rgba(2,6,23,0.12);
  position:sticky;
  top:0;
  z-index:9999;
}

/* navbar brand */
.navbar-brand img { height:44px; width:44px; border-radius:50%; object-fit:cover; margin-right:10px; }

/* Nav link hover luxury */
.navbar .nav-link {
  color: #f5f7fa;
  position:relative;
  transition: transform .25s ease, color .25s ease;
}
.navbar .nav-link::after {
  content: "";
  position: absolute;
  left: 50%;
  bottom: -6px;
  transform: translateX(-50%);
  width: 0;
  height: 3px;
  border-radius: 6px;
  background: var(--gradient-anim);
  transition: width .28s ease;
}
.navbar .nav-link:hover {
  transform: translateY(-3px);
  color: #fff;
}
.navbar .nav-link:hover::after { width: 56%; }

/* Dark toggle button */
#darkToggle {
  border: none;
  background: transparent;
  color: #fff;
  cursor:pointer;
  display:flex; align-items:center; gap:8px;
}

/* HERO: multi-layer parallax with campus layer */
.hero {
  position:relative;
  padding: 150px 0;
  color: #fff;
  overflow: hidden;
  background: linear-gradient(135deg, rgba(102,126,234,0.65), rgba(118,75,162,0.65)), url('assets/img/kampus.jpg') center/cover no-repeat;
  transition: background .4s ease;
}

/* overlay darker for contrast */
.hero::after{
  content:"";
  position:absolute; inset:0;
  background: rgba(0,0,0,0.32);
  z-index:0;
}

/* layers for mouse parallax (absolute full bleed) */
.hero .layer {
  position:absolute;
  inset:0;
  background-position:center;
  background-size:cover;
  pointer-events:none;
  z-index:0;
  will-change: transform;
}

/* specific layers (if present) */
.layer-white { z-index:0; filter:brightness(0.96) contrast(1.02); transform: translateZ(0); }
.layer-mid { z-index:1; opacity:0.95; transform: translateZ(0); }
.layer-campus { z-index:2; opacity:0.35; mix-blend-mode:soft-light; background-position:bottom center; background-size:cover; height:60%; top:20%; }
.layer-front { z-index:3; mix-blend-mode:overlay; opacity:0.55; }

/* Hero inner: content above layers */
.hero .container { position:relative; z-index:5; }

/* hero typography */
.hero h1 {
  font-size: clamp(2rem, 4.2vw, 3rem);
  font-weight:700;
  margin:0 0 8px 0;
  text-shadow: 0 10px 30px rgba(0,0,0,0.6);
}
.hero p.lead {
  font-size: 1.05rem;
  color: rgba(255,255,255,0.94);
  margin-bottom: 1.1rem;
  text-shadow: 0 6px 18px rgba(0,0,0,0.45);
}

/* Buttons inside hero */
.btn-magnetic {
  padding: 12px 20px;
  border-radius: 12px;
  background: linear-gradient(90deg,#111827,var(--primary-1));
  color:#fff;
  border:none;
  box-shadow: 0 12px 30px rgba(12,17,23,0.28);
  transition: transform .28s ease, box-shadow .28s ease;
  display:inline-flex; align-items:center; gap:10px;
}
.btn-magnetic:hover{ transform: translateY(-4px); box-shadow: 0 18px 40px rgba(12,17,23,0.34);}

/* Floating CTA button (right-bottom) */
.floating-cta {
  position: fixed;
  right: 22px;
  bottom: 26px;
  z-index: 99999;
  background: var(--gradient-anim);
  color: #fff;
  border-radius: 999px;
  padding: 14px 18px;
  box-shadow: 0 12px 40px rgba(102,126,234,0.28);
  display:flex;
  align-items:center;
  gap:10px;
  cursor:pointer;
  transition: transform .18s ease, box-shadow .25s ease, opacity .25s;
}
.floating-cta:hover { transform: translateY(-6px); box-shadow: 0 22px 60px rgba(102,126,234,0.36); }

/* CONTENT: Tabs + Cards */
.nav-tabs {
  margin-top: 8px;
}
.nav-tabs .nav-link {
  font-weight:600;
  color: var(--muted);
  border-radius: 12px 12px 0 0;
  padding:10px 20px;
  transition: all .28s ease;
}
.nav-tabs .nav-link.active {
  color:#fff !important;
  background: var(--gradient-anim);
  box-shadow: 0 12px 30px rgba(102,126,234,0.12);
}

/* card glass + gradient border animation */
.card {
  border-radius:16px;
  background: var(--card-bg);
  padding: 26px;
  box-shadow: 0 12px 36px rgba(6,10,22,0.08);
  border: 1px solid var(--glass-border);
  position: relative;
  overflow: hidden;
  transition: transform .45s cubic-bezier(.2,.9,.2,1), box-shadow .45s;
}

/* animated gradient border using pseudo element */
.card::before {
  content: "";
  position: absolute;
  inset: -2px;
  background: linear-gradient(90deg, rgba(102,126,234,0.2), rgba(118,75,162,0.2));
  z-index: -1;
  filter: blur(12px);
  opacity: 0;
  transition: opacity .45s ease;
  pointer-events:none;
}

/* gradient animated stripe inside border */
.card .animated-border {
  position:absolute;
  left:-150%; top:0;
  width:250%;
  height:4px;
  background: linear-gradient(90deg,var(--primary-1),var(--primary-2),var(--accent));
  transform: translateX(0);
  animation: borderSlide 6s linear infinite;
  opacity: .9;
}
@keyframes borderSlide {
  0% { transform: translateX(-10%); }
  50% { transform: translateX(-40%); }
  100% { transform: translateX(-10%); }
}

/* card hover reveal */
.card:hover::before { opacity: 1; transform: scale(1.02); }

/* small header underline */
h3.fw-bold::after {
  content: "";
  display:block;
  width:60px;
  height:4px;
  background: linear-gradient(90deg,var(--primary-1),var(--primary-2));
  border-radius:3px;
  margin-top:12px;
}

/* Animation helper classes */
.fade-up { opacity:0; transform: translateY(22px); transition: all .7s cubic-bezier(.2,.9,.2,1); }
.fade-up.show { opacity:1; transform: translateY(0); }
.zoom-in { transform: scale(.98); opacity:0; transition: all .65s ease; }
.zoom-in.show { transform: scale(1); opacity:1; }

/* Accessibility: reduced motion */
@media (prefers-reduced-motion: reduce) {
  .floating-cta, .card, .hero .layer { animation: none !important; transition: none !important; }
}

/* Responsive tweaks */
@media (max-width: 768px) {
  .hero { padding: 110px 0; }
  .floating-cta { right: 12px; bottom: 12px; padding: 12px 14px; }
}
</style>
</head>
<body>

<!-- NAVBAR -->
<nav class="navbar navbar-expand-lg navbar-dark">
  <div class="container">
    <a class="navbar-brand d-flex align-items-center fw-bold" href="home.php">
      <img src="logo.jpg" alt="Logo"> AMIK UNIVERSAL MEDAN
    </a>

    <div class="ms-auto d-flex align-items-center gap-2">
      <!-- Dark mode toggle -->
      <button id="darkToggle" aria-label="Toggle dark mode" title="Toggle dark mode">
        <svg id="iconLight" width="20" height="20" viewBox="0 0 24 24" fill="none" style="display:none;">
          <path d="M21 12.79A9 9 0 1111.21 3 7 7 0 0021 12.79z" fill="#fff"></path>
        </svg>
        <svg id="iconDark" width="20" height="20" viewBox="0 0 24 24" fill="none">
          <path d="M6.76 4.84l-1.8-1.79L3.17 4.84 4.96 6.63 6.76 4.84zM1 13h3v-2H1v2zm10 9h2v-3h-2v3zm7.24-2.16l1.79 1.79 1.79-1.79-1.79-1.79-1.79 1.79zM20 13h3v-2h-3v2zM6.76 19.16l-1.79 1.79 1.79 1.79 1.79-1.79-1.79-1.79zM12 4a1 1 0 110-2 1 1 0 010 2z" fill="#fff"></path>
        </svg>
      </button>
    </div>
  </div>
</nav>

<!-- HERO (Enhanced Multi-Layer Parallax) -->
<section class="hero" id="heroSection" aria-label="Hero">
  <!-- BACKGROUND LAYERS -->
  <div class="layer layer-white" style="background-image:url('assets/img/hero-back.jpg');"></div>
  <div class="layer layer-mid" style="background-image:url('assets/img/hero-logo.jpg');"></div>

  <!-- Soft campus silhouette for professional look -->
  <div class="layer layer-campus" style="background-image:url('assets/img/campus-soft.png');"></div>

  <!-- Front texture -->
  <div class="layer layer-front" style="background-image:url('assets/img/hero-front.png');"></div>

  <!-- HERO CONTENT -->
  <div class="container text-center">
    <h1 class="fade-up" id="heroTitle"><?php echo htmlspecialchars($prodi['nama_prodi']); ?></h1>
    <p class="lead fade-up" id="heroSub"><?php echo htmlspecialchars($prodi['jenjang']); ?></p>

    <div class="d-flex justify-content-center gap-3 mt-3">
      <a href="pendaftaran.php" class="btn-magnetic fade-up" style="text-decoration:none;">
        <span class="btn-magnetic-inner">Mulai Pendaftaran</span>
      </a>

      <a href="informasi.php" class="btn btn-outline-light fade-up" style="border-radius:12px;">
        Informasi
      </a>
    </div>
  </div>
</section>

<!-- Floating CTA -->
<a href="pendaftaran.php" class="floating-cta" id="floatingCta" aria-label="Daftar Sekarang">
  <svg width="18" height="18" viewBox="0 0 24 24" fill="none" style="filter:drop-shadow(0 3px 6px rgba(0,0,0,0.2));">
    <path d="M12 2v20M2 12h20" stroke="#fff" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
  </svg>
  <span style="font-weight:600;">Daftar Sekarang</span>
</a>

<?php
include 'koneksi.php';
session_start();

if(!isset($_GET['id_prodi'])) {
    header("Location: home.php");
    exit;
}

$id_prodi = $_GET['id_prodi'];
$query = mysqli_query($conn, "SELECT * FROM program_studi WHERE id_prodi='$id_prodi'");
if(mysqli_num_rows($query) == 0) {
    echo "<p class='text-center mt-5'>Program not found.</p>";
    exit;
}

$prodi = mysqli_fetch_assoc($query);

// ========================
//   PENGERTIAN DINAMIS
// ========================
$pengertian = "";

switch (strtolower($prodi['nama_prodi'])) {

    case "manajemen informatika":
        $pengertian = "Program studi Manajemen Informatika berfokus pada pengelolaan sistem informasi, analisis kebutuhan pengguna, pengembangan aplikasi, serta manajemen data untuk mendukung kegiatan bisnis. Mahasiswa dibekali kemampuan teknis sekaligus manajerial agar mampu bersaing di industri teknologi.";
        break;

    case "teknik komputer":
        $pengertian = "Program studi Teknik Komputer mempelajari sistem perangkat keras, perakitan komputer, jaringan, sistem embedded, hingga interfacing perangkat digital. Mahasiswa dipersiapkan menjadi teknisi dan engineer profesional.";
        break;

    case "sistem informasi":
        $pengertian = "Program studi Sistem Informasi mengkombinasikan teknologi informasi dengan proses bisnis. Mahasiswa mendalami analisis sistem, manajemen basis data, perancangan aplikasi, dan penerapan TI dalam organisasi modern.";
        break;

    case "komputer akuntansi":
        $pengertian = "Program studi Komputer Akuntansi mengintegrasikan pengetahuan akuntansi dengan teknologi modern. Mahasiswa mempelajari software akuntansi, sistem informasi keuangan, audit berbasis komputer, serta pengolahan laporan keuangan digital.";
        break;

    default:
        $pengertian = "Program studi ini bertujuan untuk memberikan pengetahuan teori dan praktik secara seimbang, sehingga mahasiswa mampu menguasai kompetensi sesuai bidang keahlian masing-masing dan siap menghadapi kebutuhan dunia kerja.";
        break;
}
?>

<!-- CONTENT -->
<section class="container my-5">
  <ul class="nav nav-tabs justify-content-center" id="prodiTab" role="tablist">
    <li class="nav-item" role="presentation">
      <button class="nav-link active" id="detail-tab" data-bs-toggle="tab" data-bs-target="#detail" type="button">
        Detail Program
      </button>
    </li>

    <li class="nav-item" role="presentation">
      <button class="nav-link" id="pengertian-tab" data-bs-toggle="tab" data-bs-target="#pengertian" type="button">
        Pengertian Program Studi
      </button>
    </li>
  </ul>

  <div class="tab-content mt-3">
    
    <!-- DETAIL PROGRAM -->
    <div class="tab-pane fade show active" id="detail" role="tabpanel">
      <div class="card fade-card" id="cardDetail">
        <div class="animated-border"></div>

        <div class="card-body">
          <h3 class="fw-bold zoom-in"><?php echo htmlspecialchars($prodi['nama_prodi']); ?></h3>
          <p><strong>Degree Level:</strong> <?php echo htmlspecialchars($prodi['jenjang']); ?></p>
          <p class="text-muted"><?php echo nl2br(htmlspecialchars($prodi['deskripsi'])); ?></p>

          <a href="home.php" class="btn btn-primary mt-3">Back to Home</a>
        </div>
      </div>
    </div>

    <!-- PENGERTIAN PROGRAM -->
    <div class="tab-pane fade" id="pengertian" role="tabpanel">
      <div class="card fade-card" id="cardPengertian">
        <div class="card-body">
          <h3 class="fw-bold zoom-in">Pengertian Program Studi</h3>
          <p class="text-muted">
            <?php echo $pengertian; ?>
          </p>
        </div>
      </div>
    </div>

  </div>
</section>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

<script>
// Fade card on tab change
document.querySelectorAll('.tab-pane').forEach(tab => {
  tab.addEventListener('shown.bs.tab', () => {
    const card = tab.querySelector('.fade-card');
    if(card) { card.classList.add('show'); }
  });
});

// Fade on scroll
const faders = document.querySelectorAll('.fade-card');
window.addEventListener('scroll', () => {
  faders.forEach(el => {
    const top = el.getBoundingClientRect().top;
    if(top < window.innerHeight - 80) {
      el.classList.add('show');
    }
  });
});
</script>

</body>
</html>

<<!-- FOOTER -->
<footer style="display: flex; justify-content: space-between; align-items: center; padding: 10px 20px;">
  <div class="fw-bold" style="white-space: nowrap;">AMIK UNIVERSAL MEDAN</div>
  <p style="margin: 0; text-align: center; flex: 1;">© 2025 AMIK Universal Medan | Student Registration System</p>
</footer>



 

<!-- SCRIPTS -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

<script>
/* ==========================
   Utility & prefers-reduced-motion
   ========================== */
const prefersReduced = window.matchMedia('(prefers-reduced-motion: reduce)').matches;

/* ==========================
   Dark Mode Toggle (localStorage)
   ========================== */
const body = document.body;
const darkToggle = document.getElementById('darkToggle');
const iconLight = document.getElementById('iconLight');
const iconDark = document.getElementById('iconDark');

function setDarkMode(on) {
  if(on) {
    body.classList.add('dark');
    iconLight.style.display = 'inline-block';
    iconDark.style.display = 'none';
    localStorage.setItem('amik_dark', '1');
  } else {
    body.classList.remove('dark');
    iconLight.style.display = 'none';
    iconDark.style.display = 'inline-block';
    localStorage.setItem('amik_dark', '0');
  }
}

// Initialize from storage
const stored = localStorage.getItem('amik_dark');
if(stored === '1') setDarkMode(true);
else setDarkMode(false);

darkToggle.addEventListener('click', (e) => {
  const isDark = body.classList.contains('dark');
  setDarkMode(!isDark);
});

/* ==========================
   Hero Parallax (mouse + gyroscope fallback)
   ========================== */
const hero = document.getElementById('heroSection') || document.querySelector('.hero');
const layers = hero ? hero.querySelectorAll('.layer') : [];

if(!prefersReduced && hero && layers.length) {
  hero.addEventListener('mousemove', (e) => {
    const rect = hero.getBoundingClientRect();
    const px = (e.clientX - rect.left) / rect.width - 0.5;
    const py = (e.clientY - rect.top) / rect.height - 0.5;

    // apply different intensities per layer
    layers.forEach((layer, i) => {
      const depth = (i + 1) * 6; // 6,12,18...
      gsap.to(layer, { x: px * depth, y: py * depth * 0.6, rotation: px * (depth/40), duration: 1.2, ease: "power3.out" });
    });
  });

  // gentle subtle shift on scroll
  window.addEventListener('scroll', () => {
    const sc = window.scrollY || window.pageYOffset;
    layers.forEach((layer, i) => {
      const factor = 0.02 * (i+1);
      gsap.to(layer, { y: sc * factor, duration: 0.8, ease: "none" });
    });
  }, { passive: true });
}

/* ==========================
   Entrance animations (staggered using GSAP)
   ========================== */
function revealHeroContent() {
  if(prefersReduced) {
    document.querySelectorAll('.fade-up').forEach(el => el.classList.add('show'));
    document.querySelectorAll('.zoom-in').forEach(el => el.classList.add('show'));
    return;
  }
  gsap.fromTo('#heroTitle', { y: 28, opacity: 0 }, { y: 0, opacity: 1, duration: .9, ease: "power3.out" });
  gsap.fromTo('#heroSub', { y: 28, opacity: 0 }, { y: 0, opacity: 1, duration: .9, delay: .12, ease: "power3.out" });
  gsap.fromTo('.btn-magnetic, .btn-outline-light', { y: 18, opacity: 0 }, { y:0, opacity:1, stagger: .08, delay: .22, duration: .9, ease: "power3.out" });
}

/* trigger reveal */
document.addEventListener('DOMContentLoaded', () => {
  // small timeout to allow layout
  setTimeout(revealHeroContent, 120);
});

/* ==========================
   Tab show: animate card content
   ========================== */
document.querySelectorAll('.tab-pane').forEach(tab => {
  tab.addEventListener('shown.bs.tab', () => {
    const card = tab.querySelector('.card');
    if(card) {
      // add show via class for CSS transitions
      card.classList.add('show');
      // small gsap pop
      if(!prefersReduced) gsap.fromTo(card, { y: 12, opacity: 0 }, { y:0, opacity:1, duration:.6, ease:"power2.out" });
    }
  });
});

// show the first active card on load
document.addEventListener('DOMContentLoaded', () => {
  const activeCard = document.querySelector('.tab-pane.show .card');
  if(activeCard) activeCard.classList.add('show');
});

/* ==========================
   Advanced scroll animations: IntersectionObserver (stagger)
   ========================== */
const ioOptions = { threshold: 0.12 };
const io = new IntersectionObserver((entries) => {
  entries.forEach(entry => {
    if(entry.isIntersecting) {
      entry.target.classList.add('show');
      // if GSAP available, add subtle reveal
      if(!prefersReduced && window.gsap) {
        gsap.fromTo(entry.target, { y: 12, opacity: 0 }, { y: 0, opacity: 1, duration: .7, ease: "power3.out" });
      }
      io.unobserve(entry.target);
    }
  });
}, ioOptions);

document.querySelectorAll('.fade-card, .fade-up, .zoom-in, .card').forEach(el => io.observe(el));

/* ==========================
   Floating CTA micro animation (pulse)
   ========================== */
if(!prefersReduced) {
  gsap.to('#floatingCta', { y: -6, repeat: -1, yoyo: true, duration: 2.6, ease: "sine.inOut", delay: .6 });
}

/* ==========================
   Accessibility: keyboard focus for floating cta
   ========================== */
const floating = document.getElementById('floatingCta');
floating.addEventListener('keydown', (e) => {
  if(e.key === 'Enter' || e.key === ' ') window.location.href = floating.getAttribute('href');
});

/* ==========================
   Small enhancement: show/hide floating cta on small screens if desired
   ========================== */
function handleFloatingVisibility() {
  if(window.innerWidth < 480) {
    // keep but reduce opacity
    floating.style.opacity = '0.92';
  } else {
    floating.style.opacity = '1';
  }
}
window.addEventListener('resize', handleFloatingVisibility);
handleFloatingVisibility();

</script>
</body>
</html>
