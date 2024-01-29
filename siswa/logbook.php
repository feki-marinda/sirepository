<?php
session_start();

// Inisialisasi variabel $nama
$nama = isset($_SESSION['username']) ? $_SESSION['username'] : '';


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
                        Bagaimana <span style="color: #FFD700;">Kegiatanmu</span> hari ini? Jangan lupa isi
                        <span style="color: #FFD700; font-weight: bold;">logbook</span> yaa dan Ekspresikan kegiatanmu
                        di <span style="color: #FFD700;">Logbook</span>
                    </h1>
                </div>
                <div class="col-md-3 d-flex align-items-center justify-content-end">
                    <img src="assets/img/features-2.png" alt="" style="max-height: 150px; width: auto;"
                        class="img-fluid">
                </div>
            </div>
            <br>
            <div class="ms-3 pb-5 pt-5 ps-5 pe-5 rounded shadow" style="height: 100%; max-width: 100%;">
                <div class="ms-2 pt-2 me-2 mb-3">
                    <h2 class="text-center">Logbook Harian</h2>
                    <div class="d-flex justify-content-end ">
                        <a class="btn btn-primary btn-lg" href="isilogbook.php" role="button">
                            <i class="fa-light fa-plus"></i> Isi Logbook
                        </a>

                    </div><br>
                    <table id="example" class="display">
                        <thead>
                            <tr>
                                <th>Nama</th>
                                <th>Tanggal</th>
                                <th>Aktivitas</th>
                                <th>Status</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            // Include your database connection file
                            include '../admin/conn.php';
                            
                            $query = "SELECT * FROM logbook WHERE nama = '$nama'";

                            $result = $koneksi->query($query);

                            // Check if the query was successful
                            if ($result) {
                                // Loop through the records
                                while ($row = $result->fetch_assoc()) {
                                    ?>
                                    <tr>
                                        <td>
                                        <?php echo $nama; ?>
                                        </td>
                                        <td>
                                            <?php echo $row['tanggal']; ?>
                                        </td>
                                        <td>
                                            <?php echo $row['aktivitas']; ?>
                                        </td>
                                        <td>
                                            <?php echo $row['status_logbook']; ?>
                                        </td>
                                    </tr>
                                    <?php
                                }
                            } else {
                                echo "Error executing query: " . $koneksi->error;
                            }

                            // Close the database connection
                            $koneksi->close();
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

    </main>

</body>

</html>