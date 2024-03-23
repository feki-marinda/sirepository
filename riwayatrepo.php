<?php
require 'vendor/autoload.php';
include 'conn.php';
include 'functions.php';
require 'scriptedit.php';

use Google\Client;
use Google\Service\Drive;

$username = $_SESSION['username'];

$query = "SELECT
            laporan_pkl.id_laporan,
            laporan_pkl.tanggal_kumpul,
            laporan_pkl.judul_laporan,
            laporan_pkl.google_drive_file_id,
            laporan_pkl.berkas,
            laporan_pkl.status,
            siswa.id_siswa,
            siswa.Nama_siswa
          FROM
            laporan_pkl
          INNER JOIN
            siswa ON laporan_pkl.id_siswa = siswa.id_siswa
          INNER JOIN
            user ON siswa.id_user = user.id_user
          WHERE
            user.username = ?";

$stmt = $koneksi->prepare($query);
if (!$stmt) {
    die("Query failed: " . $koneksi->error); // Menampilkan pesan kesalahan jika query gagal
}

$stmt->bind_param("s", $username);
$stmt->execute();
$result = $stmt->get_result();

if (isset($_POST['EditLaporan'])) {
    $id_siswa = $_POST['id_siswa'];
    $id_laporan = $_POST['id_laporan'];
    $Nama_siswa = $_POST['Nama_siswa'];
    $tanggal_kumpul = $_POST['tanggal_kumpul'];
    $judul = $_POST['judul_laporan'];
    $berkas = $_FILES['berkas']['name'];
    $berkas_tmp = $_FILES['berkas']['tmp_name'];

    // Pindahkan file yang diunggah ke folder lokal di server
    if (move_uploaded_file($berkas_tmp, 'admin/Laporan PKL/' . $berkas)) {
        try {
            // Edit file di Google Drive
            $googleDriveFileId = editGoogleDriveFile('admin/Laporan PKL/' . $berkas, $berkas);

            // Update entri dalam database dengan informasi terbaru dan ubah status menjadi "Terkirim"
            $query_update = "UPDATE laporan_pkl 
                            INNER JOIN siswa ON laporan_pkl.id_siswa = siswa.id_siswa
                            SET laporan_pkl.tanggal_kumpul = ?, laporan_pkl.judul_laporan = ?, laporan_pkl.berkas = ?, laporan_pkl.google_drive_file_id = ?, laporan_pkl.status = 'Terkirim'
                            WHERE laporan_pkl.id_laporan = ? AND siswa.Nama_siswa = ?";

            $stmt_update = $koneksi->prepare($query_update);
            if ($stmt_update) {
                $stmt_update->bind_param("ssssss", $tanggal_kumpul, $judul, $berkas, $googleDriveFileId, $id_laporan, $Nama_siswa);
                if ($stmt_update->execute()) {
                    $_SESSION['success_message'] = "Berhasil mengedit laporan!";
                    header("Location: riwayatrepo.php");
                    exit();
                } else {
                    $_SESSION['error_message'] = "Error saat mengedit laporan: " . $stmt_update->error;
                    header("Location: riwayatrepo.php");
                    exit();
                }
            } else {
                $_SESSION['error_message'] = "Error saat menyiapkan statement update: " . $koneksi->error;
                header("Location: riwayatrepo.php");
                exit();
            }
        } catch (Exception $e) {
            $_SESSION['error_message'] = "Terjadi kesalahan: " . $e->getMessage();
            header("Location: riwayatrepo.php");
            exit();
        }
    } else {
        $_SESSION['error_message'] = "Terjadi kesalahan saat mengunggah berkas.";
        header("Location: riwayatrepo.php");
        exit();
    }
}

?>


<style>
    table,
    form {
        font-family: Arial, sans-serif;
    }
</style>

<!DOCTYPE html>
<html lang="en">
<?php include 'head.html' ?>

<body>

    <?php include 'header_siswa.php' ?>

    <main id="main">

        <section class="breadcrumbs">
            <div class="container">
                <ol>
                    <li><a href="index.php">Home</a></li>
                    <li><a href="repository.php">Repository</a></li>
                    <li>Riwayat Repository</li>
                </ol>
                <h2>SMK Al-Muhajirin</h2>
            </div>
        </section>

        <section id="blog" class="blog">
            <div class="container">
                <div class="row">
                    <div class="entries">
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
                        <table class="table table-bordered">
                            <thead class="table-primary">
                                <tr>
                                    <th>No.</th>
                                    <th>Nama Lengkap</th>
                                    <th>Tanggal Pengumpulan</th>
                                    <th>Judul</th>
                                    <th>File</th>
                                    <th>Status</th>
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
                                    echo "<td>" . date('d F Y', strtotime($row['tanggal_kumpul'])) . "</td>";
                                    echo "<td>" . $row['judul_laporan'] . "</td>";
                                    echo "<td><a href='https://drive.google.com/uc?id=" . $row['google_drive_file_id'] . "' target='_blank' rel='noopener noreferrer'>" . $row['google_drive_file_id'] . "</a></td>";

                                    echo "<td>";
                                    if ($row['status'] == 'Terkirim') {
                                        echo "<button class='btn btn-sm btn-primary' type='button'><i class='fa-solid fa-envelope-circle-check'></i> Terkirim</button>";
                                    } elseif ($row['status'] == 'Diterima') {
                                        echo "<button class='btn btn-sm btn-success' type='button'><i class='fa-solid fa-circle-check'></i></i> Diterima</button>";
                                    } elseif ($row['status'] == 'Ditolak') {
                                        echo "<button class='btn btn-sm btn-danger' type='button'><i class='fa-solid fa-circle-xmark'></i> Ditolak</button>";
                                    }
                                    echo "</td>";



                                    echo "<td>";
                                    echo "<div class='d-flex'>";
                                    if ($row['status'] != 'Diterima' && $row['status'] != 'Terkirim') {
                                        ?>
                                        <button class="btn btn-sm btn-success me-1" type="button" data-bs-toggle="modal"
                                            data-bs-target="#edit<?= $row['id_laporan']; ?>"><i class="bi bi-pencil-fill"></i>
                                            Edit</button>
                                        <?php
                                    }                                    
                                    echo "<button class='btn btn-sm btn-info' type='button' data-bs-toggle='modal' onclick=\"window.location.href='detailsiswa.php?id_siswa={$row['id_siswa']}'\"><i class='fa-solid fa-eye'></i> Detail</button>";
                                    echo "</td>";
                                    echo "</tr>";

                                    ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                    <!-- modal edit -->
                    <div class='modal fade' id='edit<?= $row['id_laporan']; ?>' tabindex='-1'
                        aria-labelledby='exampleModalLabel' aria-hidden='true'>
                        <div class="modal-dialog modal-lg">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h5 class="modal-title" id="exampleModalLabel">Edit Data Dokumen</h5>
                                    <button type="button" class="btn-close" data-bs-dismiss="modal"
                                        aria-label="Close"></button>
                                </div>
                                <div class="modal-body">
                                    <form method="post" action="#" enctype="multipart/form-data">
                                        <input type="text" class="form-control" id="status" name="status" hidden>
                                        <div class="form-group">
                                            <input type="text" class="form-control" id="id_laporan"
                                                value="<?= $row['id_laporan']; ?>" name="id_laporan" hidden>
                                        </div>
                                        <div class="form-group">
                                            <input type="text" class="form-control" id="google_drive_file_id"
                                                value="<?= $row['google_drive_file_id']; ?>" name="google_drive_file_id"
                                                hidden>
                                        </div>
                                        <div class="form-group mb-2">
                                            <label for="Nama_siswa"><strong>Nama Lengkap</strong></label>
                                            <input type="text" class="form-control" id="Nama_siswa"
                                                value="<?= $row['Nama_siswa']; ?>" name="Nama_siswa" readonly>
                                        </div>
                                        <div class="form-group mb-2">
                                            <label for="tanggal_kumpul"><strong>Tanggal Pengumpulan</strong></label>
                                            <input type="date" class="form-control" id="tanggal_kumpul"
                                                name="tanggal_kumpul" required>
                                        </div>
                                        <div class="form-group mb-2">
                                            <label for="judul_laporan"><strong>Judul Laporan</strong></label>
                                            <input type="text" class="form-control" id="judul_laporan" name="judul_laporan"
                                                required>
                                        </div>
                                        <div class="row">
                                            <label for="berkas" class="col-form-label"><strong>Dokumen :</strong>
                                                <input type="file" class="form-control" id="berkas" name="berkas"
                                                    accept=".doc, .docx, .pdf">
                                                <small>
                                                    <?php
                                                    $currentDocument = $row['berkas'];
                                                    if (!empty($currentDocument)) {
                                                        echo '<p>Dokumen Saat Ini: <a href="Laporan PKL/' . $currentDocument . '" target="_blank">' . $currentDocument . '</a></p>';
                                                    }
                                                    ?>
                                                </small>
                                        </div>
                                        <div class="modal-footer row">
                                            <button type="submit" class="btn btn-primary" name="EditLaporan"
                                                value="Submit">Submit</button>
                                        </div>
                                    </form>

                                </div>
                            </div>
                        </div>
                    </div>


                <?php } ?>

            </div>
        </section>

    </main>

    <script src="assets/js/main.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>