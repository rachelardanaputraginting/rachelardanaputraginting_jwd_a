<?php
// Menggantikan dengan detail koneksi database sesuai server Anda
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "rachelardanaputraginting";

// Membuat koneksi
$conn = new mysqli($servername, $username, $password, $dbname);

// Memeriksa koneksi
if ($conn->connect_error) {
    die("Koneksi gagal: " . $conn->connect_error);
}
?>
