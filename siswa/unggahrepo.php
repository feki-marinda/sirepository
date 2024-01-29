<?php
include '../admin/conn.php';
include '../functions.php';

// Ambil nama siswa yang login dari fungsi otentikasi
$nama_siswa_login = getLoggedInUserName();

$query = "SELECT * FROM laporan_pkl";

$showForm = true; // Inisialisasi variabel

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $judul_laporan = $_POST['judul_laporan'];
    $tanggal_kumpul = $_POST['tanggal_kumpul'];

    // Periksa apakah nama siswa yang login sesuai dengan nama siswa yang diisi dalam formulir
    if ($nama_siswa_login) {
        // Periksa apakah laporan PKL sudah ada untuk nama siswa tertentu
        $query_check_upload = "SELECT COUNT(*) AS total FROM laporan_pkl WHERE nama_siswa = '$nama_siswa_login'";
        $result_check_upload = $koneksi->query($query_check_upload);
        $row_check_upload = $result_check_upload->fetch_assoc();

        // Menentukan apakah formulir harus ditampilkan atau disembunyikan
        $showForm = ($row_check_upload['total'] == 0);

        if ($row_check_upload['total'] == 0) {
            $file_name = $_FILES['fileLaporan']['name'];
            $file_tmp = $_FILES['fileLaporan']['tmp_name'];
            $file_size = $_FILES['fileLaporan']['size'];
            $file_error = $_FILES['fileLaporan']['error'];

            if ($file_error === 0) {
                $file_destination = '../admin/Laporan PKL/' . $file_name;
                move_uploaded_file($file_tmp, $file_destination);

                $query_insert = "INSERT INTO laporan_pkl (nama_siswa, tanggal_kumpul, berkas, judul_laporan) 
VALUES ('$nama_siswa_login', '$tanggal_kumpul', '$file_destination', '$judul_laporan')";

                // Eksekusi query
                if ($koneksi->query($query_insert) === TRUE) {
                    header('Location: repository.php');
                    exit;
                } else {
                    echo 'Error: ' . $koneksi->error;
                }
            } else {
                echo 'Error uploading file.';
            }
        }
    } else {
        echo '<script>alert("Anda tidak diizinkan mengunggah laporan untuk siswa lain.");</script>';
    }

    $koneksi->close();
}
?>

<!DOCTYPE html>
<html lang="en">

<?php include 'head.html' ?>

<body>

    <!-- ======= Header ======= -->
    <?php include 'header_siswa.html' ?>
    <!-- End Header -->

    <main id="main">

        <!-- ======= Breadcrumbs ======= -->
        <section class="">
            <div class="container">

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
                        <!-- Remove the input for Nama Siswa -->
                        <input type="hidden" name="nama_siswa" value="<?php echo $nama_siswa_login; ?>">

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
                            <!-- Button trigger modal -->
                            <button type="button" class="btn btn-primary" data-bs-toggle="modal"
                                data-bs-target="#staticBackdrop">
                                Unggah
                            </button>
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

        <script>
            document.getElementById("uploadForm").addEventListener("submit", function (event) {
                document.getElementById("submitBtn").setAttribute("disabled", "true");

                event.preventDefault();
                $('#staticBackdrop').modal('show');
            });

            document.getElementById("modalSubmitBtn").addEventListener("click", function () {
                document.getElementById("uploadForm").submit();
            });
        </script>

        <!-- Vendor JS Files -->
        <script src="assets/vendor/purecounter/purecounter_vanilla.js"></script>
        <script src="assets/vendor/aos/aos.js"></script>
        <script src="assets/vendor/bootstrap/js/bootstrap.bundle.min.js"></script>
        <script src="assets/vendor/glightbox/js/glightbox.min.js"></script>
        <script src="assets/vendor/isotope-layout/isotope.pkgd.min.js"></script>
        <script src="assets/vendor/swiper/swiper-bundle.min.js"></script>
        <script src="assets/vendor/php-email-form/validate.js"></script>

        <!-- Template Main JS File -->
        <script src="assets/js/main.js"></script>

</body>

</html>
