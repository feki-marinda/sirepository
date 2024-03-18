<?php
session_start();
include 'conn.php';

$query = "SELECT logbook.*, pkl.*, siswa.*
FROM logbook
INNER JOIN pkl ON logbook.id_pkl = pkl.id_pkl
INNER JOIN siswa ON pkl.id_siswa = siswa.id_siswa;";
$result = mysqli_query($koneksi, $query);

if (!$result) {
    die("Error in query: " . mysqli_error($koneksi));
}

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["TambahLogbook"])) {
    // Mendapatkan data dari formulir
    $id_siswa = $_POST["id_siswa"];
    $tanggal = $_POST["tanggal"];
    $aktivitas = $_POST["aktivitas"];
    $status_logbook = $_POST["status_logbook"];

    // Mendapatkan id_pkl dari formulir (data-id_pkl pada dropdown)
    $id_pkl = $_POST["id_pkl"];

    // Insert logbook entry ke tabel logbook
    $insert_query = "INSERT INTO logbook (id_siswa, id_pkl, tanggal, aktivitas, status_logbook) VALUES ('$id_siswa', '$id_pkl', '$tanggal', '$aktivitas', '$status_logbook')";
    $insert_result = mysqli_query($koneksi, $insert_query);

    if ($insert_result) {
        $_SESSION['success_message'] = "Data logbook berhasil ditambahkan!";
        header("Location: datalogbook.php");
        exit();
    } else {
        $_SESSION['error_message'] = "Error: " . $koneksi->error;
        header("Location: datalogbook.php");
        exit();
    }
}

if (isset($_POST['EditLogbook'])) {
    $id_logbook = $_POST['id_logbook'];
    $tanggal = $_POST['tanggal'];
    $aktivitas = $_POST['aktivitas'];
    $status_logbook = $_POST['status_logbook'];

    mysqli_query($koneksi, "UPDATE logbook SET 
                         tanggal='$tanggal',
                         aktivitas='$aktivitas',
                         status_logbook='$status_logbook'                                                     
                         WHERE id_logbook='$id_logbook'");
    header("location:datalogbook.php");
}

if (isset($_GET['id_logbook'])) {
    $id_logbook = $_GET['id_logbook'];

    mysqli_query($koneksi, "DELETE FROM logbook WHERE id_logbook='$id_logbook'");
    if ($result) {
        $_SESSION['success_message'] = "Data Logbook Berhasil Dihapus!";
        header("Location: datalogbook.php");
        exit();
    }
}

$koneksi->close();
?>
<!DOCTYPE html>
<html lang="en">
    <link rel="stylesheet" href="font.css">
<?php include 'head.html' ?>

<body class="sb-nav-fixed">
    <?php include 'header.php' ?>
    <div id="layoutSidenav" style="width: 100%">

        <div id="layoutSidenav_content">
            <main>
                <div class="container-fluid px-4">
                    <h1 class="mt-4">Data Logbook</h1>
                    <ol class="breadcrumb mb-4">
                        <li class="breadcrumb-item"><a href="index.php">Dashboard</a></li>
                        <li class="breadcrumb-item active">Tables</li>
                    </ol>

                    <div class="card mb-4">
                        <div class="button-container">
                            <div class="spacer"></div>
                            <div class="buttons-right">
                                <button type="button" class="btn btn-primary" data-bs-toggle="modal"
                                    data-bs-target="#tambah" data-bs-whatever="@mdo"> <i class="fas fa-plus"></i>
                                    Tambah Data Logbook</button>
                                    <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#staticBackdrop">
    <i class="fas fa-print"></i> Cetak
</button>

                            </div>
                        </div>
                    </div>

                    <div class="modal fade" id="staticBackdrop" data-bs-backdrop="static" data-bs-keyboard="false"
                        tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
                        <div class="modal-dialog">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h5 class="modal-title" id="staticBackdropLabel">Cetak Logbook Siswa</h5>
                                    <button type="button" class="btn-close" data-bs-dismiss="modal"
                                        aria-label="Close"></button>
                                </div>
                                <div class="modal-body">
                                    <form action="cetak/datalogbook.php" method="post" target="_blank">
                                        <div class="mb-3">
                                            <label for="Nama_siswa" class="form-label">Masukkan Nama:</label>
                                            <input type="text" class="form-control" id="Nama_siswa" name="Nama_siswa"
                                                required>
                                        </div>
                                        <div class="modal-footer">
                                            <button type="button" class="btn btn-secondary"
                                                data-bs-dismiss="modal">Tutup</button>
                                            <button type="submit" class="btn btn-primary" >Cetak</button>
                                        </div>
                                    </form>

                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="card mb-4">
                        <div class="card-header">
                            <i class="fas fa-table me-1"></i>
                            Data Logbook Praktik Kerja Lapangan Siswa SMK AL-Muhajirin
                        </div>
                        <div class="card-body">
                            <?php
                            if (!empty($error_message)) {
                                echo '<div class="alert alert-danger" role="alert">' . $error_message . '</div>';
                            }
                            if (!empty($success_message)) {
                                echo '<div class="alert alert-success" role="alert">' . $success_message . '</div>';
                            }
                            ?>
                            <table id="datatablesSimple" class="table table-striped table-hover">
                                <thead>
                                    <tr>
                                        <th>No.</th>
                                        <th>Nama</th>
                                        <th>Tanggal Kegiatan</th>
                                        <th>Aktivitas</th>
                                        <th>Status</th>
                                        <th>Keterangan</th>
                                    </tr>
                                </thead>

                                <tbody>
                                    <?php
                                    $no = 1;
                                    include 'conn.php';
                                    $query_table = "SELECT * FROM logbook INNER JOIN siswa on logbook.id_siswa = siswa.id_siswa";
                                    $result_table = mysqli_query($koneksi, $query_table);
                                    while ($row = mysqli_fetch_assoc($result_table)) {
                                        echo "<tr>";
                                        echo "<td>" . $no++ . "</td>";
                                        echo "<td>" . $row['Nama_siswa'] . "</td>";

                                        $formattedDate = date('d/m/Y', strtotime($row['tanggal']));
                                        echo "<td>" . $formattedDate . "</td>";

                                        echo "<td>" . $row['aktivitas'] . "</td>";
                                        echo "<td>" . $row['status_logbook'] . "</td>";
                                        echo "<td>";
                                        echo "<div class='d-flex'>";
                                        echo "<button type='button' class='btn btn-primary me-2' data-bs-toggle='modal' data-bs-target='#edit" . $row['id_logbook'] . "' data-bs-whatever='@mdo'>";
                                        echo "<i class='fas fa-pencil-alt'></i> Edit";
                                        echo "</button>";
                                        echo "<button type='button' class='btn btn-danger' data-bs-toggle='modal' data-bs-target='#hapus" . $row['id_logbook'] . "'>";
                                        echo "<i class='fas fa-trash'></i> Hapus";
                                        echo "</button>";
                                        echo "</div>";
                                        echo "</td>";
                                        echo "</tr>";

                                        ?>

                                        <!-- Modal hapus data -->
                                        <div class="modal fade" id='hapus<?= $row['id_logbook'] ?>' tabindex="-1"
                                            role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
                                            <div class="modal-dialog">
                                                <div class="modal-content">
                                                    <div class="modal-header">
                                                        <h5 class="modal-title" id="exampleModalLabel">Hapus Data Logbook
                                                        </h5>
                                                        <button type="button" class="btn-close" data-bs-dismiss="modal"
                                                            aria-label="Close"></button>
                                                    </div>
                                                    <div class="modal-body">
                                                        Apakah anda yakin ingin menghapus Data Logbook Praktik Kerja
                                                        Lapangan?
                                                    </div>
                                                    <div class="modal-footer">
                                                        <button type="button" class="btn btn-secondary"
                                                            data-bs-dismiss="modal">Tidak</button>
                                                        <a href="datalogbook.php?id_logbook=<?= $row['id_logbook'] ?>"
                                                            class="btn btn-danger">Delete</a>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>

                                        <!-- Modal edit data -->
                                        <div class='modal fade' id='edit<?= $row['id_logbook'] ?>' tabindex='-1'
                                            aria-labelledby='exampleModalLabel' aria-hidden='true'>
                                            <div class="modal-dialog">
                                                <div class="modal-content">
                                                    <div class="modal-header">
                                                        <h5 class="modal-title" id="exampleModalLabel">Edit Data dokumen
                                                        </h5>
                                                        <button type="button" class="btn-close" data-bs-dismiss="modal"
                                                            aria-label="Close"></button>
                                                    </div>
                                                    <div class="modal-body">
                                                        <form method="post" action="#" enctype="multipart/form-data">
                                                            <div class="form-group">
                                                                <div class="form-group">
                                                                    <label for="id_logbook">ID</label>
                                                                    <input type="text" class="form-control" id="id_logbook"
                                                                        value="<?= $row['id_logbook']; ?>" name="id_logbook"
                                                                        readonly>
                                                                </div>
                                                                <div class="form-group">
                                                                    <label for="tanggal">Tanggal Logbook</label>
                                                                    <input type="date" class="form-control" id="tanggal"
                                                                        value="<?= $row['tanggal']; ?>" name="tanggal"
                                                                        required>
                                                                </div>
                                                                <div class="form-group">
                                                                    <label for="aktivitas">Aktivitas</label>
                                                                    <input type="text" class="form-control" id="aktivitas"
                                                                        value="<?= $row['aktivitas']; ?>" name="aktivitas"
                                                                        required>
                                                                </div>
                                                                <div class="form-group">
                                                                    <label for="status_logbook">Status</label>
                                                                    <input type="text" class="form-control"
                                                                        id="status_logbook"
                                                                        value="<?= $row['status_logbook']; ?>"
                                                                        name="status_logbook" required>
                                                                </div>
                                                            </div>
                                                            <div class="modal-footer">
                                                                <button type="button" class="btn btn-secondary"
                                                                    data-bs-dismiss="modal">Close</button>
                                                                <button type="submit" class="btn btn-primary"
                                                                    name="EditLogbook" value="Submit">Submit</button>
                                                            </div>
                                                        </form>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <?php
                                    }
                                    ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </main>

            <!-- Modal tambah data-->
            <div class="modal fade" id="tambah" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
                <div class="modal-dialog modal-lg">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" id="exampleModalLabel">Tambah Logbook PKL</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                        <div class="modal-body">
                            <form action="#" method="post" enctype="multipart/form-data" id="formTambahData"
                                autocomplete="off">
                                <div class="form-group">
                                    <label for="id_siswa">Nama siswa - ID PKL</label>
                                    <select class="form-control" id="id_siswa" name="id_siswa" required>
                                        <?php
                                        $siswaQuery = "SELECT siswa.id_siswa, siswa.Nama_siswa, pkl.id_pkl FROM siswa INNER JOIN pkl ON siswa.id_siswa = pkl.id_siswa;";
                                        $siswaResult = mysqli_query($koneksi, $siswaQuery);

                                        while ($siswa = mysqli_fetch_assoc($siswaResult)) {
                                            $siswaOptionText = "{$siswa['Nama_siswa']} - ID PKL: {$siswa['id_pkl']}";
                                            echo "<option value='{$siswa['id_siswa']}' data-id_pkl='{$siswa['id_pkl']}'>$siswaOptionText</option>";
                                        }
                                        ?>
                                    </select>
                                </div>

                                <div class="mb-3">
                                    <label for="tanggal" class="col-form-label">Tanggal Logbook:</label>
                                    <input type="date" class="form-control" id="tanggal" name="tanggal" required>
                                </div>
                                <div class="mb-3">
                                    <label for="aktivitas" class="col-form-label">Aktivitas:</label>
                                    <input type="text" class="form-control" id="aktivitas" name="aktivitas" required>
                                </div>
                                <div class="mb-3">
                                    <label for="status_logbook" class="col-form-label">Status Logbook:</label>
                                    <input type="text" class="form-control" id="status_logbook" name="status_logbook"
                                        required>
                                </div>

                                <!-- Input tersembunyi untuk menyimpan id_logbook -->
                                <input type="int" id="id_logbook" name="id_logbook" value="">
                                <!-- Input tersembunyi untuk menyimpan id_siswa -->
                                <input type="int" id="id_siswa" name="id_siswa" value="">
                                <!-- Input tersembunyi untuk menyimpan id_pkl -->
                                <input type="int" id="id_pkl" name="id_pkl" value="">

                                <div class="modal-footer">
                                    <button type="button" class="btn btn-secondary"
                                        data-bs-dismiss="modal">Close</button>
                                    <button type="submit" class="btn btn-primary" name="TambahLogbook" value="Submit"
                                        id="submit">Submit</button>
                                </div>
                            </form>

                        </div>
                    </div>
                </div>
            </div>



            <footer class="py-4 bg-light mt-auto">
                <div class="container-fluid px-4">
                    <div class="d-flex align-items-center justify-content-between small">
                        <div class="text-muted">Copyright &copy; Your Website 2023</div>
                        <div>
                            <a href="#">Privacy Policy</a>
                            &middot;
                            <a href="#">Terms &amp; Conditions</a>
                        </div>
                    </div>
                </div>
            </footer>
        </div>
    </div>
    <?php include 'footer.php'; ?>
</body>

</html>