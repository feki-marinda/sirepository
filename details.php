<?php
session_start();
include('conn.php');

$id_user = isset($_SESSION['id_user']) ? $_SESSION['id_user'] : '';

if (empty($id_user)) {
    header("Location: index.php");
    exit;
}

if (isset($_GET['id_berita'])) {
    $id_berita = $_GET['id_berita'];

    $query = $koneksi->prepare("SELECT * FROM berita WHERE id_berita = ?");
    $query->bind_param("i", $id_berita);
    $query->execute();
    $result = $query->get_result();

    if ($result->num_rows > 0) {
        $berita = $result->fetch_assoc();
        ?>

        <!DOCTYPE html>
        <html lang="en">
        <?php include 'head.html' ?>

        <body>
            <!-- ======= Header ======= -->
            <?php include 'header_siswa.php' ?>
            <!-- End Header -->
            <?php
    } else {
        echo "Berita tidak ditemukan.";
    }

    // Tutup koneksi
    $query->close();
} else {
    echo "Parameter ID berita tidak ditemukan.";
}
?>

    <main id="main">

        <section class="breadcrumbs">
            <div class="container">
                <ol>
                    <li><a href="home.php">Home</a></li>
                    <li><a href="#">Berita</a></li>
                    <li>Detail Berita</li>
                </ol>
                <h2>SMK Al-Muhajirin</h2>
            </div>
        </section>

        <section id="blog" class="blog">
            <div class="container" data-aos="fade-up">
                <div class="row">
                    <div class="col-lg-12 entries">
                        <article class="entry entry-single">
                            <div class="entry-img d-flex align-items-center justify-content-center mt-3 ms-1">
                                <img src="admin/gambar/siswa/<?= $berita["foto"]; ?>" alt="Gambar Berita"
                                    class="img-fluid rounded" style="width:100vh;">
                            </div>
                            <p>
                                <?= $berita["tanggal"]; ?>
                            </p>
                            <h2 class="entry-title">
                                <a href="#">
                                    <h1>
                                        <?= $berita["judul"]; ?>
                                    </h1>
                                </a>
                            </h2>
                            <div class="entry-content">
                                <p>
                                    <?= $berita["isi_berita"]; ?>
                                </p>
                            </div>
                            <div class="entry-footer">
                                <i class="bi bi-folder"></i>
                                <ul class="cats">
                                    <li><a href="#">Business</a></li>
                                </ul>
                                <i class="bi bi-tags"></i>
                                <ul class="tags">
                                    <li><a href="#">Creative</a></li>
                                    <li><a href="#">Tips</a></li>
                                    <li><a href="#">Marketing</a></li>
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