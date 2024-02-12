<?php
include 'admin/conn.php';
session_start(); 

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
                    <li><a href="dokumen.php">Dokumen</a></li>
                    
                </ol>
                <h2><?php
    // Periksa apakah session nama sudah ada
    if (isset($_SESSION['username'])) {
        $nama_siswa = $_SESSION['username'];
        echo '<h2>Hallo ' . $nama_siswa . '</h2>';
    } else {
        echo '<h2>Hallo</h2>';
    }
    ?></h2>

            </div>
        </section><!-- End Breadcrumbs -->
        <div class="container">
            <div class="row ms-3 pb-5 pt-5 ps-5 pe-5 rounded shadow d-flex" style="background-color: #F0F8FF;">
                <div class="col-md-9">
                    <h1 class="font-weight-bold text-left" style="font-size: 1.5 rem; color: #333;">
                        Unduh <span style="color: #FFD700;">Dokumen</span> yang anda butuhkan
                        <span style="color: #FFD700; font-weight: bold;">disini</span>
                    </h1>
                </div>
                <div class="col-md-3 d-flex align-items-center justify-content-end">
                    <img src="assets/img/features.png" alt="" style="max-height: 150px; width: auto;" class="img-fluid">
                </div>
            </div>
            <br>
            <div class="row ms-3 pb-5 pt-5 ps-5 pe-5 rounded shadow d-flex">
                <table id="example" class="display">
                    <thead>
                        <tr>
                            <th>No</th>
                            <th>Judul Dokumen</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        $no = 1;
                        $query = "SELECT * FROM dokumen";
                        $result = mysqli_query($koneksi, $query);

                        if (!$result) {
                            die("Query failed: " . mysqli_error($koneksi));
                        }

                        while ($row = mysqli_fetch_assoc($result)) {
                            echo "<tr>";
                            echo "<td>" . $no++ . "</td>";
                            echo "<td>" . $row['judul_dokumen'] . "</td>";
                            echo "<td><a href='" . $row['Dokumen'] . "' download>Unduh</a></td>";
                            echo "</tr>";
                        }

                        mysqli_free_result($result);
                        ?>

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