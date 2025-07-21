<?php
session_start();

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

// Handle registration
if (isset($_POST['register'])) {
    $name = trim($_POST['name']);
    $email = trim(strtolower($_POST['email']));
    $password = $_POST['password'];
    $confirm_password = $_POST['confirm_password'];

    // Validate inputs
    if ($password !== $confirm_password) {
        $register_error = "Password dan konfirmasi password tidak cocok!";
    } else {
        // Check if email exists
        $stmt = $conn->prepare("SELECT * FROM users WHERE email = :email");
        $stmt->bindParam(':email', $email);
        $stmt->execute();

        if ($stmt->rowCount() > 0) {
            $register_error = "Email sudah terdaftar, silakan login.";
        } else {
            // Hash password
            $hashed_password = password_hash($password, PASSWORD_DEFAULT);
            
            // Insert new user
            $stmt = $conn->prepare("INSERT INTO users (name, email, password) VALUES (:name, :email, :password)");
            $stmt->bindParam(':name', $name);
            $stmt->bindParam(':email', $email);
            $stmt->bindParam(':password', $hashed_password);
            
            if ($stmt->execute()) {
                $register_success = "Registrasi berhasil! Silakan login.";
                // Switch to login form
                $_POST['login'] = true;
            } else {
                $register_error = "Terjadi kesalahan saat registrasi. Silakan coba lagi.";
            }
        }
    }
}

// Handle login
if (isset($_POST['login'])) {
    $email = trim(strtolower($_POST['email']));
    $password = $_POST['password'];

    // Get user from database
    $stmt = $conn->prepare("SELECT * FROM users WHERE email = :email");
    $stmt->bindParam(':email', $email);
    $stmt->execute();
    $user = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($user && password_verify($password, $user['password'])) {
        $_SESSION['user_id'] = $user['id'];
        $_SESSION['user_name'] = $user['name'];
        $_SESSION['user_email'] = $user['email'];
        
        header("Location: Admin.php");
        exit();
    } else {
        $login_error = "Email atau password salah!";
    }
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1" />
  <title>Mulai Sekarang - Login & Registrasi</title>
  <link rel="stylesheet" href="css/style.css" />
  <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;600&display=swap" rel="stylesheet" />
</head>
<body>
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
        </ul>
      </nav>
    </div>
  </header>

  <main class="auth-container">
    <div class="container">
      <div class="auth-box">
        <div class="tab-buttons">
          <button id="loginBtn" class="tab-btn <?= !isset($_POST['register']) ? 'active' : '' ?>">Login</button>
          <button id="registerBtn" class="tab-btn <?= isset($_POST['register']) ? 'active' : '' ?>">Registrasi</button>
        </div>

        <form id="loginForm" class="auth-form <?= !isset($_POST['register']) ? 'active' : '' ?>" action="#" method="POST">
          <h2>Login</h2>
          <?php if (isset($login_error)): ?>
            <div class="error-message"><?= $login_error ?></div>
          <?php endif; ?>
          
          <label for="loginEmail">Email</label>
          <input type="email" id="loginEmail" name="email" required placeholder="Masukkan email" 
                 value="<?= isset($_POST['email']) && !isset($_POST['register']) ? htmlspecialchars($_POST['email']) : '' ?>" />
          
          <label for="loginPassword">Password</label>
          <input type="password" id="loginPassword" name="password" required placeholder="Masukkan password" />
          
          <button type="submit" name="login" class="btn-primary btn-block">Masuk</button>
        </form>

        <form id="registerForm" class="auth-form <?= isset($_POST['register']) ? 'active' : '' ?>" action="#" method="POST">
          <h2>Registrasi</h2>
          <?php if (isset($register_error)): ?>
            <div class="error-message"><?= $register_error ?></div>
          <?php elseif (isset($register_success)): ?>
            <div class="success-message"><?= $register_success ?></div>
          <?php endif; ?>
          
          <label for="registerName">Nama Lengkap</label>
          <input type="text" id="registerName" name="name" required placeholder="Masukkan nama lengkap" 
                 value="<?= isset($_POST['name']) ? htmlspecialchars($_POST['name']) : '' ?>" />
          
          <label for="registerEmail">Email</label>
          <input type="email" id="registerEmail" name="email" required placeholder="Masukkan email" 
                 value="<?= isset($_POST['email']) ? htmlspecialchars($_POST['email']) : '' ?>" />
          
          <label for="registerPassword">Password</label>
          <input type="password" id="registerPassword" name="password" required placeholder="Buat password" />
          
          <label for="registerConfirmPassword">Konfirmasi Password</label>
          <input type="password" id="registerConfirmPassword" name="confirm_password" required placeholder="Konfirmasi password" />
          
          <button type="submit" name="register" class="btn-primary btn-block">Daftar</button>
        </form>
      </div>
    </div>
  </main>

  <footer class="site-footer">
    <div class="container">
      <p>&copy; 2025 Sistem Informasi Sekolah | Dibuat oleh Puan Akeyla</p>
    </div>
  </footer>

  <script>
    const loginBtn = document.getElementById('loginBtn');
    const registerBtn = document.getElementById('registerBtn');
    const loginForm = document.getElementById('loginForm');
    const registerForm = document.getElementById('registerForm');

    loginBtn.addEventListener('click', () => {
      loginBtn.classList.add('active');
      registerBtn.classList.remove('active');
      loginForm.style.display = 'block';
      registerForm.style.display = 'none';
    });

    registerBtn.addEventListener('click', () => {
      registerBtn.classList.add('active');
      loginBtn.classList.remove('active');
      registerForm.style.display = 'block';
      loginForm.style.display = 'none';
    });

    // Initialize form display based on PHP condition
    document.addEventListener('DOMContentLoaded', () => {
      <?php if (isset($_POST['register'])): ?>
        loginForm.style.display = 'none';
        registerForm.style.display = 'block';
      <?php endif; ?>
    });
  </script>
</body>
</html>