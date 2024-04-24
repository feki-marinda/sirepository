<?php
session_start();
include('conn.php');

$status = isset($_SESSION['status']) ? $_SESSION['status'] : '';

if (empty($status)) {
    header("Location: ../index.php");
    exit;
}

$admin_success_dokumen = $admin_error_dokumen = '';

$query = "SELECT * FROM dokumen";
$result = $koneksi->query($query);

if (!$result) {
    die("Error: " . $koneksi->error);
}
$ekstensi_dokumen = array('pdf', 'doc', 'docx');


if (isset($_POST['TambahDokumen'])) {
    $judul_dokumen = $_POST['judul_dokumen'];
    $rand = rand();
    $filename_dokumen = $_FILES['Dokumen']['name'];
    $ukuran_dokumen = $_FILES['Dokumen']['size'];
    $ext_dokumen = pathinfo($filename_dokumen, PATHINFO_EXTENSION);

    if (!in_array($ext_dokumen, $ekstensi_dokumen)) {
        echo "Error: Ekstensi dokumen tidak diizinkan";
    } else {
        if ($ukuran_dokumen < 208815000) {
            $file_dokumen = $rand . '_' . $filename_dokumen;
            $upload_dir = 'Dokumen/';

            if (move_uploaded_file($_FILES['Dokumen']['tmp_name'], $upload_dir . $file_dokumen)) {
                $query = "INSERT INTO dokumen (judul_dokumen, Dokumen) 
                          VALUES ('$judul_dokumen', '$file_dokumen')";

                if ($koneksi->query($query) === TRUE) {
                    $_SESSION['admin_success_dokumen'] = "Dokumen Berhasil Ditambahkan!";
                    header("Location: datadokumen.php");
                    exit();
                } else {
                    $_SESSION['admin_error_dokumen'] = "Error: " . $koneksi->error;
                    header("Location: datadokumen.php");
                    exit();
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

    if (!empty($_FILES['Dokumen']['name'])) {
        $rand = rand();
        $filename_dokumen = $_FILES['Dokumen']['name'];
        $ukuran_dokumen = $_FILES['Dokumen']['size'];
        $ext_dokumen = pathinfo($filename_dokumen, PATHINFO_EXTENSION);

        if (
            !in_array($ext_dokumen, $ekstensi_dokumen) || $ukuran_dokumen >= 208815000 ||
            !move_uploaded_file($_FILES['Dokumen']['tmp_name'], 'Dokumen/' . ($file_dokumen = $rand . '_' . $filename_dokumen))
        ) {
            $_SESSION['admin_error_dokumen'] = "Error: Gagal mengunggah file.";
            header("Location: datadokumen.php");
            exit();
        }

        $query = "UPDATE dokumen SET judul_dokumen = '$judul_dokumen', Dokumen = '$file_dokumen' WHERE id_dokumen = $id_dokumen";
        if ($koneksi->query($query) === TRUE) {
            $_SESSION['admin_success_dokumen'] = "Dokumen Berhasil Diubah!";
        } else {
            $_SESSION['admin_error_dokumen'] = "Error: " . $koneksi->error;
        }
    } else {
        $queryCheck = "SELECT judul_dokumen FROM dokumen WHERE id_dokumen = $id_dokumen";
        $resultCheck = $koneksi->query($queryCheck);

        if ($resultCheck->num_rows > 0) {
            $dataDokumen = $resultCheck->fetch_assoc();

            if ($dataDokumen['judul_dokumen'] === $judul_dokumen) {
                $_SESSION['admin_error_dokumen'] = "Tidak Ada Perubahan Data yang Dilakukan.";
                header("Location: datadokumen.php");
                exit();
            }

            $query = "UPDATE dokumen SET judul_dokumen = '$judul_dokumen' WHERE id_dokumen = $id_dokumen";
            if ($koneksi->query($query) === TRUE) {
                $_SESSION['admin_success_dokumen'] = "Judul dokumen berhasil diubah!";
            } else {
                $_SESSION['admin_error_dokumen'] = "Error: " . $koneksi->error;
            }
        } else {
            $_SESSION['admin_error_dokumen'] = "Error: Dokumen tidak ditemukan.";
        }
    }

    header("Location: datadokumen.php");
    exit();
}


if (isset($_GET['id_dokumen'])) {
    $id_dokumen = $_GET['id_dokumen'];

    mysqli_query($koneksi, "DELETE FROM dokumen WHERE id_dokumen='$id_dokumen'");
    if ($result) {
        $_SESSION['admin_success_dokumen'] = "Dokumen berhasil dihapus!";
        header("Location: datadokumen.php");
        exit();
    }

}

?>
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
                                
                            </div>
                        </div>
                    </div>

                    <div class="card mb-4">
                        <div class="card-header">
                            <i class="fas fa-table me-1"></i>
                            Data Dokumen Praktek Kerja Lapangan
                        </div>
                        <div class="card-body">
                            <?php
                            if (isset($_SESSION['admin_error_dokumen']) && !empty($_SESSION['admin_error_dokumen'])) {
                                echo '<div class="alert alert-danger" role="alert">' . $_SESSION['admin_error_dokumen'] . '</div>';
                                unset($_SESSION['admin_error_dokumen']);
                            }

                            if (isset($_SESSION['admin_success_dokumen']) && !empty($_SESSION['admin_success_dokumen'])) {
                                echo '<div class="alert alert-success" role="alert">' . $_SESSION['admin_success_dokumen'] . '</div>';
                                unset($_SESSION['admin_success_dokumen']);
                            }
                            ?>
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
                                    <?php
                                    $no = 1;
                                    $query = "SELECT * FROM dokumen";
                                    $result = mysqli_query($koneksi, $query);
                                    while ($row = mysqli_fetch_assoc($result)) {
                                        echo "<tr>";
                                        echo "<td>" . $no++ . "</td>";
                                        echo "<td>" . $row['judul_dokumen'] . "</td>";
                                        echo "<td><a href='" . 'Dokumen' . '/' . $row['Dokumen'] . "' target='_blank'>" . $row['Dokumen'] . "</a></td>";
                                        echo "<td>";
                                        echo "<div class='d-flex'>";
                                        echo "<button type='button' class='btn btn-primary me-2' data-bs-toggle='modal' data-bs-target='#edit" . $row['id_dokumen'] . "' data-bs-whatever='@mdo'>";
                                        echo "<i class='fas fa-pencil-alt'></i> Edit";
                                        echo "</button>";
                                        echo "<button type='button' class='btn btn-danger' data-bs-toggle='modal' data-bs-target='#hapus" . $row['id_dokumen'] . "'>";
                                        echo "<i class='fas fa-trash'></i> Hapus";
                                        echo "</button>";
                                        echo "</div>";
                                        echo "</td>";
                                        echo "</tr>";

                                        ?>

                                        <!-- Modal hapus data -->
                                        <div class="modal fade" id='hapus<?= $row['id_dokumen'] ?>' tabindex="-1"
                                            role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
                                            <div class="modal-dialog">
                                                <div class="modal-content">
                                                    <div class="modal-header">
                                                        <h5 class="modal-title" id="exampleModalLabel">Hapus Data Dokumen
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
                                                                <div class="form-group">
                                                                    <input type="text" class="form-control" id="id_dokumen"
                                                                        value="<?= $row['id_dokumen']; ?>" name="id_dokumen"
                                                                        hidden>
                                                                </div>
                                                                <div class="form-group">
                                                                    <label for="judul_dokumen">Judul</label>
                                                                    <input type="text" class="form-control"
                                                                        id="judul_dokumen"
                                                                        value="<?= $row['judul_dokumen']; ?>"
                                                                        name="judul_dokumen" required>
                                                                </div>
                                                                <div class="mb-3">
                                                                    <label for="Dokumen" class="col-form-label">Dokumen
                                                                        : <small>(Abaikan Jika Tidak Merubah Dokumen.)</small></label>
                                                                        <input type="file" class="form-control" id="Dokumen"
                                                                        name="Dokumen" accept=".doc, .docx, .pdf">
                                                                        <small>
                                                                        <?php
                                                                    $currentDocument = $row['Dokumen'];
                                                                    if (!empty($currentDocument)) {
                                                                        echo '<p>Dokumen Saat Ini: <a href="Dokumen/' . $currentDocument . '" target="_blank">' . $currentDocument . '</a></p>';
                                                                    }
                                                                    ?>
                                                                        </small>
                                                                    
                                                                    
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
            <div class="modal fade" id="tambah" tabindex="-1"
                aria-labelledby="exampleModalLabel" aria-hidden="true">
                <div class="modal-dialog modal-lg">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" id="exampleModalLabel">Tambah Data Dokumen</h5>
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



           
        </div>
    </div>
    <?php include 'footer.php'; ?>
</body>

</html>