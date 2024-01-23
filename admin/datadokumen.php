<?php
include 'conn.php';

$query = "SELECT * FROM dokumen";
$result = $koneksi->query($query);

if (!$result) {
    die("Error: " . $koneksi->error);
}
$ekstensi_dokumen = array('pdf', 'doc', 'docx', 'xls', 'xlsx');
if (isset($_POST['TambahDokumen'])) {
    $judul_dokumen = $_POST['judul_dokumen'];
    $rand = rand();
    $filename_dokumen = $_FILES['Dokumen']['name'];
    $ukuran_dokumen = $_FILES['Dokumen']['size'];
    $ext_dokumen = pathinfo($filename_dokumen, PATHINFO_EXTENSION);

    // Pastikan ekstensi dokumen sesuai dengan kebutuhan
    if (!in_array($ext_dokumen, $ekstensi_dokumen)) {
        echo "error: ekstensi dokumen tidak diizinkan";
    } else {
        // Pastikan ukuran dokumen tidak melebihi batas
        if ($ukuran_dokumen < 208815000) {
            $file_dokumen = $rand . '_' . $filename_dokumen;
            $upload_dir = 'Dokumen/';

            if (move_uploaded_file($_FILES['Dokumen']['tmp_name'], $upload_dir . $file_dokumen)) {
                // Lanjutkan dengan menyimpan nama dokumen ke database
                $query = "INSERT INTO dokumen (judul_dokumen, Dokumen) 
                          VALUES ('$judul_dokumen', '$file_dokumen')";

                if ($koneksi->query($query) === TRUE) {
                    // Jika berhasil, arahkan pengguna ke halaman sukses atau halaman lain
                    header('Location: datadokumen.php');
                    exit;
                } else {
                    // Jika terjadi kesalahan, arahkan pengguna ke halaman error atau tampilkan pesan error
                    echo 'Error: ' . $koneksi->error;
                }
            } else {
                echo 'Error saat mengunggah file.';
            }
        } else {
            echo 'Error: Ukuran dokumen melebihi batas maksimal';
        }
    }
}

if (isset($_POST['EditDokumen'])) {
    $id_dokumen = $_POST['id_dokumen'];
    $judul_dokumen = $_POST['judul_dokumen'];

    // Pengunggahan dokumen baru (jika ada)
    if ($_FILES['Dokumen']['name'] != "") {
        $file_name = $_FILES['Dokumen']['name'];
        $file_tmp = $_FILES['Dokumen']['tmp_name'];

        // Pindahkan file ke direktori penyimpanan
        $file_destination = 'Dokumen/' . $file_name;
        move_uploaded_file($file_tmp, $file_destination);

        $old_file = mysqli_fetch_array(mysqli_query($koneksi, "SELECT Dokumen FROM dokumen WHERE id_dokumen='$id_dokumen'"));
        if (is_file($old_file['Dokumen'])) {
            unlink($old_file['Dokumen']);
        }
    } else {
        // Jika tidak ada dokumen baru diunggah, gunakan link lama
        $link = 'Dokumen/' . $_POST['Dokumen'];
    }

    // Query untuk mengedit dokumen
    $queryEdit = "UPDATE dokumen SET judul_dokumen='$judul_dokumen', Dokumen='$file_name' WHERE id_dokumen='$id_dokumen'";
    if ($koneksi->query($queryEdit) === TRUE) {
        // Redirect ke halaman datadokumen.php setelah mengedit
        header("location: datadokumen.php");
        exit;
    } else {
        // Jika terjadi kesalahan, arahkan pengguna ke halaman error atau tampilkan pesan error
        echo 'Error: ' . $koneksi->error;
    }
}



if (isset($_GET['id_dokumen'])) {
    $id_dokumen = $_GET['id_dokumen'];

    // Peroleh informasi dokumen berdasarkan ID
    $query_select = "SELECT * FROM dokumen WHERE id_dokumen='$id_dokumen'";
    $result_select = $koneksi->query($query_select);

    if ($result_select->num_rows > 0) {
        $row_select = $result_select->fetch_assoc();
        $file_to_delete = $row_select['Dokumen'];

        // Hapus dokumen dari direktori
        $file_path = 'Dokumen/' . $file_to_delete;
        if (file_exists($file_path)) {
            unlink($file_path);
        }

        // Hapus data dokumen dari database
        $query_delete = "DELETE FROM dokumen WHERE id_dokumen='$id_dokumen'";
        if ($koneksi->query($query_delete) === TRUE) {
            // Redirect ke halaman datadokumen.php setelah menghapus
            header("location: datadokumen.php");
            exit;
        } else {
            echo 'Error: ' . $koneksi->error;
        }
    } else {
        echo 'Dokumen tidak ditemukan.';
    }
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
                    <h1 class="mt-4">Data Dokumen</h1>
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
                                    Tambah Data Dokumen</button>
                                <button id="printButton">
                                    <i class="fas fa-print"></i> Cetak
                                </button>
                            </div>
                        </div>
                    </div>

                    <div class="card mb-4">
                        <div class="card-header">
                            <i class="fas fa-table me-1"></i>
                            Data Dokumen PKL
                        </div>
                        <div class="card-body">
                            <table id="datatablesSimple" class="table table-striped table-hover">
                                <thead>
                                    <tr>
                                        <th>No.</th>
                                        <th>Judul Dokumen</th>
                                        <th>Dokumen</th>
                                        <th>Keterangan</th>
                                    </tr>
                                </thead>

                                <tbody>
                                    <!-- Modal edit data-->
                                    <?php
                                    $no = 1;
                                    $query = "SELECT * FROM dokumen";
                                    $result = mysqli_query($koneksi, $query);

                                    while ($row = mysqli_fetch_assoc($result)) {
                                        // Tampilkan data pada tabel
                                        echo "<tr>";
                                        echo "<td>" . $no++ . "</td>";
                                        echo "<td>" . $row['judul_dokumen'] . "</td>";
                                        echo "<td><a href='Dokumen/" . $row['Dokumen'] . "' target='_blank'>" . $row['judul_dokumen'] . "</a></td>";
                                        echo "<td>";
                                        echo "<div class='btn-group'>";
                                        echo "<button type='button' class='btn btn-primary' data-bs-toggle='modal' data-bs-target='#edit" . $row['id_dokumen'] . "' data-bs-whatever='@mdo'><i class='nav-icon fas fa-edit'></i> Edit</button>";
                                        echo "<button type='button' class='btn btn-danger' data-bs-toggle='modal' data-bs-target='#hapus" . $row['id_dokumen'] . "'><i class='nav-icon fas fa-trash-alt'></i> Hapus</button>";
                                        echo "</div>";
                                        echo "</td>";
                                        echo "</tr>";

                                        // Modal edit diluar loop
                                        ?>

                                        <!-- Modal hapus data -->
                                        <div class="modal fade" id='hapus<?= $row['id_dokumen'] ?>' tabindex="-1"
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
                                                        Apakah anda yakin ingin menghapus data dokumen?
                                                    </div>
                                                    <div class="modal-footer">
                                                        <button type="button" class="btn btn-secondary"
                                                            data-bs-dismiss="modal">Tidak</button>
                                                        <a href="datadokumen.php?id_dokumen=<?= $row['id_dokumen'] ?>"
                                                            class="btn btn-danger">Delete</a>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>

                                        <div class='modal fade' id='edit<?= $row['id_dokumen'] ?>' tabindex='-1'
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
                                                                    <label for="id_dokumen">ID</label>
                                                                    <input type="text" class="form-control" id="id_dokumen"
                                                                        value="<?= $row['id_dokumen']; ?>" name="id_dokumen"
                                                                        readonly>
                                                                </div>
                                                                <div class="form-group">
                                                                    <label for="judul_dokumen">Judul</label>
                                                                    <input type="text" class="form-control"
                                                                        id="judul_dokumen"
                                                                        value="<?= $row['judul_dokumen']; ?>"
                                                                        name="judul_dokumen" required>
                                                                </div>
                                                                <div class="mb-3">
                                                                    <label for="Dokumen" class="col-form-label">Dokumen :</label>
                                                                    <input type="file" class="form-control" id="Dokumen"
                                                                        name="Dokumen" accept=".doc, .docx, .pdf" required>
                                                                    <small class="form-text text-muted">Pilih file Word
                                                                        (doc/docx) atau PDF.</small>
                                                                </div>
                                                                <div class="modal-footer">
                                                                    <button type="button" class="btn btn-secondary"
                                                                        data-bs-dismiss="modal">Close</button>
                                                                    <button type="submit" class="btn btn-primary"
                                                                        name="EditDokumen" value="Submit">Submit</button>
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
                            <form action="#" method="post" id="formTambahData" enctype="multipart/form-data">
                                <div class="mb-3">
                                    <label for="judul_dokumen" class="col-form-label">Judul Dokumen:</label>
                                    <input type="text" class="form-control" id="judul_dokumen" name="judul_dokumen"
                                        required>
                                </div>
                                <div class="mb-3">
                                    <label for="Dokumen" class="col-form-label">Dokumen:</label>
                                    <input type="file" class="form-control" id="Dokumen" name="Dokumen" required>
                                </div>

                                <div class="modal-footer">
                                    <button type="button" class="btn btn-secondary"
                                        data-bs-dismiss="modal">Close</button>
                                    <button type="submit" class="btn btn-primary" name="TambahDokumen" value="Submit"
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