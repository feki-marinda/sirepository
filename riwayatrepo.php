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
    // Ambil data dari form
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

            // Update entri dalam database dengan informasi terbaru
            $query_update = "UPDATE laporan_pkl 
                            INNER JOIN siswa ON laporan_pkl.id_siswa = siswa.id_siswa
                            SET laporan_pkl.tanggal_kumpul = ?, laporan_pkl.judul_laporan = ?, laporan_pkl.berkas = ?, laporan_pkl.google_drive_file_id = ?
                            WHERE laporan_pkl.id_laporan = ? AND siswa.Nama_siswa = ?";

            $stmt = $koneksi->prepare($query_update);
            if ($stmt) {
                $stmt->bind_param("ssssss", $tanggal_kumpul, $judul, $berkas, $googleDriveFileId, $id_laporan, $Nama_siswa);
                if ($stmt->execute()) {
                    // Redirect ke halaman riwayatrepo.php setelah update
                    header("location:riwayatrepo.php");
                    exit(); // Hentikan eksekusi script setelah redirect
                } else {
                    throw new Exception("Error executing query: " . $stmt->error);
                }
            } else {
                throw new Exception("Error preparing query: " . $koneksi->error);
            }
        } catch (Exception $e) {
            echo "Error: " . $e->getMessage();
        }
    } 
    else {
        
        echo "Tidak ada file yang di ubah !";
    }
}
?>
<style>
            table {
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
                        <div>
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
                                        echo "<td>" . $row['tanggal_kumpul'] . "</td>";
                                        echo "<td>" . $row['judul_laporan'] . "</td>";
                                        echo "<td><a href='https://drive.google.com/uc?id=" . $row['google_drive_file_id'] . "' target='_blank' rel='noopener noreferrer'>" . $row['google_drive_file_id'] . "</a></td>";
                                        echo "<td>" . $row['berkas'] . "</td>";
                                        echo "<td>";
                                        echo "<div class='d-flex'>";
                                        echo '<button class="btn btn-sm btn-success me-1" type="button" data-bs-toggle="modal" data-bs-target="#edit' . $row['id_laporan'] . '"><i class="bi bi-pencil-fill"></i> Edit</button>';
                                        echo '<button class="btn btn-sm btn-info" type="button" data-bs-toggle="modal" data-bs-target="#detail' . $row['id_laporan'] . '"><i class="bi bi-info-circle-fill"></i> Detail</button>';
                                        echo "</div>";
                                        echo "</td>";


                                        echo "</tr>";

                                        ?>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>

                    <!-- Modal -->
                    <!--  -->

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
    <div class="form-group">
        <label for="id_laporan">ID</label>
        <input type="text" class="form-control" id="id_laporan" value="<?= $row['id_laporan']; ?>" name="id_laporan" readonly>
    </div>
    <div class="form-group">
        <label for="google_drive_file_id">Google Drive File ID</label>
        <input type="text" class="form-control" id="google_drive_file_id" value="<?= $row['google_drive_file_id']; ?>" name="google_drive_file_id" readonly>
    </div>
    <div class="form-group">
        <label for="Nama_siswa">Nama Lengkap</label>
        <input type="text" class="form-control" id="Nama_siswa" value="<?= $row['Nama_siswa']; ?>" name="Nama_siswa" required>
    </div>
    <div class="form-group">
        <label for="tanggal_kumpul">Tanggal Pengumpulan</label>
        <input type="text" class="form-control" id="tanggal_kumpul" value="<?= $row['tanggal_kumpul']; ?>" name="tanggal_kumpul" required>
    </div>
    <div class="form-group">
        <label for="judul_laporan">Judul Laporan</label>
        <input type="text" class="form-control" id="judul_laporan" value="<?= $row['judul_laporan']; ?>" name="judul_laporan" required>
    </div>
    <div class="mb-3">
        <label for="berkas" class="col-form-label">Dokumen : <small>(Abaikan Jika Tidak Merubah Dokumen.)</small></label>
        <input type="file" class="form-control" id="berkas" name="berkas" accept=".doc, .docx, .pdf">
        <small>
            <?php
            $currentDocument = $row['berkas'];
            if (!empty($currentDocument)) {
                echo '<p>Dokumen Saat Ini: <a href="Laporan PKL/' . $currentDocument . '" target="_blank">' . $currentDocument . '</a></p>';
            }
            ?>
        </small>
    </div>
    <div class="modal-footer">
        <button type="submit" class="btn btn-primary" name="EditLaporan" value="Submit">Submit</button>
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