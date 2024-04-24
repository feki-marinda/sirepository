<?php
session_start();
include('conn.php');

$status = isset($_SESSION['status']) ? $_SESSION['status'] : '';

if (empty($status)) {
    header("Location: ../index.php");
    exit;
}

$admin_seccess_nilai = '';
$query = "SELECT nilai_pkl.id_nilai, nilai_pkl.nilai, siswa.id_siswa, siswa.Nama_siswa, indikator.id_indikator, indikator.indikator 
FROM nilai_PKL 
INNER JOIN siswa ON nilai_pkl.id_siswa = siswa.id_siswa 
INNER JOIN indikator ON indikator.id_indikator = nilai_pkl.id_indikator 
GROUP BY siswa.id_siswa;
";
$result = $koneksi->query($query);
if (!$result) {
    die("Error: " . $koneksi->error);
}

if (isset($_POST['TambahNilai'])) {
    $id_siswa = $_POST['id_siswa'];

    for ($i = 0; $i < 10; $i++) {
        $id_indikator = $_POST['id_indikator' . $i];
        $nilai = $_POST['nilai' . $i];

        $query_insert = "INSERT INTO nilai_pkl (id_siswa, id_indikator, nilai) VALUES ('$id_siswa', '$id_indikator', '$nilai')";
        $result_insert = mysqli_query($koneksi, $query_insert);

        if ($result_insert) {
            $_SESSION['admin_success_nilai'] = "Data Nilai berhasil ditambahkan!";
        } else {
            $_SESSION['admin_error_nilai'] = "Error: " . mysqli_error($koneksi);
            break; 
        }
    }

    header("Location: datanilai.php");
    exit();
}


if (isset($_POST['EditNilai'])) {
    $id_nilai = $_POST['id_nilai'];
    $id_siswa = $_POST['id_siswa'];

    for ($i = 0; $i < 10; $i++) {
        $id_indikator = $_POST['id_indikator' . $i];
        $nilai = $_POST['nilai' . $i];

        mysqli_query($koneksi, "UPDATE nilai_pkl SET id_siswa = '$id_siswa', id_indikator = '$id_indikator', nilai = '$nilai' WHERE id_nilai = '$id_nilai'");
    }

    $_SESSION['admin_seccess_nilai'] = "Data nilai telah berhasil diperbarui.";
    header("Location: datanilai.php");
    exit();
}

if (isset($_GET['id_siswa'])) {
    $id_siswa = $_GET['id_siswa'];
    $delete_query = "DELETE FROM nilai_pkl WHERE id_siswa='$id_siswa'";

    if (mysqli_query($koneksi, $delete_query)) {
        $_SESSION['admin_seccess_nilai'] = "Berhasil Menghapus Data Nilai!";
        header("Location: datanilai.php");
        exit();
    } else {
        $_SESSION['admin_error_nilai'] = "Error: Tidak Dapat Menghapus Data Nilai !";
        header("Location: datanilai.php");
        exit();
    }
}


?>


<!DOCTYPE html>
<html lang="en">

<?php include 'head.html' ?>

<body class="sb-nav-fixed">
    <?php include 'header.php' ?>
    <div id="layoutSidenav" style="width: 100%">

        <div id="layoutSidenav_content">
            <main>
                <div class="container-fluid px-4">
                    <h1 class="mt-4">Data Nilai PKL</h1>
                    <ol class="breadcrumb mb-4">
                        <li class="breadcrumb-item"><a href="index.php">Dashboard</a></li>
                        <li class="breadcrumb-item active">Tables</li>
                    </ol>

                    <div class="card mb-4">
                        <div class="button-container">
                            <div class="spacer"></div>
                            <div class="buttons-right">
                                <button type="button" class="btn btn-primary" data-bs-toggle="modal"
                                    data-bs-target="#tambah" data-bs-whatever="@mdo"> <i class="fas fa-plus"></i>
                                    Tambah Data Nilai PKL</button>                                
                            </div>
                        </div>
                    </div>

                    <div class="card mb-4">
                        <div class="card-header">
                            <i class="fas fa-table me-1"></i>
                            Data Nilai PKL
                        </div>
                        <div class="card-body">
                            <?php
                            if (isset($_SESSION['admin_error_nilai']) && !empty($_SESSION['admin_error_nilai'])) {
                                echo '<div class="alert alert-danger" role="alert">' . $_SESSION['admin_error_nilai'] . '</div>';
                                unset($_SESSION['admin_error_nilai']);
                            }

                            if (isset($_SESSION['admin_seccess_nilai']) && !empty($_SESSION['admin_seccess_nilai'])) {
                                echo '<div class="alert alert-success" role="alert">' . $_SESSION['admin_seccess_nilai'] . '</div>';
                                unset($_SESSION['admin_seccess_nilai']);
                            }
                            ?>
                            <table id="datatablesSimple" class="table table-striped table-hover">
                                <thead>
                                    <tr>
                                        <th>No.</th>
                                        <th>Nama Siswa</th>
                                        <th>Detail</th>
                                        <th>Keterangan</th>
                                    </tr>
                                </thead>

                                <tbody>
                                    <?php
                                    $no = 1;

                                    while ($row = mysqli_fetch_assoc($result)) {
                                        echo "<tr>";
                                        echo "<td>" . $no++ . "</td>";
                                        echo "<td>" . $row['Nama_siswa'] . "</td>";
                                        echo "<td><a href='detailnilai.php?id_siswa=" . $row['id_siswa'] . "'>Detail Nilai</a></td>";
                                        echo "<td>";
                                        echo "<button type='button' class='btn btn-danger' data-bs-toggle='modal' data-bs-target='#hapus" . $row['id_nilai'] . "'>";
                                        echo "<i class='fas fa-trash'></i> Hapus";
                                        echo "</button>";
                                        echo "</td>";
                                        echo "</tr>";
                                        ?>
                                        <div class="modal fade" id='hapus<?= $row['id_nilai'] ?>' tabindex="-1"
                                            role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
                                            <div class="modal-dialog modal-lg">
                                                <div class="modal-content">
                                                    <div class="modal-header">
                                                        <h5 class="modal-title" id="exampleModalLabel">Hapus Data Nilai
                                                        </h5>
                                                        <button type="button" class="btn-close" data-bs-dismiss="modal"
                                                            aria-label="Close"></button>
                                                    </div>
                                                    <div class="modal-body">
                                                        Apakah anda yakin ingin menghapus data nilai?
                                                    </div>
                                                    <div class="modal-footer">
                                                        <button type="button" class="btn btn-secondary"
                                                            data-bs-dismiss="modal">Tidak</button>                                                            
                                                        <a href="datanilai.php?id_siswa=<?= $row['id_siswa'] ?>"
                                                            class="btn btn-danger">Delete</a>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>

                                        <?php
                                    }
                                    ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </main>


            <div class="modal fade" id="tambah" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
                <div class="modal-dialog modal-lg">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" id="exampleModalLabel">Tambah Data Nilai</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                        <div class="modal-body ">
                            <form method="POST" action="#" enctype="multipart/form-data">
                                <input type="hidden" name="id_nilai" value="<?= $row['id_nilai']; ?>">
                                <div class="form-group">
                                    <label for="id_siswa" class="col-form-label">Nama Siswa :</label>
                                    <select name="id_siswa" id="id_siswa" class="form-control" required>
                                        <?php
                                        $query_siswa = "SELECT * FROM siswa INNER JOIN PKL ON siswa.id_siswa=PKL.id_siswa";
                                        $result_siswa = $koneksi->query($query_siswa);
                                        while ($row_siswa = mysqli_fetch_assoc($result_siswa))
                                            echo "<option value='" . $row_siswa['id_siswa'] . "'>" . $row_siswa['Nama_siswa'] . "</option>";
                                        ?>
                                    </select>
                                </div>

                                <?php for ($i = 0; $i < 10; $i++): ?>
                                    <div class="row">
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label for="id_indikator<?= $i ?>" class="col-form-label">Indikator
                                                    Penilaian</label>
                                                <select name="id_indikator<?= $i ?>" id="id_indikator<?= $i ?>"
                                                    class="form-control" required>
                                                    <?php
                                                    $query_siswa = "SELECT * FROM indikator";
                                                    $result_siswa = $koneksi->query($query_siswa);
                                                    while ($row_siswa = mysqli_fetch_assoc($result_siswa))
                                                        echo "<option value='" . $row_siswa['id_indikator'] . "'>" . $row_siswa['indikator'] . "</option>";
                                                    ?>
                                                </select>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label for="nilai<?= $i ?>" class="col-form-label">Nilai</label>
                                                <input type="text" class="form-control" id="nilai<?= $i ?>"
                                                    name="nilai<?= $i ?>" required>
                                            </div>
                                        </div>
                                    </div>
                                <?php endfor; ?>

                                <div class="modal-footer">
                                    <button type="button" class="btn btn-secondary"
                                        data-bs-dismiss="modal">Close</button>
                                    <button type="submit" class="btn btn-primary" name="TambahNilai" value="Submit"
                                        id="submit">Submit</button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
            
        </div>
    </div>
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