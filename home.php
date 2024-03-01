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
  .hero {
    position: relative;
    width: 100%;
    height: 100vh;
    background: url(assets/img/sekolah.jpg) top center no-repeat;
    background-size: cover;

  }

  .overlay {
    position: absolute;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    background-color: rgba(255, 255, 255, 0.8);
  }

  .hero .container {
    position: relative;
    z-index: 2;
  }

  .hero h1,
  .hero h2,
  .hero .btn-get-started {
    color: #000;
  }

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

  <?php include 'header_siswa.php' ?>
  <section id="hero" class="hero d-flex align-items-center ">
    <div class="overlay"></div>
    <div class="container">
      <div class="row">
        <div class="col-lg-10 d-flex flex-column align-items-center">
          <h1 style="text-align: center;" data-aos="fade-up" data-aos-duration="1000">Selamat Datang Di Sistem Informasi
            Repository Laporan Praktek Kerja Lapangan Siswa <br> SMK AL - MUHAJIRIN</h1>
          <h2 data-aos="fade-up" data-aos-delay="400" data-aos-duration="1000" class="justify-content-center">Anda dapat
            <span style="font-weight: bold; color: #FF8C00;">menemukan</span> dan <span
              style="font-weight: bold; color: #FF8C00;">menyimpan</span> semua dokumen <span
              style="font-weight: bold; color: #FF8C00;">Praktek Kerja Lapangan</span> dengan Cepat dan Tepat. Untuk
            memulai silahkan <span style="font-weight: bold; color: #FF8C00;">daftar</span> terlebih dahulu.</h2>
          <div data-aos="fade-up" data-aos-delay="600" data-aos-duration="1000">
            <div class="text-start text-lg-start">
              <a href="daftarpkl.php"
                class="btn-get-started scrollto d-inline-flex align-items-center justify-content-center align-self-center"
                style="width: 300px; background-color: #FF8C00; ">
                <span style="color: #ffffff;">Daftar PKL</span>
                <i class="bi bi-arrow-right" style="color: #ffffff;"></i>
              </a>
            </div>

          </div>
        </div>
        <!-- <div class="col-lg-6 hero-img" data-aos="zoom-out" data-aos-delay="200" data-aos-duration="1000">
                <img src="assets/img/dewi.png" style="width: 70%" class="img-fluid" alt="">
            </div> -->
      </div>
    </div>
  </section>
  <section id="recent-blog-posts" class="recent-blog-posts">
    <div class="container">
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

        $koneksi->close();
        ?>
      </div>
    </div>
  </section>



  <main id="main">
    <section id="about" class="about" style="background-color: #F5F5F5;">

      <div class="container">
        <div class="row gx-0 ">

          <div class="col-lg-12 d-flex flex-column justify-content-center" data-aos="fade-up" data-aos-delay="500">
            <h3 style="text-align: center;">Apa itu Sirepository ?</h3>
            <div class="content rounded" style="background-color: #B0E0E6;">

              <h2>Sistem Informasi Repository Laporan PKL Siswa SMK Al-Muhajirin</h2>
              <h4 style="text-align: justify;">
                Sistem Informasi Repository Laporan PKL (Praktek Kerja Lapangan) adalah suatu platform atau sistem yang
                dirancang untuk menyimpan, mengelola, dan membagikan laporan praktek kerja lapangan siswa. Sistem ini
                bertujuan untuk menyediakan wadah terpusat di mana laporan-laporan tersebut dapat diunggah, dicari, dan
                diakses oleh pihak-pihak yang berkepentingan, seperti instansi yang menyelenggarakan PKL, Siswa, dan
                pihak lainnya.
              </h4>


              <div class="text-start text-lg-start">
                <a href="about.php"
                  class="btn-get-started rounded scrollto d-inline-flex align-items-center justify-content-center align-self-center"
                  style="width: 300px; height:50px; background-color: #FF8C00; ">
                  <span style="color: #ffffff;">Tentang Sirepository </span>
                  <i class="bi bi-arrow-right" style="color: #ffffff;"></i>
                </a>

              </div>
            </div>

            <!-- <div class="col-lg-6 d-flex align-items-center justify-content-center">
            <img src="assets/img/about.png" class="img-fluid" alt="">
          </div> -->


          </div>
        </div>

    </section>

    <section id="services" class="services">

      <div class="container">

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
  <script>
    AOS.init();
  </script>
  <?php include 'footer.html' ?>
  <script src="assets/js/main.js"></script>
</body>

</html>