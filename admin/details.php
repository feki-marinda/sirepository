<?php
session_start();
include('conn.php');

session_start();
include('conn.php');

$status = isset($_SESSION['status']) ? $_SESSION['status'] : '';

if (empty($status)) {
    header("Location: ../index.php");
    exit;
}

if (isset($_GET['tahun_pelajaran'])) {
    $tahun_pelajaran = $_GET['tahun_pelajaran'];
} else {
    $tahun_pelajaran = "Tahun Pelajaran Tidak Ditemukan";
}
?>

<!DOCTYPE html>
<html lang="en">

<?php include 'head.html' ?>

<body class="sb-nav-fixed">
    <?php include 'header.php' ?>
    <div id="layoutSidenav" style="width: 100%">

        <div id="layoutSidenav_content">
            <main>
                <div class="container-fluid px-4">
                    <h1 class="mt-4">Data Laporan PKL Siswa</h1>
                    <ol class="breadcrumb mb-4">
                        <li class="breadcrumb-item"><a href="index.php">Dashboard</a></li>
                        <li class="breadcrumb-item active">Tables</li>
                    </ol>

                    <div class="card mb-4 text-center">
                        <h3><strong>Data Laporan PKL Siwsa SMK Al-Muhajirin <br> Tahun
                                <?php echo $tahun_pelajaran; ?>
                            </strong></h3>
                    </div>

                    <div class="card mb-4">
                        <div class="card-header">
                            <i class="fas fa-table me-1"></i>
                            Data Dokumen Praktek Kerja Lapangan
                        </div>
                        <div class="card-body">
                            <table id="datatablesSimple" class="table table-striped table-hover">

                                <thead>
                                    <tr>
                                        <th>No.</th>
                                        <th>Nama Lengkap</th>
                                        <th>Tanggal Pengupulan</th>
                                        <th>Judul Laporan</th>
                                        <th>Tahun Pelajaran</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php
                                    if (isset($_GET['tahun_pelajaran'])) {
                                        $tahun_pelajaran = $_GET['tahun_pelajaran'];

                                        $query = "SELECT siswa.*, laporan_pkl.tanggal_kumpul, laporan_pkl.judul_laporan, laporan_pkl.berkas , pkl.tahun_pelajaran
                          FROM siswa 
                          LEFT JOIN laporan_pkl ON siswa.id_siswa = laporan_pkl.id_siswa
                          LEFT JOIN pkl ON siswa.id_siswa = pkl.id_siswa 
                          WHERE pkl.tahun_pelajaran = '$tahun_pelajaran'";
                                        $result = mysqli_query($koneksi, $query);

                                        if (!$result) {
                                            die("Error in query: " . mysqli_error($koneksi));
                                        }

                                        $no = 1;
                                        while ($row = mysqli_fetch_assoc($result)) {
                                            echo "<tr>";
                                            echo "<td>$no</td>";
                                            echo "<td>" . $row['Nama_siswa'] . "</td>";
                                            echo "<td>" . date('d-m-Y', strtotime($row['tanggal_kumpul'])) . "</td>";
                                            echo "<td>" . $row['judul_laporan'] . "</td>";
                                            echo "<td>" . $row['tahun_pelajaran'] . "</td>";
                                            echo "</tr>";
                                            $no++;
                                        }
                                    } else {
                                        echo "<tr><td colspan='5'>Parameter tahun_pelajaran tidak tersedia.</td></tr>";
                                    }
                                    ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </main>



            
        </div>
    </div>
    <?php include 'footer.php'; ?>
</body>

</html>