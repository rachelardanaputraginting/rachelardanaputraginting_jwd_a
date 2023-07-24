<?php
session_start();

// Cek jika pengguna sudah login, maka arahkan ke halaman dashboard
if (isset($_SESSION['is_authenticated'])) {
    header('Location: ../index.php');
    exit;
}

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    require("../connection/connect.php");

    $email = $_POST['email'];
    $username = $_POST['username'];
    $nama = $_POST['nama'];
    $password = md5($_POST['password']);
    $confirmPassword = md5($_POST['confirm_password']);

    // Check if the email already exists in the database
    $query = "SELECT * FROM users WHERE email='$email'";
    $result = $conn->query($query);
    if ($result === false) {
        // Error handling for the query
        die("Error: " . $conn->error);
    }

    if ($result->num_rows > 0) {
        $message = "Email sudah digunakan. Tolong gunakan email lain!";
    } elseif ($password !== $confirmPassword) {
        $message = "Password tidak cocok.";
    } else {
        $query = "INSERT INTO users (email, password, username, nama) VALUES ('$email', '$password', '$username', '$nama')";
        $insert_result = $conn->query($query);
        if ($insert_result === TRUE) {
            $message = "Berhasil registrasi. Selamat Datang!";
            header("Location: ../index.php?message=" . urlencode($message));
            exit;
        } else {
            $message = "Error: " . $query . "<br>" . $conn->error;
        }
    }

    $conn->close();
}
?>

<!doctype html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>JWD - Rachel Ardana Putra Ginting</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-9ndCyUaIbzAi2FUVXJi0CjmCapSmO7SnpJef0486qhLnuZ2cdeRhO02iuK6FUUVM" crossorigin="anonymous">

     <!-- Font -->
     <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">

    <style>
        body {
            font-family: 'Poppins', sans-serif;
        }
    </style>
</head>

<body>
    <div class="container mt-5">
        <div class="row justify-content-center mt-5">
            <div class="col-md-5 mt-5">
                <div class="card">
                    <div class="card-body">
                        <h5 class="text-center my-3">Daftar</h5>
                        <?php if (isset($message)) : ?>
                            <div class="alert alert-danger my-4"><?php echo $message; ?></div>
                        <?php endif; ?>
                        <form method="post">
                            <div class="mb-3">
                                <label for="nama" class="form-label">Nama</label>
                                <input type="text" class="form-control" id="nama" name="nama" required>
                            </div>
                            <div class="mb-3">
                                <label for="email" class="form-label">Email</label>
                                <input type="email" class="form-control" id="email" name="email" required>
                            </div>
                            <div class="mb-3">
                                <label for="username" class="form-label">Nama Pengguna</label>
                                <input type="text" class="form-control" id="username" name="username" required>
                            </div>
                            <div class="mb-3">
                                <label for="password" class="form-label">Kata Sandi</label>
                                <input type="password" class="form-control" id="password" name="password" required>
                            </div>
                            <div class="mb-3">
                                <label for="confirm_password" class="form-label">Konfirmasi Sandi</label>
                                <input type="password" class="form-control" id="confirm_password" name="confirm_password" required>
                            </div>
                            <div>
                                <button type="submit" class="btn btn-primary d-block w-100">Register</button>
                            </div>
                            <div>
                                <p class="text-center mt-3">
                                    Already have an account? <a href="login.php" class="fw-semibold">Login here</a>
                                </p>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js" integrity="sha384-geWF76RCwLtnZ8qwWowPQNguL3RmwHVBC9FhGdlKrxdiJJigb/j/68SIy3Te4Bkz" crossorigin="anonymous"></script>
</body>

</html>
