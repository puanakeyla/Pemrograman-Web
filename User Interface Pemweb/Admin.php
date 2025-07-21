<?php
session_start();

// Redirect to login if not authenticated
if (!isset($_SESSION['user_id'])) {
    header("Location: index.php");
    exit();
}

// Database connection
$host = 'localhost';
$dbname = 'school_system';
$username = 'root';
$password = '';

try {
    $conn = new PDO("mysql:host=$host;dbname=$dbname", $username, $password);
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch(PDOException $e) {
    die("Connection failed: " . $e->getMessage());
}

// Get counts for dashboard cards
try {
    // Count lecturers
    $stmt = $conn->query("SELECT COUNT(*) FROM lecturers");
    $lecturer_count = $stmt->fetchColumn();
    
    // Count students
    $stmt = $conn->query("SELECT COUNT(*) FROM students");
    $student_count = $stmt->fetchColumn();
    
    // Count courses
    $stmt = $conn->query("SELECT COUNT(*) FROM courses");
    $course_count = $stmt->fetchColumn();
    
    // Count grades records
    $stmt = $conn->query("SELECT COUNT(*) FROM grades");
    $grade_count = $stmt->fetchColumn();
    
    // Count schedules
    $stmt = $conn->query("SELECT COUNT(*) FROM schedules");
    $schedule_count = $stmt->fetchColumn();
    
    // Count rooms
    $stmt = $conn->query("SELECT COUNT(*) FROM rooms");
    $room_count = $stmt->fetchColumn();
} catch(PDOException $e) {
    // If tables don't exist yet, set counts to 0
    $lecturer_count = $student_count = $course_count = $grade_count = $schedule_count = $room_count = 0;
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1" />
  <title>Dashboard Admin Sekolah</title>
  <link rel="stylesheet" href="css/style.css" />
  <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;600&display=swap" rel="stylesheet" />
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css" />
</head>
<body>
  <!-- HEADER -->
  <header class="site-header">
    <div class="container header-layout">
      <div class="logo">
        <h1><span class="blue">SI</span>-Sekolah</h1>
      </div>

      <nav class="main-nav">
        <ul>
          <li><a href="Admin.php" class="active">Beranda</a></li>
          <li><a href="#">Modul</a></li>
          <li><a href="#">Kontak</a></li>
          <li><a href="#">Tentang</a></li>
        </ul>
      </nav>

      <div class="user-info">
        <i class="fas fa-user-shield"></i> 
        <span><?= htmlspecialchars($_SESSION['user_name']) ?></span>
        <a href="logout.php" class="logout-btn"><i class="fas fa-sign-out-alt"></i></a>
      </div>
    </div>
  </header>

  <!-- MAIN CONTENT -->
  <main>
    <section class="cards">
      <div class="card">
        <h3>Dosen</h3>
        <p><?= $lecturer_count ?> dosen terdaftar</p>
        <a href="dosen.php">Lihat Detail</a>
      </div>

      <div class="card">
        <h3>Mahasiswa</h3>
        <p><?= $student_count ?> mahasiswa terdaftar</p>
        <a href="mahasiswa.php">Lihat Detail</a>
      </div>

      <div class="card">
        <h3>Mata Kuliah</h3>
        <p><?= $course_count ?> mata kuliah tersedia</p>
        <a href="mata_kuliah.php">Lihat Detail</a>
      </div>

      <div class="card">
        <h3>Nilai</h3>
        <p><?= $grade_count ?> catatan nilai</p>
        <a href="nilai.php">Lihat Detail</a>
      </div>

      <div class="card">
        <h3>Jadwal</h3>
        <p><?= $schedule_count ?> jadwal perkuliahan</p>
        <a href="jadwal.php">Lihat Detail</a>
      </div>

      <div class="card">
        <h3>Ruangan</h3>
        <p><?= $room_count ?> ruangan tersedia</p>
        <a href="ruangan.php">Lihat Detail</a>
      </div>
    </section>
  </main>

  <!-- FOOTER -->
  <footer class="site-footer">
    <div class="container">
      <p>&copy; 2025 Sistem Informasi Sekolah | Dibuat oleh Puan Akeyla</p>
    </div>
  </footer>
</body>
</html>