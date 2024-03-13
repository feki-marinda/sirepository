<?php
session_start();
include 'conn.php';


$query = "SELECT * FROM mitra";
$result = mysqli_query($koneksi, $query);

if (!$result) {
    die("Error in query: " . mysqli_error($koneksi));
}

if (isset($_POST['TambahMitra'])) {
    $nama_perusahaan = $_POST['nama'];
    $alamat = $_POST['alamat'];
    $kontak = $_POST['kontak'];

    // File upload handling
    $foto_nama = $_FILES['foto']['name'];
    $foto_tmp = $_FILES['foto']['tmp_name'];
    $foto_path = "../gambar/" . $foto_nama;

    move_uploaded_file($foto_tmp, $foto_path);

    $query_insert = "INSERT INTO mitra (nama, alamat, kontak, foto) 
              VALUES ('$nama_perusahaan', '$alamat', '$kontak', '$foto_path')";

    if ($koneksi->query($query_insert) === TRUE) {
        $_SESSION['success_message'] = "Data Mitra berhasil ditambahkan!";
        header("Location: datamitra.php");
        exit();
    } else {
        $_SESSION['error_message'] = "Error: " . $koneksi->error;
        header("Location: datamitra.php");
        exit();
    }
}

if (isset($_POST['EditMitra'])) {
    $id_mitra = $_POST['id_mitra'];
    $nama = $_POST['nama'];
    $alamat = $_POST['alamat'];
    $kontak = $_POST['kontak'];

    // Inisialisasi pesan error
    $error_message = "";

    // Periksa apakah file foto baru diunggah
    if (isset($_FILES['foto']) && $_FILES['foto']['error'] == 0) {
        $foto_nama = $_FILES['foto']['name'];
        $foto_tmp = $_FILES['foto']['tmp_name'];
        $foto_path = "../gambar/" . $foto_nama;

        // Pindahkan file ke direktori yang diinginkan
        if (move_uploaded_file($foto_tmp, $foto_path)) {
            // Update query dengan foto
            $query = "UPDATE mitra SET 
                         nama='$nama',
                         alamat='$alamat',
                         kontak='$kontak',
                         foto='$foto_path'                                                     
                         WHERE id_mitra='$id_mitra'";
        } else {
            $error_message = "Gagal mengunggah foto.";
        }
    } else {
        // Update query tanpa foto
        $query = "UPDATE mitra SET 
                         nama='$nama',
                         alamat='$alamat',
                         kontak='$kontak'                                                     
                         WHERE id_mitra='$id_mitra'";
    }

    if (empty($error_message)) {
        // Eksekusi query
        $result = mysqli_query($koneksi, $query);

        if ($result) {
            $rows_affected = mysqli_affected_rows($koneksi);
            if ($rows_affected > 0) {
                $_SESSION['success_message'] = "Data Mitra berhasil diubah!";
            } else {
                $_SESSION['error_message'] = "Tidak ada perubahan pada Data Mitra!";
            }
        } else {
            $_SESSION['error_message'] = "Error: " . mysqli_error($koneksi);
        }
    } else {
        $_SESSION['error_message'] = $error_message;
    }

    header("Location: datamitra.php");
    exit();
}

if (isset($_GET['id_mitra'])) {
    $id_mitra = $_GET['id_mitra'];

    mysqli_query($koneksi, "DELETE FROM mitra WHERE id_mitra='$id_mitra'");
    if ($result) {
        $_SESSION['success_message'] = "Data mitra Pamong berhasil dihapus!";
        header("Location: datamitra.php");
        exit();
    }

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
                    <h1 class="mt-4">Data Mitra</h1>
                    <ol class="breadcrumb mb-4">
                        <li class="breadcrumb-item"><a href="index.php">Dashboard</a></li>
                        <li class="breadcrumb-item active">Tables</li>
                    </ol>

                    <div class="card mb-4">
                        <div class="button-container">
                            <div class="spacer"></div>
                            <div class="buttons-right">
                                <button type="button" class="btn btn-primary" data-bs-toggle="modal"
                                    data-bs-target="#tambah" data-bs-whatever="@mdo">
                                    <i class="fas fa-plus"></i> Tambah Data Mitra
                                </button>
                                <button id="printButton">
                                    <a href="cetak/datamitra.php" style="text-decoration: none; color: inherit;" target="_blank">
                                        <i class="fas fa-print"></i> Cetak
                                    </a>
                                </button>
                            </div>
                        </div>
                    </div>
                    <div class="card mb-4">
                        <div class="card-header">
                            <i class="fas fa-table me-1"></i>
                            Data Mitra SMK AL-Muhajirin
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
                                        <th>Nama Perusahaan</th>
                                        <th>Alamat</th>
                                        <th>Kontak</th>
                                        <th>Foto</th>
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
                                        echo "<td>" . $row['alamat'] . "</td>";
                                        echo "<td>" . $row['kontak'] . "</td>";
                                        echo '<td><img src="../gambar/' . $row['foto'] . '" width="125" height="130"></td>';
                                        echo "<td>";
                                        echo "<div class='d-flex'>";
                                        echo "<button type='button' class='btn btn-primary me-2' data-bs-toggle='modal' data-bs-target='#edit" . $row['id_mitra'] . "' data-bs-whatever='@mdo'>";
                                        echo "<i class='fas fa-pencil-alt'></i> Edit";
                                        echo "</button>";
                                        echo "<button type='button' class='btn btn-danger' data-bs-toggle='modal' data-bs-target='#hapus" . $row['id_mitra'] . "'>";
                                        echo "<i class='fas fa-trash'></i> Hapus";
                                        echo "</button>";
                                        echo "</div>";
                                        echo "</td>";
                                        echo "</tr>";

                                        ?>

                                        <!-- Modal hapus data -->
                                        <div class="modal fade" id='hapus<?= $row['id_mitra'] ?>' tabindex="-1"
                                            role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
                                            <div class="modal-dialog">
                                                <div class="modal-content">
                                                    <div class="modal-header">
                                                        <h5 class="modal-title" id="exampleModalLabel">Hapus Data Mitra
                                                        </h5>
                                                        <button type="button" class="btn-close" data-bs-dismiss="modal"
                                                            aria-label="Close"></button>
                                                    </div>
                                                    <div class="modal-body">
                                                        Apakah anda yakin ingin menghapus Data Mitra Praktik Kerja
                                                        Lapangan?
                                                    </div>
                                                    <div class="modal-footer">
                                                        <button type="button" class="btn btn-secondary"
                                                            data-bs-dismiss="modal">Tidak</button>
                                                        <a href="datamitra.php?id_mitra=<?= $row['id_mitra'] ?>"
                                                            class="btn btn-danger">Delete</a>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>

                                        <!-- Modal edit data -->
                                        <div class='modal fade' id='edit<?= $row['id_mitra'] ?>' tabindex='-1'
                                            aria-labelledby='exampleModalLabel' aria-hidden='true'>
                                            <div class="modal-dialog modal-lg">
                                                <div class="modal-content">
                                                    <div class="modal-header">
                                                        <h5 class="modal-title" id="exampleModalLabel">Edit Data Dokumen
                                                        </h5>
                                                        <button type="button" class="btn-close" data-bs-dismiss="modal"
                                                            aria-label="Close"></button>
                                                    </div>
                                                    <div class="modal-body">
                                                        <form method="post" action="#" enctype="multipart/form-data">
                                                            <div class="form-group">
                                                                <label for="id_mitra">ID</label>
                                                                <input type="text" class="form-control" id="id_mitra"
                                                                    value="<?= $row['id_mitra']; ?>" name="id_mitra" hidden>
                                                            </div>

                                                            <div class="form-group">
                                                                <label for="nama">Nama Perusahaan</label>
                                                                <input type="text" class="form-control" id="nama"
                                                                    value="<?= $row['nama']; ?>" name="nama" required>
                                                            </div>

                                                            <div class="form-group">
                                                                <label for="alamat">Alamat</label>
                                                                <input type="text" class="form-control" id="alamat"
                                                                    value="<?= $row['alamat']; ?>" name="alamat" required>
                                                            </div>

                                                            <div class="form-group">
                                                                <label for="kontak">Telp</label>
                                                                <input type="text" class="form-control" id="kontak"
                                                                    value="<?= $row['kontak']; ?>" name="kontak" required>
                                                            </div>

                                                            <div class="mb-3">
                                                                <label for="foto" class="col-form-label">Foto:</label>
                                                                <input type="file" class="form-control" id="foto"
                                                                    name="foto" accept="image/*">
                                                                <small class="text-muted">Abaikan jika tidak mengedit
                                                                    gambar</small>
                                                                <input type="hidden" id="foto_lama" name="foto_lama"
                                                                    value="<?= $row['foto']; ?>">
                                                            </div>



                                                            <div class="modal-footer">
                                                                <button type="button" class="btn btn-secondary"
                                                                    data-bs-dismiss="modal">Close</button>
                                                                <button type="submit" class="btn btn-primary"
                                                                    name="EditMitra" value="Submit">Submit</button>
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
                            <h5 class="modal-title" id="exampleModalLabel">Tambah Mitra PKL</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                        <div class="modal-body ">
                            <form action="#" method="post" enctype="multipart/form-data" id="formTambahData">
                                <div class="mb-3">
                                    <label for="nama" class="col-form-label">Nama Perusahaan :</label>
                                    <input type="text" class="form-control" id="nama" name="nama" required>
                                </div>
                                <div class="mb-3">
                                    <label for="alamat" class="col-form-label">Alamat :</label>
                                    <input type="text" class="form-control" id="alamat" name="alamat" required>
                                </div>
                                <div class="mb-3">
                                    <label for="kontak" class="col-form-label">Telp :</label>
                                    <input type="text" class="form-control" id="kontak" name="kontak" required>
                                </div>
                                <div class="mb-3">
                                    <label for="foto" class="col-form-label">Foto:</label>
                                    <input type="file" class="form-control" id="foto" name="foto" accept="image/*"
                                        required>
                                </div>
                                <div class="modal-footer">
                                    <button type="button" class="btn btn-secondary"
                                        data-bs-dismiss="modal">Close</button>
                                    <button type="submit" class="btn btn-primary" name="TambahMitra" value="Submit"
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