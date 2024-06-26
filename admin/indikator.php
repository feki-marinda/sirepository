<?php
session_start();
include('conn.php');

$status = isset($_SESSION['status']) ? $_SESSION['status'] : '';

if (empty($status)) {
    header("Location: ../index.php");
    exit;
}

include 'conn.php';

if (isset($_POST['Simpanindikator'])) {
    $indikator = $_POST['indikator'];
    $result = mysqli_query($koneksi, "INSERT INTO indikator (indikator) VALUES ('$indikator')");

    if ($result) {
        $_SESSION['admin_success_indikator'] = "Data Indikator berhasil ditambahkan!";
    } else {
        $_SESSION['admin_error_indikator'] = "Gagal menambahkan data Indikator: " . mysqli_error($koneksi);
    }
}

if (isset($_POST['Editindikator'])) {
    $id_indikator = $_POST['id_indikator'];
    $indikator = $_POST['indikator'];
    $result = mysqli_query($koneksi, "UPDATE indikator SET indikator='$indikator' WHERE id_indikator='$id_indikator'");

    if ($result) {
        $_SESSION['admin_success_indikator'] = "Data Indikator Berhasil Diubah!";
    } else {
        $_SESSION['admin_error_indikator'] = "Gagal mengubah data Indikator: " . mysqli_error($koneksi);
    }
}

if (isset($_GET['id_indikator'])) {
    $id_indikator = $_GET['id_indikator'];
    $result = mysqli_query($koneksi, "DELETE FROM indikator WHERE id_indikator='$id_indikator'");

    if ($result) {
        $_SESSION['admin_success_indikator'] = "Data Indikator berhasil dihapus!";
    } else {
        $_SESSION['admin_error_indikator'] = "Gagal menghapus data Indikator: " . mysqli_error($koneksi);
    }
}
?>

<!DOCTYPE html>
<html lang="en">

<?php include 'head.html'; ?>

<body class="sb-nav-fixed">

<?php include 'header.php'; ?>
<div id="layoutSidenav" style="width: 100%">
<div id="layoutSidenav_content">
<div class="container-fluid">

    <div class="card bg-light mt-3">
        <div class="card-body">
            <h2 class="text-center">Tambah Indikator Penilaian</h2>

            <form method="post" action="#">
                <div class="form-group">
                    <label for="indikator">Indikator:</label>
                    <input type="text" class="form-control" id="indikator" name="indikator" required>
                </div>
                <button type="submit" class="btn btn-primary mt-2" name="Simpanindikator">Simpan</button>
            </form>
        </div>
    </div>

    <div class="">
        <h2 style="text-align: center;">Indikator Penilaian Praktik Kerja Lapangan</h2>
        <table class="table table-bordered table-striped mt-3">

        <?php
                        if (isset($_SESSION['admin_error_indikator']) && !empty($_SESSION['admin_error_indikator'])) {
                            echo '<div class="alert alert-danger" role="alert">' . $_SESSION['admin_error_indikator'] . '</div>';
                            unset($_SESSION['admin_error_indikator']);
                        }

                        if (isset($_SESSION['admin_success_indikator']) && !empty($_SESSION['admin_success_indikator'])) {
                            echo '<div class="alert alert-success" role="alert">' . $_SESSION['admin_success_indikator'] . '</div>';
                            unset($_SESSION['admin_success_indikator']);
                        }
                        ?>

            <thead>
                <tr>
                    <th>No</th>
                    <th>Indikator</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
                <?php
                $query = "SELECT * FROM indikator";
                $result = mysqli_query($koneksi, $query);
                $no = 1;
                while ($row = mysqli_fetch_assoc($result)) {
                    echo "<tr>";
                    echo "<td>" . $no++ . "</td>";
                    echo "<td>" . $row['indikator'] . "</td>";
                    echo '<td>                
                        <a href="" data-toggle="modal" data-target="#editindikator' . $row['id_indikator'] . '" class="btn btn-primary btn-sm"><i class="nav-icon fas fa-edit"></i> Edit</a>
                        <a href="" data-toggle="modal" data-target="#deleteindikator' . $row['id_indikator'] . '" class="btn btn-danger btn-sm"><i class="nav-icon fas fa-trash-alt"></i> Hapus</a>
                    </td>';
                    echo "</tr>";
                }
                ?>
            </tbody>
        </table>
    </div>

    <?php
    $query = "SELECT * FROM indikator";
    $result = mysqli_query($koneksi, $query);
    while ($row = mysqli_fetch_assoc($result)) {
        ?>
        <div class="modal fade" id="editindikator<?php echo $row['id_indikator']; ?>">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h4 class="modal-title">Edit Data indikator</h4>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                       
                        <form method="post" action="">
                            <div class="card-body">
                                <div class="form-group">
                                    <label for="ID indikator">ID</label>
                                    <input type="text" class="form-control" id="id_indikator"
                                        value="<?= $row['id_indikator']; ?>" name="id_indikator" readonly>
                                </div>
                                <div class="form-group">
                                    <label for="indikator">Indikator</label>
                                    <input type="text" class="form-control" id="indikator"
                                        value="<?= $row['indikator']; ?>" name="indikator" required>
                                </div>
                                <div class="modal-footer justify-content-between">
                                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                                    <button type="submit" class="btn btn-primary" name="Editindikator">Submite</button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>

        <div class="modal fade" id="deleteindikator<?php echo $row['id_indikator']; ?>">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h4 class="modal-title">Delete Data indikator</h4>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <p align="center">Apakah anda yakin ingin menghapus indikator <strong>
                                <?= $row['indikator']; ?>
                            </strong>?</p>
                    </div>
                    <div class="modal-footer justify-content-between">
                        <button type="button" class="btn btn-danger" data-dismiss="modal">Cancel</button>
                        <a href="indikator.php?id_indikator=<?= $row['id_indikator']; ?>"
                            class="btn btn-primary">Delete</a>
                    </div>
                </div>
            </div>
        </div>
    <?php } ?>
    
</div>
</div>
</div>
<script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.5.4/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/js/bootstrap.bundle.min.js"
        crossorigin="anonymous"></script>
    <script src="js/scripts.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/2.8.0/Chart.min.js" crossorigin="anonymous"></script>
    <script src="assets/demo/chart-area-demo.js"></script>
    <script src="assets/demo/chart-bar-demo.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/simple-datatables@7.1.2/dist/umd/simple-datatables.min.js"
        crossorigin="anonymous"></script>
    <script src="js/datatables-simple-demo.js"></script>
    <script src="https://code.jquery.com/jquery-3.6.4.min.js"></script>

    <script src="https://code.jquery.com/jquery-3.6.4.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/js/bootstrap.bundle.min.js"
        crossorigin="anonymous"></script>

</body>
</html>
