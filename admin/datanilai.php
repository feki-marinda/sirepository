<?php
session_start();
require_once 'conn.php';

$success_message = ''; 
$query = "SELECT siswa.id_siswa, nilai_pkl.id_nilai, siswa.Nama_siswa, nilai_pkl.nilai, nilai_pkl.grade, nilai_pkl.file FROM nilai_PKL INNER JOIN siswa ON nilai_pkl.id_siswa = siswa.id_siswa";
$result = $koneksi->query($query);
if (!$result) {
    die("Error: " . $koneksi->error);
}

$ekstensi_file = array('pdf', 'doc', 'docx', 'xls', 'xlsx');
$max_file_size = 5 * 1024 * 1024; // 5MB

if (isset($_POST['TambahNilai'])) {
    $id_siswa = $_POST['id_siswa'];
    $nilai = $_POST['nilai'];
    $grade = $_POST['grade'];

    // Periksa apakah file diunggah
    if (isset($_FILES['file']) && $_FILES['file']['error'] == 0) {
        // Mengelola file yang diunggah
        $rand = rand();
        $file_name = $_FILES['file']['name'];
        $file_tmp = $_FILES['file']['tmp_name'];
        $file_path = "Nilai/" . $rand . '_' . $file_name;

        // Validasi jenis file yang diijinkan
        $ekstensi_file = array('pdf', 'doc', 'docx');
        $file_extension = pathinfo($file_name, PATHINFO_EXTENSION);

        if (in_array($file_extension, $ekstensi_file)) {
            $max_file_size = 5 * 1024 * 1024; // 5MB
            if ($_FILES['file']['size'] <= $max_file_size) {
                // Pindahkan file ke direktori yang diinginkan
                if (move_uploaded_file($file_tmp, $file_path)) {
                    // Gunakan prepared statement untuk mencegah SQL injection
                    $query = "INSERT INTO nilai_pkl (id_siswa, grade, nilai, file) VALUES (?, ?, ?, ?)";

                    $stmt = $koneksi->prepare($query);

                    $stmt->bind_param("isss", $id_siswa, $grade, $nilai, $file_path);

                    if ($stmt->execute()) {
                        $_SESSION['success_message'] = "Data Nilai Berhasil Ditambahkan!";
                        header('Location: datanilai.php');
                        exit;
                    } else {
                        $_SESSION['error_message'] = 'Error executing query: ' . $stmt->error;
                        header('Location: datanilai.php');
                        exit;
                    }

                } else {
                    $_SESSION['error_message'] = 'Error uploading file.';
                    header('Location: datanilai.php');
                    exit;
                }
            } else {
                $_SESSION['error_message'] = 'File size exceeds the allowed limit.';
                header('Location: datanilai.php');
                exit;
            }
        } else {
            $_SESSION['error_message'] = 'Invalid file type. Allowed types: ' . implode(', ', $ekstensi_file);
            header('Location: datanilai.php');
            exit;
        }
    } else {
        $_SESSION['error_message'] = 'No file uploaded or error in file upload.';
        header('Location: datanilai.php');
        exit;
    }

}


if (isset($_POST['EditNilai'])) {
    $id_nilai = $_POST['id_nilai'];
    $nilai = $_POST['nilai'];
    $grade = $_POST['grade'];

    if (!empty($_FILES['file']['name'])) {
        $rand = rand();
        $file_name = $_FILES['file']['name'];
        $file_size = $_FILES['file']['size'];
        $file_extension = pathinfo($file_name, PATHINFO_EXTENSION);

        if (
            in_array($file_extension, $ekstensi_file) && $file_size <= $max_file_size &&
            move_uploaded_file($_FILES['file']['tmp_name'], 'Nilai/' . ($file_path = $rand . '_' . $file_name))
        ) {
            // Query update data
            $query = "UPDATE nilai_pkl SET nilai = ?, grade = ?, file = ? WHERE id_nilai = ?";
            $stmt = $koneksi->prepare($query);
            $stmt->bind_param("ssss", $nilai, $grade, $file_path, $id_nilai);

            if ($stmt->execute()) {
                $_SESSION['success_message'] = "Data berhasil diubah!";
            } else {
                $_SESSION['error_message'] = "Error: " . $koneksi->error;
            }
        } else {
            $_SESSION['error_message'] = 'Error uploading file.';
        }
    } else {
        // Tidak ada file baru diunggah, cek apakah ada perubahan
        $queryCheck = "SELECT nilai FROM nilai_pkl WHERE id_nilai = $id_nilai";
        $resultCheck = $koneksi->query($queryCheck);

        if ($resultCheck->num_rows === 0 || $resultCheck->fetch_assoc()['nilai'] === $nilai) {
            $_SESSION['error_message'] = "Tidak ada perubahan data yang dilakukan.";
            header("Location: datanilai.php");
            exit();
        }

        // Query update data
        $query = "UPDATE nilai_pkl SET nilai = ?, grade = ? WHERE id_nilai = ?";
        $stmt = $koneksi->prepare($query);
        $stmt->bind_param("sss", $nilai, $grade, $id_nilai);

        if ($stmt->execute()) {
            $_SESSION['success_message'] = "Nilai berhasil diubah!";
        } else {
            $_SESSION['error_message'] = "Error: " . $koneksi->error;
        }
    }

    header("Location: datanilai.php");
    exit();
}

if (isset($_GET['id_nilai'])) {
    $id_nilai = $_GET['id_nilai'];

    mysqli_query($koneksi, "DELETE FROM nilai_pkl WHERE id_nilai='$id_nilai'");
    if ($result) {
        $_SESSION['success_message'] = "Nilai berhasil dihapus!";
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
                                    <button id="printButton">
                                    <a href="cetak/datanilai.php" style="text-decoration: none; color: inherit;" target="_blank">
                                        <i class="fas fa-print"></i> Cetak
                                    </a>
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
                            <?php
                            if (isset($_SESSION['error_message']) && !empty($_SESSION['error_message'])) {
                                echo '<div class="alert alert-danger" role="alert">' . $_SESSION['error_message'] . '</div>';
                                unset($_SESSION['error_message']);
                            }

                            if (isset($_SESSION['success_message']) && !empty($_SESSION['success_message'])) {
                                echo '<div class="alert alert-success" role="alert">' . $_SESSION['success_message'] . '</div>';
                                unset($_SESSION['success_message']);
                            }
                            ?>
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

                                    while ($row = mysqli_fetch_assoc($result)) {
                                        echo "<tr>";
                                        echo "<td>" . $no++ . "</td>";
                                        echo "<td>" . $row['Nama_siswa'] . "</td>";
                                        echo "<td>" . $row['nilai'] . "</td>";
                                        echo "<td>" . $row['grade'] . "</td>";
                                        echo "<td><a href='Nilai/" . $row['file'] . "' target='_blank'>" . $row['file'] . "</a></td>";
                                        echo "<td>";
                                        echo "<div class='d-flex'>";
                                        echo "<button type='button' class='btn btn-primary me-2' data-bs-toggle='modal' data-bs-target='#edit" . $row['id_nilai'] . "' data-bs-whatever='@mdo'>";
                                        echo "<i class='fas fa-pencil-alt'></i> Edit";
                                        echo "</button>";
                                        echo "<button type='button' class='btn btn-danger' data-bs-toggle='modal' data-bs-target='#hapus" . $row['id_nilai'] . "'>";
                                        echo "<i class='fas fa-trash'></i> Hapus";
                                        echo "</button>";
                                        echo "</div>";
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
                                                        <h5 class="modal-title" id="exampleModalLabel">Edit Data Nilai
                                                        </h5>
                                                        <button type="button" class="btn-close" data-bs-dismiss="modal"
                                                            aria-label="Close"></button>
                                                    </div>
                                                    <div class="modal-body">
                                                        <form method="post" action="#" enctype="multipart/form-data">
                                                            <div class="form-group">
                                                                <div class="form-group">
                                                                    <label for="id_nilai">ID</label>
                                                                    <input type="text" class="form-control" id="id_nilai"
                                                                        value="<?= $row['id_nilai']; ?>" name="id_nilai"
                                                                        readonly>
                                                                </div>
                                                                <div class="form-group">
                                                                    <label for="Nama_siswa">Nama siswa</label>
                                                                    <input type="text" class="form-control" id="Nama_siswa"
                                                                        value="<?= $row['Nama_siswa']; ?>" name="Nama_siswa"
                                                                        required>
                                                                </div>
                                                                <div class="mb-3">
                                                                    <label for="file" class="col-form-label">file
                                                                        :</label>
                                                                    <small class="form-text text-muted">Abaikan jika tidak
                                                                        merubah file.</small>
                                                                    <input type="file" class="form-control" id="file"
                                                                        name="file" accept=".doc, .docx, .pdf">
                                                                </div>
                                                                <div class="form-group">
                                                                    <label for="nilai">Nilai</label>
                                                                    <input type="text" class="form-control" id="nilai"
                                                                        value="<?= $row['nilai']; ?>" name="nilai" required>
                                                                </div>
                                                                <div class="form-group">
                                                                    <label for="grade">Grade</label>
                                                                    <input type="text" class="form-control" id="grade"
                                                                        value="<?= $row['grade']; ?>" name="grade" required>
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
                <div class="modal-dialog modal-lg">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" id="exampleModalLabel">Tambah Data Nilai</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                        <div class="modal-body ">
                            <form method="POST" action="#" enctype="multipart/form-data">
                                <input type="hidden" name="id_nilai" value="<?= $row['id_nilai']; ?>">
                                <div class="mb-3">
                                    <label for="id_siswa" class="col-form-label">Nama Siswa :</label>
                                    <select name="id_siswa" id="id_siswa" class="form-control" required>
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
                                <div class="form-group">
                                    <label for="file">File</label>
                                    <input type="file" class="form-control" id="file" name="file" required>
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

    <script src="https://code.jquery.com/jquery-3.6.4.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/js/bootstrap.bundle.min.js"
        crossorigin="anonymous"></script>


</body>

</html>