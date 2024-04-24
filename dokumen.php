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

    <!-- ======= Header ======= -->
    <?php include 'header_siswa.php' ?>
    <!-- End Header -->

    <main id="main">

        <!-- ======= Breadcrumbs ======= -->
        <section class="breadcrumbs">
            <div class="container">

                <ol>
                    <li><a href="index.php">Home</a></li>
                    <li><a href="dokumen.php">Dokumen</a></li>
                    
                </ol>
                <h2>
                    SMK Al-Muhajirin
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
                        Unduh <span style="color: #FFD700;">Dokumen</span> yang anda butuhkan
                        <span style="color: #FFD700; font-weight: bold;">disini</span>
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
        <tr>
            <th>No</th>
            <th>Judul Dokumen</th>
            <th>Keterangan</th>
            <th>Action</th>
        </tr>
    </thead>
    <tbody>
        <?php
        $no = 1;
        $query = "SELECT * FROM dokumen";
        $result = mysqli_query($koneksi, $query);

        if (!$result) {
            die("Query failed: " . mysqli_error($koneksi));
        }

        while ($row = mysqli_fetch_assoc($result)) {
            echo "<tr>";
            echo "<td>" . $no++ . "</td>";
            echo "<td>" . $row['judul_dokumen'] . "</td>";
            echo "<td><a href='admin/Dokumen/{$row['Dokumen']}' target = _blank>
            Detail
          </a></td>";
            echo "<td><a href='admin/Dokumen/{$row['Dokumen']}' class='btn btn-primary me-md-2' download>
            Download <i class='fas fa-download'></i>
          </a></td>";           
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