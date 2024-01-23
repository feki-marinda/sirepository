<?php
include 'conn.php';

$query = "SELECT * FROM nilai_pkl";
$result = $koneksi->query($query);

if (!$result) {
    die("Error: " . $koneksi->error);
}
if (isset($_POST['TambahNilai'])) {

    $nama_siswa = $_POST['nama_siswa'];
    $indikator = $_POST['indikator'];
    $nilai = $_POST['nilai'];

    $query = "INSERT INTO nilai_pkl (nama_siswa, indikator, nilai) 
          VALUES ('$nama_siswa', '$indikator', '$nilai')";

    // Eksekusi query
    if ($koneksi->query($query) === TRUE) {
        // Jika berhasil, arahkan pengguna ke halaman sukses atau halaman lain
        header('Location: datanilai.php');
        exit;
    } else {
        // Jika terjadi kesalahan, arahkan pengguna ke halaman error atau tampilkan pesan error
        echo 'Error: ' . $koneksi->error;
    }

    // Tutup koneksi database
    $koneksi->close();

}
if (isset($_POST['EditNilai'])) {
    $id_nilai = $_POST['id_nilai'];
    $nilai = $_POST['nilai'];
    $nama_siswa = $_POST['nama_siswa'];
    $indikator = $_POST['indikator'];

    mysqli_query($koneksi, "UPDATE nilai_pkl SET nilai='$nilai', nama_siswa='$nama_siswa', indikator='$indikator' WHERE id_nilai='$id_nilai'");

}
if (isset($_GET['id_nilai'])) {
    $id_nilai = $_GET['id_nilai'];

    mysqli_query($koneksi, "DELETE FROM nilai_pkl WHERE id_nilai='$id_nilai'");
}

?>
<!DOCTYPE html>
<html lang="en">

<?php include 'head.html' ?>

<body class="sb-nav-fixed">
    <?php include 'header.html' ?>
    <div id="layoutSidenav" style="width: 100%">

        <div id="layoutSidenav_content">
            <main>
                <div class="container-fluid px-4">
                    <h1 class="mt-4">Data Nilai PKL</h1>
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
                                    Tambah Data Nilai PKL</button>
                                <button id="printButton">
                                    <i class="fas fa-print"></i> Cetak
                                </button>
                            </div>
                        </div>
                    </div>

                    <div class="card mb-4">
                        <div class="card-header">
                            <i class="fas fa-table me-1"></i>
                            Data Nilai PKL
                        </div>
                        <div class="card-body">
                            <table id="datatablesSimple" class="table table-striped table-hover">
                                <thead>
                                    <tr>
                                        <th>No.</th>
                                        <th>Nama Siswa</th>
                                        <th>Indikator</th>
                                        <th>Nilai</th>
                                        <th>Keterangan</th>
                                    </tr>
                                </thead>

                                <tbody>
                                    <!-- Modal edit data-->
                                    <?php
                                    $no = 1;
                                    $query = "SELECT * FROM nilai_PKL";
                                    $result = mysqli_query($koneksi, $query);

                                    while ($row = mysqli_fetch_assoc($result)) {
                                        // Tampilkan data pada tabel
                                        echo "<tr>";
                                        echo "<td>" . $no++ . "</td>";
                                        echo "<td>" . $row['nama_siswa'] . "</td>";
                                        echo "<td>" . $row['indikator'] . "</td>";
                                        echo "<td>" . $row['nilai'] . "</td>";
                                        echo "<td>";
                                        echo "<div class='btn-group'>";
                                        echo "<button type='button' class='btn btn-primary' data-bs-toggle='modal' data-bs-target='#edit" . $row['id_nilai'] . "' data-bs-whatever='@mdo'><i class='nav-icon fas fa-edit'></i> Edit</button>";
                                        echo "<button type='button' class='btn btn-danger' data-bs-toggle='modal' data-bs-target='#hapus" . $row['id_nilai'] . "'><i class='nav-icon fas fa-trash-alt'></i> Hapus</button>";
                                        echo "</div>";
                                        echo "</td>";
                                        echo "</tr>";

                                        // Modal edit diluar loop
                                        ?>

                                        <!-- Modal hapus data -->
                                        <div class="modal fade" id='hapus<?= $row['id_nilai'] ?>' tabindex="-1"
                                            role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
                                            <div class="modal-dialog">
                                                <div class="modal-content">
                                                    <div class="modal-header">
                                                        <h5 class="modal-title" id="exampleModalLabel">Hapus Data Nilai
                                                        </h5>
                                                        <button type="button" class="btn-close" data-bs-dismiss="modal"
                                                            aria-label="Close"></button>
                                                    </div>
                                                    <div class="modal-body">
                                                        Apakah anda yakin ingin menghapus data nilai?
                                                    </div>
                                                    <div class="modal-footer">
                                                        <button type="button" class="btn btn-secondary"
                                                            data-bs-dismiss="modal">Tidak</button>
                                                        <a href="datanilai.php?id_nilai=<?= $row['id_nilai'] ?>"
                                                            class="btn btn-danger">Delete</a>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>

                                        <div class='modal fade' id='edit<?= $row['id_nilai'] ?>' tabindex='-1'
                                            aria-labelledby='exampleModalLabel' aria-hidden='true'>
                                            <div class="modal-dialog">
                                                <div class="modal-content">
                                                    <div class="modal-header">
                                                        <h5 class="modal-title" id="exampleModalLabel">Edit Data nilai</h5>
                                                        <button type="button" class="btn-close" data-bs-dismiss="modal"
                                                            aria-label="Close"></button>
                                                    </div>
                                                    <div class="modal-body">
                                                        <form method="post" action="#" enctype="multipart/form-data">
                                                            <div class="form-group">
                                                                <div class="form-group">
                                                                    <label for="id_nilai">ID</label>
                                                                    <input type="text" class="form-control" id="id_nilai"
                                                                        value="<?= $row['id_nilai']; ?>" name="id_nilai"
                                                                        readonly>
                                                                </div>
                                                                <div class="form-group">
                                                                    <label for="nama_siswa">Nama Siswa</label>
                                                                    <input type="text" class="form-control" id="nama_siswa"
                                                                        value="<?= $row['nama_siswa']; ?>" name="nama_siswa"
                                                                        required>
                                                                </div>
                                                                <div class="form-group">
                                                                    <label for="indikator">Indikator</label>
                                                                    <input type="longtext" class="form-control"
                                                                        id="indikator" value="<?= $row['indikator']; ?>"
                                                                        name="indikator" required>
                                                                </div>
                                                                <div class="form-group">
                                                                    <label for="nilai">Nilai</label>
                                                                    <input type="int" class="form-control" id="nilai"
                                                                        value="<?= $row['nilai']; ?>" name="nilai" required>
                                                                </div>

                                                                <div class="modal-footer">
                                                                    <button type="button" class="btn btn-secondary"
                                                                        data-bs-dismiss="modal">Close</button>
                                                                    <button type="submit" class="btn btn-primary"
                                                                        name="EditNilai" value="Submit">Submit</button>
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
                            <h5 class="modal-title" id="exampleModalLabel">Tambah Data nilai</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                        <div class="modal-body ">
                            <form action="#" method="post" id="formTambahData">
                                <div class="mb-3">
                                    <label for="nama_siswa" class="col-form-label">Nama Siswa :</label>
                                    <input type="text" class="form-control" id="nama_siswa" name="nama_siswa" required>
                                </div>
                                <div class="mb-3">
                                    <label for="indikator" class="col-form-label">Indikator :</label>
                                    <textarea class="form-control" id="indikator" name="indikator" required></textarea>
                                </div>
                                <div class="mb-3">
                                    <label for="nilai" class="col-form-label">Nilai :</label>
                                    <input type="int" class="form-control" id="nilai" name="nilai" required>
                                </div>
                                <div class="modal-footer">
                                    <button type="button" class="btn btn-secondary"
                                        data-bs-dismiss="modal">Close</button>
                                    <button type="submit" class="btn btn-primary" name="TambahNilai" value="Submit"
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
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/js/bootstrap.bundle.min.js"
        crossorigin="anonymous"></script>
    <script src="js/scripts.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/2.8.0/Chart.min.js" crossorigin="anonymous"></script>
    <script src="assets/demo/chart-area-demo.js"></script>
    <script src="assets/demo/chart-bar-demo.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/simple-datatables@7.1.2/dist/umd/simple-datatables.min.js"
        crossorigin="anonymous"></script>
    <script src="js/datatables-simple-demo.js"></script>
    <script src="https://code.jquery.com/jquery-3.6.4.min.js"></script>

</body>

</html>