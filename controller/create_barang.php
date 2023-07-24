<?php
require '../connection/connect.php';

session_start();

// Cek jika pengguna belum login, maka arahkan ke halaman login
if (!isset($_SESSION['is_authenticated'])) {
    header('Location: auth/login.php');
    exit;
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $kode_barang = $_POST['kode_barang'];
    $nama_barang = $_POST['nama_barang'];
    $harga = $_POST['harga'];
    $stok = $_POST['stok'];
    $kategori = $_POST['kategori'];

    // Query SQL untuk menambahkan data barang
    $sql = "INSERT INTO barang (kode_barang, nama_barang, harga, stok, kategori) VALUES ('$kode_barang', '$nama_barang', '$harga', '$stok', '$kategori')";

    if ($conn->query($sql) === TRUE) {
        $message = "Data barang berhasil ditambahkan";
    } else {
        $message = "Error: " . $sql . "<br>" . $conn->error;
    }

    $conn->close();
    header("Location: ../barang.php?message=" . urlencode($message));
}
?>
