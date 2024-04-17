<?php
require '../vendor/autoload.php';
include 'conn.php';
require '../uploadsave.php';
session_start();
$status = isset($_SESSION['status']) ? $_SESSION['status'] : '';

if (empty($status)) {
    header("Location: ../index.php");
    exit;
}

use Google\Client;
use Google\Service\Drive;

$query = "SELECT sertifikat.*, siswa.Nama_siswa 
FROM sertifikat
INNER JOIN siswa ON sertifikat.id_siswa = siswa.id_siswa
INNER JOIN pkl ON pkl.id_siswa = siswa.id_siswa 
ORDER BY siswa.Nama_siswa DESC";

$result = mysqli_query($koneksi, $query);

if (!$result) {
    die("Error in query: " . mysqli_error($koneksi));
}

if (isset($_POST['TambahSertifikat'])) {
    $Nama_siswa = $_POST['Nama_siswa'];

    $file_sertifikat_name = $_FILES['file_sertifikat']['name'];
    $file_sertifikat_temp = $_FILES['file_sertifikat']['tmp_name'];
    $secure_file_name = strtolower(preg_replace('/[^a-zA-Z0-9_.]/', '_', $file_sertifikat_name));

    $file_sertifikat_path = $secure_file_name;

    $query_get_id = "SELECT id_siswa FROM siswa WHERE Nama_siswa = ?";
    $stmt_get_id = $koneksi->prepare($query_get_id);
    $stmt_get_id->bind_param("s", $Nama_siswa);
    $stmt_get_id->execute();
    $stmt_get_id->bind_result($id_siswa);
    $stmt_get_id->fetch();
    $stmt_get_id->close();

    $googleDriveFileId = uploadToGoogleDrive($file_sertifikat_temp, $file_sertifikat_path);

    $query_insert = "INSERT INTO sertifikat (id_siswa, file_sertifikat, google_drive) 
                    VALUES (?, ?, ?)";
    $stmt_insert = $koneksi->prepare($query_insert);
    $stmt_insert->bind_param("sss", $id_siswa, $file_sertifikat_path, $googleDriveFileId);

    if ($stmt_insert->execute()) {
        $_SESSION['success_message'] = "Sertifikat Berhasil Ditambahkan!";
        header("Location: datasertifikat.php");
        exit();
    } else {
        $_SESSION['error_message'] = "Error: " . $koneksi->error;
        header("Location: datasertifikat.php");
        exit();
    }
}

if (isset($_POST['EditSertifikat'])) {
    $id_sertifikat = $_POST['id_sertifikat'];
    $Nama_siswa = $_POST['Nama_siswa'];

    if ($_FILES['file_sertifikat']['name'] != "") {
        $file_sertifikat_name = $_FILES['file_sertifikat']['name'];

        $secure_file_name = strtolower(preg_replace('/[^a-zA-Z0-9_.]/', '_', $file_sertifikat_name));

        $file_sertifikat_path = "Sertifikat/" . $secure_file_name;

        move_uploaded_file($_FILES['file_sertifikat']['tmp_name'], $file_sertifikat_path);

        // Upload file ke Google Drive
        $googleDriveFileId = uploadToGoogleDrive($file_sertifikat_path, $secure_file_name);

        // Update database dengan ID file di Google Drive
        mysqli_query($koneksi, "UPDATE sertifikat SET 
                                 file_sertifikat='$secure_file_name',
                                 google_drive='$googleDriveFileId'                                                                             
                                 WHERE id_sertifikat='$id_sertifikat'");
        if ($result) {
            $_SESSION['success_message'] = "Data Sertifikat Berhasil Diubah!";
            header("Location: datasertifikat.php");
            exit();
        }
    } else {
        mysqli_query($koneksi, "UPDATE sertifikat SET 
                                 Nama_siswa='$Nama_siswa'
                                 WHERE id_sertifikat='$id_sertifikat'");
    }

    header("location: datasertifikat.php");
}

if (isset($_GET['id_sertifikat'])) {
    $id_sertifikat = $_GET['id_sertifikat'];

    mysqli_query($koneksi, "DELETE FROM sertifikat WHERE id_sertifikat='$id_sertifikat'");
    if ($result) {
        $_SESSION['success_message'] = "Data Sertifikat Berhasil Dihapus!";
        header("Location: datasertifikat.php");
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
                    <h1 class="mt-4">Data Sertifikat</h1>
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
                                    Tambah Data Sertifikat</button>

                            </div>
                        </div>
                    </div>

                    <div class="card mb-4">
                        <div class="card-header">
                            <i class="fas fa-table me-1"></i>
                            Data Sertifikat Siswa
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
                                        echo "<td><a href='..\admin/Sertifikat/" . $row['file_sertifikat'] . "' target='_blank'>" . $row['file_sertifikat'] . "</a></td>";
                                        echo "<td>";
                                        echo "<div class='d-flex'>";
                                        echo "<button type='button' class='btn btn-primary me-2' data-bs-toggle='modal' data-bs-target='#edit" . $row['id_sertifikat'] . "' data-bs-whatever='@mdo'>";
                                        echo "<i class='fas fa-pencil-alt'></i> Edit";
                                        echo "</button>";
                                        echo "<button type='button' class='btn btn-danger' data-bs-toggle='modal' data-bs-target='#hapus" . $row['id_sertifikat'] . "'>";
                                        echo "<i class='fas fa-trash'></i> Hapus";
                                        echo "</button>";
                                        echo "</div>";
                                        echo "</td>";
                                        echo "</tr>";

                                        ?>

                                        <!-- Modal hapus data -->
                                        <div class="modal fade" id='hapus<?= $row['id_sertifikat'] ?>' tabindex="-1"
                                            role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
                                            <div class="modal-dialog">
                                                <div class="modal-content">
                                                    <div class="modal-header">
                                                        <h5 class="modal-title" id="exampleModalLabel">Hapus Data Sertifikat
                                                        </h5>
                                                        <button type="button" class="btn-close" data-bs-dismiss="modal"
                                                            aria-label="Close"></button>
                                                    </div>
                                                    <div class="modal-body">
                                                        Apakah anda yakin ingin menghapus Data Sertifikat Praktik Kerja
                                                        Lapangan?
                                                    </div>
                                                    <div class="modal-footer">
                                                        <button type="button" class="btn btn-secondary"
                                                            data-bs-dismiss="modal">Tidak</button>
                                                        <a href="datasertifikat.php?id_sertifikat=<?= $row['id_sertifikat'] ?>"
                                                            class="btn btn-danger">Delete</a>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>

                                        <!-- Modal edit data -->
                                        <div class='modal fade' id='edit<?= $row['id_sertifikat'] ?>' tabindex='-1'
                                            aria-labelledby='exampleModalLabel' aria-hidden='true'>
                                            <div class="modal-dialog modal-lg">
                                                <div class="modal-content">
                                                    <div class="modal-header">
                                                        <h5 class="modal-title" id="exampleModalLabel">Edit Data Sertifikat
                                                        </h5>
                                                        <button type="button" class="btn-close" data-bs-dismiss="modal"
                                                            aria-label="Close"></button>
                                                    </div>
                                                    <div class="modal-body">
                                                        <form method="post" action="#" enctype="multipart/form-data">
                                                            <div class="form-group">
                                                                <div class="form-group">
                                                                    <input type="text" class="form-control"
                                                                        id="id_sertifikat"
                                                                        value="<?= $row['id_sertifikat']; ?>"
                                                                        name="id_sertifikat" hidden>
                                                                </div>
                                                                <div class="form-group">
                                                                    <label for="Nama_siswa">Nama Siswa</label>
                                                                    <input type="text" class="form-control" id="Nama_siswa"
                                                                        value="<?= $row['Nama_siswa']; ?>" name="Nama_siswa"
                                                                        readonly>
                                                                </div>
                                                                <div class="form-group">
                                                                    <label for="file_sertifikat">File</label>
                                                                    <input type="file" class="form-control"
                                                                        id="file_sertifikat" name="file_sertifikat">
                                                                    <small class="text-muted">Current File:
                                                                        <?= $row['file_sertifikat']; ?>
                                                                    </small>
                                                                </div>
                                                            </div>
                                                            <div class="modal-footer">
                                                                <button type="button" class="btn btn-secondary"
                                                                    data-bs-dismiss="modal">Close</button>
                                                                <button type="submit" class="btn btn-primary"
                                                                    name="EditSertifikat" value="Submit">Submit</button>
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

            <!-- Modal tambah data-->
            <div class="modal fade" id="tambah" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
                <div class="modal-dialog modal-lg">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" id="exampleModalLabel">Tambah Sertifikat</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                        <div class="modal-body ">
                            <form action="#" method="post" enctype="multipart/form-data" id="formTambahData">
                                <div class="form-group">
                                    <label for="Nama_siswa" class="col-form-label">Pilih Siswa:</label>
                                    <select class="form-select" id="Nama_siswa" name="Nama_siswa" required>
                                        <option value="" disabled selected>Pilih Siswa</option>
                                        <?php
                                        $result_siswa = mysqli_query($koneksi, "SELECT siswa.Nama_siswa 
                                        FROM siswa
                                        INNER JOIN pkl ON pkl.id_siswa = siswa.id_siswa 
                                        ORDER BY siswa.Nama_siswa DESC;
                                        ");
                                        while ($row_siswa = mysqli_fetch_assoc($result_siswa)) {
                                            echo "<option value='" . $row_siswa['Nama_siswa'] . "'>" . $row_siswa['Nama_siswa'] . "</option>";
                                        }
                                        ?>
                                    </select>
                                </div>

                                <div class="form-group">
                                    <label for="file_sertifikat" class="col-form-label">File Sertifikat:</label>
                                    <input type="file" class="form-control" id="file_sertifikat" name="file_sertifikat"
                                        required>
                                </div>
                                <div class="modal-footer">
                                    <button type="button" class="btn btn-secondary"
                                        data-bs-dismiss="modal">Close</button>
                                    <button type="submit" class="btn btn-primary" name="TambahSertifikat" value="Submit"
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
    <?php include 'footer.php'; ?>

</body>

</html>