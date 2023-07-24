<?php
require '../connection/connect.php';

session_start();

// Cek jika pengguna belum login, maka arahkan ke halaman login
if (!isset($_SESSION['is_authenticated'])) {
    header('Location: auth/login.php');
    exit;
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $nim = $_POST['nim'];
    $nama = $_POST['nama'];
    $prodi = $_POST['prodi'];

    // Query SQL untuk menambahkan data
    $sql = "INSERT INTO mahasiswa (nim, nama, prodi) VALUES ('$nim', '$nama', '$prodi')";

    if ($conn->query($sql) === TRUE) {
        $message = "Data mahasiswa berhasil ditambahkan";
    } else {
        $message = "Error: " . $sql . "<br>" . $conn->error;
    }

    $conn->close();
    header("Location: ../mahasiswa.php?message=" . urlencode($message));
}
?>
