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

// Get student data
$student = null;
if (isset($_GET['id'])) {
    $stmt = $conn->prepare("SELECT * FROM students WHERE id = :id");
    $stmt->bindParam(':id', $_GET['id']);
    $stmt->execute();
    $student = $stmt->fetch(PDO::FETCH_ASSOC);
}

if (!$student) {
    header("Location: mahasiswa.php");
    exit();
}

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $name = $_POST['name'];
    $npm = $_POST['npm'];
    $email = $_POST['email'];
    $phone = $_POST['phone'];
    $program_study = $_POST['program_study'];
    $semester = $_POST['semester'];

    $stmt = $conn->prepare("UPDATE students SET name = :name, npm = :npm, email = :email, phone = :phone, program_study = :program_study, semester = :semester WHERE id = :id");
    $stmt->bindParam(':name', $name);
    $stmt->bindParam(':npm', $npm);
    $stmt->bindParam(':email', $email);
    $stmt->bindParam(':phone', $phone);
    $stmt->bindParam(':program_study', $program_study);
    $stmt->bindParam(':semester', $semester);
    $stmt->bindParam(':id', $student['id']);
    
    if ($stmt->execute()) {
        header("Location: mahasiswa.php");
        exit();
    } else {
        $error = "Gagal mengupdate mahasiswa";
    }
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1" />
  <title>Edit Mahasiswa</title>
  <link rel="stylesheet" href="css/Mahasiswa.css" />
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
      <a href="mahasiswa.php" class="back-link" title="Kembali ke Data Mahasiswa">&lt;</a>
      <h2>Edit Data Mahasiswa</h2>
    </div>
    
    <?php if (isset($error)): ?>
      <div class="error-message"><?= $error ?></div>
    <?php endif; ?>
    
    <form method="POST" class="student-form">
      <div class="form-group">
        <label for="name">Nama Lengkap</label>
        <input type="text" id="name" name="name" value="<?= htmlspecialchars($student['name']) ?>" required>
      </div>
      
      <div class="form-group">
        <label for="npm">NIM</label>
        <input type="text" id="npm" name="npm" value="<?= htmlspecialchars($student['npm']) ?>" required>
      </div>
      
      <div class="form-group">
        <label for="email">Email</label>
        <input type="email" id="email" name="email" value="<?= htmlspecialchars($student['email']) ?>" required>
      </div>
      
      <div class="form-group">
        <label for="phone">Telepon</label>
        <input type="tel" id="phone" name="phone" value="<?= htmlspecialchars($student['phone']) ?>">
      </div>
      
      <div class="form-group">
        <label for="program_study">Program Studi</label>
        <select id="program_study" name="program_study" required>
          <option value="">Pilih Program Studi</option>
          <option value="Teknik Informatika" <?= $student['program_study'] == 'Teknik Informatika' ? 'selected' : '' ?>>Teknik Informatika</option>
          <option value="Teknik Elektro" <?= $student['program_study'] == 'Teknik Elektro' ? 'selected' : '' ?>>Teknik Elektro</option>
          <option value="Teknik Mesin" <?= $student['program_study'] == 'Teknik Mesin' ? 'selected' : '' ?>>Teknik Mesin</option>
          <option value="Teknik Sipil" <?= $student['program_study'] == 'Teknik Sipil' ? 'selected' : '' ?>>Teknik Sipil</option>
          <option value="Manajemen" <?= $student['program_study'] == 'Manajemen' ? 'selected' : '' ?>>Manajemen</option>
          <option value="Akuntansi" <?= $student['program_study'] == 'Akuntansi' ? 'selected' : '' ?>>Akuntansi</option>
        </select>
      </div>
      
      <div class="form-group">
        <label for="semester">Semester</label>
        <select id="semester" name="semester" required>
          <option value="">Pilih Semester</option>
          <?php for ($i = 1; $i <= 8; $i++): ?>
            <option value="<?= $i ?>" <?= $student['semester'] == $i ? 'selected' : '' ?>><?= $i ?></option>
          <?php endfor; ?>
        </select>
      </div>
      
      <div class="form-actions">
        <button type="submit" class="submit-btn">Simpan Perubahan</button>
        <a href="mahasiswa.php" class="cancel-btn">Batal</a>
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