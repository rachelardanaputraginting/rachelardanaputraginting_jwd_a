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

$sql = "SELECT * FROM barang";
if (!empty($searchKeyword)) {
    $sql .= " WHERE kode_barang LIKE '%$searchKeyword%' OR nama_barang LIKE '%$searchKeyword%' OR kategori LIKE '%$searchKeyword%'";
}

$sql .= " LIMIT $offset, $itemsPerPage";
$result = $conn->query($sql);

$countSql = "SELECT COUNT(*) AS total FROM barang";
if (!empty($searchKeyword)) {
    $countSql .= " WHERE kode_barang LIKE '%$searchKeyword%' OR nama_barang LIKE '%$searchKeyword%' OR kategori LIKE '%$searchKeyword%'";
}

$countResult = $conn->query($countSql);
$totalRows = $countResult->fetch_assoc()['total'];
$totalPages = ceil($totalRows / $itemsPerPage);

$message = isset($_GET['message']) ? $_GET['message'] : "";
?>

<div class="container">
    <div class="row mt-5 mb-2">
        <h4>Daftar Barang</h4>
    </div>
    <div class="row justify-content-between align-items-center">
        <div class="col-md-4">
            <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addBarangModal">Tambah</button>
        </div>
        <div class="col-md-4">
            <form action="" method="get">
                <div class="input-group">
                    <input type="search" class="form-control" placeholder="Cari barang.." name="search" value="<?php echo $searchKeyword; ?>">
                    <button class="btn btn-primary" type="submit">Cari</button>
                </div>
            </form>
        </div>
    </div>
    <div class="row mt-4 justify-content-between">
        <div class="col-md-4">
            <p>Total Barang : <?php echo $totalRows; ?></p>
        </div>
    </div>
    <div class="row mt-1">
        <div class="table-responsive">
            <table class="table">
                <thead>
                    <tr>
                        <th scope="col">#</th>
                        <th scope="col">Kode Barang</th>
                        <th scope="col">Nama Barang</th>
                        <th scope="col">Harga</th>
                        <th scope="col">Stok</th>
                        <th scope="col">Kategori</th>
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
                                <td><?php echo $row['kode_barang']; ?></td>
                                <td><?php echo $row['nama_barang']; ?></td>
                                <td><?php echo 'Rp ' . number_format($row['harga'], 0, ',', '.'); ?></td>
                                <td><?php echo $row['stok']; ?></td>
                                <td><?php echo $row['kategori']; ?></td>
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
                                            <!-- Adjust the target modals to match the ones for editing and deleting barang -->
                                            <li>
                                                <button type="button" class="dropdown-item" data-bs-toggle="modal" data-bs-target="#<?php echo $row['id']; ?>EditBarangModal">
                                                    Edit
                                                </button>
                                            </li>
                                            <li>
                                                <button type="button" class="dropdown-item" data-bs-toggle="modal" data-bs-target="#<?php echo $row['id']; ?>DeleteBarangModal">
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
                        echo "<tr><td colspan='7' align='center'>Tidak ada data barang</td></tr>";
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
                                <a class="page-link" href="?page=<?php echo $page - 1; ?>&search=<?php echo $searchKeyword; ?>" aria-label="Previous">
                                    <span aria-hidden="true">&laquo;</span>
                                </a>
                            </li>
                        <?php endif; ?>

                        <?php for ($i = 1; $i <= $totalPages; $i++) : ?>
                            <li class="page-item <?php echo $page === $i ? 'active' : ''; ?>">
                                <a class="page-link" href="?page=<?php echo $i; ?>&search=<?php echo $searchKeyword; ?>"><?php echo $i; ?></a>
                            </li>
                        <?php endfor; ?>

                        <?php if ($page < $totalPages) : ?>
                            <li class="page-item">
                                <a class="page-link" href="?page=<?php echo $page + 1; ?>&search=<?php echo $searchKeyword; ?>" aria-label="Next">
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

<!-- Add Barang Modal -->
<div class="modal fade" id="addBarangModal" tabindex="-1" role="dialog" aria-labelledby="addBarangModalLabel" aria-hidden="true">
<div class="modal-dialog" role="document">
    <div class="modal-content">
        <div class="modal-header">
            <h5 class="modal-title" id="addBarangModalLabel">Tambah Barang</h5>
            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <div class="modal-body">
            <form method="post" action="controller/create_barang.php">
                <div class="mb-3">
                    <label for="kode_barang" class="form-label">Kode Barang</label>
                    <input type="text" class="form-control" id="kode_barang" name="kode_barang" required>
                </div>
                <div class="mb-3">
                    <label for="nama_barang" class="form-label">Nama Barang</label>
                    <input type="text" class="form-control" id="nama_barang" name="nama_barang" required>
                </div>
                <div class="mb-3">
                    <label for="harga" class="form-label">Harga</label>
                    <input type="number" class="form-control" id="harga" name="harga" required>
                </div>
                <div class="mb-3">
                    <label for="stok" class="form-label">Stok</label>
                    <input type="number" class="form-control" id="stok" name="stok" required>
                </div>
                <div class="mb-3">
                    <label for="kategori" class="form-label">Kategori</label>
                    <input type="text" class="form-control" id="kategori" name="kategori" required>
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

<!-- Edit Barang Modal -->
<?php
$result->data_seek(0);

while ($barang = $result->fetch_assoc()) {
?>
    <div class="modal fade" id="<?php echo $barang['id']; ?>EditBarangModal" tabindex="-1" role="dialog" aria-labelledby="editBarangModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
    <div class="modal-content">
        <div class="modal-header">
            <h5 class="modal-title" id="editBarangModalLabel">Edit Barang</h5>
            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <div class="modal-body">
            <form method="post" action="controller/update_barang.php">
                <input type="hidden" name="id" value="<?php echo $barang['id']; ?>">
                <div class="mb-3">
                    <label for="kode_barang" class="form-label">Kode Barang</label>
                    <input type="text" class="form-control" id="kode_barang" name="kode_barang" value="<?php echo $barang['kode_barang']; ?>" required>
                </div>
                <div class="mb-3">
                    <label for="nama_barang" class="form-label">Nama Barang</label>
                    <input type="text" class="form-control" id="nama_barang" name="nama_barang" value="<?php echo $barang['nama_barang']; ?>" required>
                </div>
                <div class="mb-3">
                    <label for="harga" class="form-label">Harga</label>
                    <input type="number" class="form-control" id="harga" name="harga" value="<?php echo $barang['harga']; ?>" required>
                </div>
                <div class="mb-3">
                    <label for="stok" class="form-label">Stok</label>
                    <input type="number" class="form-control" id="stok" name="stok" value="<?php echo $barang['stok']; ?>" required>
                </div>
                <div class="mb-3">
                    <label for="kategori" class="form-label">Kategori</label>
                    <input type="text" class="form-control" id="kategori" name="kategori" value="<?php echo $barang['kategori']; ?>" required>
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

<!-- Delete Barang Modal -->
<?php
$result->data_seek(0);

while ($barang = $result->fetch_assoc()) {
?>
    <div class="modal fade" id="<?php echo $barang['id']; ?>DeleteBarangModal" tabindex="-1" role="dialog" aria-labelledby="deleteBarangModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-sm" role="document">
    <div class="modal-content">
        <div class="modal-header">
            <h5 class="modal-title" id="deleteBarangModalLabel">Hapus Data Barang</h5>
            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <div class="modal-body">
            <form method="post" action="controller/delete_barang.php">
                <input type="hidden" name="id" value="<?php echo $barang['id']; ?>">
                <p>Anda yakin menghapus <strong><?php echo $barang['nama_barang']; ?></strong>?</p>
                <div class="row">
                    <div class="col-md-6">
                        <button type="button" class="btn btn-secondary d-block w-100" data-bs-dismiss="modal">Batal</button>
                    </div>
                    <div class="col-md-6">
                        <button type="submit" class="btn btn-danger d-block w-100">Ya</button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>

    </div>
<?php
}; ?>

<!-- Your toast message code -->
<?php if ($message != "") { ?>
    <div class="position-fixed top-0 start-50 translate-middle-x p-3" style="z-index: 9999;">
        <div class="toast align-items-center text-white bg-primary border-0" role="alert" aria-live="assertive" aria-atomic="true" data-bs-autohide="true" data-bs-delay="3000">
            <div class="toast-body d-flex align-items-center px-3 py-1">
                <p class="ms-2 mt-3"><?php echo $message; ?></p>
            </div>
        </div>
    </div>
<?php } ?>

<?php include 'footer.php'; ?>
