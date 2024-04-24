<?php
session_start();
include('conn.php');

$username = isset($_SESSION['username']) ? $_SESSION['username'] : '';

if (empty($username)) {
    header("Location: ../index.php");
    exit;
}
$query = "SELECT * FROM guru_pamong";
$result = mysqli_query($koneksi, $query);

if (!$result) {
    die("Error in query: " . mysqli_error($koneksi));
}

if (isset($_POST['TambahGP'])) {
    $nama = $_POST['nama'];
    $NIP = $_POST['NIP'];
    $Email = $_POST['Email'];
    $Alamat = $_POST['Alamat'];
    $Foto = $_POST['Foto'];
    $no_telp = $_POST['no_telp'];

    $rand = rand();
$ekstensi = array('png', 'jpg', 'jpeg', 'gif', 'webp');
$filename = $_FILES['Foto']['name'];
$ukuran = $_FILES['Foto']['size'];
$ext = pathinfo($filename, PATHINFO_EXTENSION);
if (!in_array($ext, $ekstensi)) {
    echo "error";
} else {
    if ($ukuran < 208815000) {
        $xx = $rand . '_' . $filename;
        move_uploaded_file($_FILES['Foto']['tmp_name'], 'gambar/' . $xx);

        $query = "INSERT INTO guru_pamong (nama, NIP, Email, Alamat, Foto, no_telp) 
            VALUES ('$nama', '$NIP', '$Email', '$Alamat', '$xx', '$no_telp')";

            // Eksekusi query
            if ($koneksi->query($query) === TRUE) {
                // Jika berhasil, arahkan pengguna ke halaman sukses atau halaman lain
                header('Location: dataGP.php');
                exit;
            } else {
                // Jika terjadi kesalahan, tampilkan pesan error dan hentikan eksekusi
                die("Error in query: " . $koneksi->error);
            }
           

        }
    }
}

if (isset($_POST['EditGP'])) {
    $id_guru = $_POST['id_guru'];
    $nama = $_POST['nama'];
    $NIP = $_POST['NIP'];
    $Email = $_POST['Email'];
    $Alamat = $_POST['Alamat'];
    $foto_baru = $_FILES['gambarnew']['name'];
    $no_telp = $_POST['no_telp'];

    if ($foto_baru != "") {
        $ekstensi_diperbolehkan = array('png', 'jpg', 'jpeg', 'webp', 'gif');
        $x = explode('.', $foto_baru);
        $ekstensi = strtolower(end($x));
        $file_tmp = $_FILES['gambarnew']['tmp_name'];
        $angka_acak = rand(1, 999);
        $nama_gambar_baru = $angka_acak . '-' . $foto_baru;
        if (in_array($ekstensi, $ekstensi_diperbolehkan) === true) {
            $dt = mysqli_fetch_array(mysqli_query($koneksi, "SELECT * FROM guru_pamong WHERE id_guru='$id_guru'"));
            $gambarlama = $dt['Foto'];
            if (is_file("gambar/" . $gambarlama)) {
                unlink("gambar/" . $gambarlama);
            }

            move_uploaded_file($_FILES['gambarnew']['tmp_name'], 'gambar/' . $nama_gambar_baru);
            mysqli_query($koneksi, "UPDATE guru_pamong SET nama='$nama', NIP='$NIP', Email='$Email', Alamat='$Alamat', Foto='$nama_gambar_baru', no_telp='$no_telp'  WHERE id_guru='$id_guru'");


            if (!$result) {
                die("Query gagal dijalankan: " . mysqli_errno($koneksi) .
                    " - " . mysqli_error($koneksi));
            } else {
                echo "<script>alert('Data berhasil diubah.');window.location='dataGP.php';</script>";
            }
        } else {
            echo "<script>alert('Ekstensi gambar yang boleh hanya jpg, png,atau jpeg.');window.location='dataGP.php';</script>";
        }
    } else {
        $query = $query = "UPDATE guru_pamong SET nama='$nama', NIP='$NIP', Email='$Email', Alamat='$Alamat', no_telp='$no_telp'  WHERE id_guru='$id_guru'";

        $result = mysqli_query($koneksi, $query);
        if (!$result) {
            die("Query gagal dijalankan: " . mysqli_errno($koneksi) .
                " - " . mysqli_error($koneksi));
        } else {
            header("location:dataGP.php");
        }
    }
}

if (isset($_GET['id_guru'])) {
    $id_guru = $_GET['id_guru'];

    mysqli_query($koneksi, "DELETE FROM guru_pamong WHERE id_guru='$id_guru'");
    header('Location: dataGP.php');
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
                    <h1 class="mt-4">Data Guru Pamong</h1>
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
                                    Tambah Data Guru Pamong</button>
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
                                        <th>NIP</th>
                                        <th>Email</th>
                                        <th>Alamat</th>
                                        <th>Foto</th>
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
                                        echo "<td>" . $row['nama'] . "</td>";
                                        echo "<td>" . $row['NIP'] . "</td>";
                                        echo "<td>" . $row['Email'] . "</td>";
                                        echo "<td>" . $row['Alamat'] . "</td>";
                                        echo "<td><img src='gambar/" . $row['Foto'] . "' width='120' height='120'></td>";
                                        echo "<td>" . $row['no_telp'] . "</td>";
                                        echo "<td>";
                                        echo "<div class='btn-group'>";
                                        echo "<button type='button' class='btn btn-primary' data-bs-toggle='modal' data-bs-target='#edit" . $row['id_guru'] . "' data-bs-whatever='@mdo'><i class='nav-icon fas fa-edit'></i> Edit</button>";
                                        echo "<button type='button' class='btn btn-danger' data-bs-toggle='modal' data-bs-target='#hapus" . $row['id_guru'] . "'><i class='nav-icon fas fa-trash-alt'></i> Hapus</button>";
                                        echo "</div>";
                                        echo "</td>";
                                        echo "</tr>";

                                        ?>

                                        <!-- Modal hapus data -->
                                        <div class="modal fade" id='hapus<?= $row['id_guru'] ?>' tabindex="-1" role="dialog"
                                            aria-labelledby="exampleModalLabel" aria-hidden="true">
                                            <div class="modal-dialog">
                                                <div class="modal-content">
                                                    <div class="modal-header">
                                                        <h5 class="modal-title" id="exampleModalLabel">Hapus Data dokumen
                                                        </h5>
                                                        <button type="button" class="btn-close" data-bs-dismiss="modal"
                                                            aria-label="Close"></button>
                                                    </div>
                                                    <div class="modal-body">
                                                        Apakah anda yakin ingin menghapus data guru?
                                                    </div>
                                                    <div class="modal-footer">
                                                        <button type="button" class="btn btn-secondary"
                                                            data-bs-dismiss="modal">Tidak</button>
                                                        <a href="dataGP.php?id_guru=<?= $row['id_guru'] ?>"
                                                            class="btn btn-danger">Delete</a>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>

                                        <div class='modal fade' id='edit<?= $row['id_guru'] ?>' tabindex='-1'
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
                                                                    <label for="id_guru">ID</label>
                                                                    <input type="text" class="form-control" id="id_guru"
                                                                        value="<?= $row['id_guru']; ?>" name="id_guru"
                                                                        readonly>
                                                                </div>
                                                                <div class="form-group">
                                                                    <label for="nama">Nama Lengkap</label>
                                                                    <input type="text" class="form-control" id="nama"
                                                                        value="<?= $row['nama']; ?>" name="nama" required>
                                                                </div>
                                                                <div class="form-group">
                                                                    <label for="NIP">NIP</label>
                                                                    <input type="int" class="form-control" id="NIP"
                                                                        value="<?= $row['NIP']; ?>" name="NIP" required>
                                                                </div>
                                                                <div class="form-group">
                                                                    <label for="Email">Email</label>
                                                                    <input type="email" class="form-control" id="Email"
                                                                        value="<?= $row['Email']; ?>" name="Email" required>
                                                                </div>
                                                                <div class="form-group">
                                                                    <label for="Alamat">Alamat</label>
                                                                    <input type="Alamat" class="form-control" id="Alamat"
                                                                        value="<?= $row['Alamat']; ?>" name="Alamat"
                                                                        required>
                                                                </div>
                                                                <div class="form-group">
                                                                    <label for="Foto">Foto</label>
                                                                    <img src="gambar/<?php echo $row['Foto']; ?>"
                                                                        height="120" width="120">
                                                                    <input type="file" name="gambarnew"
                                                                        class="form-control-file">
                                                                    <small>Abaikan jika tidak merubah gambar.</small>
                                                                </div>
                                                                <div class="form-group">
                                                                    <label for="no_telp">No Hp</label>
                                                                    <input type="text" class="form-control" id="no_telp"
                                                                        value="<?= $row['no_telp']; ?>" name="no_telp"
                                                                        required>
                                                                </div>
                                                                <div class="modal-footer">
                                                                    <button type="button" class="btn btn-secondary"
                                                                        data-bs-dismiss="modal">Close</button>
                                                                    <button type="submit" class="btn btn-primary"
                                                                        name="EditGP" value="Submit">Submit</button>
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
                            <form action="#" method="post" enctype="multipart/form-data" id="formTambahData">
                                <div class="mb-3">
                                    <label for="nama" class="col-form-label">Nama Lengkap:</label>
                                    <input type="text" class="form-control" id="nama" name="nama" required>
                                </div>
                                <div class="mb-3">
                                    <label for="NIP" class="col-form-label">NIP:</label>
                                    <input type="text" class="form-control" id="NIP" name="NIP" required>
                                </div>
                                <div class="mb-3">
                                    <label for="Email" class="col-form-label">Email:</label>
                                    <input type="email" class="form-control" id="Email" name="Email" required>
                                </div>
                                <div class="mb-3">
                                    <label for="Alamat" class="col-form-label">Alamat:</label>
                                    <input type="text" class="form-control" id="Alamat" name="Alamat" required>
                                </div>
                                <div class="mb-3">
                                    <label for="Foto" class="col-form-label">Foto:</label>
                                    <input type="file" class="form-control" id="Foto" name="Foto" required>
                                </div>
                                <div class="mb-3">
                                    <label for="no_telp" class="col-form-label">No telp:</label>
                                    <input type="text" class="form-control" id="no_telp" name="no_telp" required>
                                </div>
                                <div class="modal-footer">
                                    <button type="button" class="btn btn-secondary"
                                        data-bs-dismiss="modal">Close</button>
                                    <button type="submit" class="btn btn-primary" name="TambahGP" value="Submit"
                                        id="submit">Submit</button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>


        </div>
    </div><?php include 'footer.php';?>

</body>

</html>