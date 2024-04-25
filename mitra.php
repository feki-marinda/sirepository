<?php
session_start();
include('conn.php');

$id_user = isset($_SESSION['id_user']) ? $_SESSION['id_user'] : '';

if (empty($id_user)) {
    header("Location: index.php");
    exit;
}

?>

<style>
    .card {
        height: 100%;
        display: flex;
        flex-direction: column;
    }

    .card-img-top {
        flex: 1;
    }

    .card-body {
        flex: 1;
    }
</style>

<!DOCTYPE html>
<html lang="en">
<?php include 'head.html'; ?>

<body>
    <?php include 'header_siswa.php'; ?><br>
    <section class="breadcrumbs">
        <div class="container">
            <ol>
                <li><a href="index.php">Home</a></li>
                <li><a href="mitra.php">Mitra</a></li>
            </ol>
            <h2>
               SMK Al-Muhajirin
            </h2>
        </div>
    </section>

    <div class="container">
        <div class="row mt-3 mb-3 pb-5 pt-5 ps-5 pe-5 rounded shadow d-flex" style="background-color: #F0F8FF;">
            <h2 class="font-weight-bold text-center" style="font-size: 2.5rem; color: #333;">
                Mitra yang bekerja sama dalam kegiatan <span style="color: #FFD700; font-weight: bold;">Praktik Kerja
                    Lapangan</span> SMK Al-Muhajirin
            </h2>
        </div>
        <div class="row row-cols-1 row-cols-md-3 g-4">
            <?php
            include 'admin/conn.php';
            $querydokumen = $koneksi->query("SELECT * FROM mitra limit 4");
            if ($querydokumen && mysqli_num_rows($querydokumen) > 0) {
                while ($data = mysqli_fetch_array($querydokumen)) {
                    ?>
                    <div class="col">
                        <div class="card h-100">
                            <img src="<?php echo "./gambar/" . $data['foto']; ?>" class="card-img-top" alt="...">
                            <div class="card-body">
                                <h5 class="card-title">
                                    <?php echo $data['nama']; ?>
                                </h5>
                                <p class="card-text">
                                    <?php echo $data['alamat']; ?>
                                </p>
                            </div>
                        </div>
                    </div>
                    <?php
                }
            }
            ?>
        </div>
    </div>

    <?php include 'footer.html'; ?>
    <script src="assets/js/main.js"></script>

</body>

</html>
