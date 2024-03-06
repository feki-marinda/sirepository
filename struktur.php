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
                                <a href="sejarah.php">Struktur Organisasi SMK Al-Muhajirin</a><br>
                            </h1>
                            </div>
                            
                            <div class="entry-content" style="text-align:justify">
                                <div>
                                    <img src="assets/img/stk.jpg" alt="" class="img-fluid rounded">
                                </div><br>
                               
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