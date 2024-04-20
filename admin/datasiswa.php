<?php
session_start();
include('conn.php');

$status = isset($_SESSION['status']) ? $_SESSION['status'] : '';

if (empty($status)) {
    header("Location: ../index.php");
    exit;
}

if (!$koneksi) {
    die("Koneksi database gagal: " . mysqli_connect_error());
}
$error_siswa = $success_siswa = '';

if (isset($_POST['TambahSiswa'])) {
    $Nama_siswa = $_POST['Nama_siswa'];
    $NIS = $_POST['NIS'];
    $kelas = $_POST['kelas'];
    $jenis_kelamin = $_POST['jenis_kelamin'];
    $alamat = $_POST['alamat'];
    $tanggal_lahir = $_POST['tanggal_lahir'];
    $no_hp = $_POST['no_hp'];
    $id_user = $_POST['id_user'];
    $email = $_POST['email'];

    $query = "INSERT INTO siswa (id_user, Nama_siswa, NIS, kelas, jenis_kelamin, alamat, tanggal_lahir, no_hp, email) 
              VALUES ('$id_user', '$Nama_siswa', '$NIS', '$kelas', '$jenis_kelamin', '$alamat', '$tanggal_lahir', '$no_hp','$email')";

    $result = mysqli_query($koneksi, $query);

    if ($result) {
        $rows_affected = mysqli_affected_rows($koneksi);
        if ($rows_affected > 0) {
            $success_siswa = "Berhasil Menambah Data Siswa!";
        } else {
            $error_siswa = "Siswa Sudah Terdaftar!";
        }
    } else {
        $error_siswa = "Tidak dapat Memperbarui Data Siswa!";
        header("location:datasiswa.php");
    }

}


if (isset($_POST['EditSiswa'])) {
    $id_siswa = $_POST['id_siswa'];
    $Nama_siswa = $_POST['Nama_siswa'];
    $NIS = $_POST['NIS'];
    $kelas = $_POST['kelas'];
    $jenis_kelamin = $_POST['jenis_kelamin'];
    $alamat = $_POST['alamat'];
    $tanggal_lahir = $_POST['tanggal_lahir'];
    $no_hp = $_POST['no_hp'];
    $email = $_POST['email'];

    $query = "UPDATE siswa SET 
                Nama_siswa='$Nama_siswa',
                NIS='$NIS',
                kelas='$kelas',
                jenis_kelamin='$jenis_kelamin',
                alamat='$alamat',
                tanggal_lahir='$tanggal_lahir',
                no_hp='$no_hp',
                email='$email'
                WHERE id_siswa='$id_siswa'";

    $result = mysqli_query($koneksi, $query);

    if ($result) {
        $rows_affected = mysqli_affected_rows($koneksi);
        if ($rows_affected > 0) {
            $success_siswa = "Berhasil Memperbarui Data Siswa!";
        } else {
            $error_siswa = "Tidak ada perubahan pada Data Siswa!";
        }
    } else {
        $error_siswa = "Tidak dapat Memperbarui Data Siswa!";
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
        $success_siswa = "Berhasil Menghapus data siswa!";
    } else {
        $error_siswa = "Tidak Dapat Menghapus Data Siswa yang Terdaftar PKL !";
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
                                <button type="button" class="btn btn-primary" data-bs-toggle="modal"
                                    data-bs-target="#tambahsiswa" data-bs-whatever="@mdo"> <i class="fas fa-plus"></i>
                                    Tambah Data siswa PKL</button>
                                <button id="printButton">
                                    <a href="cetak/datasiswa.php" style="text-decoration: none; color: inherit;"
                                        target="_blank">
                                        <i class="fas fa-print"></i> Cetak
                                    </a>
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
                            if (!empty($error_siswa)) {
                                echo '<div class="alert alert-danger" role="alert">' . $error_siswa . '</div>';
                            }
                            if (!empty($success_siswa)) {
                                echo '<div class="alert alert-success" role="alert">' . $success_siswa . '</div>';
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
                                        <th>Email</th>
                                        <th>Keterangan</th>
                                    </tr>
                                </thead>

                                <tbody>
                                    <?php
                                    include 'conn.php';
                                    $no = 1;
                                    $query = "SELECT * FROM siswa";
                                    $result = mysqli_query($koneksi, $query);

                                    if (!$result) {
                                        die("Error in query: " . mysqli_error($koneksi));
                                    }
                                    while ($row = mysqli_fetch_assoc($result)) {
                                        echo "<tr>";
                                        echo "<td>" . $no++ . "</td>";
                                        echo "<td>" . $row['Nama_siswa'] . "</td>";
                                        echo "<td>" . $row['NIS'] . "</td>";
                                        echo "<td>" . $row['kelas'] . "</td>";
                                        echo "<td>" . $row['jenis_kelamin'] . "</td>";
                                        echo "<td>" . $row['alamat'] . "</td>";
                                        echo "<td>" . date('d-m-Y', strtotime($row['tanggal_lahir'])) . "</td>";
                                        echo "<td>" . $row['no_hp'] . "</td>";
                                        echo "<td>" . $row['email'] . "</td>";
                                        echo "<td>";
                                        echo "<div class='d-flex'>";
                                        echo "<button type='button' class='btn btn-primary me-2' data-bs-toggle='modal' data-bs-target='#edit" . $row['id_siswa'] . "' data-bs-whatever='@mdo'>";
                                        echo "<i class='fas fa-pencil-alt'></i> Edit";
                                        echo "</button>";
                                        echo "<button type='button' class='btn btn-danger' data-bs-toggle='modal' data-bs-target='#hapus" . $row['id_siswa'] . "'>";
                                        echo "<i class='fas fa-trash'></i> Hapus";
                                        echo "</button>";
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
                                            <div class="modal-dialog modal-lg">
                                                <div class="modal-content">
                                                    <div class="modal-header">
                                                        <h5 class="modal-title" id="exampleModalLabel">Edit Data Siswa
                                                        </h5>
                                                        <button type="button" class="btn-close" data-bs-dismiss="modal"
                                                            aria-label="Close"></button>
                                                    </div>
                                                    <div class="modal-body">
                                                        <form method="post" action="#" enctype="multipart/form-data">
                                                            <div class="form-group">
                                                                <div class="form-group">
                                                                    <input type="text" class="form-control" id="id_siswa"
                                                                        value="<?= $row['id_siswa']; ?>" name="id_siswa"
                                                                        hidden>
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
                                                                    <select class="form-control" id="kelas" name="kelas"
                                                                        required>
                                                                        <option value="" disabled selected>Pilih Kelas
                                                                        </option>
                                                                        <option value="XI A" <?php echo ($row['kelas'] == 'XI A') ? 'selected' : ''; ?>>Kelas XI A</option>
                                                                        <option value="XI B" <?php echo ($row['kelas'] == 'XI B') ? 'selected' : ''; ?>>Kelas XI B</option>
                                                                    </select>
                                                                </div>

                                                                <div class="form-group">
                                                                    <label for="jenis_kelamin">Jenis Kelamin</label>
                                                                    <select class="form-control" id="jenis_kelamin"
                                                                        name="jenis_kelamin" required>
                                                                        <option value="" disabled selected>Pilih Jenis
                                                                            Kelamin</option>
                                                                        <option value="Laki-laki" <?php echo ($row['jenis_kelamin'] == 'Laki-laki') ? 'selected' : ''; ?>>Laki-laki</option>
                                                                        <option value="Perempuan" <?php echo ($row['jenis_kelamin'] == 'Perempuan') ? 'selected' : ''; ?>>Perempuan</option>
                                                                    </select>
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
                                                                <div class="form-group">
                                                                    <label for="email">Email</label>
                                                                    <input type="email" class="form-control" id="email"
                                                                        value="<?= $row['email']; ?>" name="email" required>
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

            <!-- Form tambah siswa -->
            <div class="modal fade" id="tambahsiswa" tabindex="-1" aria-labelledby="exampleModalLabel"
                aria-hidden="true">
                <div class="modal-dialog modal-lg">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" id="exampleModalLabel">Tambah Data siswa PKL</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                        <div class="modal-body ">
                            <form action="#" method="post" enctype="multipart/form-data" id="formTambahData">
                                <div class="mb-2">
                                    <label for="id_user" class="col-form-label">Pilih User:</label>
                                    <select class="form-select" id="id_user" name="id_user" required>
                                        <?php
                                        $queryUser = "SELECT id_user, username FROM user WHERE status = 'siswa'";
                                        $resultUser = mysqli_query($koneksi, $queryUser);

                                        while ($rowUser = mysqli_fetch_assoc($resultUser)) {
                                            echo "<option value='" . $rowUser['id_user'] . "'>" . $rowUser['username'] . "</option>";
                                        }
                                        ?>
                                    </select>
                                </div>

                                <div class="mb-2">
                                    <label for="Nama_siswa" class="col-form-label">Nama Lengkap:</label>
                                    <input type="text" class="form-control" id="Nama_siswa" name="Nama_siswa" required>
                                </div>
                                <div class="mb-2">
                                    <label for="NIS" class="col-form-label">NIS:</label>
                                    <input type="number" class="form-control" id="NIS" name="NIS" required>
                                </div>
                                <div class="mb-2">
                                    <label for="kelas" class="col-form-label">Kelas:</label>
                                    <select class="form-control" id="kelas" name="kelas" required>
                                        <option value="" disabled selected>Pilih Kelas</option>
                                        <option value="XI A">Kelas XI A</option>
                                        <option value="XI B">Kelas XI B</option>
                                    </select>
                                </div>

                                <div class="mb-2">
                                    <label for="jenis_kelamin" class="col-form-label">Jenis Kelamin:</label>
                                    <select class="form-control" id="jenis_kelamin" name="jenis_kelamin" required>
                                        <option value="" disabled selected>Pilih Jenis Kelamin</option>
                                        <option value="Laki-laki">Laki-laki</option>
                                        <option value="Perempuan">Perempuan</option>
                                    </select>
                                </div>

                                <div class="mb-2">
                                    <label for="alamat" class="col-form-label">Alamat:</label>
                                    <input type="text" class="form-control" id="alamat" name="alamat" required>
                                </div>
                                <div class="mb-2">
                                    <label for="tanggal_lahir" class="col-form-label">Tanggal Lahir:</label>
                                    <input type="date" class="form-control" id="tanggal_lahir" name="tanggal_lahir"
                                        required>
                                </div>
                                <div class="mb-2">
                                    <label for="no_hp" class="col-form-label">Nomor HP:</label>
                                    <input type="text" class="form-control" id="no_hp" name="no_hp" required>
                                </div>
                                <div class="mb-2">
                                    <label for="email" class="col-form-label">Email:</label>
                                    <input type="email" class="form-control" id="email" name="email" required>
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