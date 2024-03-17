<?php
session_start();
include('conn.php');

$id_user = isset($_SESSION['id_user']) ? $_SESSION['id_user'] : '';

if (empty($id_user)) {
    header("Location: index.php");
    exit;
}
?>
<!DOCTYPE html>
<html lang="en">
<?php include 'head.html' ?>

<body>

    <?php include 'header_siswa.php' ?>

    <main id="main">

        <!-- ======= Breadcrumbs ======= -->
        <section class="breadcrumbs">
            <div class="container">

                <ol>
                    <li><a href="index.php">Home</a></li>
                    <li><a href="about.php">Profil</a></li>
                    <li>Sejarah</li>
                </ol>
                <h2>SMK Al-Muhajirin</h2>

            </div>
        </section>
        <section id="blog" class="blog">
            <div class="container" data-aos="fade-up">

                <div class="row">

                    <div class=" entries">

                        <article class="entry">
                            <div style="text-align: center">
                                <h1 class="entry-title">
                                    <a href="sejarah.php">Sejarah SMK Al-Muhajirin</a><br>
                                </h1>
                            </div>

                            <div class="entry-content">
                                <div class="mb-3" style="text-align: center; padding-left: 20px; padding-right: 20px;">
                                    <img src="assets/img/sekolah.jpg" alt="" class="img-fluid rounded"
                                        style="width: 700px; height: 400px; margin-left: auto; margin-right: auto;">
                                </div>

                                <div class="" style="text-align:justify">
                                    <h5>
                                        SMKS Al Muhajirin meruhpakan sekolah menengah kejuruan yang berdiri pada tanggal
                                        8
                                        Juni 2008 dan berstatus sekolah swasta dibawah naungan Yayasan Hidayatulloh
                                        Al-Muhajirin.
                                        SMKS Al Muhajirin terletak di Dusun Paserean Bawah Desa Buduran Kecamatan
                                        Arosbaya,
                                        Kabupaten Bangkalan dan secara geografis berada di antara koordinat
                                        6°56′-112°51′
                                        dengan ketinggian ± 8 m dari permukaan laut, serta berada pada lahan seluas 0,21
                                        ha.
                                    </h5>

                                    <h5>
                                        SMKS Al Muhajirin terakreditasi B dengan nomor pokok sekolah nasional (NPSN)
                                        20555424. Sebagai salah satu sekolah kejuruan SMKS Al Muhajirin memiliki
                                        kompetensi
                                        keahlian yaitu Teknik Komputer dan Jaringan (TKJ). Pada kompetensi keahlian
                                        teknik
                                        komputer dan jaringan (TKJ) peserta didik di bekali ilmu pengetahuan dan
                                        keterampilan tentang teknisi komputer, teknisi jaringan, dan administrasi
                                        jaringan.
                                    </h5>

                                    <h5>Untuk menciptakan kegiatan belajar mengajar yang nyaman SMKS Al Muhajirin
                                        menyediakan
                                        fasilitas yang memadai, diantaranya: gedung sekolah yang representatif dengan
                                        lokasi
                                        yang strategis serta lingkungan yang kondusif untuk kegiatan belajar mengajar,
                                        ruang
                                        kelas yang nyaman, laboraturiom komputer, laboraturium kimia/fisika,
                                        perpustakaan,
                                        lapangan olah raga, dan masjid yang megah sebagai pusat kegiatan keagamaan yang
                                        merupakan bagian upaya membentuk generasi muda yang islami dan berakhlak mulia.
                                    </h5>
                                    <h5>Selain aktifitas belajar mengajar SMKS Al Muhajirin mendukung kreatifitas
                                        peserta
                                        didiknya di bidang non akademis, SMKS Al Muhajirin mendukung siswanya untuk
                                        berorganisasi dalam sekolah di antaranya organisasi siswa intra sekolah atau
                                        OSIS,
                                        hal ini tentu saja agar peserta didik memiliki kemampuan untuk bersosialisasi
                                        dengan
                                        peserta didik lainnya serta pihak terkait agar tercipta pribadi yang memiliki
                                        mental
                                        yang baik dan tangguh.</h5>
                                    <h5>Untuk mengasah dan mengembangkan bakat dan minat setiap peserta didik SMKS Al
                                        Muhajirin menyediakan fasilitas sebaik baiknya kepada peserta didik untuk
                                        memilih
                                        kegiatan ekstrakurikuler, baik yang bersifat wajib maupun pilihan.

                                        Kegiatan ekstrakurukuler SMKS Al Muhajirin antara lain Pramuka (Wajib), desain
                                        grafis, jaringan, basket, dan volli. Rangkaian kegiatan siswa tersebut merupakan
                                        upaya agar peserta didik memiliki kemampuan hard skill maupun soft skill
                                        sehingga
                                        SMKS Al Muhajirin tidak hanya menciptakan lulusan berkualitas dalam bidang dunia
                                        kerja, namun juga memiliki kualitas pribadi yang berkarakter, berbudi pekerti
                                        dan
                                        religi.</h5>
                                </div>
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

    </main>
    <?php include 'footer.html' ?>

    <script src="assets/js/main.js"></script>

</body>

</html>