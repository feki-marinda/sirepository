<?php
session_start();
include 'conn.php';

$error_message = $success_message = '';
$query = "SELECT*FROM laporan_pkl JOIN siswa ON siswa.id_siswa=laporan_pkl.id_siswa";
$result = mysqli_query($koneksi, $query);

if (!$result) {
    die("Error in query: " . mysqli_error($koneksi));
}


if (isset($_POST['EditLaporan']) && $_SERVER["REQUEST_METHOD"] == "POST") {
    $id_laporan = $_POST['id_laporan'];
    $id_siswa = $_POST['Nama_siswa'];
    $tanggal_kumpul = $_POST['tanggal_kumpul'];

    $file_name = $_FILES['berkas']['name'];
    $file_tmp = $_FILES['berkas']['tmp_name'];
    $file_size = $_FILES['berkas']['size'];
    $file_error = $_FILES['berkas']['error'];

    if ($file_error === 0) {
        // Menangani unggahan berkas
        $file_destination = 'admin/Laporan PKL/' . $file_name;
        move_uploaded_file($file_tmp, $file_destination);

        // Mengambil data berkas lama
        $old_file_query = mysqli_query($koneksi, "SELECT berkas FROM laporan_pkl WHERE id_laporan='$id_laporan'");
        $old_file = mysqli_fetch_array($old_file_query);

        // Memeriksa dan menghapus berkas lama
        if ($old_file && is_file($old_file['berkas'])) {
            unlink($old_file['berkas']);
        }

        // Menggunakan prepared statements untuk mencegah SQL injection
        $query = "UPDATE laporan_pkl 
                  SET id_siswa=?, 
                      tanggal_kumpul=?, 
                      berkas=? 
                  WHERE id_laporan=?";
        $stmt = mysqli_prepare($koneksi, $query);
        mysqli_stmt_bind_param($stmt, "isss", $id_siswa, $tanggal_kumpul, $file_destination, $id_laporan);
        $result = mysqli_stmt_execute($stmt);

        // Memeriksa keberhasilan eksekusi query
        if ($result) {
            $rows_affected = mysqli_stmt_affected_rows($stmt);
            if ($rows_affected > 0) {
                $success_message = "Berhasil Memperbarui Data Laporan!";
                header("location:datalaporan.php");
            } else {
                $error_message = "Tidak ada perubahan pada Data Laporan!";
            }
        } else {
            $error_message = "Tidak dapat Memperbarui Data Laporan! Error: " . mysqli_error($koneksi);
        }
    } else {
        $error_message = "Error uploading file. Error code: " . $file_error;
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
    <?php include 'header.php' ?>
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
                                <?php
                                if (!empty($error_message)) {
                                    echo '<div class="alert alert-danger" role="alert">' . $error_message . '</div>';
                                }
                                if (!empty($success_message)) {
                                    echo '<div class="alert alert-success" role="alert">' . $success_message . '</div>';
                                }
                                ?>
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
                                    include 'conn.php';
                                    $row = mysqli_query($koneksi, "SELECT * FROM laporan_pkl");
                                    $no = 1;
                                    while ($row = mysqli_fetch_assoc($result)) {
                                        echo "<tr>";
                                        echo "<td>" . $no++ . "</td>";
                                        echo "<td>" . $row['Nama_siswa'] . "</td>";
                                        echo "<td>" . date('d-m-Y', strtotime($row['tanggal_kumpul'])) . "</td>";
                                        echo "<td><a href='" . 'Laporan PKL' . '/' . $row['berkas'] . "' target='_blank'>" . $row['berkas'] . "</a></td>";
                                        echo "<td>";
                                        echo "<div class='d-flex'>";
                                        echo "<button type='button' class='btn btn-primary me-2' data-bs-toggle='modal' data-bs-target='#edit" . $row['id_laporan'] . "' data-bs-whatever='@mdo'>";
                                        echo "<i class='fas fa-pencil-alt'></i> Edit";
                                        echo "</button>";
                                        echo "<button type='button' class='btn btn-danger' data-bs-toggle='modal' data-bs-target='#hapus" . $row['id_laporan'] . "'>";
                                        echo "<i class='fas fa-trash'></i> Hapus";
                                        echo "</button>";
                                        echo "</div>";
                                        echo "</td>";
                                        echo "</tr>";

                                        ?>

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

                                        <div class="modal fade" id="edit<?= $row['id_laporan'] ?>" tabindex="-1"
                                            aria-labelledby="exampleModalLabel" aria-hidden="true">
                                            <div class="modal-dialog modal-lg">
                                                <div class="modal-content">
                                                    <div class="modal-header">
                                                        <h5 class="modal-title" id="exampleModalLabel">Edit Data dokumen
                                                        </h5>
                                                        <button type="button" class="btn-close" data-bs-dismiss="modal"
                                                            aria-label="Close"></button>
                                                    </div>
                                                    <div class="modal-body">
                                                        <form method="post" action="datalaporan.php"
                                                            enctype="multipart/form-data">
                                                            <div class="form-group">
                                                                <label for="id_laporan">ID</label>
                                                                <input type="text" class="form-control" id="id_laporan"
                                                                    value="<?= $row['id_laporan']; ?>" name="id_laporan"
                                                                    readonly>
                                                            </div>
                                                            <div class="form-group">
                                                                <label for="Nama_siswa">Nama Lengkap :</label>
                                                                <select class="form-control" id="Nama_siswa"
                                                                    name="Nama_siswa" required>
                                                                    <?php
                                                                    $query_nama_siswa = mysqli_query($koneksi, "SELECT * FROM siswa");
                                                                    while ($data_nama_siswa = mysqli_fetch_assoc($query_nama_siswa)) {
                                                                        $selected = ($row['id_siswa'] == $data_nama_siswa['id_siswa']) ? 'selected' : '';
                                                                        echo "<option value='{$data_nama_siswa['id_siswa']}' $selected>{$data_nama_siswa['Nama_siswa']}</option>";
                                                                    }
                                                                    ?>
                                                                </select>
                                                            </div>
                                                            <div class="form-group">
                                                                <label for="tanggal_kumpul">Tanggal Pengumpulan</label>
                                                                <input type="date" class="form-control" id="tanggal_kumpul"
                                                                    value="<?= $row['tanggal_kumpul']; ?>"
                                                                    name="tanggal_kumpul" required>
                                                            </div>
                                                            <div class="form-group">
                                                            <label for="berkas" class="col-form-label">Berkas (Word/PDF):</label>
                                                                <?php
                                                                echo "<p>Dokumen Saat Ini: {$row['berkas']}</p>";
                                                                ?>
                                                                <input type="file" class="form-control" id="berkas"
                                                                    name="berkas" accept=".doc, .docx, .pdf">
                                                                <small class="form-text text-muted">Pilih file Word
                                                                    (doc/docx) atau PDF. Kosongkan jika tidak ingin
                                                                    mengganti.</small>
                                                            </div>

                                                            <div class="modal-footer">
                                                                <button type="button" class="btn btn-secondary"
                                                                    data-bs-dismiss="modal">Tutup</button>
                                                                <button type="submit" class="btn btn-primary"
                                                                    name="EditLaporan">Submit</button>
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