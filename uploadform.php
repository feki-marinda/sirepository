<!DOCTYPE html>
<html lang="en">

<?php include '../sirepository/siswa/head.html' ?>

<body>

    <!-- ======= Header ======= -->
    <?php include '../sirepository/siswa/header_siswa.php' ?>
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
                    <form action="uploadsave.php" method="post" enctype="multipart/form-data" id="uploadForm">
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
                        <button class="btn btn-primary" type="submit">Unggah</button>
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
        <?php include '../sirepository/siswa/footer.html' ?>
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
