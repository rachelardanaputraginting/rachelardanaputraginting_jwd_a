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
    $password = md5($_POST['password']);

    // Check if the email and password match the database
    $query = "SELECT * FROM users WHERE email='$email' AND password='$password'";
    $result = $conn->query($query);
    if ($result === false) {
        // Error handling for the query
        die("Error: " . $conn->error);
    }

    if ($result->num_rows > 0) {
        // Login successful, set session variables
        $data = $result->fetch_assoc();
        $_SESSION['is_authenticated'] = true;
        $_SESSION['id'] = $data['id'];
        $_SESSION['username'] = $data['username'];
        $_SESSION['nama'] = $data['nama'];

        // Check if "Ingat Saya" is checked
        if (isset($_POST['remember'])) {
            // Set cookies for one hour
            setcookie('username', $data['username'], time() + 3600);
            setcookie('nama', $data['nama'], time() + 3600);
        }

        // Redirect to the admin page after successful login
        $message = "Berhasil masuk. Selamat datang, " . $_SESSION['nama'] . "!";
        header("Location: ../index.php?message=" . urlencode($message));
        exit; // Stop further execution after redirection
    } else {
        // Login failed, display error message
        $error_message = "Email atau password salah.";
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
                        <h5 class="text-center my-3">Silahkan Masuk</h5>
                        <?php if (isset($error_message)) : ?>
                            <div class="alert alert-danger my-4"><?php echo $error_message; ?></div>
                        <?php endif; ?>
                        <form method="post">
                            <div class="mb-3">
                                <label for="email" class="form-label">Email</label>
                                <input type="email" class="form-control" id="email" name="email" required>
                            </div>
                            <div class="mb-3">
                                <label for="password" class="form-label">Password</label>
                                <input type="password" class="form-control" id="password" name="password" required>
                            </div>
                            <div class="mb-3 form-check">
                                <input type="checkbox" class="form-check-input" id="remember" name="remember">
                                <label class="form-check-label" for="remember">Ingat Saya</label>
                            </div>
                            <div>
                                <button type="submit" class="btn btn-primary d-block w-100">Masuk</button>
                            </div>
                            <div>
                                <p class="text-center mt-3">
                                    Belum daftar? <a href="register.php" class="fw-semibold"> Silahkan daftar!</a>
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
