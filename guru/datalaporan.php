<?php
session_start();
require '../vendor/autoload.php';
include 'conn.php';
require '../scriptedit.php';


use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

$error_message = $success_message = '';

// Ambil data laporan PKL dan siswa dari database
$query = "SELECT * FROM laporan_pkl JOIN siswa ON siswa.id_siswa=laporan_pkl.id_siswa";
$result = mysqli_query($koneksi, $query);

if (!$result) {
    die("Error in query: " . mysqli_error($koneksi));
}

// Jika form EditLaporan disubmit dan metode adalah POST
if (isset($_POST['EditLaporan']) && $_SERVER["REQUEST_METHOD"] == "POST") {
    $id_laporan = $_POST['id_laporan'];
    $id_siswa = $_POST['Nama_siswa'];
    $tanggal_kumpul = $_POST['tanggal_kumpul'];
    $status = $_POST['status'];
    $catatan = $_POST['catatan']; // Ambil nilai catatan dari input form

    $query_email_penerima = mysqli_query($koneksi, "SELECT email FROM siswa WHERE id_siswa='$id_siswa'");
    $row_email_penerima = mysqli_fetch_array($query_email_penerima);
    $emailPenerima = $row_email_penerima['email'];

    // Perbarui berkas jika ada yang diunggah
    if ($_FILES['berkas']['error'] === 0 && !empty($_FILES['berkas']['name'])) {
        $file_name = $_FILES['berkas']['name'];
        $file_tmp = $_FILES['berkas']['tmp_name'];
        $file_destination = 'admin/Laporan PKL/' . $file_name;

        move_uploaded_file($file_tmp, $file_destination);

        // Hapus berkas lama jika ada
        $old_file_query = mysqli_query($koneksi, "SELECT berkas FROM laporan_pkl WHERE id_laporan='$id_laporan'");
        $old_file = mysqli_fetch_array($old_file_query);
        if ($old_file && is_file($old_file['berkas'])) {
            unlink($old_file['berkas']);
        }
    } else {
        // Jika tidak ada berkas yang diunggah, gunakan berkas lama
        $old_file_query = mysqli_query($koneksi, "SELECT berkas FROM laporan_pkl WHERE id_laporan='$id_laporan'");
        $old_file = mysqli_fetch_array($old_file_query);
        $file_destination = $old_file['berkas'];
    }

    // Perbarui data laporan PKL
    $query = "UPDATE laporan_pkl 
    SET id_siswa=?, 
        tanggal_kumpul=?, 
        berkas=?,
        catatan=?, 
        status=? 
    WHERE id_laporan=?";
    $stmt = mysqli_prepare($koneksi, $query);
    mysqli_stmt_bind_param($stmt, "issssi", $id_siswa, $tanggal_kumpul, $file_destination, $catatan, $status, $id_laporan);
    $result_update = mysqli_stmt_execute($stmt);
    
    if ($result_update) {
        $rows_affected = mysqli_stmt_affected_rows($stmt);
        if ($rows_affected > 0) {
            $_SESSION['success_message'] = "Data Laporan Berhasil Diubah !";
           
        } else {
            $_SESSION['error_message'] = "Tidak Ada Perubahan Pada Data Laporan !";
        }
    } else {
        $_SESSION['error_message'] = "Error: " . mysqli_error($koneksi);
    }

    if ($status == 'Ditolak') {
        require_once "../library/PHPMailer.php";
        require_once "../library/Exception.php";
        require_once "../library/OAuth.php";
        require_once "../library/POP3.php";
        require_once "../library/SMTP.php";

        $mail = new PHPMailer();

        $mail->SMTPDebug = 0;
        $mail->isSMTP();
        $mail->Host = "ssl://smtp.gmail.com";
        $mail->SMTPAuth = true;
        $mail->Username = "sirepositorysmkalmujahirin@gmail.com";
        $mail->Password = "cqutrhboyqtviwck";
        $mail->SMTPSecure = "ssl";
        $mail->Port = 465;

        $mail->setFrom("sirepositorysmkalmujahirin@gmail.com", "Sirepository-Sistem Informasi Repository");
        $mail->addAddress($emailPenerima);

        $mail->isHTML(true);
        $mail->Subject = "Laporan PKL Ditolak";
        $mail->Body = "Laporan PKL Ditolak. Harap periksa kembali laporan Anda dan lakukan revisi sesuai petunjuk yang diberikan.
        <br> Detail catatan revisi dapat anda lihat pada riwayat laporan !";

        if (!$mail->send()) {
            echo "Terjadi kesalahan dalam mengirim email: " . $mail->ErrorInfo;
        } else {
            echo "Email notifikasi laporan ditolak berhasil dikirim.";
        }
    }
}

if (isset($_GET['id_laporan'])) {
    $id_laporan = $_GET['id_laporan'];
    mysqli_query($koneksi, "DELETE FROM laporan_pkl WHERE id_laporan='$id_laporan'");
    header("location:datalaporan.php");
}
?>


<style>
    body,
    table {
        font-family: "Poppins", sans-serif;
    }

    .form,
    label,
    input {
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
                                    <a href="../admin/cetak/datalaporan.php"
                                        style="text-decoration: none; color: inherit;" target="_blank">
                                        <i class="fas fa-print"></i> Cetak
                                    </a>
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
                            if (isset($_SESSION['error_message']) && !empty($_SESSION['error_message'])) {
                                echo '<div class="alert alert-danger" role="alert">' . $_SESSION['error_message'] . '</div>';
                                unset($_SESSION['error_message']);
                            }

                            if (isset($_SESSION['success_message']) && !empty($_SESSION['success_message'])) {
                                echo '<div class="alert alert-success" role="alert">' . $_SESSION['success_message'] . '</div>';
                                unset($_SESSION['success_message']);
                            }
                            ?>
                                <thead>
                                    <tr>
                                        <th>No.</th>
                                        <th>Nama Lengkap</th>
                                        <th>Tanggal Pengupulan</th>
                                        <th>file</th>
                                        <th>status</th>
                                        <th>catatan</th>
                                        <th>Keterangan</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php
                                    $no = 1;
                                    $query = "SELECT * FROM laporan_pkl JOIN siswa ON siswa.id_siswa=laporan_pkl.id_siswa";
                                    $result = mysqli_query($koneksi, $query);
                                    while ($row = mysqli_fetch_assoc($result)) {
                                        echo "<tr>";
                                        echo "<td>" . $no++ . "</td>";
                                        echo "<td>" . $row['Nama_siswa'] . "</td>";
                                        echo "<td>" . date('d-m-Y', strtotime($row['tanggal_kumpul'])) . "</td>";
                                        echo "<td><a href='" . 'Laporan PKL' . '/' . $row['berkas'] . "' target='_blank'>" . $row['berkas'] . "</a></td>";
                                        echo "<td>" . $row['status'] . "</td>";
                                        echo "<td>" . $row['catatan'] . "</td>";
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
                                                                <label for="status">Status</label>
                                                                <select class="form-control" id="status" name="status"
                                                                    required>
                                                                    <option value="" disabled selected>Pilih status</option>
                                                                    <option value="Diterima" <?php echo ($row['status'] == 'Diterima') ? 'selected' : ''; ?>>
                                                                        Diterima</option>
                                                                    <option value="Ditolak" <?php echo ($row['status'] == 'Ditolak') ? 'selected' : ''; ?>>
                                                                        Ditolak</option>
                                                                </select>
                                                            </div>
                                                            <div class="form-group">
                                                                <label for="catatan">Catatan Revisi</label>
                                                                <input type="text" class="form-control" id="catatan"
                                                                    name="catatan" value="<?= $row['catatan']; ?>">
                                                            </div>

                                                            <div class="form-group">
                                                                <input type="text" class="form-control" id="id_laporan"
                                                                    value="<?= $row['id_laporan']; ?>" name="id_laporan"
                                                                    hidden>
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
                                                                <label for="berkas" class="col-form-label">Berkas
                                                                    (Word/PDF):</label>
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