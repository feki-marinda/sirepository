<?php
require '../vendor/autoload.php';
include 'conn.php';
require '../uploadscript.php';
session_start();

$status = isset($_SESSION['status']) ? $_SESSION['status'] : '';

if (empty($status)) {
    header("Location: ../index.php");
    exit;
}

use Google\Client;
use Google\Service\Drive;

$error_message = $success_message = '';
$query = "SELECT*FROM laporan_pkl JOIN siswa ON siswa.id_siswa=laporan_pkl.id_siswa";
$result = mysqli_query($koneksi, $query);

if (!$result) {
    die("Error in query: " . mysqli_error($koneksi));
}

$ekstensi_dokumen = array('pdf', 'doc', 'docx');

if (isset($_POST['TambahLaporan'])) {
    $id_laporan = $_POST['id_laporan'];
    $id_siswa = $_POST['id_siswa'];
    $tanggal_kumpul = $_POST['tanggal_kumpul'];
    $rand = rand();
    $file_name = $_FILES['berkas']['name'];
    $file_tmp = $_FILES['berkas']['tmp_name'];
    $ukuran_file = $_FILES['berkas']['size'];
    $ext_file = pathinfo($file_name, PATHINFO_EXTENSION);

    if (!in_array($ext_file, $ekstensi_dokumen)) {
        echo "Error : Ekstensi file tidak sesuai !";
    } else {
        if ($ukuran_file < 100 * 1024 * 1024) {
            $file_dokumen = $rand . '_' . $file_name;
            $upload_dir = 'Laporan PKL/';

            $googleDriveFileId = uploadToGoogleDrive('admin/Laporan PKL/' . $file_name, $file_name);

            if (move_uploaded_file($_FILES['berkas']['tmp_name'], $upload_dir . $file_dokumen)) {
                $query = "INSERT INTO laporan_pkl (id_siswa, tanggal_kumpul,berkas, google_drive_file_id) 
                          VALUES ('$id_siswa', '$tanggal_kumpul', '$file_dokumen','$googleDriveFileId')";

                if ($koneksi->query($query) === TRUE) {
                    $_SESSION['success_message'] = "Laporan berhasil ditambahkan!";
                    header("Location: datalaporan.php");
                    exit();
                } else {
                    $_SESSION['error_message'] = "Error: " . $koneksi->error;
                    header("Location: datalaporan.php");
                    exit();
                }
            } else {
                echo 'Error saat mengunggah file.';
            }
        } else {
            echo 'Error: Ukuran dokumen melebihi batas maksimal';
        }
    }
}


if (isset($_POST['EditLaporan']) && $_SERVER["REQUEST_METHOD"] == "POST") {
    $id_laporan = $_POST['id_laporan'];
    $id_siswa = $_POST['Nama_siswa'];
    $tanggal_kumpul = $_POST['tanggal_kumpul'];

    $file_name = $_FILES['berkas']['name'];
    $file_tmp = $_FILES['berkas']['tmp_name'];
    $file_size = $_FILES['berkas']['size'];
    $file_error = $_FILES['berkas']['error'];

    if ($file_error === 0) {
        // Menangani unggahan berkas
        $file_destination = 'admin/Laporan PKL/' . $file_name;
        move_uploaded_file($file_tmp, $file_destination);

        // Mengambil data berkas lama
        $old_file_query = mysqli_query($koneksi, "SELECT berkas FROM laporan_pkl WHERE id_laporan='$id_laporan'");
        $old_file = mysqli_fetch_array($old_file_query);

        // Memeriksa dan menghapus berkas lama
        if ($old_file && is_file($old_file['berkas'])) {
            unlink($old_file['berkas']);
        }

        // Menggunakan prepared statements untuk mencegah SQL injection
        $query = "UPDATE laporan_pkl 
                  SET id_siswa=?, 
                      tanggal_kumpul=?, 
                      berkas=? 
                  WHERE id_laporan=?";
        $stmt = mysqli_prepare($koneksi, $query);
        mysqli_stmt_bind_param($stmt, "isss", $id_siswa, $tanggal_kumpul, $file_name, $id_laporan);
        $result = mysqli_stmt_execute($stmt);

        // Memeriksa keberhasilan eksekusi query
        if ($result) {
            $rows_affected = mysqli_stmt_affected_rows($stmt);
            if ($rows_affected > 0) {
                $success_message = "Berhasil Memperbarui Data Laporan!";
                header("location:datalaporan.php");
            } else {
                $error_message = "Tidak ada perubahan pada Data Laporan!";
            }
        } else {
            $error_message = "Tidak dapat Memperbarui Data Laporan! Error: " . mysqli_error($koneksi);
        }
    } else {
        $error_message = "Error uploading file. Error code: " . $file_error;
    }
}


if (isset($_GET['id_laporan'])) {
    $id_laporan = $_GET['id_laporan'];

    mysqli_query($koneksi, "DELETE FROM laporan_pkl WHERE id_laporan='$id_laporan'");
    header("location:datalaporan.php");
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
                    <h1 class="mt-4">Data Laporan PKL</h1>
                    <ol class="breadcrumb mb-4">
                        <li class="breadcrumb-item"><a href="index.php">Dashboard</a></li>
                        <li class="breadcrumb-item active">Tables</li>
                    </ol>

                    <div class="card mb-4">
                        <div class="button-container">
                            <div class="spacer d-flex"></div>
                            <div class="buttons-right">
                                <button type="button" class="btn btn-primary" data-bs-toggle="modal"
                                    data-bs-target="#tambah" data-bs-whatever="@mdo"> <i class="fas fa-plus"></i>
                                    Tambah Data Dokumen</button>

                            </div>
                            <div class="buttons-right">
                                <button id="printButton">
                                    <i class="fas fa-print"></i> Cetak
                                </button>
                            </div>
                        </div>
                    </div>

                    <div class="card mb-4">
                        <div class="card-header">
                            <i class="fas fa-table me-1"></i>
                            DataTable Example
                        </div>
                        <div class="card-body">
                            <table id="datatablesSimple" class="table table-striped table-hover">
                                <?php
                                if (!empty($error_message)) {
                                    echo '<div class="alert alert-danger" role="alert">' . $error_message . '</div>';
                                }
                                if (!empty($success_message)) {
                                    echo '<div class="alert alert-success" role="alert">' . $success_message . '</div>';
                                }
                                ?>
                                <thead>
                                    <tr>
                                        <th>No.</th>
                                        <th>Nama Lengkap</th>
                                        <th>Tanggal Pengupulan</th>
                                        <th>file</th>
                                        <th>Keterangan</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php
                                    include 'conn.php';
                                    $row = mysqli_query($koneksi, "SELECT * FROM laporan_pkl");
                                    $no = 1;
                                    while ($row = mysqli_fetch_assoc($result)) {
                                        echo "<tr>";
                                        echo "<td>" . $no++ . "</td>";
                                        echo "<td>" . $row['Nama_siswa'] . "</td>";
                                        echo "<td>" . date('d-m-Y', strtotime($row['tanggal_kumpul'])) . "</td>";
                                        echo "<td><a href='" . 'Laporan PKL' . '/' . $row['berkas'] . "' target='_blank'>" . $row['berkas'] . "</a></td>";
                                        echo "<td>";
                                        echo "<div class='d-flex'>";
                                        echo "<button type='button' class='btn btn-primary me-2' data-bs-toggle='modal' data-bs-target='#edit" . $row['id_laporan'] . "' data-bs-whatever='@mdo'>";
                                        echo "<i class='fas fa-pencil-alt'></i> Edit";
                                        echo "</button>";
                                        echo "<button type='button' class='btn btn-danger' data-bs-toggle='modal' data-bs-target='#hapus" . $row['id_laporan'] . "'>";
                                        echo "<i class='fas fa-trash'></i> Hapus";
                                        echo "</button>";
                                        echo "</div>";
                                        echo "</td>";
                                        echo "</tr>";

                                        ?>

                                        <div class="modal fade" id='hapus<?= $row['id_laporan'] ?>' tabindex="-1"
                                            role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
                                            <div class="modal-dialog">
                                                <div class="modal-content">
                                                    <div class="modal-header">
                                                        <h5 class="modal-title" id="exampleModalLabel">Hapus Data
                                                            dokumen
                                                        </h5>
                                                        <button type="button" class="btn-close" data-bs-dismiss="modal"
                                                            aria-label="Close"></button>
                                                    </div>
                                                    <div class="modal-body">
                                                        Apakah anda yakin ingin menghapus data laporan?
                                                    </div>
                                                    <div class="modal-footer">
                                                        <button type="button" class="btn btn-secondary"
                                                            data-bs-dismiss="modal">Tidak</button>
                                                        <a href="datalaporan.php?id_laporan=<?= $row['id_laporan'] ?>"
                                                            class="btn btn-danger">Delete</a>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="modal fade" id="edit<?= $row['id_laporan'] ?>" tabindex="-1"
                                            aria-labelledby="exampleModalLabel" aria-hidden="true">
                                            <div class="modal-dialog modal-lg">
                                                <div class="modal-content">
                                                    <div class="modal-header">
                                                        <h5 class="modal-title" id="exampleModalLabel">Edit Data dokumen
                                                        </h5>
                                                        <button type="button" class="btn-close" data-bs-dismiss="modal"
                                                            aria-label="Close"></button>
                                                    </div>
                                                    <div class="modal-body">
                                                        <form method="post" action="datalaporan.php"
                                                            enctype="multipart/form-data">
                                                            <div class="form-group">
                                                                <input type="text" class="form-control" id="id_laporan"
                                                                    value="<?= $row['id_laporan']; ?>" name="id_laporan"
                                                                    hidden>
                                                            </div>
                                                            <div class="form-group">
                                                                <label for="Nama_siswa">Nama Lengkap :</label>
                                                                <select class="form-control" id="Nama_siswa"
                                                                    name="Nama_siswa" required>
                                                                    <?php
                                                                    $query_nama_siswa = mysqli_query($koneksi, "SELECT * FROM siswa");
                                                                    while ($data_nama_siswa = mysqli_fetch_assoc($query_nama_siswa)) {
                                                                        $selected = ($row['id_siswa'] == $data_nama_siswa['id_siswa']) ? 'selected' : '';
                                                                        echo "<option value='{$data_nama_siswa['id_siswa']}' $selected>{$data_nama_siswa['Nama_siswa']}</option>";
                                                                    }
                                                                    ?>
                                                                </select>
                                                            </div>
                                                            <div class="form-group">
                                                                <label for="tanggal_kumpul">Tanggal Pengumpulan</label>
                                                                <input type="date" class="form-control" id="tanggal_kumpul"
                                                                    value="<?= $row['tanggal_kumpul']; ?>"
                                                                    name="tanggal_kumpul" required>
                                                            </div>
                                                            <div class="form-group">
                                                                <label for="berkas" class="col-form-label">Berkas
                                                                    (Word/PDF):</label>
                                                                <?php
                                                                echo "<p>Dokumen Saat Ini: {$row['berkas']}</p>";
                                                                ?>
                                                                <input type="file" class="form-control" id="berkas"
                                                                    name="berkas" accept=".doc, .docx, .pdf">
                                                                <small class="form-text text-muted">Pilih file Word
                                                                    (doc/docx) atau PDF. Kosongkan jika tidak ingin
                                                                    mengganti.</small>
                                                            </div>

                                                            <div class="modal-footer">
                                                                <button type="button" class="btn btn-secondary"
                                                                    data-bs-dismiss="modal">Tutup</button>
                                                                <button type="submit" class="btn btn-primary"
                                                                    name="EditLaporan">Submit</button>
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
            </main>

            <div class="modal fade" id="tambah" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
                <div class="modal-dialog modal-lg">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h3 class="modal-title" id="exampleModalLabel">Tambah Laporan Praktek Kerja Lapangan</h3>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                        <div class="modal-body ">
                            <form action="#" method="post" id="formTambahData" enctype="multipart/form-data">
                                <div class="form-group">
                                    <label for="id_siswa" class="col-form-label">Pilih Siswa:</label>
                                    <select class="form-select" id="id_siswa" name="id_siswa" required>
                                        <option value="" disabled selected>Pilih Siswa</option>
                                        <?php
                                        $result_siswa = mysqli_query($koneksi, "SELECT siswa.Nama_siswa, siswa.id_siswa
                                        FROM siswa
                                        INNER JOIN pkl ON pkl.id_siswa = siswa.id_siswa 
                                        ORDER BY siswa.Nama_siswa DESC;
                                        ");
                                        while ($row_siswa = mysqli_fetch_assoc($result_siswa)) {
                                            echo "<option value='" . $row_siswa['id_siswa'] . "'>" . $row_siswa['Nama_siswa'] . "</option>";
                                        }
                                        ?>
                                    </select>
                                </div>
                                <div class="form-group">
                                    <label for="tanggal_kumpul" class="col-form-label">Tanggal Pengumpulan:</label>
                                    <input type="date" class="form-control" id="tanggal_kumpul" name="tanggal_kumpul"
                                        required>
                                </div>
                                <div class="form-group">
                                    <label for="berkas" class="col-form-label">berkas Laporan :</label>
                                    <input type="file" class="form-control" id="berkas" name="berkas" required>
                                </div>

                                <div class="modal-footer">
                                    <button type="button" class="btn btn-secondary"
                                        data-bs-dismiss="modal">Close</button>
                                    <button type="submit" class="btn btn-primary" name="TambahLaporan" value="Submit"
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