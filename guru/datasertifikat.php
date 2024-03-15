<?php
include 'conn.php';

$query = "SELECT * FROM sertifikat INNER JOIN siswa";
$result = mysqli_query($koneksi, $query);

if (!$result) {
    die("Error in query: " . mysqli_error($koneksi));
}

if (isset($_POST['TambahSertifikat'])) {
    $nama_siswa = $_POST['nama_siswa'];

    $file_sertifikat = $_FILES['file_sertifikat']['name'];
    $file_temp = $_FILES['file_sertifikat']['tmp_name'];
    $file_path = "path/to/upload/directory/" . $file_sertifikat; 
    if (move_uploaded_file($file_temp, $file_path)) {
        $query = "INSERT INTO sertifikat (nama_siswa, file_sertifikat) 
                  VALUES ('$nama_siswa', '$file_path')";

        if ($koneksi->query($query) === TRUE) {
            header('Location: datasertifikat.php');
            exit;
        } else {
            echo 'Error: ' . $koneksi->error;
        }
    } else {
        echo 'Error uploading file.';
    }

    $koneksi->close();
}


if (isset($_POST['EditSertifikat'])) {
    $id_sertifikat = $_POST['id_sertifikat'];
    $nama_siswa = $_POST['nama_siswa'];
    $file_sertifikat = $_POST['file_sertifikat'];    

    mysqli_query($koneksi, "UPDATE sertifikat SET 
                         nama_siswa='$nama_siswa',
                         file_sertifikat='$file_sertifikat'                                                                             
                         WHERE id_sertifikat='$id_sertifikat'");
    header("location:datasertifikat.php");
}

if (isset($_GET['id_sertifikat'])) {
    $id_sertifikat = $_GET['id_sertifikat'];

    mysqli_query($koneksi, "DELETE FROM sertifikat WHERE id_sertifikat='$id_sertifikat'");
    header("location:datasertifikat.php");
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
                    <h1 class="mt-4">Data Sertifikat</h1>
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
                                    Tambah Data Sertifikat</button>
                                <button id="printButton">
                                    <i class="fas fa-print"></i> Cetak
                                </button>
                            </div>
                        </div>
                    </div>

                    <div class="card mb-4">
                        <div class="card-header">
                            <i class="fas fa-table me-1"></i>
                            Data Sertifikat Siswa
                        </div>
                        <div class="card-body">
                            <table id="datatablesSimple" class="table table-striped table-hover">
                                <thead>
                                    <tr>
                                        <th>No.</th>
                                        <th>Nama Siswa</th>
                                        <th>File</th>                                        
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
                                        echo "<td>" . $row['file_sertifikat'] . "</td>";                                        
                                        echo "<td>";
                                        echo "<div class='btn-group'>";
                                        echo "<button type='button' class='btn btn-primary' data-bs-toggle='modal' data-bs-target='#edit" . $row['id_sertifikat'] . "' data-bs-whatever='@mdo'><i class='nav-icon fas fa-edit'></i> Edit</button>";
                                        echo "<button type='button' class='btn btn-danger' data-bs-toggle='modal' data-bs-target='#hapus" . $row['id_sertifikat'] . "'><i class='nav-icon fas fa-trash-alt'></i> Hapus</button>";
                                        echo "</div>";
                                        echo "</td>";
                                        echo "</tr>";

                                        ?>

                                        <!-- Modal hapus data -->
                                        <div class="modal fade" id='hapus<?= $row['id_sertifikat'] ?>' tabindex="-1"
                                            role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
                                            <div class="modal-dialog">
                                                <div class="modal-content">
                                                    <div class="modal-header">
                                                        <h5 class="modal-title" id="exampleModalLabel">Hapus Data Sertifikat
                                                        </h5>
                                                        <button type="button" class="btn-close" data-bs-dismiss="modal"
                                                            aria-label="Close"></button>
                                                    </div>
                                                    <div class="modal-body">
                                                        Apakah anda yakin ingin menghapus Data Sertifikat Praktik Kerja
                                                        Lapangan?
                                                    </div>
                                                    <div class="modal-footer">
                                                        <button type="button" class="btn btn-secondary"
                                                            data-bs-dismiss="modal">Tidak</button>
                                                        <a href="datasertifikat.php?id_sertifikat=<?= $row['id_sertifikat'] ?>"
                                                            class="btn btn-danger">Delete</a>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>

                                        <!-- Modal edit data -->
                                        <div class='modal fade' id='edit<?= $row['id_sertifikat'] ?>' tabindex='-1'
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
                                                                    <label for="id_sertifikat">ID</label>
                                                                    <input type="text" class="form-control" id="id_sertifikat"
                                                                        value="<?= $row['id_sertifikat']; ?>" name="id_sertifikat"
                                                                        readonly>
                                                                </div>
                                                                <div class="form-group">
                                                                    <label for="nama_siswa">Nama Siswa</label>
                                                                    <input type="text" class="form-control" id="nama_siswa"
                                                                        value="<?= $row['nama_siswa']; ?>" name="nama_siswa"
                                                                        required>
                                                                </div>
                                                                <div class="form-group">
                                                                    <label for="file_sertifikat">File</label>
                                                                    <input type="text" class="form-control" id="file_sertifikat"
                                                                        value="<?= $row['file_sertifikat']; ?>" name="file_sertifikat"
                                                                        required>
                                                                </div>                                                                
                                                            </div>
                                                            <div class="modal-footer">
                                                                <button type="button" class="btn btn-secondary"
                                                                    data-bs-dismiss="modal">Close</button>
                                                                <button type="submit" class="btn btn-primary"
                                                                    name="EditSertifikat" value="Submit">Submit</button>
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
                            <h5 class="modal-title" id="exampleModalLabel">Tambah Mitra PKL</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                        <div class="modal-body ">
                        <form action="#" method="post" enctype="multipart/form-data" id="formTambahData">
    <div class="mb-3">
        <label for="nama_siswa" class="col-form-label">Nama Siswa:</label>
        <input type="text" class="form-control" id="nama_siswa" name="nama_siswa" required>
    </div>
    <div class="mb-3">
        <label for="file_sertifikat" class="col-form-label">File Sertifikat:</label>
        <input type="file" class="form-control" id="file_sertifikat" name="file_sertifikat" required>
    </div>                                
    <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
        <button type="submit" class="btn btn-primary" name="TambahSertifikat" value="Submit" id="submit">Submit</button>
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
    <?php include 'footer.php';?>

</body>

</html>