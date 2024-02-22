<?php
session_start();
$username = isset($_SESSION['username']) ? $_SESSION['username'] : '';

include 'conn.php';

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
                    <li><a href="about.php">Logbook</a></li>
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
                <div class="col-md-9">
                    <h1 class="font-weight-bold text-left" style="font-size: 2.5rem; color: #333;">
                        Bagaimana <span style="color: #FFD700;">Kegiatanmu</span> hari ini? Jangan lupa isi
                        <span style="color: #FFD700; font-weight: bold;">logbook</span> yaa dan Ekspresikan kegiatanmu
                        di <span style="color: #FFD700;">Logbook</span>
                    </h1>
                </div>
                <div class="col-md-3 d-flex align-items-center justify-content-end">
                    <img src="assets/img/features-2.png" alt="" style="max-height: 150px; width: auto;"
                        class="img-fluid">
                </div>
            </div>
            <br>
            <div class="ms-3 pb-5 pt-5 ps-5 pe-5 rounded shadow" style="height: 100%; max-width: 100%;">
                <div class="ms-2 pt-2 me-2 mb-3">
                    <h2 class="text-center">Logbook Harian</h2>
                    <div class="d-flex justify-content-end ">
                        <a class="btn btn-primary btn-lg" href="isilogbook.php" role="button">
                            <i class="fa-light fa-plus"></i> Isi Logbook
                        </a>
                    </div><br>

                    <table id="example" class="display">
                        <thead>
                            <tr>
                                <th>Nama</th>
                                <th>Tanggal</th>
                                <th>Aktivitas</th>
                                <th>Status</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php

                            $query = "SELECT
                                        siswa.Nama_siswa,
                                        logbook.tanggal,
                                        logbook.aktivitas,
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
                                        <td>
                                            <?php echo $row['Nama_siswa']; ?>
                                        </td>
                                        <td>
                                            <?php echo $row['tanggal']; ?>
                                        </td>
                                        <td>
                                            <?php echo $row['aktivitas']; ?>
                                        </td>
                                        <td>
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

                <script>
                    $(document).ready(function () {
                        $('#example').DataTable();
                    });
                </script>
            </div>
        </div>
    </div>

    <script src="assets/js/main.js"></script>

    </main>

    </body>

    </html>
