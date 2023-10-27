<?php

session_start();

// Cek jika pengguna belum login, maka arahkan ke halaman login
if (!isset($_SESSION['is_authenticated'])) {
    header('Location: auth/login.php');
    exit;
}

// Ambil data pengguna dari session
$nama = isset($_SESSION['users']['nama']) ? $_SESSION['users']['nama'] : '';

include 'header.php';

$message = isset($_GET['message']) ? $_GET['message'] : "";

?>

<div class="container">
    <div class="row">


        <div class="container my-5">
            <div class="p-5 text-center bg-body-tertiary rounded-3">
                <h5>Vocational School Graduate Academy</h5>
                <h1 class="text-body-emphasis">Rachel Ardana Putra Ginting | Tes Webhook</h1>
                <p class="col-lg-10 mx-auto fs-5 text-muted">
                    Sejak bersekolah di SMK Negeri 2 Langsa dengan Program Keahlian Rekayasa Perangkat Lunak, kesenangan saya dalam dunia
                    pemrograman mulai tumbuh. Selain belajar, saya juga menikmati kesempatan untuk berbagi pengetahuan melalui artikel yang saya
                    tulis di RAJARTAN Tech dan video tutorial yang di publikasikan di saluran YouTube RAJARTAN | Programming. Pendidikan saya
                    kemudian dilanjutkan di Politeknik Negeri Lhokseumawe dengan Program Studi Teknik Informatika.
                </p>
                <div class="d-inline-flex gap-2 mb-5">
                    <a href="https://www.youtube.com/@rajartan" class="btn btn-secondary"><svg xmlns="http://www.w3.org/2000/svg" class="icon icon-tabler icon-tabler-brand-youtube" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
                            <path stroke="none" d="M0 0h24v24H0z" fill="none"></path>
                            <path d="M3 5m0 4a4 4 0 0 1 4 -4h10a4 4 0 0 1 4 4v6a4 4 0 0 1 -4 4h-10a4 4 0 0 1 -4 -4z"></path>
                            <path d="M10 9l5 3l-5 3z"></path>
                        </svg></a>
                    <a href="https://www.instagram.com/@rachlapg_" class="btn btn-secondary"><svg xmlns="http://www.w3.org/2000/svg" class="icon icon-tabler icon-tabler-brand-instagram" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
                            <path stroke="none" d="M0 0h24v24H0z" fill="none"></path>
                            <path d="M4 4m0 4a4 4 0 0 1 4 -4h8a4 4 0 0 1 4 4v8a4 4 0 0 1 -4 4h-8a4 4 0 0 1 -4 -4z"></path>
                            <path d="M12 12m-3 0a3 3 0 1 0 6 0a3 3 0 1 0 -6 0"></path>
                            <path d="M16.5 7.5l0 .01"></path>
                        </svg></a>
                    <a href="https://www.github.com/rachelardanaputraginting" class="btn btn-secondary"><svg xmlns="http://www.w3.org/2000/svg" class="icon icon-tabler icon-tabler-brand-github" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
                            <path stroke="none" d="M0 0h24v24H0z" fill="none"></path>
                            <path d="M9 19c-4.3 1.4 -4.3 -2.5 -6 -3m12 5v-3.5c0 -1 .1 -1.4 -.5 -2c2.8 -.3 5.5 -1.4 5.5 -6a4.6 4.6 0 0 0 -1.3 -3.2a4.2 4.2 0 0 0 -.1 -3.2s-1.1 -.3 -3.5 1.3a12.3 12.3 0 0 0 -6.2 0c-2.4 -1.6 -3.5 -1.3 -3.5 -1.3a4.2 4.2 0 0 0 -.1 3.2a4.6 4.6 0 0 0 -1.3 3.2c0 4.6 2.7 5.7 5.5 6c-.6 .6 -.6 1.2 -.5 2v3.5"></path>
                        </svg></a>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Your toast message code -->
<?php if ($message != "") { ?>
    <div class="position-fixed top-0 start-50 translate-middle-x p-3" style="z-index: 9999;">
        <div class="toast align-items-center text-white bg-primary border-0" role="alert" aria-live="assertive" aria-atomic="true" data-bs-autohide="true" data-bs-delay="3000">
            <div class="toast-body d-flex align-items-center px-3 py-1">
                <!-- Remove the check icon -->
                <p class="ms-2 mt-3"><?php echo $message; ?></p>
            </div>
        </div>
    </div>
<?php } ?>



<?php include 'footer.php' ?>
