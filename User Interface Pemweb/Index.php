<?php
session_start();
require 'koneksi.php'; // sambungkan ke database

$isLoggedIn = isset($_SESSION['user']);
$userName = null;

// Ambil data user dari database jika sudah login
if ($isLoggedIn) {
    $userId = $_SESSION['user']['id'];
    $result = mysqli_query($conn, "SELECT name FROM users WHERE id = $userId");
    if ($data = mysqli_fetch_assoc($result)) {
        $userName = $data['name'];
    }
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1" />
  <title>Beranda Sekolah</title>
  <link rel="stylesheet" href="css/style.css" />
  <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;600&display=swap" rel="stylesheet" />
  <script src="https://kit.fontawesome.com/a2e0e1f4d1.js" crossorigin="anonymous"></script>
</head>
<body>
  <!-- HEADER -->
  <header class="site-header">
    <div class="container">
      <div class="logo">
        <h1><span class="blue">SI</span>-Sekolah</h1>
      </div>
      <nav class="main-nav">
        <ul>
          <li><a href="index.php">Beranda</a></li>
          <li><a href="#">Modul</a></li>
          <li><a href="#">Kontak</a></li>
          <li><a href="#">Tentang</a></li>
          <?php if ($isLoggedIn): ?>
            <li><a href="logout.php">Keluar</a></li>
          <?php else: ?>
            <li><a href="login.php">Masuk</a></li>
          <?php endif; ?>
        </ul>
      </nav>
      <div class="nav-icons">
        <i class="fas fa-search"></i>
        <i class="fas fa-bars"></i>
      </div>
    </div>
  </header>

  <!-- HERO SECTION -->
  <section class="hero">
    <div class="container">
      <h2>Selamat Datang di Dashboard Sekolah</h2>
      <p>
        <?php if ($isLoggedIn): ?>
          Halo, <strong><?= htmlspecialchars($userName) ?></strong>! Kelola data sekolah dengan mudah.
        <?php else: ?>
          Kelola data dosen, mahasiswa, mata kuliah, dan segala aktivitas perkuliahan dengan mudah.
        <?php endif; ?>
      </p>
      <?php if (!$isLoggedIn): ?>
        <a href="login.php" class="btn-primary">Masuk</a>
      <?php endif; ?>
    </div>
  </section>

  <!-- FOOTER -->
  <footer class="site-footer">
    <div class="container">
      <p>&copy; 2025 Sistem Informasi Sekolah | Dibuat oleh Puan Akeyla</p>
    </div>
  </footer>
</body>
</html>
