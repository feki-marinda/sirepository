<?php
session_start();
include('conn.php');

$username = isset($_SESSION['username']) ? $_SESSION['username'] : '';

if (empty($username)) {
    header("Location: ../index.php");
    exit;
}

$query = "SELECT * FROM nilai_PKL INNER JOIN siswa on nilai_pkl.id_siswa=siswa.id_siswa";
$result = $koneksi->query($query);

if (!$result) {
    die("Error: " . $koneksi->error);
}
if (isset($_POST['TambahNilai'])) {
    $id_siswa = $_POST['id_siswa'];
    $nilai = $_POST['nilai'];
    $grade = $_POST['grade'];
    $file = $_POST['file'];

    $query = "INSERT INTO nilai_pkl (id_siswa, grade, nilai, file) 
              VALUES ('$id_siswa', '$grade', '$nilai', '$file')";

    if ($koneksi->query($query) === TRUE) {
        header('Location: datanilai.php');
        exit;
    } else {
        echo 'Error: ' . $koneksi->error;
    }
}
if (isset($_POST['EditNilai'])) {
    $id_nilai = $_POST['id_nilai'];
    $nilai = $_POST['nilai'];
    $id_siswa = $_POST['id_siswa'];
    $grade = $_POST['grade'];
    $file = $_POST['file'];

    $update_query = "UPDATE nilai_pkl SET nilai=?, id_siswa=?, grade=?, file=? WHERE id_nilai=?";

    // Prepared statement
    $stmt = mysqli_prepare($koneksi, $update_query);

    if (!$stmt) {
        // Gagal membuat prepared statement
        echo 'Error creating prepared statement: ' . mysqli_error($koneksi);
        exit;
    }

    // Bind parameters
    $success_bind = mysqli_stmt_bind_param($stmt, "ssssi", $nilai, $id_siswa, $grade, $file, $id_nilai);

    if (!$success_bind) {
        // Gagal binding parameters
        echo 'Error binding parameters: ' . mysqli_stmt_error($stmt);
        exit;
    }

    // Eksekusi statement
    $success_execute = mysqli_stmt_execute($stmt);

    if ($success_execute) {
        // Redirect setelah edit
        header('Location: datanilai.php');
        exit;
    } else {
        echo 'Error updating data: ' . mysqli_stmt_error($stmt);
    }

    // Tutup prepared statement
    mysqli_stmt_close($stmt);
}


if (isset($_GET['id_nilai'])) {
    $id_nilai = $_GET['id_nilai'];

    mysqli_query($koneksi, "DELETE FROM nilai_pkl WHERE id_nilai='$id_nilai'");
}

?>

<style>
    body, table{
        font-family: "Poppins", sans-serif;
    }
    .form,label,input{
        font-family: "Poppins", sans-serif;
    }
</style>

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
                                <button id="printButton">
                                    <i class="fas fa-print"></i> Cetak
                                </button>
                            </div>
                        </div>
                    </div>

                    <div class="card mb-4">
                        <div class="card-header">
                            <i class="fas fa-table me-1"></i>
                            Data Nilai PKL
                        </div>
                        <div class="card-body">
                            <table id="datatablesSimple" class="table table-striped table-hover">
                                <thead>
                                    <tr>
                                        <th>No.</th>
                                        <th>Nama Siswa</th>
                                        <th>Nilai</th>
                                        <th>Grade</th>
                                        <th>File</th>
                                        <th>Keterangan</th>
                                    </tr>
                                </thead>

                                <tbody>
                                    <?php
                                    $no = 1;
                                    $query = "SELECT * FROM nilai_PKL INNER JOIN siswa on nilai_pkl.id_siswa=siswa.id_siswa";
                                    $result = $koneksi->query($query);
                                    while ($row = mysqli_fetch_assoc($result)) {
                                        echo "<tr>";
                                        echo "<td>" . $no++ . "</td>";
                                        echo "<td>" . $row['Nama_siswa'] . "</td>";
                                        echo "<td>" . $row['nilai'] . "</td>";
                                        echo "<td>" . $row['grade'] . "</td>";
                                        echo "<td>" . $row['file'] . "</td>";
                                        echo "<td>";
                                        // Tombol Detail
                                        echo "<div class='btn-group me-2'>";
                                        echo "<button type='button' class='btn btn-success' data-bs-toggle='modal' data-bs-target='#hapus" . $row['id_nilai'] . "'><i class='fa-solid fa-eye'></i> Detail</button>";
                                        echo "</div>";

                                        // Tombol Edit
                                        echo "<div class='btn-group me-2'>";
                                        echo "<button type='button' class='btn btn-primary' data-bs-toggle='modal' data-bs-target='#edit" . $row['id_nilai'] . "' data-bs-whatever='@mdo'><i class='nav-icon fas fa-edit'></i> Edit</button>";
                                        echo "</div>";

                                        // Tombol Hapus
                                        echo "<div class='btn-group mt-2'>";
                                        echo "<button type='button' class='btn btn-danger' data-bs-toggle='modal' data-bs-target='#hapus" . $row['id_nilai'] . "'><i class='nav-icon fas fa-trash-alt'></i> Hapus</button>";
                                        echo "</div>";

                                        echo "</td>";
                                        echo "</tr>";
                                        ?>
                                        <div class="modal fade" id='hapus<?= $row['id_nilai'] ?>' tabindex="-1"
                                            role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
                                            <div class="modal-dialog">
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
                                                        <a href="datanilai.php?id_nilai=<?= $row['id_nilai'] ?>"
                                                            class="btn btn-danger">Delete</a>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>

                                        <div class='modal fade' id='edit<?= $row['id_nilai'] ?>' tabindex='-1'
                                            aria-labelledby='exampleModalLabel' aria-hidden='true'>
                                            <div class="modal-dialog">
                                                <div class="modal-content">
                                                    <div class="modal-header">
                                                        <h5 class="modal-title" id="exampleModalLabel">Edit Data nilai</h5>
                                                        <button type="button" class="btn-close" data-bs-dismiss="modal"
                                                            aria-label="Close"></button>
                                                    </div>
                                                    <div class="modal-body">
                                                        <?php
                                                        var_dump($row['id_siswa']);
                                                        var_dump($row['Nama_siswa']);
                                                        ?>
                                                        <div class="modal-body">
                                                            <form method="POST" action="#" enctype="multipart/form-data">
                                                                <div class="form-group">
                                                                    <label for="id_siswa">ID</label>
                                                                    <input type="text" class="form-control" id="id_siswa"
                                                                        value="<?= $row['id_siswa']; ?>" name="id_siswa"
                                                                        readonly>
                                                                </div>
                                                                <div class="form-group">
                                                                    <label for="Nama_siswa">Nama Siswa</label>
                                                                    <input type="text" class="form-control" id="Nama_siswa"
                                                                        value="<?= $row['Nama_siswa']; ?>" name="Nama_siswa"
                                                                        required>
                                                                </div>
                                                                <div class="form-group">
                                                                    <label for="nilai">Rata-rata Nilai</label>
                                                                    <input type="int" class="form-control" id="nilai"
                                                                        value="<?= $row['nilai']; ?>" name="nilai" required>
                                                                </div>
                                                                <div class="form-group">
                                                                    <label for="grade">Grade</label>
                                                                    <input type="text" class="form-control" id="grade"
                                                                        value="<?= $row['grade']; ?>" name="grade" required>
                                                                </div>
                                                                <div class="form-group">
                                                                    <label for="file">File</label>
                                                                    <input type="text" class="form-control" id="file"
                                                                        value="<?= $row['file']; ?>" name="file" required>
                                                                </div>
                                                                <div class="modal-footer">
                                                                    <button type="button" class="btn btn-secondary"
                                                                        data-bs-dismiss="modal">Close</button>
                                                                    <button type="submit" class="btn btn-primary"
                                                                        name="EditNilai" value="Submit">Submit</button>
                                                                </div>
                                                            </form>
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
                <div class="modal-dialog">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" id="exampleModalLabel">Tambah Data nilai</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                        <div class="modal-body ">
                            <form action="#" method="post" id="formTambahData">
                                <div class="mb-3">
                                    <label for="nama_siswa" class="col-form-label">Nama Siswa :</label>
                                    <select name="id_siswa" id="id_siswa" required>
                                        <?php
                                        $query_siswa = "SELECT * FROM siswa";
                                        $result_siswa = $koneksi->query($query_siswa);
                                        while ($row_siswa = mysqli_fetch_assoc($result_siswa))
                                            echo "<option value='" . $row_siswa['id_siswa'] . "'>" . $row_siswa['Nama_siswa'] . "</option>";
                                        ?>
                                    </select>
                                </div>
                                <div class="mb-3">
                                    <label for="nilai" class="col-form-label">Rata-rata Nilai :</label>
                                    <input type="int" class="form-control" id="nilai" name="nilai" required>
                                </div>
                                <div class="mb-3">
                                    <label for="grade" class="col-form-label">Grade :</label>
                                    <input type="text" class="form-control" id="grade" name="grade" required>
                                </div>
                                <div class="mb-3">
                                    <label for="file" class="col-form-label">file :</label>
                                    <input type="text" class="form-control" id="file" name="file" required>
                                </div>
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



            <footer class="py-4 bg-light mt-auto">
                <div class="container-fluid px-4">
                    <div class="d-flex align-items-center justify-content-between small">
                        <div class="text-muted">Copyright &copy; Your Website 2023</div>
                        <div>
                            <a href="#">Privacy Policy</a>
                            &middot;
                            <a href="#">Terms &amp; Conditions</a>
                        </div>
                    </div>
                </div>
            </footer>
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

</body>

</html>