<!DOCTYPE html>
<html lang="en">

<?php include 'head.html' ?>

<body>

    <!-- ======= Header ======= -->
    <?php include 'header_siswa.html' ?>
    <!-- End Header -->

    <main id="main">

        <!-- ======= Breadcrumbs ======= -->
        <section class="breadcrumbs">
            <div class="container">

                <ol>
                    <li><a href="index.php">Home</a></li>
                    <li><a href="about.php">Repository</a></li>
                    <li>Repository</li>
                </ol>
                <h2>Laporan PKL Siswa</h2>

            </div>
        </section><!-- End Breadcrumbs -->
        <div class="container">
            <div class="row ms-3 pb-5 pt-5 ps-5 pe-5 rounded shadow d-flex" style="background-color: #F0F8FF;">
                <div class="col-md-9">
                    <h1 class="font-weight-bold text-left" style="font-size: 1.5 rem; color: #333;">
                        Berikut <span style="color: #FFD700;">Laporan</span> Praktik Kerja Lapangan
                        <span style="color: #FFD700; font-weight: bold;">yang sudah tersimpan</span>
                    </h1>
                </div>
                <div class="col-md-3 d-flex align-items-center justify-content-end">
                    <img src="assets/img/features.png" alt="" style="max-height: 150px; width: auto;"
                        class="img-fluid">
            </div>
            </div>
            <br>
            <div class="row ms-3 pb-5 pt-5 ps-5 pe-5 rounded shadow d-flex">
                <table id="example" class="display">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Nama</th>
                            <th>Alamat</th>
                            <th>Usia</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td>1</td>
                            <td>John Doe</td>
                            <td>Jakarta</td>
                            <td>25</td>
                        </tr>
                        <tr>
                            <td>2</td>
                            <td>Jane Smith</td>
                            <td>Bandung</td>
                            <td>28</td>
                        </tr>
                        <tr>
                            <td>3</td>
                            <td>Mary Johnson</td>
                            <td>Surabaya</td>
                            <td>22</td>
                        </tr>
                    </tbody>
                </table>

                <script>
                    $(document).ready(function () {
                        $('#example').DataTable();
                    });
                </script>
            </div>
        </div>

        <a href="#" class="back-to-top d-flex align-items-center justify-content-center"><i
                class="bi bi-arrow-up-short"></i></a>

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