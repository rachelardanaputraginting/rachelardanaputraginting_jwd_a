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
    $nim = $_POST['nim'];
    $nama = $_POST['nama'];
    $prodi = $_POST['prodi'];

    // Query SQL untuk update data
    $sql = "UPDATE mahasiswa SET nim='$nim', nama='$nama', prodi='$prodi' WHERE id='$id'";

    if ($conn->query($sql) === TRUE) {
        $message = "Data mahasiswa berhasil diubah";
    } else {
        $message = "Error: " . $sql . "<br>" . $conn->error;
    }

    $conn->close();
    header("Location: ../mahasiswa.php?message=" . urlencode($message));
}
?>
