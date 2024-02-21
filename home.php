<?php 
include('conn.php');
session_start();

$id_siswa = isset($_SESSION['id_siswa']) ? $_SESSION['id_siswa'] : '';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
  header("Location: daftarpkl.php");
  exit();
}
?>

<!DOCTYPE html>
<html lang="en">

<?php include 'head.html' ?>
<style>
  .recent-blog-posts {
    padding: 60px 0;
  }

  .section-header {
    text-align: center;
    margin-bottom: 30px;
  }

  .section-header p {
    font-size: 24px;
    color: #007bff;
  }

  .row {
    display: flex;
    justify-content: center;
    align-items: stretch;
  }

  .col-lg-4 {
    margin: 10px;
  }

  .post-box {
    padding: 20px;
    border-radius: 10px;
    text-align: center;
  }

  .post-img img {
    width: 100%;
    height: auto;
    max-height: 200px;
    object-fit: cover;
    border-radius: 8px;
  }

  .post-date {
    display: block;
    margin-top: 10px;
    font-size: 14px;
    color: #ffffff;
  }

  .readmore {
    display: inline-block;
    margin-top: 10px;
    color: blue;
    text-decoration: none;
  }

  .bi-arrow-right {
    margin-left: 5px;
  }
</style>

<body>

  <!-- ======= Header ======= -->
  <?php include 'header_siswa.php' ?>
  <!-- End Header -->

  <!-- ======= Hero Section ======= -->
  <section id="hero" class="hero d-flex align-items-center">

    <div class="container">
      <div class="row">
        <div class="col-lg-6 d-flex flex-column align-items-left">
          <h1 style="text-align: left;" data-aos="fade-up">Selamat Datang Di Sistem Informasi Repository Laporan PKL
          </h1>
          <h2 data-aos="fade-up" data-aos-delay="400" class="justify-content-center">Anda dapat <span
              style="font-weight: bold; color: #FF8C00;">menemukan</span> dan <span
              style="font-weight: bold; color: #FF8C00;">menyimpan</span> semua dokumen <span
              style="font-weight: bold; color: #FF8C00;">Praktek Kerja Lapangan</span> dengan Cepat dan Tepat</h2>
          <div data-aos="fade-up" data-aos-delay="600">
          <div class="text-start text-lg-start">
    <form action="daftarpkl.php" method="post">
        <button type="submit" class="btn-get-started scrollto d-inline-flex align-items-center justify-content-center align-self-center">
            <span>Daftar PKL</span>
            <i class="bi bi-arrow-right"></i>
        </button>
    </form>
</div>

          </div>
        </div>
        <div class="col-lg-6 hero-img" data-aos="zoom-out" data-aos-delay="200">
          <img src="assets/img/hero-img.png" class="img-fluid" alt="">
        </div>
      </div>
    </div>

  </section><!-- End Hero -->

  <!-- ======= Recent Blog Posts Section ======= -->
  <section id="recent-blog-posts" class="recent-blog-posts">
    <div class="container" data-aos="fade-up">
      <header class="section-header">
        <p>Berita dan Informasi</p>
      </header>

      <div class="row">
        <?php
        $query = $koneksi->prepare("SELECT * FROM berita ORDER BY tanggal DESC LIMIT 3");
        $query->execute();
        $result = $query->get_result();
        if ($result->num_rows > 0) {
          while ($data = $result->fetch_assoc()) {
            ?>
            <div class="col-4">
              <div class="post-box">
                <div class="post-img"><img src="admin/gambar/siswa/<?= $data["foto"]; ?>" class="img-fluid" alt=""
                    style="width: 100%; height: 500px;"></div>
                <span class="post-date">
                  <p>
                    <?php echo date('l, j F Y', strtotime($data['tanggal'])); ?>
                  </p>
                </span>
                <h4><a href="details.php?id_berita=<?= $data["id_berita"]; ?>">
                    <?= $data["judul"]; ?>
                  </a></h4>
              </div>
            </div>
            <?php
          }
        }

        // Tutup koneksi
        $koneksi->close();
        ?>
      </div>
    </div>
  </section>

  <!-- End Recent Blog Posts Section -->


  <main id="main">
    <!-- ======= About Section ======= -->
    <section id="about" class="about">

      <div class="container" data-aos="fade-up">
        <div class="row gx-0">

          <div class="col-lg-6 d-flex flex-column justify-content-center" data-aos="fade-up" data-aos-delay="200">
            <div class="content">
              <h3>Who SiRepository Are</h3>
              <h2>Sistem Informasi Repository Laporan PKL Siswa SMK Al-Muhajirin</h2>
              <p style="text-align: justify;">
                Sistem Informasi Repository Laporan PKL (Praktek Kerja Lapangan) adalah suatu platform atau sistem yang
                dirancang untuk menyimpan, mengelola, dan membagikan laporan praktek kerja lapangan siswa. Sistem ini
                bertujuan untuk menyediakan wadah terpusat di mana laporan-laporan tersebut dapat diunggah, dicari, dan
                diakses oleh pihak-pihak yang berkepentingan, seperti instansi yang menyelenggarakan PKL, Siswa, dan
                pihak lainnya.
              </p>

              <div class="text-center text-lg-start">
                <a href="about.php"
                  class="btn-read-more d-inline-flex align-items-center justify-content-center align-self-center">
                  <span>Read More</span>
                  <i class="bi bi-arrow-right"></i>
                </a>
              </div>

            </div>
          </div>

          <div class="col-lg-6 d-flex align-items-center justify-content-center" data-aos="zoom-out"
            data-aos-delay="200">
            <img src="assets/img/about.png" class="img-fluid" alt="">
          </div>


        </div>
      </div>

    </section><!-- End About Section -->

    <!-- ======= Services Section ======= -->
    <section id="services" class="services">

      <div class="container" data-aos="fade-up">

        <header class="section-header">
          <h2>Dokumen PKL</h2>
        </header>

        <div class="container mt-4">
          <div class="row row-cols-1 row-cols-md-3 g-4">
            <?php
            include 'admin/conn.php';
            $querydokumen = $koneksi->query("SELECT * FROM dokumen LIMIT 3");
            if ($querydokumen && mysqli_num_rows($querydokumen) > 0) {
              while ($data = mysqli_fetch_array($querydokumen)) {
                ?>
                    <div class="row">
                      <div class="col-sm-12">
                        <div class="card shadow">
                          <div class="card-body">
                            <h5 class="card-title">
                              <?php echo $data['judul_dokumen']; ?>
                            </h5>
                            <a href='../admin/Dokumen/<?php echo $data['Dokumen']; ?>' target='_blank'
                              class="btn btn-primary">Buka Dokumen</a>
                          </div>
                        </div>
                      </div>

                    </div>


                    <?php
              }
            } else {
              echo "Tidak ada data ditemukan.";
            }
            ?>
              </div>
            </div>

          </div>

        </div>


  </main><!-- End #main -->

  <?php include 'footer.html' ?>
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