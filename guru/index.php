<?php
session_start();
include('conn.php');

$username = isset($_SESSION['username']) ? $_SESSION['username'] : '';

if (empty($username)) {
    header("Location: ../index.php");
    exit;
}


$query = "SELECT * FROM guru_pamong INNER JOIN user 
ON user.id_user=guru_pamong.id_user WHERE username='$username'";
$result_data = mysqli_query($koneksi, $query);

if (!$result_data) {
    die("Error: " . mysqli_error($koneksi));
}


?>

<!DOCTYPE html>
<html lang="en">

<?php include 'head.html' ?>
<style>
    .profile img {
        height: 300px;
        max-width: 100%;
        border-radius: 5%;
    }

    @media (min-width: 768px) {
        .profile img {
            max-width: 300px;
        }
    }

    .profile-table th {
        width: 30%;
    }
</style>

<body class="sb-nav-fixed">
    <?php include 'header.php' ?>
    <div id="layoutSidenav_content">
        <main>
            <div class="container-fluid px-4">
                <h1 class="mt-4">Dashboard</h1>
                
                <div class="row">
                    <?php
                    include 'conn.php';

                    $sql = "SELECT
                    COUNT(*) AS jumlah_siswa,
                    pkl.tahun_pelajaran
                FROM
                    laporan_pkl
                LEFT JOIN siswa ON laporan_pkl.id_siswa = siswa.id_siswa
                LEFT JOIN pkl ON pkl.id_siswa = siswa.id_siswa
                WHERE
                    pkl.tahun_pelajaran BETWEEN YEAR(CURDATE()) - 2 AND YEAR(CURDATE())
                GROUP BY
                    pkl.tahun_pelajaran
                LIMIT 3";

                    $colors = ['bg-primary', 'bg-secondary', 'bg-success', 'bg-danger', 'bg-warning', 'bg-info', 'bg-dark'];

                    $result = mysqli_query($koneksi, $sql);
                    if (!$result) {
                        die("Error: " . mysqli_error($koneksi));
                    }

                    $color_index = 0;

                    while ($row = mysqli_fetch_assoc($result)) {
                        $card_color = $colors[$color_index % count($colors)];

                        echo '<div class="col-xl-3 col-md-6">';
                        echo '<div class="card ' . $card_color . ' text-white mb-4">';
                        echo '<div class="card-body">' . $row['tahun_pelajaran'] . '</div>';
                        echo '<div class="card-footer d-flex align-items-center justify-content-between">';
                        echo '<a class="small text-white stretched-link" href="details.php?tahun_pelajaran=' . $row['tahun_pelajaran'] . '">View Details</a>';
                        echo '<div class="small text-white"><i class="fas fa-angle-right"></i></div>';
                        echo '</div>';
                        echo '</div>';
                        echo '</div>';

                        $color_index++;
                    }

                    mysqli_close($koneksi);
                    ?>

                </div>

                <div class="row text-center">
                    <h2 class=""><strong>Selamat Datang Di <br> Sistem Informasi Repository Laporan PKL
                            Siswa</strong></h2>
                    <h2><strong>SMK Al - Muhajirin</strong></h2>
                </div>
                <div class="row justify-content-center">
                    <div class="col-md-6 text-center">
                        <img src="assets/img/smk.png" alt=""
                            style="width: 50%; height: 100%; display: block; margin: 0 auto;">
                    </div>
                </div>

                
            </div>
        </main>
        <footer class="py-4 bg-light mt-auto">
            <div class="container-fluid px-4">
                <div class="d-flex align-items-center justify-content-between small">
                    <div class="text-muted">Sirepository</div>

                </div>
            </div>
        </footer>
    </div>
    </div>
    <?php include 'footer.php'; ?>
</body>

</html>