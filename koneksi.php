<?php
$host = "localhost";
$user = "root";
$password = "";
$database = "userinterfacedatabase"; // Ganti dengan nama database kamu

$conn = mysqli_connect($host, $user, $password, $database);

// Cek koneksi
if (!$conn) {
    die("Koneksi gagal: " . mysqli_connect_error());
}
?>
