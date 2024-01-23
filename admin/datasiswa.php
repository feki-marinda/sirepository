<?php
include 'conn.php';

$query = "SELECT * FROM siswa";
$result = mysqli_query($koneksi, $query);

if (!$result) {
    die("Error in query: " . mysqli_error($koneksi));
}

if (isset($_POST['TambahSiswa'])) {
    $Nama_siswa = $_POST['Nama_siswa'];
    $NIS = $_POST['NIS'];
    $kelas = $_POST['kelas'];
    $foto = $_POST['foto'];
    $jenis_kelamin = $_POST['jenis_kelamin'];
    $alamat = $_POST['alamat'];
    $tanggal_lahir = $_POST['tanggal_lahir'];
    $no_hp = $_POST['no_hp'];
    $rand = rand();
    $ekstensi = array('png', 'jpg', 'jpeg', 'gif', 'webp');
    $filename = $_FILES['foto']['name'];
    $ukuran = $_FILES['foto']['size'];
    $ext = pathinfo($filename, PATHINFO_EXTENSION);
    if (!in_array($ext, $ekstensi)) {
        echo "error";
    } else {
        if ($ukuran < 208815000) {
            $xx = $rand . '_' . $filename;
            move_uploaded_file($_FILES['foto']['tmp_name'], 'gambar/' . $rand . '_' . $filename);

            $query = "INSERT INTO siswa (Nama_siswa, NIS, kelas, foto, jenis_kelamin, alamat, tanggal_lahir, no_hp) 
          VALUES ('$Nama_siswa', '$NIS', '$kelas','$xx','$jenis_kelamin','$alamat','$tanggal_lahir','$no_hp')";

            // Eksekusi query
            if ($koneksi->query($query) === TRUE) {
                // Jika berhasil, arahkan pengguna ke halaman sukses atau halaman lain
                header('Location: datasiswa.php');
                exit;
            } else {
                // Jika terjadi kesalahan, arahkan pengguna ke halaman error atau tampilkan pesan error
                echo 'Error: ' . $koneksi->error;
            }

            // Tutup koneksi database
            $koneksi->close();

        }
    }
}
if (isset($_POST['EditSiswa'])) {
    $id_siswa = $_POST['id_siswa'];
    $Nama_siswa = $_POST['Nama_siswa'];
    $NIS = $_POST['NIS'];
    $kelas = $_POST['kelas'];
    $foto_baru = $_FILES['gambarnew']['name'];
    $jenis_kelamin = $_POST['jenis_kelamin'];
    $alamat = $_POST['alamat'];
    $tanggal_lahir = $_POST['tanggal_lahir'];
    $no_hp = $_POST['no_hp'];

    if ($foto_baru != "") {
        $ekstensi_diperbolehkan = array('png', 'jpg', 'jpeg', 'webp', 'gif');
        $x = explode('.', $foto_baru);
        $ekstensi = strtolower(end($x));
        $file_tmp = $_FILES['gambarnew']['tmp_name'];
        $angka_acak = rand(1, 999);
        $nama_gambar_baru = $angka_acak . '-' . $foto_baru;
        if (in_array($ekstensi, $ekstensi_diperbolehkan) === true) {
            $dt = mysqli_fetch_array(mysqli_query($koneksi, "SELECT * FROM siswa WHERE id_siswa='$id_siswa'"));
            $gambarlama = $dt['foto'];
            if (is_file("gambar/" . $gambarlama)) {
                unlink("gambar/" . $gambarlama);
            }
            move_uploaded_file($file_tmp, 'gambar/' . $nama_gambar_baru);


            mysqli_query($koneksi, "UPDATE siswa SET 
                            Nama_siswa='$Nama_siswa',
                            NIS='$NIS',
                            kelas='$kelas',
                            foto='$nama_gambar_baru',
                            jenis_kelamin='$jenis_kelamin',
                            alamat='$alamat',
                            tanggal_lahir='$tanggal_lahir',
                            no_hp='$no_hp'
                            WHERE id_siswa='$id_siswa'");

            
            if (!$result) {
                die("Query gagal dijalankan: " . mysqli_errno($koneksi) .
                    " - " . mysqli_error($koneksi));
            } else {
                echo "<script>alert('Data berhasil diubah.');window.location='datasiswa.php';</script>";
            }
        } else {
            echo "<script>alert('Ekstensi gambar yang boleh hanya jpg, png,atau jpeg.');window.location='datasiswa.php';</script>";
        }
    } else {
        $query = $query = "UPDATE siswa SET 
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
            die("Query gagal dijalankan: " . mysqli_errno($koneksi) .
                " - " . mysqli_error($koneksi));
        } else {
            header("location:datasiswa.php");
        }
    }
}

if (isset($_GET['id_siswa'])) {
    $id_siswa = $_GET['id_siswa'];

    mysqli_query($koneksi, "DELETE FROM siswa WHERE id_siswa='$id_siswa'");
    header("location:datasiswa.php");
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
                                    data-bs-target="#tambah" data-bs-whatever="@mdo"> <i class="fas fa-plus"></i>
                                    Tambah Data siswa PKL</button>
                                <button id="printButton">
                                    <i class="fas fa-print"></i> Cetak
                                </button>
                            </div>
                        </div>
                    </div>

                    <div class="card mb-4">
                        <div class="card-header">
                            <i class="fas fa-table me-1"></i>
                            Data Guru Pamong
                        </div>
                        <div class="card-body">
                            <table id="datatablesSimple" class="table table-striped table-hover">
                                <thead>
                                    <tr>
                                        <th>No.</th>
                                        <th>Nama Lengkap</th>
                                        <th>NIS</th>
                                        <th>Kelas</th>
                                        <th>Foto</th>
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
                                        // Tampilkan data pada tabel
                                        echo "<tr>";
                                        echo "<td>" . $no++ . "</td>";
                                        echo "<td>" . $row['Nama_siswa'] . "</td>";
                                        echo "<td>" . $row['NIS'] . "</td>";
                                        echo "<td>" . $row['kelas'] . "</td>";
                                        echo "<td><img src='gambar/" . $row['foto'] . "' width='120' height='120'></td>";
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

                                        // Modal edit diluar loop
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
                                                                    <label for="foto">Foto</label>

                                                                    <img src="gambar/<?php echo $row['foto']; ?>"
                                                                        height="120" width="120">
                                                                    <input type="file" name="gambarnew"
                                                                        class="form-control-file">
                                                                    <small>Abaikan jika tidak merubah gambar.</small>

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
            <div class="modal modal-fullscreen-xxl-down fade" id="tambah" tabindex="-1"
                aria-labelledby="exampleModalLabel" aria-hidden="true">
                <div class="modal-dialog modal-fullscreen-xxl-down">
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
                                    <label for="foto" class="col-form-label">Foto:</label>
                                    <input type="file" class="form-control" id="foto" name="foto" required>
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