<?php
include 'conn.php';

$query = "SELECT * FROM pkl";
$result = mysqli_query($koneksi, $query);

if (!$result) {
    die("Error in query: " . mysqli_error($koneksi));
}

if (isset($_POST['Tambahpkl'])) {
    $tgl_mulai = $_POST['tgl_mulai'];
    $tgl_selesai = $_POST['tgl_selesai'];
    $nama_siswa = $_POST['nama_siswa'];
    $angkatan = $_POST['angkatan'];
    $nama_perusahaan = $_POST['nama_perusahaan'];
    $tahun_pelajaran = $_POST['tahun_pelajaran'];

    $query = "INSERT INTO pkl (tgl_mulai, tgl_selesai, nama_siswa, angkatan, nama_perusahaan, tahun_pelajaran) 
          VALUES ('$tgl_mulai', '$tgl_selesai', '$nama_siswa','$angkatan','$nama_perusahaan','$tahun_pelajaran')";

    // Eksekusi query
    if ($koneksi->query($query) === TRUE) {
        // Jika berhasil, arahkan pengguna ke halaman sukses atau halaman lain
        header('Location: dataPKL.php');
        exit;
    } else {
        // Jika terjadi kesalahan, arahkan pengguna ke halaman error atau tampilkan pesan error
        echo 'Error: ' . $koneksi->error;
    }

    // Tutup koneksi database
    $koneksi->close();

}
if (isset($_POST['Editpkl'])) {
    $id_pkl = $_POST['id_pkl'];
    $tgl_mulai = $_POST['tgl_mulai'];
    $tgl_selesai = $_POST['tgl_selesai'];
    $nama_siswa = $_POST['nama_siswa'];
    $angkatan = $_POST['angkatan'];
    $nama_perusahaan = $_POST['nama_perusahaan'];
    $tahun_pelajaran = $_POST['tahun_pelajaran'];

    mysqli_query($koneksi, "UPDATE pkl SET 
                         tgl_mulai='$tgl_mulai',
                         tgl_selesai='$tgl_selesai',
                         nama_siswa='$nama_siswa',
                         angkatan='$angkatan',
                         nama_perusahaan='$nama_perusahaan',
                         tahun_pelajaran='$tahun_pelajaran'                            
                         WHERE id_pkl='$id_pkl'");
    header("location:dataPKL.php");
}

if (isset($_GET['id_pkl'])) {
    $id_pkl = $_GET['id_pkl'];

    mysqli_query($koneksi, "DELETE FROM pkl WHERE id_pkl='$id_pkl'");
    header("location:dataPKL.php");
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
                    <h1 class="mt-4">Data PKL</h1>
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
                                    Tambah Data PKL</button>
                                <button id="printButton">
                                    <i class="fas fa-print"></i> Cetak
                                </button>
                            </div>
                        </div>
                    </div>

                    <div class="card mb-4">
                        <div class="card-header">
                            <i class="fas fa-table me-1"></i>
                            Data Praktik Kerja Lapangan Siswa <br> SMK AL-Muhajirin
                        </div>
                        <div class="card-body">
                            <table id="datatablesSimple" class="table table-striped table-hover">
                                <thead>
                                    <tr>
                                        <th>No.</th>
                                        <th>Nama Lengkap</th>
                                        <th>Tanggal Mulai</th>
                                        <th>Tanggal Selesai</th>
                                        <th>Angkatan</th>
                                        <th>Nama Perusahaan</th>
                                        <th>Tahun Pelajaran</th>
                                        <th>Keterangan</th>
                                    </tr>
                                </thead>

                                <tbody>
                                    <?php
                                    $no = 1;
                                    while ($row = mysqli_fetch_assoc($result)) {
                                        // Tampilkan data pada tabel
                                        echo "<tr>";
                                        echo "<td>" . $no++ . "</td>";
                                        echo "<td>" . $row['nama_siswa'] . "</td>";
                                        echo "<td>" . $row['tgl_mulai'] . "</td>";
                                        echo "<td>" . $row['tgl_selesai'] . "</td>";
                                        echo "<td>" . $row['angkatan'] . "</td>";
                                        echo "<td>" . $row['nama_perusahaan'] . "</td>";
                                        echo "<td>" . $row['tahun_pelajaran'] . "</td>";
                                        echo "<td>";
                                        echo "<div class='btn-group'>";
                                        echo "<button type='button' class='btn btn-primary' data-bs-toggle='modal' data-bs-target='#edit" . $row['id_pkl'] . "' data-bs-whatever='@mdo'><i class='nav-icon fas fa-edit'></i> Edit</button>";
                                        echo "<button type='button' class='btn btn-danger' data-bs-toggle='modal' data-bs-target='#hapus" . $row['id_pkl'] . "'><i class='nav-icon fas fa-trash-alt'></i> Hapus</button>";
                                        echo "</div>";
                                        echo "</td>";
                                        echo "</tr>";

                                        ?>

                                        <!-- Modal hapus data -->
                                        <div class="modal fade" id='hapus<?= $row['id_siswa'] ?>' tabindex="-1"
                                            role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
                                            <div class="modal-dialog">
                                                <div class="modal-content">
                                                    <div class="modal-header">
                                                        <h5 class="modal-title" id="exampleModalLabel">Hapus Data dokumen
                                                        </h5>
                                                        <button type="button" class="btn-close" data-bs-dismiss="modal"
                                                            aria-label="Close"></button>
                                                    </div>
                                                    <div class="modal-body">
                                                        Apakah anda yakin ingin menghapus Data Praktik Kerja Lapangan?
                                                    </div>
                                                    <div class="modal-footer">
                                                        <button type="button" class="btn btn-secondary"
                                                            data-bs-dismiss="modal">Tidak</button>
                                                        <a href="dataPKL.php?id_pkl=<?= $row['id_pkl'] ?>"
                                                            class="btn btn-danger">Delete</a>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>

                                        <!-- Modal edit data -->
                                        <div class='modal fade' id='edit<?= $row['id_pkl'] ?>' tabindex='-1'
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
                                                                    <label for="id_pkl">ID</label>
                                                                    <input type="text" class="form-control" id="id_pkl"
                                                                        value="<?= $row['id_pkl']; ?>" name="id_pkl"
                                                                        readonly>
                                                                </div>
                                                                <div class="form-group">
                                                                    <label for="nama_siswa">Nama Lengkap</label>
                                                                    <input type="text" class="form-control" id="nama_siswa"
                                                                        value="<?= $row['nama_siswa']; ?>" name="nama_siswa"
                                                                        required>
                                                                </div>
                                                                <div class="form-group">
                                                                    <label for="tgl_mulai">tgl_mulai</label>
                                                                    <input type="text" class="form-control" id="tgl_mulai"
                                                                        value="<?= $row['tgl_mulai']; ?>" name="tgl_mulai"
                                                                        required>
                                                                </div>
                                                                <div class="form-group">
                                                                    <label for="tgl_selesai">tgl_selesai</label>
                                                                    <input type="text" class="form-control" id="tgl_selesai"
                                                                        value="<?= $row['tgl_selesai']; ?>"
                                                                        name="tgl_selesai" required>
                                                                </div>
                                                                <div class="form-group">
                                                                    <label for="angkatan">angkatan</label>
                                                                    <input type="text" class="form-control" id="angkatan"
                                                                        value="<?= $row['angkatan']; ?>" name="angkatan"
                                                                        required>
                                                                </div>
                                                                <div class="form-group">
                                                                    <label for="nama_perusahaan">Nama Perusahaan</label>
                                                                    <input type="text" class="form-control"
                                                                        id="nama_perusahaan"
                                                                        value="<?= $row['nama_perusahaan']; ?>"
                                                                        name="nama_perusahaan" required>
                                                                </div>
                                                                <div class="form-group">
                                                                    <label
                                                                        for="tahun_pelajaran">Tahun Pelajaran</label><input
                                                                        type="text" class="form-control"
                                                                        id="tahun_pelajaran"
                                                                        value="<?= $row['tahun_pelajaran']; ?>"
                                                                        name="tahun_pelajaran" required>
                                                                </div>
                                                            </div>
                                                            <div class="modal-footer">
                                                                <button type="button" class="btn btn-secondary"
                                                                    data-bs-dismiss="modal">Close</button>
                                                                <button type="submit" class="btn btn-primary" name="Editpkl"
                                                                    value="Submit">Submit</button>
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
                            <h5 class="modal-title" id="exampleModalLabel">Tambah Data PKL</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                        <div class="modal-body ">
                            <form action="#" method="post" enctype="multipart/form-data" id="formTambahData">
                                <div class="mb-3">
                                    <label for="nama_siswa" class="col-form-label">Nama Lengkap:</label>
                                    <input type="text" class="form-control" id="nama_siswa" name="nama_siswa" required>
                                </div>
                                <div class="mb-3">
                                    <label for="tgl_mulai" class="col-form-label">tgl_mulai:</label>
                                    <input type="date" class="form-control" id="tgl_mulai" name="tgl_mulai" required>
                                </div>
                                <div class="mb-3">
                                    <label for="tgl_selesai" class="col-form-label">tgl_selesai:</label>
                                    <input type="date" class="form-control" id="tgl_selesai" name="tgl_selesai"
                                        required>
                                </div>
                                <div class="mb-3">
                                    <label for="angkatan" class="col-form-label">angkatan:</label>
                                    <input type="text" class="form-control" id="angkatan" name="angkatan" required>
                                </div>
                                <div class="mb-3">
                                    <label for="nama_perusahaan" class="col-form-label">Nama Perusahaan:</label>
                                    <input type="text" class="form-control" id="nama_perusahaan" name="nama_perusahaan"
                                        required>
                                </div>
                                <div class="mb-3">
                                    <label for="tahun_pelajaran" class="col-form-label">tahun_pelajaran:</label>
                                    <input type="text" class="form-control" id="tahun_pelajaran" name="tahun_pelajaran"
                                        required>
                                </div>
                                <div class="modal-footer">
                                    <button type="button" class="btn btn-secondary"
                                        data-bs-dismiss="modal">Close</button>
                                    <button type="submit" class="btn btn-primary" name="Tambahpkl" value="Submit"
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