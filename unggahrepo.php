<?php
require 'vendor/autoload.php';
include 'admin/conn.php';
include 'functions.php';
require 'uploadscript.php';

use Google\Client;
use Google\Service\Drive;

error_reporting(0);
ini_set('display_errors', 0);


$nama_siswa_login = getLoggedInUserName();

// Initialize $showForm to true
$showForm = true;

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $nama_siswa = $_POST['Nama_siswa']; // Ambil nama siswa dari input form

    // Query to get id_siswa from siswa table based on username
    $query_get_id_siswa = "SELECT id_siswa FROM siswa WHERE Nama_siswa = ?";
    $stmt_get_id_siswa = $koneksi->prepare($query_get_id_siswa);
    $stmt_get_id_siswa->bind_param("s", $nama_siswa);
    $stmt_get_id_siswa->execute();
    $result_id_siswa = $stmt_get_id_siswa->get_result();
    $row_id_siswa = $result_id_siswa->fetch_assoc();
    
    $id_siswa = $row_id_siswa['id_siswa'];
    
    if ($id_siswa === null) {
        echo "Nama siswa tidak ditemukan.";
    } else {
        // Query to check if the student has already uploaded a report
        $query_check_upload = "SELECT COUNT(*) AS total FROM laporan_pkl WHERE id_siswa = ?";
        $stmt_check_upload = $koneksi->prepare($query_check_upload);
        $stmt_check_upload->bind_param("s", $id_siswa);
        $stmt_check_upload->execute();
        $result_check_upload = $stmt_check_upload->get_result();
        $row_check_upload = $result_check_upload->fetch_assoc();

        if ($row_check_upload['total'] == 0) {
            // Process the uploaded file if the student hasn't uploaded a report yet
            $judul_laporan = $_POST['judul_laporan'];
            $tanggal_kumpul = $_POST['tanggal_kumpul'];

            $file_name = $_FILES['fileLaporan']['name'];
            $file_tmp = $_FILES['fileLaporan']['tmp_name'];

            if (move_uploaded_file($file_tmp, 'admin/Laporan PKL/' . $file_name)) {
                try {
                    $googleDriveFileId = uploadToGoogleDrive('admin/Laporan PKL/' . $file_name, $file_name);

                    // Query to insert data into laporan_pkl table
                    $query_insert = "INSERT INTO laporan_pkl (id_siswa, tanggal_kumpul, berkas, judul_laporan, google_drive_file_id) 
                                    VALUES (?, ?, ?, ?, ?)";
                    $stmt_insert = $koneksi->prepare($query_insert);
                    $stmt_insert->bind_param("sssss", $id_siswa, $tanggal_kumpul, $file_name, $judul_laporan, $googleDriveFileId);

                    if ($stmt_insert->execute()) {
                        // Set $showForm to false after successful upload
                        $showForm = false;
                        header("Location: repository.php");
                        exit;                        
                    } else {
                        throw new Exception('Error executing query: ' . $stmt_insert->error);
                    }
                } catch (Exception $e) {
                    echo 'Error: ' . $e->getMessage();
                }
            } else {
                echo 'Error uploading file.';
            }
        } else {
            $showForm = false;
        }
    }

    $stmt_get_id_siswa->close();
    $stmt_check_upload->close();
}

$koneksi->close();
?>

<!DOCTYPE html>
<html lang="en">

<?php include 'head.html' ?>

<body>

    <!-- ======= Header ======= -->
    <?php include 'header_siswa.php' ?>
    <!-- End Header -->

    <main id="main">

        <!-- ======= Breadcrumbs ======= -->
        <section class="breadcrumbs">
            <div class="container">

                <ol>
                    <li><a href="index.php">Home</a></li>
                    <li><a href="repository.php">Repository</a></li>
                    <li>Unggah Repository</li>
                </ol>
                <h2>
                    <?php
                    // Periksa apakah session nama sudah ada
                    if (isset($_SESSION['username'])) {
                        $nama_siswa = $_SESSION['username'];
                        echo '<h2>Hallo ' . $nama_siswa . '</h2>';
                    } else {
                        echo '<h2>Hallo</h2>';
                    }
                    ?>
                </h2>

            </div>
        </section><!-- End Breadcrumbs -->
        <div class="container">
            <div class="row ms-3 pb-5 pt-5 ps-5 pe-5 rounded shadow d-flex" style="background-color: #F0F8FF;">
                <div class="col-md-9">
                    <h1 class="font-weight-bold text-left" style="font-size: 2.5rem; color: #333;">
                        Simpan <span style="color: #FFD700;">Laporan</span> Praktik Kerja Lapanganmu
                        <span style="color: #FFD700; font-weight: bold;">Disini</span> yaa<br>
                    </h1>
                </div>
                <div class="col-md-3 d-flex align-items-center justify-content-end">
                    <img src="assets/img/hero-img.png" alt="" style="max-height: 150px; width: auto;" class="img-fluid">
                </div>
            </div><br>

            <div class="row ms-3 pb-5 pt-5 ps-5 pe-5 rounded shadow d-flex">
                <?php if ($showForm) { ?>
                    <form action="#" method="post" enctype="multipart/form-data" id="uploadForm">
                    <div class="mb-3">
                            <label for="Nama_siswa" class="form-label fw-bold">Nama Lengkap :</label>
                            <input type="text" class="form-control" id="Nama_siswa" name="Nama_siswa" required>
                        </div>
                        <div class="mb-3">
                            <label for="tanggal_kumpul" class="form-label fw-bold">Tanggal Pengumpulan</label>
                            <input type="date" class="form-control" id="tanggal_kumpul" name="tanggal_kumpul" required>
                        </div>
                        <div class="mb-3">
                            <label for="judul_laporan" class="form-label fw-bold">Judul Laporan</label>
                            <small>Format Judul : Laporan Praktik Kerja Lapangan di (Nama Perusahaan)</small>
                            <input type="text" class="form-control" id="judul_laporan" name="judul_laporan" required>
                        </div>
                        <div class="mb-3">
                            <label for="fileLaporan" class="form-label fw-bold">File Laporan</label><br>
                            <input class="form-control" type="file" id="fileLaporan" name="fileLaporan" required>
                        </div>
                        <div class="d-grid gap-2">
                            <button class="btn btn-primary" type="button" id="modalSubmitBtn">Unggah</button>
                        </div>
                    </form>
                </div>

                <!-- Modal -->
                <div class="modal fade" id="staticBackdrop" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1"
                    aria-labelledby="staticBackdropLabel" aria-hidden="true">
                    <div class="modal-dialog">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h5 class="modal-title" id="staticBackdropLabel">Apakah anda yakin ingin mengunggah laporan
                                    ? </h5>
                                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                            </div>
                            <div class="modal-body">
                                Laporan yang sudah diunggah tidak dapat diedit kembali !
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                                <button class="btn btn-primary" type="button" id="modalSubmitBtn">Unggah</button>
                            </div>
                        </div>
                    </div>
                </div>
            <?php } else { ?>
                <p>Laporan PKL sudah pernah diunggah untuk siswa ini.</p>
            <?php } ?>
        </div>

        <a href="#" class="back-to-top d-flex align-items-center justify-content-center"><i
                class="bi bi-arrow-up-short"></i></a>

        <!-- Pastikan menggunakan id yang benar "modalSubmitBtn" -->
        <script>
            document.getElementById("uploadForm").addEventListener("submit", function (event) {
                // document.getElementById("submitBtn").setAttribute("disabled", "true");
                document.getElementById("modalSubmitBtn").setAttribute("disabled", "true");

                event.preventDefault();
                $('#staticBackdrop').modal('show');
            });

            document.getElementById("modalSubmitBtn").addEventListener("click", function () {
                document.getElementById("uploadForm").submit();
            });
        </script>

        <!-- Vendor JS Files -->
        <!-- Hapus baris-baris berikut jika tidak diperlukan -->
        <!-- <script src="assets/vendor/purecounter/purecounter_vanilla.js"></script>
        <script src="assets/vendor/aos/aos.js"></script>
        <script src="assets/vendor/bootstrap/js/bootstrap.bundle.min.js"></script>
        <script src="assets/vendor/glightbox/js/glightbox.min.js"></script>
        <script src="assets/vendor/isotope-layout/isotope.pkgd.min.js"></script>
        <script src="assets/vendor/swiper/swiper-bundle.min.js"></script>
        <script src="assets/vendor/php-email-form/validate.js"></script> -->

        <!-- Template Main JS File -->
        <script src="assets/js/main.js"></script>

    </body>
</html>
