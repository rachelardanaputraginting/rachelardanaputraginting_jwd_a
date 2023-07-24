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

    // Query SQL untuk menghapus data
    $sql = "DELETE FROM mahasiswa WHERE id='$id'";

    if ($conn->query($sql) === TRUE) {
        $message = "Data mahasiswa berhasil dihapus";
    } else {
        $message = "Error: " . $sql . "<br>" . $conn->error;
    }

    $conn->close();
    header("Location: ../mahasiswa.php?message=" . urlencode($message));
}
?>
