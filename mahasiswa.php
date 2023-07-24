<?php
require 'connection/connect.php';

session_start();

if (!isset($_SESSION['is_authenticated'])) {
    header('Location: auth/login.php');
    exit;
}
include 'header.php';

$itemsPerPage = 4;
$page = isset($_GET['page']) ? intval($_GET['page']) : 1;
$offset = ($page - 1) * $itemsPerPage;

$searchKeyword = isset($_GET['search']) ? $_GET['search'] : '';
$filterProdi = isset($_GET['filterProdi']) ? $_GET['filterProdi'] : '';

$sql = "SELECT * FROM mahasiswa WHERE 1=1";

if (!empty($searchKeyword)) {
    $sql .= " AND (nim LIKE '%$searchKeyword%' OR nama LIKE '%$searchKeyword%' OR prodi LIKE '%$searchKeyword%')";
}

if (!empty($filterProdi)) {
    $sql .= " AND prodi = '$filterProdi'";
}

$sql .= " LIMIT $offset, $itemsPerPage";
$result = $conn->query($sql);

$countSql = "SELECT COUNT(*) AS total FROM mahasiswa";
if (!empty($searchKeyword)) {
    $countSql .= " WHERE nim LIKE '%$searchKeyword%' OR nama LIKE '%$searchKeyword%' OR prodi LIKE '%$searchKeyword%'";
}

$countResult = $conn->query($countSql);
$totalRows = $countResult->fetch_assoc()['total'];
$totalPages = ceil($totalRows / $itemsPerPage);

$message = isset($_GET['message']) ? $_GET['message'] : "";

?>

<div class="container">
    <div class="row mt-5 mb-2">
        <h4>Daftar Mahasiswa</h4>
    </div>
    <div class="row justify-content-between align-items-center">
        <div class="col-md-4">
            <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addMahasiswaModal">Tambah</button>
        </div>
        <div class="col-md-4">
            <form action="" method="get">
                <div class="input-group">
                    <input type="search" class="form-control" placeholder="Cari mahasiswa.." name="search" value="<?php echo $searchKeyword; ?>">
                    <button class="btn btn-primary" type="submit">Cari</button>
                </div>
            </form>
        </div>
    </div>
    <div class="row mt-4 justify-content-between align-items-end">
        <div class="col-md-4">
            <p>Total Mahasiswa : <?php echo $totalRows; ?></p>
        </div>
        <div class="col-md-4">
            <form method="get" action="">
                <label for="filterProdi" class="form-label">Filter Prodi:</label>
                <div class="d-flex gap-2">
                    <select class="form-select" id="filterProdi" name="filterProdi">
                        <option value="">Semua Prodi</option>
                        <option value="TI" <?php echo ($filterProdi === 'TI') ? 'selected' : ''; ?>>TI</option>
                        <option value="TRKJ" <?php echo ($filterProdi === 'TRKJ') ? 'selected' : ''; ?>>TRKJ</option>
                        <option value="TRMM" <?php echo ($filterProdi === 'TRMM') ? 'selected' : ''; ?>>TRMM</option>
                    </select>
                    <button type="submit" class="btn btn-primary">Filter</button>
                </div class="d-flex">
            </form>
        </div>
    </div>
    <div class="row mt-1">
        <div class="table-responsive">
            <table class="table">
                <thead>
                    <tr>
                        <th scope="col">#</th>
                        <th scope="col">NIM</th>
                        <th scope="col">Nama</th>
                        <th scope="col">Prodi</th>
                        <th scope="col" width="5%"></th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    if ($result->num_rows > 0) {
                        $no = 1;
                        while ($row = $result->fetch_assoc()) {
                    ?>
                            <tr>
                                <td><?php echo $no++; ?></td>
                                <td><?php echo $row['nim']; ?></td>
                                <td><?php echo $row['nama']; ?></td>
                                <td><?php echo $row['prodi']; ?></td>
                                <td>
                                    <div class="dropdown">
                                        <button class="btn" type="button" data-bs-toggle="dropdown" aria-expanded="false">
                                            <svg xmlns="http://www.w3.org/2000/svg" class="icon icon-tabler icon-tabler-dots-vertical text-dark" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
                                                <path stroke="none" d="M0 0h24v24H0z" fill="none"></path>
                                                <path d="M12 12m-1 0a1 1 0 1 0 2 0a1 1 0 1 0 -2 0"></path>
                                                <path d="M12 19m-1 0a1 1 0 1 0 2 0a1 1 0 1 0 -2 0"></path>
                                                <path d="M12 5m-1 0a1 1 0 1 0 2 0a1 1 0 1 0 -2 0"></path>
                                            </svg>
                                        </button>
                                        <ul class="dropdown-menu position-absolute action">
                                            <li>
                                                <!-- Perbaiki nama id modal yang sesuai dengan id mahasiswa -->
                                                <button type="button" class="dropdown-item" data-bs-toggle="modal" data-bs-target="#<?php echo $row['id']; ?>EditMahasiswaModal">
                                                    Edit
                                                </button>
                                            </li>
                                            <li>
                                                <button type="button" class="dropdown-item" data-bs-toggle="modal" data-bs-target="#<?php echo $row['id']; ?>DeleteMahasiswaModal">
                                                    Hapus
                                                </button>
                                            </li>
                                        </ul>
                                    </div>
                                </td>
                            </tr>
                    <?php
                        }
                    } else {
                        echo "<tr><td colspan='5' align='center'>Tidak ada data mahasiswa</td></tr>";
                    }
                    ?>
                </tbody>
            </table>
        </div>

        <div class="row mt-4">
            <div class="col-md-12">
                <nav aria-label="Page navigation">
                    <ul class="pagination justify-content-center">
                        <?php if ($page > 1) : ?>
                            <li class="page-item">
                                <a class="page-link" href="?page=<?php echo $page - 1; ?>" aria-label="Previous">
                                    <span aria-hidden="true">&laquo;</span>
                                </a>
                            </li>
                        <?php endif; ?>

                        <?php for ($i = 1; $i <= $totalPages; $i++) : ?>
                            <li class="page-item <?php echo $page === $i ? 'active' : ''; ?>">
                                <a class="page-link" href="?page=<?php echo $i; ?>"><?php echo $i; ?></a>
                            </li>
                        <?php endfor; ?>

                        <?php if ($page < $totalPages) : ?>
                            <li class="page-item">
                                <a class="page-link" href="?page=<?php echo $page + 1; ?>" aria-label="Next">
                                    <span aria-hidden="true">&raquo;</span>
                                </a>
                            </li>
                        <?php endif; ?>
                    </ul>
                </nav>
            </div>
        </div>
    </div>

</div>
</div>

<!-- Add Mahasiswa Modal -->
<div class="modal fade" id="addMahasiswaModal" tabindex="-1" role="dialog" aria-labelledby="addMahasiswaModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="addMahasiswaModalLabel">Tambah Mahasiswa</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form method="post" action="controller/create_mahasiswa.php">
                    <div class="mb-3">
                        <label for="nim" class="form-label">NIM</label>
                        <input type="text" class="form-control" id="nim" name="nim" required>
                    </div>
                    <div class="mb-3">
                        <label for="nama" class="form-label">Nama</label>
                        <input type="text" class="form-control" id="nama" name="nama" required>
                    </div>
                    <div class="mb-3">
                        <label for="prodi" class="form-label">Prodi</label>
                        <select class="form-select" id="prodi" name="prodi" required>
                            <option value="TI">TI</option>
                            <option value="TRKJ">TRKJ</option>
                            <option value="TRMM">TRMM</option>
                        </select>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                        <button type="submit" class="btn btn-primary">Simpan</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<!-- Edit Mahasiswa Modal -->
<?php
$result->data_seek(0);

while ($student = $result->fetch_assoc()) {
?>
    <div class="modal fade" id="<?php echo $student['id']; ?>EditMahasiswaModal" tabindex="-1" role="dialog" aria-labelledby="editMahasiswaModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="editMahasiswaModalLabel">Edit Mahasiswa</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form method="post" action="controller/update_mahasiswa.php">
                        <input type="hidden" name="id" value="<?php echo $student['id']; ?>">
                        <div class="mb-3">
                            <label for="nim" class="form-label">NIM</label>
                            <input type="text" class="form-control" id="nim" name="nim" value="<?php echo $student['nim']; ?>" required>
                        </div>
                        <div class="mb-3">
                            <label for="nama" class="form-label">Nama</label>
                            <input type="text" class="form-control" id="nama" name="nama" value="<?php echo $student['nama']; ?>" required>
                        </div>
                        <div class="mb-3">
                            <label for="prodi" class="form-label">Prodi</label>
                            <select class="form-control" id="prodi" name="prodi" required>
                                <option value="TI" <?php if ($student['prodi'] == 'TI') echo 'selected'; ?>>TI</option>
                                <option value="TRKJ" <?php if ($student['prodi'] == 'TRKJ') echo 'selected'; ?>>TRKJ</option>
                                <option value="TRMM" <?php if ($student['prodi'] == 'TRMM') echo 'selected'; ?>>TRMM</option>
                            </select>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                            <button type="submit" class="btn btn-primary">Ubah</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
<?php
}; ?>

<!-- Delete Mahasiswa Modal -->
<?php
$result->data_seek(0);

while ($student = $result->fetch_assoc()) {
?>
    <div class="modal fade" id="<?php echo $student['id']; ?>DeleteMahasiswaModal" tabindex="-1" role="dialog" aria-labelledby="editMahasiswaModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-sm" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="editMahasiswaModalLabel">Hapus Data <?php echo $student['nama']; ?></h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form method="post" action="controller/delete_mahasiswa.php">
                        <input type="hidden" name="id" value="<?php echo $student['id']; ?>">
                        <p>Anda yakin?</p>
                        <div class="row">
                            <div class="col-md-6">
                                <button type="button" class="btn btn-secondary d-block w-100" data-bs-dismiss="modal">Batal</button>
                            </div>
                            <div class="col-md-6">
                                <button type="submit" class="btn btn-primary d-block w-100">Ya</button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
<?php
}; ?>

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
