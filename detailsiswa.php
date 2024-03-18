<?php
session_start();
include('conn.php');

$id_user = isset($_SESSION['id_user']) ? $_SESSION['id_user'] : '';

if (empty($id_user)) {
    header("Location: index.php");
    exit;
}

$nama = isset($_SESSION['username']) ? $_SESSION['username'] : '';

$query = "SELECT laporan_pkl.*, siswa.*, user.username, user.id_user, pkl.*
FROM laporan_pkl
INNER JOIN siswa ON laporan_pkl.id_siswa = siswa.id_siswa
INNER JOIN user ON siswa.id_user = user.id_user
INNER JOIN pkl ON pkl.id_siswa = siswa.id_siswa
WHERE user.username = ?
";

$stmt = $koneksi->prepare($query);
if (!$stmt) {
    die("Query failed: " . $koneksi->error); // Menampilkan pesan kesalahan jika query gagal
}

// Binding parameter
$stmt->bind_param("s", $nama);
// Eksekusi statement
$stmt->execute();
// Menyimpan hasil query
$result = $stmt->get_result();
?>
<!DOCTYPE html>
<html lang="en">

<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet">
<link rel="stylesheet" href="assets/css/styl.css">
<?php include 'head.html'; ?>

<style>
    body {
        font-family: "Nunito", sans-serif;
    }

    table,
    tr,
    td {
        font-family: "Nunito", sans-serif;
    }

    .warna {
        color: #012970;
    }

    table,
    th,
    tr,
    td {
        font-family: "Nunito", sans-serif;
        color: #768ace;
    }
    label{
        font-family: "Nunito", sans-serif;
        color: #768ace;
        font-weight: bold;
    }

</style>

<body>
    <?php include 'header_siswa.php'; ?>
    <main id="main">

        <section class="breadcrumbs">
            <div class="container">
                <ol>
                    <li><a href="home.php">Home</a></li>
                    <li><a href="detailsiswa.php">Detail Aktivitas</a></li>
                </ol>
                <h2>
                    <?php
                    if (isset($_SESSION['username'])) {
                        $nama_siswa = $_SESSION['username'];
                        echo '<h2>Hallo ' . $nama_siswa . '</h2>';
                    } else {
                        echo '<h2>Hallo</h2>';
                    }
                    ?>
                </h2>
            </div>
        </section>

        <div class="container mt-3 card shadow">
            <div class="row">
                <div class="col-md-2 border-end rounded" style="background: #012970;">
                    <ul class="nav nav-pills flex-column" id="myTab" role="tablist" aria-orientation="vertical">
                        <li class="nav-item" role="presentation">
                            <button class="nav-link active btn-custom" id="home-tab" data-bs-toggle="tab"
                                data-bs-target="#home" type="button" role="tab" aria-controls="home"
                                aria-selected="true">Detail Aktivitas</button>
                        </li>

                    </ul>
                </div>

                <div class="col-md-10">
                    <div class="tab-content" id="myTabContent">
                        <div class="tab-pane fade show active rounded" id="home" role="tabpanel"
                            aria-labelledby="home-tab">
                            <table class="table table-striped table-responsive">
                                <thead>
                                    <tr>
                                        <th colspan="2" class="table-primary" style="color:#00008B;font-size : 20px">Detail Aktivitas Siswa</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php
                                    while ($row = mysqli_fetch_assoc($result)) { ?>
                                        <tr>
                                            <td>
                                                <label for="">Nama Siswa</label></td>
                                            <td class="warna">
                                                <?php echo $row['Nama_siswa'] ?>
                                            </td>

                                        </tr>
                                        <tr>
                                            <td><label for="">NIS</label></td>
                                            <td>
                                                <?php echo $row['NIS'] ?>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td><label for="">Tanggal Lahir</label></td>
                                            <td>
                                                <?php echo date('d F Y', strtotime($row['tanggal_lahir'])) ?>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td><label for="Alamat">Alamat</label></td>
                                            <td>
                                                <?php echo $row['alamat'] ?>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td><label for="">Alamat</label></td>
                                            <td>
                                                <?php echo $row['jenis_kelamin'] ?>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td><label for="">No Hp</label></td>
                                            <td>
                                                <?php echo $row['no_hp'] ?>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td><label for="">Email</label></td>
                                            <td>
                                                <?php echo $row['email'] ?>
                                            </td>
                                        </tr>
                                        <thead>
                                            <tr>
                                                <th colspan="2" class="table-success" style="color:#00008B;font-size : 20px">Detail Kegiatan Siswa</th>
                                            </tr>
                                        </thead>
                                    <tbody>
                                        <tr>
                                            <td><label for="">Tempat Praktik Kerja Lapangan</label></td>
                                            <td>
                                                <?php echo $row['nama_perusahaan'] ?>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td><label for="">Tanggal Mulai</label></td>
                                            <td>
                                                <?php echo date('d F Y', strtotime($row['tgl_mulai'])) ?>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td><label for="">Tanggal Selesai</label></td>
                                            <td>
                                                <?php echo date('d F Y', strtotime($row['tgl_selesai'])) ?>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td><label for="">Tahun Pelajaran</label></td>
                                            <td>
                                                <?php echo $row['tahun_pelajaran'] ?>
                                            </td>
                                        </tr>
                                        <thead>
                                            <tr>
                                                <th colspan="2" class="table-danger" style="color:#00008B;font-size : 20px">Detail Laporan Siswa</th>
                                            </tr>
                                        </thead>
                                    <tbody>
                                        <tr>
                                            <td><label for="">Judul Laporan</label></td>
                                            <td>
                                                <?php echo $row['judul_laporan'] ?>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td><label for="">Tanggal Pengumpulan</label></td>
                                            <td>
                                                <?php echo date('d F Y', strtotime($row['tanggal_kumpul'])) ?>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td><label for="">Status</label></td>
                                            <td>
                                                <?php echo $row['status'] ?>
                                            </td>
                                        </tr>
                                    <?php } ?>
                                </tbody>
                            </table>

                        </div>
                    </div>
                </div>
            </div>
        </div>


    </main>

    <a href="#" class="back-to-top d-flex align-items-center justify-content-center"><i
            class="bi bi-arrow-up-short"></i></a>


    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js">
        <script src="assets/js/main.js"></script>

</body>

</html>