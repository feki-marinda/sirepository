<?php
include 'conn.php';

$query = "SELECT * FROM siswa";
$result = mysqli_query($koneksi, $query);

if (!$result) {
    die("Error in query: " . mysqli_error($koneksi));
}

// if (isset($_POST['TambahSiswa'])) {
//     $Nama_siswa = $_POST['Nama_siswa'];
//     $NIS = $_POST['NIS'];
//     $kelas = $_POST['kelas'];
//     $jenis_kelamin = $_POST['jenis_kelamin'];
//     $alamat = $_POST['alamat'];
//     $tanggal_lahir = $_POST['tanggal_lahir'];
//     $no_hp = $_POST['no_hp'];
//     $rand = rand();

//     $query = "INSERT INTO siswa (Nama_siswa, NIS, kelas, jenis_kelamin, alamat, tanggal_lahir, no_hp) 
//               VALUES ('$Nama_siswa', '$NIS', '$kelas','$jenis_kelamin','$alamat','$tanggal_lahir','$no_hp')";

//     if ($koneksi->query($query) === TRUE) {
//         header('Location: datasiswa.php');
//         exit;
//     } else {
//         echo 'Error: ' . $koneksi->error;
//     }

//     $koneksi->close();
// }

if (isset($_POST['EditSiswa'])) {
    $id_siswa = $_POST['id_siswa'];
    $Nama_siswa = $_POST['Nama_siswa'];
    $NIS = $_POST['NIS'];
    $kelas = $_POST['kelas'];
    $jenis_kelamin = $_POST['jenis_kelamin'];
    $alamat = $_POST['alamat'];
    $tanggal_lahir = $_POST['tanggal_lahir'];
    $no_hp = $_POST['no_hp'];

    $query = "UPDATE siswa SET 
                Nama_siswa='$Nama_siswa',
                NIS='$NIS',
                kelas='$kelas',
                jenis_kelamin='$jenis_kelamin',
                alamat='$alamat',
                tanggal_lahir='$tanggal_lahir',
                no_hp='$no_hp'
                WHERE id_siswa='$id_siswa'";

    $result = mysqli_query($koneksi, $query);
    if (!$result) {
        die("Query gagal dijalankan: " . mysqli_errno($koneksi) . " - " . mysqli_error($koneksi));
    } else {
        header("location:datasiswa.php");
    }
}

if (isset($_GET['id_siswa'])) {
    $id_siswa = $_GET['id_siswa'];

    $stmt = mysqli_prepare($koneksi, "DELETE FROM siswa WHERE id_siswa = ?");

    mysqli_stmt_bind_param($stmt, "i", $id_siswa);
    mysqli_stmt_execute($stmt);

    $affected_rows = mysqli_stmt_affected_rows($stmt);

    if ($affected_rows > 0) {
        $success_message = "Berhasil Menghapus data siswa!";
    } else {
        $error_message = "Tidak dapat menghapus data siswa !";
    }

    mysqli_stmt_close($stmt);
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
                    <h1 class="mt-4">Data siswa PKL</h1>
                    <ol class="breadcrumb mb-4">
                        <li class="breadcrumb-item"><a href="index.php">Dashboard</a></li>
                        <li class="breadcrumb-item active">Tables</li>
                    </ol>

                    <div class="card mb-4">
                        <div class="button-container">
                            <div class="spacer"></div>
                            <div class="buttons-right">
                                <!-- <button type="button" class="btn btn-primary" data-bs-toggle="modal"
                                    data-bs-target="#tambah" data-bs-whatever="@mdo"> <i class="fas fa-plus"></i>
                                    Tambah Data siswa PKL</button> -->
                                <button id="printButton">
                                    <i class="fas fa-print"></i> Cetak
                                </button>
                            </div>
                        </div>
                    </div>

                    <div class="card mb-4">
                        <div class="card-header">
                            <i class="fas fa-table me-1"></i>
                            Data Siswa
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
                                        <th>Nama Lengkap</th>
                                        <th>NIS</th>
                                        <th>Kelas</th>
                                        <th>Jenis Kelamin</th>
                                        <th>Alamat</th>
                                        <th>Tanggal Lahir</th>
                                        <th>No Hp</th>
                                        <th>Keterangan</th>
                                    </tr>
                                </thead>

                                <tbody>
                                    <?php
                                    $no = 1;
                                    while ($row = mysqli_fetch_assoc($result)) {
                                        echo "<tr>";
                                        echo "<td>" . $no++ . "</td>";
                                        echo "<td>" . $row['Nama_siswa'] . "</td>";
                                        echo "<td>" . $row['NIS'] . "</td>";
                                        echo "<td>" . $row['kelas'] . "</td>";
                                        echo "<td>" . $row['jenis_kelamin'] . "</td>";
                                        echo "<td>" . $row['alamat'] . "</td>";
                                        echo "<td>" . $row['tanggal_lahir'] . "</td>";
                                        echo "<td>" . $row['no_hp'] . "</td>";
                                        echo "<td>";
                                        echo "<div class='btn-group'>";
                                        echo "<button type='button' class='btn btn-primary' data-bs-toggle='modal' data-bs-target='#edit" . $row['id_siswa'] . "' data-bs-whatever='@mdo'><i class='nav-icon fas fa-edit'></i> Edit</button>";
                                        echo "<button type='button' class='btn btn-danger' data-bs-toggle='modal' data-bs-target='#hapus" . $row['id_siswa'] . "'><i class='nav-icon fas fa-trash-alt'></i> Hapus</button>";
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
                                                        Apakah anda yakin ingin menghapus data siswa?
                                                    </div>
                                                    <div class="modal-footer">
                                                        <button type="button" class="btn btn-secondary"
                                                            data-bs-dismiss="modal">Tidak</button>
                                                        <a href="datasiswa.php?id_siswa=<?= $row['id_siswa'] ?>"
                                                            class="btn btn-danger">Delete</a>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>

                                        <div class='modal fade' id='edit<?= $row['id_siswa'] ?>' tabindex='-1'
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
                                                                    <label for="id_siswa">ID</label>
                                                                    <input type="text" class="form-control" id="id_siswa"
                                                                        value="<?= $row['id_siswa']; ?>" name="id_siswa"
                                                                        readonly>
                                                                </div>
                                                                <div class="form-group">
                                                                    <label for="Nama_siswa">Nama Lengkap</label>
                                                                    <input type="text" class="form-control" id="Nama_siswa"
                                                                        value="<?= $row['Nama_siswa']; ?>" name="Nama_siswa"
                                                                        required>
                                                                </div>
                                                                <div class="form-group">
                                                                    <label for="NIS">NIS</label>
                                                                    <input type="text" class="form-control" id="NIS"
                                                                        value="<?= $row['NIS']; ?>" name="NIS" required>
                                                                </div>
                                                                <div class="form-group">
                                                                    <label for="kelas">Kelas</label>
                                                                    <input type="text" class="form-control" id="kelas"
                                                                        value="<?= $row['kelas']; ?>" name="kelas" required>
                                                                </div>
                                                                <div class="form-group">
                                                                    <label for="jenis_kelamin">Jenis Kelamin</label>
                                                                    <input type="text" class="form-control"
                                                                        id="jenis_kelamin"
                                                                        value="<?= $row['jenis_kelamin']; ?>"
                                                                        name="jenis_kelamin" required>
                                                                </div>
                                                                <div class="form-group">
                                                                    <label for="alamat">Alamat</label>
                                                                    <input type="text" class="form-control" id="alamat"
                                                                        value="<?= $row['alamat']; ?>" name="alamat"
                                                                        required>
                                                                </div>
                                                                <div class="form-group">
                                                                    <label for="tanggal_lahir">Tanggal Lahir</label>
                                                                    <input type="date" class="form-control"
                                                                        id="tanggal_lahir"
                                                                        value="<?= $row['tanggal_lahir']; ?>"
                                                                        name="tanggal_lahir" required>
                                                                </div>
                                                                <div class="form-group">
                                                                    <label for="no_hp">Nomor HP</label>
                                                                    <input type="text" class="form-control" id="no_hp"
                                                                        value="<?= $row['no_hp']; ?>" name="no_hp" required>
                                                                </div>
                                                            </div>
                                                            <div class="modal-footer">
                                                                <button type="button" class="btn btn-secondary"
                                                                    data-bs-dismiss="modal">Close</button>
                                                                <button type="submit" class="btn btn-primary"
                                                                    name="EditSiswa" value="Submit">Submit</button>
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
            <!-- <div class="modal fade" id="tambah" tabindex="-1"
                aria-labelledby="exampleModalLabel" aria-hidden="true">
                <div class="modal-dialog">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" id="exampleModalLabel">Tambah Data siswa PKL</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                        <div class="modal-body ">
                            <form action="#" method="post" enctype="multipart/form-data" id="formTambahData">
                                <div class="mb-3">
                                    <label for="Nama_siswa" class="col-form-label">Nama Lengkap:</label>
                                    <input type="text" class="form-control" id="Nama_siswa" name="Nama_siswa" required>
                                </div>
                                <div class="mb-3">
                                    <label for="NIS" class="col-form-label">NIS:</label>
                                    <input type="text" class="form-control" id="NIS" name="NIS" required>
                                </div>
                                <div class="mb-3">
                                    <label for="kelas" class="col-form-label">Kelas:</label>
                                    <input type="text" class="form-control" id="kelas" name="kelas" required>
                                </div>
                                <div class="mb-3">
                                    <label for="jenis_kelamin" class="col-form-label">Jenis Kelamin:</label>
                                    <input type="text" class="form-control" id="jenis_kelamin" name="jenis_kelamin"
                                        required>
                                </div>
                                <div class="mb-3">
                                    <label for="alamat" class="col-form-label">Alamat:</label>
                                    <input type="text" class="form-control" id="alamat" name="alamat" required>
                                </div>
                                <div class="mb-3">
                                    <label for="tanggal_lahir" class="col-form-label">Tanggal Lahir:</label>
                                    <input type="date" class="form-control" id="tanggal_lahir" name="tanggal_lahir"
                                        required>
                                </div>
                                <div class="mb-3">
                                    <label for="no_hp" class="col-form-label">Nomor HP:</label>
                                    <input type="text" class="form-control" id="no_hp" name="no_hp" required>
                                </div>
                                <div class="modal-footer">
                                    <button type="button" class="btn btn-secondary"
                                        data-bs-dismiss="modal">Close</button>
                                    <button type="submit" class="btn btn-primary" name="TambahSiswa" value="Submit"
                                        id="submit">Submit</button>
                                </div>
                            </form>

                        </div>
                    </div>
                </div>
            </div> -->




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
    <?php include 'footer.php';?>

</body>

</html>