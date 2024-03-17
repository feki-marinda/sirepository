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
                    <li><a href="index.php">Beranda</a></li>
                    <li><a href="about.php">Repository</a></li>
                    <li>Repository</li>
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
        
        <style>
            table {
                font-family: Arial, sans-serif;
            }
            th, td {
                font-family: Arial, sans-serif;
            }
        </style>

        <div class="container">
            <div class="row ms-3 pb-5 pt-5 ps-5 pe-5 rounded shadow d-flex" style="background-color: #F0F8FF;">
                <div class="col-md-9">
                    <h1 class="font-weight-bold text-left" style="font-size: 1.5 rem; color: #333;">
                        Berikut <span style="color: #FFD700;">Laporan</span> Praktik Kerja Lapangan
                        <span style="color: #FFD700; font-weight: bold;">yang sudah tersimpan</span>
                    </h1>
                </div>
                <div class="col-md-3 d-flex align-items-center justify-content-end">
                    <img src="assets/img/features.png" alt="" style="max-height: 150px; width: auto;" class="img-fluid">
                </div>
            </div>
            <br>
            <div class="row ms-3 pb-5 pt-5 ps-5 pe-5 rounded shadow d-flex">
                <table id="example" class="display">
                    <thead>
                        <tr class="table-primary">
                            <th>No</th>
                            <th>Nama</th>
                            <th>Judul Laporan</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        $no = 1;
                        $query = "SELECT * 
                        FROM laporan_pkl 
                        LEFT JOIN siswa ON laporan_pkl.id_siswa = siswa.id_siswa 
                        WHERE status = 'Diterima' OR status = 'Terkirim';
                        ";
                        $result = mysqli_query($koneksi, $query);
                        
                        if (!$result) {
                            die("Query gagal: " . mysqli_error($koneksi));
                        }
                        
                        while ($row = mysqli_fetch_assoc($result)) {
                            echo "<tr>";
                            echo "<td>" . $no++ . "</td>";
                            echo "<td>" . $row['Nama_siswa'] . "</td>";
                            echo "<td>" . $row['judul_laporan'] . "</td>";
                            echo "<td><a href='https://drive.google.com/uc?id=" . $row['google_drive_file_id'] . "' target='_blank' rel='noopener noreferrer'>Unduh</a></td>";
                            echo "</tr>";
                        }                                     

                        mysqli_free_result($result);
                        ?>

                    </tbody>
                </table>

                <script>
                    $(document).ready(function () {
                        $('#example').DataTable();
                    });
                </script>
            </div>
        </div>
       
        <script src="assets/js/main.js"></script>

</body>

</html>
