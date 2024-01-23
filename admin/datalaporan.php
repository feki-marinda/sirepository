<?php
include 'conn.php';

$query = "SELECT * FROM laporan_pkl";
$result = mysqli_query($koneksi, $query);

if (!$result) {
    die("Error in query: " . mysqli_error($koneksi));
}

if (isset($_POST['TambahLaporan'])) {
    $tanggal_kumpul = $_POST['tanggal_kumpul'];
    $nama_penulis = $_POST['nama_penulis'];

    // Tangani file yang diunggah
    $file_name = $_FILES['berkas']['name'];
    $file_tmp = $_FILES['berkas']['tmp_name'];
    $file_size = $_FILES['berkas']['size'];
    $file_error = $_FILES['berkas']['error'];

    // Periksa apakah file sudah diunggah
    if ($file_error === 0) {
        // Pindahkan file ke lokasi yang diinginkan
        $file_destination = 'Laporan PKL/' . $file_name;
        move_uploaded_file($file_tmp, $file_destination);

        // Jalankan query untuk menyimpan data ke database
        $query = "INSERT INTO laporan_pkl (tanggal_kumpul, nama_penulis, berkas) 
                  VALUES ('$tanggal_kumpul', '$nama_penulis', '$file_destination')";

        // Eksekusi query
        if ($koneksi->query($query) === TRUE) {
            // Jika berhasil, arahkan pengguna ke halaman sukses atau halaman lain
            header('Location: datalaporan.php');
            exit;
        } else {
            // Jika terjadi kesalahan, arahkan pengguna ke halaman error atau tampilkan pesan error
            echo 'Error: ' . $koneksi->error;
        }
    } else {
        // Handle error file, bisa ditambahkan pesan kesalahan sesuai kebutuhan
        echo 'Error uploading file.';
    }

    $koneksi->close();
}

if (isset($_POST['EditLaporan'])) {
    $id_laporan = $_POST['id_laporan'];
    $tanggal_kumpul = $_POST['tanggal_kumpul'];
    $nama_penulis = $_POST['nama_penulis'];

    // Tangani file yang diunggah
    $file_name = $_FILES['berkas']['name'];
    $file_tmp = $_FILES['berkas']['tmp_name'];
    $file_size = $_FILES['berkas']['size'];
    $file_error = $_FILES['berkas']['error'];

    // Periksa apakah file sudah diunggah
    if ($file_error === 0) {
        // Pindahkan file ke lokasi yang diinginkan
        $file_destination = $nama_penulis . 'Laporan PKL/' . $file_name;
        move_uploaded_file($file_tmp, $file_destination);

        // Hapus file lama jika ada
        $old_file = mysqli_fetch_array(mysqli_query($koneksi, "SELECT berkas FROM laporan_pkl WHERE id_laporan='$id_laporan'"));
        if (is_file($old_file['berkas'])) {
            unlink($old_file['berkas']);
        }

        // Jalankan query untuk menyimpan data ke database
        mysqli_query($koneksi, "UPDATE laporan_pkl SET nama_penulis='$nama_penulis', tanggal_kumpul='$tanggal_kumpul', berkas='$file_destination' WHERE id_laporan='$id_laporan'");

        // Arahkan pengguna ke halaman data setelah berhasil menyimpan
        header("location:datalaporan.php");
    } else {
        // Handle error file, bisa ditambahkan pesan kesalahan sesuai kebutuhan
        echo 'Error uploading file.';
    }
}

if (isset($_GET['id_laporan'])) {
    $id_laporan = $_GET['id_laporan'];

    mysqli_query($koneksi, "DELETE FROM laporan_pkl WHERE id_laporan='$id_laporan'");
    header("location:datalaporan.php");
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
                    <h1 class="mt-4">Data Laporan PKL</h1>
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
                                    Tambah Data Laporan PKL</button>
                                <button id="printButton">
                                    <i class="fas fa-print"></i> Cetak
                                </button>
                            </div>
                        </div>
                    </div>

                    <div class="card mb-4">
                        <div class="card-header">
                            <i class="fas fa-table me-1"></i>
                            DataTable Example
                        </div>
                        <div class="card-body">
                            <table id="datatablesSimple" class="table table-striped table-hover">
                                <thead>
                                    <tr>
                                        <th>No.</th>
                                        <th>Nama Lengkap</th>
                                        <th>Tanggal Pengupulan</th>
                                        <th>file</th>
                                        <th>Keterangan</th>
                                    </tr>
                                </thead>
                                <tfoot>
                                    <th>No.</th>
                                    <th>Nama Lengkap</th>
                                    <th>Tanggal Pengupulan</th>
                                    <th>file</th>
                                    <th>Keterangan</th>
                                </tfoot>
                                <tbody>
                                    <?php
                                    $no = 1;
                                    while ($row = mysqli_fetch_assoc($result)) {
                                        // Tampilkan data pada tabel
                                        echo "<tr>";
                                        echo "<td>" . $no++ . "</td>";
                                        echo "<td>" . $row['nama_penulis'] . "</td>";
                                        echo "<td>" . $row['tanggal_kumpul'] . "</td>";
                                        echo "<td><a href='" . $row['berkas'] . "' target='_blank'>" . $row['nama_penulis'] . "/" . $row['berkas'] . "</a></td>";
                                        echo "<td>";
                                        echo "<div class='btn-group'>";
                                        echo "<button type='button' class='btn btn-primary' data-bs-toggle='modal' data-bs-target='#edit" . $row['id_laporan'] . "' data-bs-whatever='@mdo'><i class='nav-icon fas fa-edit'></i> Edit</button>";
                                        echo "<button type='button' class='btn btn-danger' data-bs-toggle='modal' data-bs-target='#hapus" . $row['id_laporan'] . "'><i class='nav-icon fas fa-trash-alt'></i> Hapus</button>";
                                        echo "</div>";
                                        echo "</td>";
                                        echo "</tr>";

                                        // Modal edit diluar loop
                                        ?>

                                        <!-- Modal hapus data -->
                                        <div class="modal fade" id='hapus<?= $row['id_laporan'] ?>' tabindex="-1"
                                            role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
                                            <div class="modal-dialog">
                                                <div class="modal-content">
                                                    <div class="modal-header">
                                                        <h5 class="modal-title" id="exampleModalLabel">Hapus Data
                                                            dokumen
                                                        </h5>
                                                        <button type="button" class="btn-close" data-bs-dismiss="modal"
                                                            aria-label="Close"></button>
                                                    </div>
                                                    <div class="modal-body">
                                                        Apakah anda yakin ingin menghapus data laporan?
                                                    </div>
                                                    <div class="modal-footer">
                                                        <button type="button" class="btn btn-secondary"
                                                            data-bs-dismiss="modal">Tidak</button>
                                                        <a href="datalaporan.php?id_laporan=<?= $row['id_laporan'] ?>"
                                                            class="btn btn-danger">Delete</a>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>

                                        <div class='modal fade' id='edit<?= $row['id_laporan'] ?>' tabindex='-1'
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
                                                                    <label for="id_laporan">ID</label>
                                                                    <input type="text" class="form-control" id="id_laporan"
                                                                        value="<?= $row['id_laporan']; ?>" name="id_laporan"
                                                                        readonly>
                                                                </div>
                                                                <div class="form-group">
                                                                    <label for="nama_penulis">Nama Lengkap</label>
                                                                    <input type="text" class="form-control"
                                                                        id="nama_penulis"
                                                                        value="<?= $row['nama_penulis']; ?>"
                                                                        name="nama_penulis" required>
                                                                </div>
                                                                <div class="form-group">
                                                                    <label for="tanggal_kumpul">Tanggal
                                                                        Pengumpulan</label>
                                                                    <input type="date" class="form-control"
                                                                        id="tanggal_kumpul"
                                                                        value="<?= $row['tanggal_kumpul']; ?>"
                                                                        name="tanggal_kumpul" required>
                                                                </div>
                                                                <div class="mb-3">
                                                                    <label for="berkas" class="col-form-label">Berkas
                                                                        (Word/PDF):</label>
                                                                    <input type="file" class="form-control" id="berkas"
                                                                        name="berkas" accept=".doc, .docx, .pdf" required>
                                                                    <small class="form-text text-muted">Pilih file Word
                                                                        (doc/docx) atau PDF.</small>
                                                                </div>

                                                                <div class="modal-footer">
                                                                    <button type="button" class="btn btn-secondary"
                                                                        data-bs-dismiss="modal">Close</button>
                                                                    <button type="submit" class="btn btn-primary"
                                                                        name="EditLaporan" value="Submit">Submit</button>
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
            </main>

            <!-- Modal tambah data-->
            <div class="modal modal-fullscreen-xxl-down fade" id="tambah" tabindex="-1"
                aria-labelledby="exampleModalLabel" aria-hidden="true">
                <div class="modal-dialog modal-fullscreen-xxl-down">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" id="exampleModalLabel">Tambah Data Laporan PKL</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                        <div class="modal-body ">
                            <form action="#" method="post" enctype="multipart/form-data" id="formTambahData">
                                <div class="mb-3">
                                    <label for="nama_penulis" class="col-form-label">Nama Lengkap:</label>
                                    <input type="text" class="form-control" id="nama_penulis" name="nama_penulis"
                                        required>
                                </div>
                                <div class="mb-3">
                                    <label for="tanggal_kumpul" class="col-form-label">Tanggal Pengumpulan:</label>
                                    <input type="date" class="form-control" id="tanggal_kumpul" name="tanggal_kumpul"
                                        required>
                                </div>
                                <div class="mb-3">
                                    <label for="berkas" class="col-form-label">Berkas:</label>
                                    <input type="file" class="form-control" id="berkas" name="berkas" required>
                                </div>
                                <div class="modal-footer">
                                    <button type="button" class="btn btn-secondary"
                                        data-bs-dismiss="modal">Close</button>
                                    <button type="submit" class="btn btn-primary" name="TambahLaporan" value="Submit"
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