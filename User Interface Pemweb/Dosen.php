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

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $name = $_POST['name'];
    $nip = $_POST['nip'];
    $email = $_POST['email'];
    $phone = $_POST['phone'];
    $expertise = $_POST['expertise'];

    $stmt = $conn->prepare("INSERT INTO lecturers (name, nip, email, phone, expertise) VALUES (:name, :nip, :email, :phone, :expertise)");
    $stmt->bindParam(':name', $name);
    $stmt->bindParam(':nip', $nip);
    $stmt->bindParam(':email', $email);
    $stmt->bindParam(':phone', $phone);
    $stmt->bindParam(':expertise', $expertise);
    
    if ($stmt->execute()) {
        header("Location: dosen.php");
        exit();
    } else {
        $error = "Gagal menambahkan dosen";
    }
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1" />
  <title>Tambah Dosen</title>
  <link rel="stylesheet" href="css/Dosen.css" />
  <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;600&display=swap" rel="stylesheet" />
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css" />
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
          <li><a href="Admin.php">Beranda</a></li>
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
  <main class="container">
    <div class="main-header">
      <a href="dosen.php" class="back-link" title="Kembali ke Data Dosen">&lt;</a>
      <h2>Tambah Dosen Baru</h2>
    </div>
    
    <?php if (isset($error)): ?>
      <div class="error-message"><?= $error ?></div>
    <?php endif; ?>
    
    <form method="POST" class="lecturer-form">
      <div class="form-group">
        <label for="name">Nama Lengkap</label>
        <input type="text" id="name" name="name" required>
      </div>
      
      <div class="form-group">
        <label for="nip">NIDN</label>
        <input type="text" id="nip" name="nip" required>
      </div>
      
      <div class="form-group">
        <label for="email">Email</label>
        <input type="email" id="email" name="email" required>
      </div>
      
      <div class="form-group">
        <label for="phone">Telepon</label>
        <input type="tel" id="phone" name="phone">
      </div>
      
      <div class="form-group">
        <label for="expertise">Bidang Keahlian/Prodi</label>
        <input type="text" id="expertise" name="expertise" required>
      </div>
      
      <div class="form-actions">
        <button type="submit" class="submit-btn">Simpan</button>
        <a href="dosen.php" class="cancel-btn">Batal</a>
      </div>
    </form>
  </main>

  <!-- FOOTER -->
  <footer class="site-footer">
    <div class="container">
      <p>&copy; 2025 Sistem Informasi Sekolah | Dibuat oleh Puan Akeyla</p>
    </div>
  </footer>
</body>
</html>