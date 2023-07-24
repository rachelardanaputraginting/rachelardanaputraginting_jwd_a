<?php
require '../connection/connect.php';

session_start();

// Cek jika pengguna belum login, maka arahkan ke halaman login
if (!isset($_SESSION['is_authenticated'])) {
    header('Location: auth/login.php');
    exit;
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $id = $_POST['id'];
    $nama_barang = $_POST['nama_barang'];
    $harga = $_POST['harga'];
    $stok = $_POST['stok'];
    $kategori = $_POST['kategori'];

    // Query SQL untuk update data barang
    $sql = "UPDATE barang SET nama_barang='$nama_barang', harga='$harga', stok='$stok', kategori='$kategori' WHERE id='$id'";

    if ($conn->query($sql) === TRUE) {
        $message = "Data barang berhasil diubah";
    } else {
        $message = "Error: " . $sql . "<br>" . $conn->error;
    }

    $conn->close();
    header("Location: ../barang.php?message=" . urlencode($message));
}
