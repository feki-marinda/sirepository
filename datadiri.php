<?php
session_start();

// Inisialisasi variabel $nama
$nama = isset($_SESSION['username']) ? $_SESSION['username'] : '';


?>
<!DOCTYPE html>
<html lang="en">

<?php include 'head.html';?>

<body>
  <?php include 'header_siswa.php';?>
  <main id="main">

   <!-- ======= Breadcrumbs ======= -->
   <section class="breadcrumbs">
            <div class="container">

                <ol>
                    <li><a href="index.php">Home</a></li>
                    <li><a href="datadiri.php">Data Siswa</a></li>
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

    <!-- ======= Portfolio Details Section ======= -->
    <section id="portfolio-details" class="portfolio-details">
      <div class="container">

        <div class="row gy-4">

        <div class="col-lg-8 d-flex">
    <div>
        <table class="table table-sm">
            <tr>
                <td>Nama</td>
                <td>....</td>
            </tr>
            <tr>
                <td>Tempat/Tanggal Lahir</td>
                <td>....</td>
            </tr>
        </table>
    </div>
</div>



          <div class="col-lg-4">
            <div class="portfolio-info">
              <h3>Informasi Data Siswa</h3>
              <table class="table table-sm">
                <tr>
                  <td>Nilai PKL Siswa</td>
                </tr>
                <tr>
                  <td>Sertfikat</td>
                </tr>
</table>
            </div>
          </div>

        </div>

      </div>
    </section><!-- End Portfolio Details Section -->

  </main><!-- End #main -->
  
  <a href="#" class="back-to-top d-flex align-items-center justify-content-center"><i class="bi bi-arrow-up-short"></i></a>

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