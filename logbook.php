<?php
session_start();
include('conn.php');

$id_user = isset($_SESSION['id_user']) ? $_SESSION['id_user'] : '';

if (empty($id_user)) {
    header("Location: index.php");
    exit;
}

$username = isset($_SESSION['username']) ? $_SESSION['username'] : '';

include 'conn.php';

?>

<style>
    table {
        font-family: Arial, sans-serif;
    }

    th,
    td {
        font-family: Arial, sans-serif;
    }
</style>
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
                    <li><a href="Logbook.php">Logbook</a></li>
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


        <div class="container">
            <div class="row ms-3 pb-5 pt-5 ps-5 pe-5 rounded shadow d-flex" style="background-color: #F0F8FF;">

                <h1 class="font-weight-bold text-left" style="font-size: 2.5rem; color: #333;">
                    Bagaimana <span style="color: #FFD700;">Kegiatanmu</span> hari ini? Jangan lupa isi
                    <span style="color: #FFD700; font-weight: bold;">logbook</span> yaa dan Ekspresikan kegiatanmu
                    di <span style="color: #FFD700;">Logbook</span>
                </h1>
            </div>
            <br>
            <div class="ms-3 pb-5 pt-5 ps-5 pe-5 rounded shadow">
                <div class="ms-2 pt-2 me-2 mb-3">
                    <h2 class="text-center">Logbook Harian</h2>
                    <div class="d-flex justify-content-end">
                        <a class="btn btn-primary btn-lg me-3" href="isilogbook.php" role="button">
                            <i class="fal fa-plus"></i> Isi Logbook
                        </a>
                        <button id="printButton" class="btn btn-lg btn-success">
                            <a href="admin/cetak/logbooksiswa.php" style="text-decoration: none; color: inherit;"
                                target="_blank">
                                <i class="fas fa-print"></i> Cetak
                            </a>
                        </button>
                    </div>
                    <br>

                    <table id="example" class="display" style="table-layout: fixed; width: 100%;">
                        <thead class="table-primary">
                            <tr>
                                <th style="width: 25%;">Nama</th>
                                <th style="width: 15%;">Tanggal</th>
                                <th style="width: 25%;">Aktivitas</th>
                                <th style="width: 25%;">Dokumentasi</th>
                                <th style="width: 10%;">Status</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            $query = "SELECT
                    siswa.Nama_siswa,
                    logbook.tanggal,
                    logbook.aktivitas,
                    logbook.dokumentasi,
                    logbook.status_logbook
                FROM
                    user
                JOIN siswa ON user.id_user = siswa.id_user
                JOIN logbook ON siswa.id_siswa = logbook.id_siswa
                WHERE user.username = '$username';";

                            $result = $koneksi->query($query);

                            if ($result) {
                                while ($row = $result->fetch_assoc()) {
                                    ?>
                                    <tr>
                                        <td style="width: 25%;">
                                            <?php echo $row['Nama_siswa']; ?>
                                        </td>
                                        <td style="width: 15%;">
                                            <?php echo date('d F Y', strtotime($row['tanggal'])); ?>
                                        </td>
                                        <td style="width: 25%; text-align: justify;">
                                            <?php echo $row['aktivitas']; ?>
                                        </td>
                                        <td style="width: 25%;">
                                            <?php echo "<img src='Logbook/" . $row['dokumentasi'] . "' style='max-width: 30%; height: auto;' class='img-responsive'>"; ?>
                                        </td>
                                        <td style="width: 10%;">
                                            <?php echo $row['status_logbook']; ?>
                                        </td>
                                    </tr>
                                    <?php
                                }
                            } else {
                                echo "Error executing query: " . $koneksi->error;
                            }

                            $koneksi->close();
                            ?>
                        </tbody>
                    </table>



                </div>
            </div>
        </div>
        <script>
            $(document).ready(function () {
                $('#example').DataTable();
            });
        </script>
        <script src="assets/js/main.js"></script>

    </main>

</body>

</html>