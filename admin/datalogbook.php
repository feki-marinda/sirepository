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
    $id_siswa = $_POST["id_siswa"];
    $tanggal = $_POST["tanggal"];
    $aktivitas = $_POST["aktivitas"];
    $id_pkl = $_POST["id_pkl"];

    if (isset($_FILES['dokumentasi'])) {
        $rand = uniqid();
        $ekstensi = array('png', 'jpg', 'jpeg', 'gif', 'webp');
        $filename = $_FILES['dokumentasi']['name'];
        $ukuran = $_FILES['dokumentasi']['size'];
        $ext = pathinfo($filename, PATHINFO_EXTENSION);

        if (!in_array($ext, $ekstensi)) {
            $_SESSION['error_message'] = "Error: Ekstensi file tidak sesuai!";
        } else {
            if ($ukuran < 208815000) {
                $xx = $rand . '_' . $filename;
                move_uploaded_file($_FILES['dokumentasi']['tmp_name'], '../Logbook/' . $xx);

                // Perbaikan pada query
                $insert_query = "INSERT INTO logbook (id_siswa, id_pkl, tanggal, aktivitas, dokumentasi) 
                VALUES ('$id_siswa', '$id_pkl', '$tanggal', '$aktivitas', '$xx')";
                $insert_result = mysqli_query($koneksi, $insert_query);

                if ($insert_result) {
                    // Pesan sukses jika query berhasil dieksekusi
                    $_SESSION['success_message'] = "Data logbook berhasil ditambahkan!";
                } else {
                    // Pesan kesalahan jika query gagal dieksekusi
                    $_SESSION['error_message'] = "Error: " . mysqli_error($koneksi);
                }
            } else {
                // Pesan kesalahan jika ukuran file terlalu besar
                $_SESSION['error_message'] = "Error: Ukuran file terlalu besar!";
            }
        }
    } else {
        // Pesan kesalahan jika file belum dipilih
        $_SESSION['error_message'] = "Error: File belum dipilih!";
    }

    header("Location: datalogbook.php");
    exit();
}

if (isset($_POST['EditLogbook'])) {
    $id_siswa = $_POST["id_siswa"];
    $id_logbook = $_POST["id_logbook"];
    $tanggal = $_POST["tanggal"];
    $aktivitas = $_POST["aktivitas"];
    $id_pkl = $_POST["id_pkl"];
    $foto_baru = $_FILES['gambarnew']['name'];

    if ($foto_baru != "") {
        $ekstensi_diperbolehkan = array('png', 'jpg', 'jpeg');
        $x = explode('.', $foto_baru);
        $ekstensi = strtolower(end($x));
        $file_tmp = $_FILES['gambarnew']['tmp_name'];
        $angka_acak = rand(1, 999);
        $nama_gambar_baru = $angka_acak . '-' . $foto_baru;

        if (in_array($ekstensi, $ekstensi_diperbolehkan) === true) {
            $dt = mysqli_fetch_array(mysqli_query($koneksi, "SELECT * FROM logbook WHERE id_logbook='$id_logbook'"));
            $gambarlama = $dt['dokumentasi'];

            if (is_file("../Logbook/" . $gambarlama)) {
                unlink("../Logbook/" . $gambarlama);
            }
            move_uploaded_file($_FILES['gambarnew']['tmp_name'], '../Logbook/' . $nama_gambar_baru);
            $query = "UPDATE logbook SET id_siswa = '$id_siswa', id_pkl = '$id_pkl', tanggal='$tanggal', aktivitas='$aktivitas', dokumentasi='$nama_gambar_baru' 
            WHERE id_logbook='$id_logbook'";
        } else {
            $_SESSION['error_message'] = "Ekstensi gambar yang diizinkan hanya jpg, png, atau jpeg.";
            header("Location: datalogbook.php");
            exit();
        }
    } else {
        $query = "UPDATE logbook SET id_siswa = '$id_siswa', id_pkl = '$id_pkl', tanggal='$tanggal', aktivitas='$aktivitas'
        WHERE id_logbook='$id_logbook'";
    }
    $result = mysqli_query($koneksi, $query);

    if ($result) {
        $rows_affected = mysqli_affected_rows($koneksi);
        if ($rows_affected > 0) {
            $_SESSION['success_message'] = "Data berhasil diubah!";
        } else {
            $_SESSION['error_message'] = "Tidak ada perubahan pada data!";
        }
    } else {
        $_SESSION['error_message'] = "Error: " . mysqli_error($koneksi);
    }

    header("Location: datalogbook.php");
    exit();
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
                                <button type="button" class="btn btn-primary" data-bs-toggle="modal"
                                    data-bs-target="#staticBackdrop">
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
                                            <button type="submit" class="btn btn-primary">Cetak</button>
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
                            if (isset($_SESSION['error_message']) && !empty($_SESSION['error_message'])) {
                                echo '<div class="alert alert-danger" role="alert">' . $_SESSION['error_message'] . '</div>';
                                unset($_SESSION['error_message']);
                            }

                            if (isset($_SESSION['success_message']) && !empty($_SESSION['success_message'])) {
                                echo '<div class="alert alert-success" role="alert">' . $_SESSION['success_message'] . '</div>';
                                unset($_SESSION['success_message']);
                            }
                            ?>
                            <table id="datatablesSimple" class="table table-striped table-hover">
                                <thead>
                                    <tr>
                                        <th>No.</th>
                                        <th>Nama</th>
                                        <th>Tanggal Kegiatan</th>
                                        <th>Dokumentasi</th>
                                        <th>Aktivitas</th>
                                        <th>Keterangan</th>
                                    </tr>
                                </thead>

                                <tbody>
                                    <?php
                                    $no = 1;
                                    include 'conn.php';
                                    $query_table = "SELECT * 
                                    FROM logbook 
                                    INNER JOIN siswa ON logbook.id_siswa = siswa.id_siswa 
                                    ORDER BY tanggal ASC;
                                    ";
                                    $result_table = mysqli_query($koneksi, $query_table);
                                    while ($row = mysqli_fetch_assoc($result_table)) {
                                        echo "<tr>";
                                        echo "<td>" . $no++ . "</td>";
                                        echo "<td>" . $row['Nama_siswa'] . "</td>";
                                        echo "<td>" . date('d/m/Y', strtotime($row['tanggal'])) . "</td>";
                                        echo "<td ><img src='../Logbook/" . $row['dokumentasi'] . "' style='width : 200px; height : 10px; max-width: auto; height: auto;' class='img-responsive'></td>";
                                        echo "<td>" . $row['aktivitas'] . "</td>";
                                        echo "<td>";
                                        echo "<div class='d-flex' >";
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
                                                                    <input type="text" class="form-control" id="id_logbook"
                                                                        value="<?= $row['id_logbook']; ?>" name="id_logbook"
                                                                        hidden>
                                                                </div>
                                                                <div class="form-group">
                                                                    <input type="text" class="form-control" id="id_siswa"
                                                                        value="<?= $row['id_siswa']; ?>" name="id_siswa"
                                                                        hidden>
                                                                </div>
                                                                <div class="form-group">
                                                                    <input type="text" class="form-control" id="id_pkl"
                                                                        value="<?= $row['id_pkl']; ?>" name="id_pkl"
                                                                        hidden>
                                                                </div>
                                                                <div class="form-group">
                                                                    <label for="Nama_siswa">Nama_siswa</label>
                                                                    <input type="text" class="form-control" id="Nama_siswa"
                                                                        value="<?= $row['Nama_siswa']; ?>" name="Nama_siswa"
                                                                        required>
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
                                                                <div class="form-group mt-3">
                                                                    <label for="dokumentasi">Dokumentasi</label>
                                                                    <input type="file" name="gambarnew"
                                                                        class="form-control-file">
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
                            <form action="#" method="POST" enctype="multipart/form-data" id="formTambahData"
                                autocomplete="off">
                                <div class="form-group">
                                    <label for="id_siswa">Nama siswa</label>
                                    <select class="form-control" id="id_siswa" name="id_siswa" required>
                                        <?php
                                        $siswaQuery = "SELECT siswa.id_siswa, siswa.Nama_siswa, pkl.id_pkl FROM siswa INNER JOIN pkl ON siswa.id_siswa = pkl.id_siswa;";
                                        $siswaResult = mysqli_query($koneksi, $siswaQuery);

                                        while ($siswa = mysqli_fetch_assoc($siswaResult)) {
                                            $siswaOptionText = "{$siswa['Nama_siswa']} ";
                                            echo "<option value='{$siswa['id_siswa']}' data-id_siswa='{$siswa['id_siswa']}'>$siswaOptionText</option>";
                                        }
                                        ?>
                                    </select>
                                </div>
                                <div class="form-group">
                                    <label for="id_pkl">NIS</label>
                                    <select class="form-control" id="id_pkl" name="id_pkl" required>
                                        <?php
                                        $siswaQuery = "SELECT siswa.id_siswa, siswa.Nama_siswa,siswa.NIS, pkl.id_pkl FROM siswa INNER JOIN pkl ON siswa.id_siswa = pkl.id_siswa;";
                                        $siswaResult = mysqli_query($koneksi, $siswaQuery);

                                        while ($siswa = mysqli_fetch_assoc($siswaResult)) {
                                            $siswaOptionText = "{$siswa['NIS']} - {$siswa['Nama_siswa']}";
                                            echo "<option value='{$siswa['id_pkl']}' data-id_pkl='{$siswa['id_pkl']}'>$siswaOptionText</option>";
                                        }
                                        ?>
                                    </select>
                                </div>
                                <div class="form-group">
                                    <label for="tanggal">Tanggal Logbook:</label>
                                    <input type="date" class="form-control" id="tanggal" name="tanggal" required>
                                </div>

                                <div class="form-group">
                                    <label for="aktivitas">Aktivitas:</label>
                                    <input type="text" class="form-control" id="aktivitas" name="aktivitas" required>
                                </div>

                                <div class="form-group">
                                    <label for="dokumentasi">Dokumentasi Kegiatan:</label>
                                    <input type="file" class="form-control" id="dokumentasi" name="dokumentasi"
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
    </div>
    <?php include 'footer.php'; ?>
</body>

</html>