<?php
session_start();
include('conn.php');

$password = isset($_SESSION['password']) ? $_SESSION['password'] : '';

if (empty($password)) {
  header("Location: index.php");
  exit;
}
?>

<style>
  .center-image {
    display: flex;
    justify-content: center;
    align-items: center;
  }

  .center-image img {
    max-width: 100%;
    max-height: 100%;
  }
</style>

<!DOCTYPE html>
<html lang="en">

<?php include 'head.html' ?>

<body>

  <?php include 'header_siswa.php' ?>

  <div class="background-image"></div>
  <main id="main">
    <section class="breadcrumbs">
      <div class="container">
        <ol>
          <li><a href="index.php">Home</a></li>
          <li><a href="about.php">About</a></li>
          <li>About SiRepository</li>
        </ol>
        <h2>Tentang SiRepository</h2>
      </div>
    </section>

    <section id="blog" class="blog">
      <div class="container" data-aos="fade-up">
        <div class="row">
          <div class="entries">
            <article class="entry">
              <h2 class="entry-title"><a href="about.php">Apa itu Sistem Informasi Repository Laporan PKL Siswa ?</a>
              </h2>
              <div class="center-image">
                <img src="admin/gambar/R.png" alt="" class="img-fluid rounded">
              </div>
              <div class="entry-content" style="text-align: justify;">
                <h5><strong>Sistem Informasi Repository Laporan PKL (Praktek Kerja Lapangan) adalah</strong> suatu platform atau sistem
                  yang dirancang untuk menyimpan, mengelola, dan membagikan laporan praktek kerja lapangan siswa. Sistem
                  ini bertujuan untuk menyediakan wadah terpusat di mana laporan-laporan tersebut dapat diunggah,
                  dicari, dan diakses oleh pihak-pihak yang berkepentingan, seperti instansi yang menyelenggarakan PKL,
                  siswa, dan pihak lainnya.</h5>

                <h5>Dengan memanfaatkan teknologi informasi, sistem ini menciptakan lingkungan digital yang efisien
                  untuk pengarsipan dan distribusi laporan PKL, menggantikan metode manual yang mungkin cenderung
                  memakan waktu dan kurang efektif.</h5>

                <blockquote>
                  <h5>"SiRepository: Meningkatkan Akses, Memudahkan Pencarian, Transformasi Digital untuk Masa Depan
                    PKL."</h5>
                </blockquote>

                <h3>Tujuan Adanya SiRepository:</h3>
                <ul>
                  <li><strong>Pusat Penyimpanan Dokumen:</strong> <h6>Memberikan tempat penyimpanan yang terpusat untuk
                    laporan praktek kerja lapangan siswa, sehingga memudahkan pengelolaan dan aksesibilitas.</h6> </li>
                  <li><strong>Pencarian Efisien:</strong> <h6>Memungkinkan pencarian efisien terhadap laporan-laporan PKL
                    berdasarkan kriteria tertentu, seperti nama siswa, instansi, atau periode pelaksanaan.</h6></li>
                  <li><strong>Keterbukaan Informasi:</strong> <h6>Menyediakan akses terbuka bagi pihak yang berkepentingan
                    untuk melihat dan mengakses laporan-laporan PKL sesuai dengan aturan dan kebijakan yang berlaku.</h6>
                  </li>
                </ul>

                <h3>Apa Manfaat SiRepository</h3>
                <ul>
                  <li><strong>Pengelolaan Laporan Secara Terpusat:</strong> <h6>Repository memungkinkan penyimpanan laporan
                    PKL secara terpusat, yang memudahkan pengelolaan dan pemeliharaan dokumen-dokumen tersebut.</h6></li>
                  <li><strong>Aksesibilitas dan Pencarian:</strong> <h6>Memfasilitasi aksesibilitas dokumen oleh pihak-pihak
                    yang berkepentingan, seperti sekolah, siswa, dan pihak lainnya. Sistem pencarian dapat diterapkan
                    untuk membantu pengguna menemukan laporan yang relevan dengan mudah.</h6></li>
                  <li><strong>Kolaborasi dan Berbagi Informasi:</strong> <h6>Memungkinkan siswa untuk berbagi pengalaman dan
                    pengetahuan melalui laporan-laporan mereka.</h6></li>
                  <li><strong>Pemantauan Kemajuan Siswa:</strong> <h6>Memungkinkan pemantauan kemajuan siswa selama PKL,
                    termasuk aktivitas yang dilakukan, hasil yang dicapai, dan pembelajaran yang diperoleh.</h6></li>
                  <li><strong>Penyimpanan Aman dan Efisiensi Administratif:</strong> <h6>Menyediakan penyimpanan aman untuk
                    laporan-laporan PKL dengan kemampuan manajemen versi serta mengurangi beban administratif dengan
                    mengotomatiskan penyimpanan, penelusuran, dan pemeliharaan laporan PKL.</h6></li>
                </ul>
              </div>

              <div class="entry-footer">
                <i class="bi bi-folder"></i>
                <ul class="cats">
                  <li><a href="#">Repository</a></li>
                </ul>
                <i class="bi bi-tags"></i>
                <ul class="tags">
                  <li><a href="#">Laporan</a></li>
                  <li><a href="#">PKL</a></li>
                  <li><a href="#">Penyimpanan Digital</a></li>
                </ul>
              </div>
            </article>
          </div>
        </div>
      </div>
    </section>
  </main>
  <?php include 'footer.html' ?>
  <script src="assets/js/main.js"></script>
</body>

</html>