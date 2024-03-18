<?php
include 'conn.php';

$query = "SELECT * FROM logbook INNER JOIN siswa ON logbook.id_siswa=siswa.id_siswa";
$result = mysqli_query($koneksi, $query);

if (!$result) {
    die("Error in query: " . mysqli_error($koneksi));
}

if (isset($_POST['TambahLogbook'])) {
    $tanggal = $_POST['tanggal'];
    $aktivitas = $_POST['aktivitas'];
    $status_logbook = $_POST['status_logbook'];

    $query = "INSERT INTO logbook (tanggal, aktivitas, status_logbook) 
          VALUES ('$tanggal', '$aktivitas', '$status_logbook')";

    if ($koneksi->query($query) === TRUE) {
        header('Location: datalogbook.php');
        exit;
    } else {
        echo 'Error: ' . $koneksi->error;
    }

    $koneksi->close();

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
    header("location:datalogbook.php");
}

?>

<style>
    body, table{
        font-family: "Poppins", sans-serif;
    }
    .form,label,input{
        font-family: "Poppins", sans-serif;
    }
</style>

<!DOCTYPE html>
<html lang="en">

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
                                <button id="printButton">
                                    <i class="fas fa-print"></i> Cetak
                                </button>
                            </div>
                        </div>
                    </div>

                    <div class="card mb-4">
                        <div class="card-header">
                            <i class="fas fa-table me-1"></i>
                            Data Logbook Praktik Kerja Lapangan Siswa SMK AL-Muhajirin
                        </div>
                        <div class="card-body">
                            <table id="datatablesSimple" class="table table-striped table-hover">
                                <thead>
                                    <tr>
                                        <th>No.</th>
                                        <th>Nama</th>
                                        <th>Tanggal</th>
                                        <th>Aktivitas</th>
                                        <th>Status</th>
                                        <th>Keterangan</th>
                                    </tr>
                                </thead>

                                <tbody>
                                    <?php
                                    $no = 1;
                                    while ($row = mysqli_fetch_assoc($result)) {
                                        echo "<tr>";
                                        echo "<td>" . $no++ . "</td>";
                                        echo "<td>" . $row['Nama_siswa']. "</td>";
                                        echo "<td>" . $row['tanggal'] . "</td>";
                                        echo "<td>" . $row['aktivitas'] . "</td>";
                                        echo "<td>";
                                        // Tombol Terima
                                        echo "<div class='btn-group me-2'>";
                                        echo "<button type='button' class='btn btn-success' data-bs-toggle='modal' data-bs-target='#hapus" . $row['id_logbook'] . "'><i class='fa-solid fa-check'></i> Terima</button>";
                                        echo "</div>";

                                        // Tombol Tolak
                                        echo "<div class='btn-group me-2'>";
                                        echo "<button type='button' class='btn btn-danger' data-bs-toggle='modal' data-bs-target='#edit" . $row['id_logbook'] . "' data-bs-whatever='@mdo'><i class='nav-icon fas fa-times'></i> Tolak</button>";
                                        echo "</div>";
                                        echo "</td>";
                                        echo "<td>";
                                        echo "<div class='btn-group'>";
                                        echo "<button type='button' class='btn btn-primary' data-bs-toggle='modal' data-bs-target='#edit" . $row['id_logbook'] . "' data-bs-whatever='@mdo'><i class='nav-icon fas fa-edit'></i> Edit</button>";
                                        echo "<button type='button' class='btn btn-danger' data-bs-toggle='modal' data-bs-target='#hapus" . $row['id_logbook'] . "'><i class='nav-icon fas fa-trash-alt'></i> Hapus</button>";
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
                                            <div class="modal-dialog modal-lg">
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
            <div class="modal modal-fullscreen-xxl-down fade" id="tambah" tabindex="-1"
                aria-labelledby="exampleModalLabel" aria-hidden="true">
                <div class="modal-dialog modal-fullscreen-xxl-down">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" id="exampleModalLabel">Tambah Logbook PKL</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                        <div class="modal-body ">
                            <form action="#" method="post" enctype="multipart/form-data" id="formTambahData">
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
    </div><?php include 'footer.php';?>
</body>

</html>