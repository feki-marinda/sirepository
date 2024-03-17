<?php
require 'vendor/autoload.php';
include 'conn.php';
include 'functions.php';
require 'uploadscript.php';

use Google\Client;
use Google\Service\Drive;

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

error_reporting(0);
ini_set('display_errors', 0);

$nama_siswa_login = getLoggedInUserName();
$showForm = true;

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $nama_siswa = $_POST['Nama_siswa'];

    $query_get_id_siswa = "SELECT id_siswa, email FROM siswa WHERE LOWER(Nama_siswa) = LOWER(?)";
    $stmt_get_id_siswa = $koneksi->prepare($query_get_id_siswa);
    $lowered_nama_siswa = strtolower($nama_siswa);
    $stmt_get_id_siswa->bind_param("s", $lowered_nama_siswa);
    $stmt_get_id_siswa->execute();
    $result_id_siswa = $stmt_get_id_siswa->get_result();
    $row_id_siswa = $result_id_siswa->fetch_assoc();
    $emailPenerima = $row_id_siswa['email'];

    $id_siswa = $row_id_siswa['id_siswa'];

    if ($id_siswa === null) {
        echo "Nama siswa tidak ditemukan.";
    } else {
        $query_check_upload = "SELECT COUNT(*) AS total FROM laporan_pkl WHERE id_siswa = ?";
        $stmt_check_upload = $koneksi->prepare($query_check_upload);
        $stmt_check_upload->bind_param("s", $id_siswa);
        $stmt_check_upload->execute();
        $result_check_upload = $stmt_check_upload->get_result();
        $row_check_upload = $result_check_upload->fetch_assoc();

        if ($row_check_upload['total'] == 0) {
            $judul_laporan = $_POST['judul_laporan'];
            $tanggal_kumpul = $_POST['tanggal_kumpul'];

            $file_name = $_FILES['fileLaporan']['name'];
            $file_tmp = $_FILES['fileLaporan']['tmp_name'];

            if (move_uploaded_file($file_tmp, 'admin/Laporan PKL/' . $file_name)) {
                try {
                    $googleDriveFileId = uploadToGoogleDrive('admin/Laporan PKL/' . $file_name, $file_name);

                    $query_insert = "INSERT INTO laporan_pkl (id_siswa, tanggal_kumpul, berkas, judul_laporan, google_drive_file_id) 
                                    VALUES (?, ?, ?, ?, ?)";
                    $stmt_insert = $koneksi->prepare($query_insert);
                    $stmt_insert->bind_param("sssss", $id_siswa, $tanggal_kumpul, $file_name, $judul_laporan, $googleDriveFileId);

                    if ($stmt_insert->execute()) {
                        $showForm = false;

                        require_once "library/PHPMailer.php";
                        require_once "library/Exception.php";
                        require_once "library/OAuth.php";
                        require_once "library/POP3.php";
                        require_once "library/SMTP.php";

                        // Pengiriman email hanya saat laporan pertama kali diunggah
                        if (isset($_POST['send']) && $_POST['send'] == "uploadLaporan") {
                            $mail = new PHPMailer();

                            $mail->SMTPDebug = 0;
                            $mail->isSMTP();
                            $mail->Host = "ssl://smtp.gmail.com";
                            $mail->SMTPAuth = true;
                            $mail->Username = "sirepositorysmkalmujahirin@gmail.com";
                            $mail->Password = "cqutrhboyqtviwck";
                            $mail->SMTPSecure = "ssl";
                            $mail->Port = 465;

                            $mail->From = $mail->Username;
                            $mail->FromName = "Sirepository-Sistem Informasi Repository";

                            // Penerima
                            $mail->addAddress($emailPenerima);
                            if (empty($emailPenerima)) {
                                echo "Email siswa tidak ditemukan atau kosong.";
                            } else {
                                $mail->addAddress($emailPenerima);
                            }

                            $mail->isHTML(true);

                            $mail->Subject = $nama_siswa;
                            $mail->Body = "Selamat, laporan praktik kerja lapangan Anda berhasil diunggah. Berikut detailnya:<br>"
                                . "Nama Siswa: " . $_POST['Nama_siswa'] . "<br>"
                                . "Tanggal Kumpul: " . $_POST['tanggal_kumpul'] . "<br>"
                                . "Judul Laporan: " . $_POST['judul_laporan'] . "<br>";

                            if (!$mail->send()) {
                                echo "mailer eror" . $mail->ErrorInfo;
                            } else {
                                echo "Pesan email berhasil dikirim";
                                echo "<script>
                                        setTimeout(function(){
                                            window.location.href='repository.php';
                                        }, 5000); // Redirect setelah 5 detik
                                      </script>";
                                exit;

                            }


                        }
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

    <?php include 'header_siswa.php' ?>

    <main id="main">

        <section class="breadcrumbs">
            <div class="container">
                <ol>
                    <li><a href="index.php">Home</a></li>
                    <li><a href="repository.php">Repository</a></li>
                    <li>Unggah Repository</li>
                </ol>
                <h2>
                    <?php
                    if (isset($_SESSION['username'])) {
                        $nama_siswa = $_SESSION['username'];
                        echo '<h2>Hallo ' . $nama_siswa . '</h2>';
                    } else {
                        echo '<h2>Hallo</h2>';
                    }
                    ?>
                </h2>
            </div>
        </section>

        <style>
            form {
                font-family: Arial, sans-serif;
            }
            
        </style>

        <div class="container">
            <div class="row ms-3 pb-5 pt-5 ps-5 pe-5 rounded shadow d-flex" style="background-color: #F0F8FF;">
                <div class="col-12">
                    <h2 class="font-weight-bold text-left" style="font-size: 2.5rem; color: #333;">
                        Simpan <span style="color: #FFD700;">Laporan</span> Praktik Kerja Lapanganmu
                        <span style="color: #FFD700; font-weight: bold;">Disini</span> yaa<br>
                    </h2>
                </div>
            </div><br>

            <div class="row ms-3 pb-5 pt-5 ps-5 pe-5 rounded d-flex" style="box-shadow: 10px 10px 20px 12px lightblue;">
                <?php if ($showForm) { ?>
                    <form action="#" method="post" enctype="multipart/form-data" id="uploadForm" >
                        <div class="mb-3">
                            <label for="Nama_siswa" class="form-label fw-bold">Nama Lengkap :</label>
                            <input type="text" class="form-control" id="Nama_siswa" name="Nama_siswa" required
                                placeholder="Masukkan Nama Lengkap Anda">
                        </div>
                        <div class="mb-3">
                            <label for="tanggal_kumpul" class="form-label fw-bold">Tanggal Pengumpulan</label>
                            <input type="date" class="form-control" id="tanggal_kumpul" name="tanggal_kumpul" required>
                        </div>
                        <div class="mb-3">
                            <label for="judul_laporan" class="form-label fw-bold">Judul Laporan</label>
                            <small>Format Judul : Laporan Praktik Kerja Lapangan di (Nama Perusahaan)</small>
                            <input type="text" class="form-control" id="judul_laporan" name="judul_laporan" required
                                placeholder="Masukkan Judul Dokumen">
                        </div>
                        <div class="mb-3">
                            <label for="fileLaporan" class="form-label fw-bold">File Laporan</label><br>
                            <input class="form-control" type="file" id="fileLaporan" name="fileLaporan" required>
                        </div>
                        <div class="d-grid gap-2">
                            <button class="btn btn-primary" type="button" id="modalSubmitBtn">Unggah</button>
                        </div>
                        <input type="hidden" value="uploadLaporan" name="send">
                    </form>
                <?php } else { ?>
                    <p>Laporan PKL sudah pernah diunggah untuk siswa ini.</p>
                <?php } ?>
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
        </div>

        <a href="#" class="back-to-top d-flex align-items-center justify-content-center"><i
                class="bi bi-arrow-up-short"></i></a>

        <script>
            document.getElementById("uploadForm").addEventListener("submit", function (event) {
                document.getElementById("modalSubmitBtn").setAttribute("disabled", "true");

                event.preventDefault();
                $('#staticBackdrop').modal('show');
            });

            document.getElementById("modalSubmitBtn").addEventListener("click", function () {
                document.getElementById("uploadForm").submit();
            });
        </script>
        <script src="assets/js/main.js"></script>

    </main>
</body>

</html>