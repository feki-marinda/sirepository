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
                    <div class="col-lg-8 entries">
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
                    <div class="col-lg-4">
                        <div class="sidebar">
                            <h3 class="sidebar-title">Search</h3>
                            <div class="sidebar-item search-form">
                                <form action="">
                                    <input type="text">
                                    <button type="submit"><i class="bi bi-search"></i></button>
                                </form>
                            </div>
                            <h3 class="sidebar-title">Categories</h3>
                            <div class="sidebar-item categories">
                                <ul>
                                    <li><a href="#">General <span>(25)</span></a></li>
                                    <li><a href="#">Lifestyle <span>(12)</span></a></li>
                                    <li><a href="#">Travel <span>(5)</span></a></li>
                                    <li><a href="#">Design <span>(22)</span></a></li>
                                    <li><a href="#">Creative <span>(8)</span></a></li>
                                    <li><a href="#">Educaion <span>(14)</span></a></li>
                                </ul>
                            </div>

                            <h3 class="sidebar-title">Recent Posts</h3>
                            <div class="sidebar-item recent-posts">
                                <div class="post-item clearfix">
                                    <img src="assets/img/blog/blog-recent-1.jpg" alt="">
                                    <h4><a href="blog-single.html">Nihil blanditiis at in nihil autem</a></h4>
                                    <time datetime="2020-01-01">Jan 1, 2020</time>
                                </div>

                                <div class="post-item clearfix">
                                    <img src="assets/img/blog/blog-recent-2.jpg" alt="">
                                    <h4><a href="blog-single.html">Quidem autem et impedit</a></h4>
                                    <time datetime="2020-01-01">Jan 1, 2020</time>
                                </div>

                                <div class="post-item clearfix">
                                    <img src="assets/img/blog/blog-recent-3.jpg" alt="">
                                    <h4><a href="blog-single.html">Id quia et et ut maxime similique occaecati ut</a>
                                    </h4>
                                    <time datetime="2020-01-01">Jan 1, 2020</time>
                                </div>

                                <div class="post-item clearfix">
                                    <img src="assets/img/blog/blog-recent-4.jpg" alt="">
                                    <h4><a href="blog-single.html">Laborum corporis quo dara net para</a></h4>
                                    <time datetime="2020-01-01">Jan 1, 2020</time>
                                </div>

                                <div class="post-item clearfix">
                                    <img src="assets/img/blog/blog-recent-5.jpg" alt="">
                                    <h4><a href="blog-single.html">Et dolores corrupti quae illo quod dolor</a></h4>
                                    <time datetime="2020-01-01">Jan 1, 2020</time>
                                </div>

                            </div>

                            <h3 class="sidebar-title">Tags</h3>
                            <div class="sidebar-item tags">
                                <ul>
                                    <li><a href="#">App</a></li>
                                    <li><a href="#">IT</a></li>
                                    <li><a href="#">Business</a></li>
                                    <li><a href="#">Mac</a></li>
                                    <li><a href="#">Design</a></li>
                                    <li><a href="#">Office</a></li>
                                    <li><a href="#">Creative</a></li>
                                    <li><a href="#">Studio</a></li>
                                    <li><a href="#">Smart</a></li>
                                    <li><a href="#">Tips</a></li>
                                    <li><a href="#">Marketing</a></li>
                                </ul>
                            </div>

                        </div>

                    </div>
                </div>

            </div>
        </section>
    </main>

    <?php include 'footer.html' ?>


    <script src="assets/js/main.js"></script>

</body>

</html>