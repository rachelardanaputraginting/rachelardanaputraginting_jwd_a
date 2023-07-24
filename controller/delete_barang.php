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

    // Query SQL untuk menghapus data barang
    $sql = "DELETE FROM barang WHERE id='$id'";

    if ($conn->query($sql) === TRUE) {
        $message = "Data barang berhasil dihapus";
    } else {
        $message = "Error: " . $sql . "<br>" . $conn->error;
    }

    $conn->close();
    header("Location: ../barang.php?message=" . urlencode($message));
}
