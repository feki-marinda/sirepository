<?php
session_start();
include('conn.php');

$status = isset($_SESSION['status']) ? $_SESSION['status'] : '';

if (empty($status)) {
    header("Location: ../index.php");
    exit;
}

$query = "SELECT * FROM berita";
$result = $koneksi->query($query);

if (!$result) {
    die("Error: " . $koneksi->error);
}
if (isset($_POST['TambahBerita'])) {
    $judul = $_POST['judul'];
    $isi_berita = $_POST['isi_berita'];
    $tanggal = $_POST['tanggal'];
    $foto = $xx;
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
            move_uploaded_file($_FILES['foto']['tmp_name'], 'gambar/siswa/' . $rand . '_' . $filename);

            $query = "INSERT INTO berita (judul, isi_berita, tanggal, foto) VALUES ('$judul', '$isi_berita', '$tanggal', '$xx')";

            if ($koneksi->query($query) === TRUE) {
                $rows_affected = $koneksi->affected_rows;

                if ($rows_affected > 0) {
                    $_SESSION['admin_berita_success'] = "Berhasil Menambah Data Berita!";
                    header("Location: databerita.php");
                    exit();
                } else {
                    $_SESSION['admin_error_berita'] = "Tidak ada perubahan pada Data Berita!";
                    header("Location: databerita.php");
                    exit();
                }
            } else {
                $_SESSION['admin_error_berita'] = "Error: " . $koneksi->error;
                header("Location: databerita.php");
                exit();
            }
        }
    }
}

if (isset($_POST['EditBerita'])) {
    $id_berita = $_POST['id_berita'];
    $judul = $_POST['judul'];
    $isi_berita = $_POST['isi_berita'];
    $tanggal = $_POST['tanggal'];
    $foto_baru = $_FILES['gambarnew']['name'];

    if (!empty($foto_baru)) {
        $ekstensi_diperbolehkan = array('png', 'jpg', 'jpeg', 'webp', 'gif');
        $x = explode('.', $foto_baru);
        $ekstensi = strtolower(end($x));
        $file_tmp = $_FILES['gambarnew']['tmp_name'];
        $angka_acak = rand(1, 999);
        $nama_gambar_baru = $angka_acak . '-' . $foto_baru;

        if (in_array($ekstensi, $ekstensi_diperbolehkan)) {
            $berita_sebelumnya = mysqli_fetch_assoc(mysqli_query($koneksi, "SELECT * FROM berita WHERE id_berita='$id_berita'"));
            $gambar_lama = $berita_sebelumnya['foto'];

            if (is_file("gambar/siswa/" . $gambar_lama)) {
                unlink("gambar/siswa/" . $gambar_lama);
            }

            move_uploaded_file($file_tmp, 'gambar/siswa/' . $nama_gambar_baru);

            $query = "UPDATE berita SET judul=?, isi_berita=?, tanggal=?, foto=? WHERE id_berita=?";
            $stmt = mysqli_prepare($koneksi, $query);
            mysqli_stmt_bind_param($stmt, 'ssssi', $judul, $isi_berita, $tanggal, $nama_gambar_baru, $id_berita);
        } else {
            $_SESSION['admin_error_berita'] = "Ekstensi file gambar tidak valid. Gunakan png, jpg, jpeg, webp, atau gif.";
        }
    } else {
        $query = "UPDATE berita SET judul=?, isi_berita=?, tanggal=? WHERE id_berita=?";
        $stmt = mysqli_prepare($koneksi, $query);
        mysqli_stmt_bind_param($stmt, 'sssi', $judul, $isi_berita, $tanggal, $id_berita);
    }

    if (isset($stmt)) {
        $result = mysqli_stmt_execute($stmt);

        if ($result) {
            $rows_affected = mysqli_stmt_affected_rows($stmt);
            if ($rows_affected > 0) {
                $_SESSION['admin_berita_success'] = "Data Berita Berhasil Diubah!";
            } else {
                $_SESSION['admin_error_berita'] = "Tidak Ada Perubahan Pada Data Berita!";
            }
        } else {
            $_SESSION['admin_error_berita'] = "Error: " . mysqli_stmt_error($stmt);
        }

        mysqli_stmt_close($stmt);
    }

    header("location:databerita.php");
    exit();
}

if (isset($_GET['id_berita'])) {
    $id_berita = $_GET['id_berita'];

    mysqli_query($koneksi, "DELETE FROM berita WHERE id_berita='$id_berita'");
    if ($result) {
        $_SESSION['admin_berita_success'] = "Data Berita Berhasil Dihapus!";
        header("Location: databerita.php");
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
                    <h1 class="mt-4">Data Berita</h1>
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
                                    Tambah Data Berita</button>

                            </div>
                        </div>
                    </div>

                    <div class="card mb-4">
                        <div class="card-header">
                            <i class="fas fa-table me-1"></i>
                            Data Berita
                        </div>
                        <div class="card-body">
                            <?php
                            if (isset($_SESSION['admin_error_berita']) && !empty($_SESSION['admin_error_berita'])) {
                                echo '<div class="alert alert-danger" role="alert">' . $_SESSION['admin_error_berita'] . '</div>';
                                unset($_SESSION['admin_error_berita']);
                            }

                            if (isset($_SESSION['admin_berita_success']) && !empty($_SESSION['admin_berita_success'])) {
                                echo '<div class="alert alert-success" role="alert">' . $_SESSION['admin_berita_success'] . '</div>';
                                unset($_SESSION['admin_berita_success']);
                            }
                            ?>
                            <table id="datatablesSimple" class="table table-striped table-hover">
                                <thead>
                                    <tr>
                                        <th>No.</th>
                                        <th>Judul Berita</th>
                                        <th>Isi</th>
                                        <th>Tanggal</th>
                                        <th>gambar</th>
                                        <th>Keterangan</th>
                                    </tr>
                                </thead>
                                <tfoot>
                                    <tr>
                                        <th>No.</th>
                                        <th>Judul Berita</th>
                                        <th>Isi</th>
                                        <th>Tanggal</th>
                                        <th>Gambar</th>
                                        <th>Keterangan</th>
                                    </tr>
                                </tfoot>
                                <tbody>
                                    <?php
                                    $no = 1;
                                    $query = "SELECT * FROM berita";
                                    $result = mysqli_query($koneksi, $query);

                                    while ($row = mysqli_fetch_assoc($result)) {
                                        echo "<tr>";
                                        echo "<td>" . $no++ . "</td>";
                                        echo "<td>" . $row['judul'] . "</td>";
                                        echo "<td>";
                                        echo "<div style='text-align: justify;'>";
                                        echo "<span class='expandable' onclick='expandContent(this)'>" . substr($row['isi_berita'], 0, 100) . "<a href='#' style='color: blue;'> Selengkapnya</a></span>";
                                        echo "<div class='expanded-content' style='display: none;'>" . $row['isi_berita'] . "</div>";
                                        echo "</div>";
                                        echo "</td>";
                                        echo "<td>" . date('d-m-Y', strtotime($row['tanggal'])) . "</td>";
                                        echo "<td> <img src='gambar/siswa/" . $row['foto'] . "' width='120' height='120'></td>";
                                        echo "<td>";
                                        echo "<div class='d-flex'>";
                                        echo "<button type='button' class='btn btn-primary me-2' data-bs-toggle='modal' data-bs-target='#edit" . $row['id_berita'] . "' data-bs-whatever='@mdo'>";
                                        echo "<i class='fas fa-pencil-alt'></i> Edit";
                                        echo "</button>";
                                        echo "<button type='button' class='btn btn-danger' data-bs-toggle='modal' data-bs-target='#hapus" . $row['id_berita'] . "'>";
                                        echo "<i class='fas fa-trash'></i> Hapus";
                                        echo "</button>";
                                        echo "</div>";
                                        echo "</td>";
                                        echo "</tr>";

                                        ?>

                                        <div class="modal fade" id='hapus<?= $row['id_berita'] ?>' tabindex="-1"
                                            role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
                                            <div class="modal-dialog modal-lg">
                                                <div class="modal-content">
                                                    <div class="modal-header">
                                                        <h5 class="modal-title" id="exampleModalLabel">Hapus Data Berita
                                                        </h5>
                                                        <button type="button" class="btn-close" data-bs-dismiss="modal"
                                                            aria-label="Close"></button>
                                                    </div>
                                                    <div class="modal-body">
                                                        Apakah anda yakin ingin menghapus data berita?
                                                    </div>
                                                    <div class="modal-footer">
                                                        <button type="button" class="btn btn-secondary"
                                                            data-bs-dismiss="modal">Tidak</button>
                                                        <a href="databerita.php?id_berita=<?= $row['id_berita'] ?>"
                                                            class="btn btn-danger">Delete</a>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>

                                        <div class='modal fade' id='edit<?= $row['id_berita'] ?>' tabindex='-1'
                                            aria-labelledby='exampleModalLabel' aria-hidden='true'>
                                            <div class="modal-dialog modal-lg">
                                                <div class="modal-content">
                                                    <div class="modal-header">
                                                        <h5 class="modal-title" id="exampleModalLabel">Edit Data Berita</h5>
                                                        <button type="button" class="btn-close" data-bs-dismiss="modal"
                                                            aria-label="Close"></button>
                                                    </div>
                                                    <div class="modal-body">
                                                        <form method="post" action="#" enctype="multipart/form-data">
                                                            <div class="form-group">
                                                                <div class="form-group">
                                                                    <input type="text" class="form-control" id="id_berita"
                                                                        value="<?= $row['id_berita']; ?>" name="id_berita"
                                                                        hidden>
                                                                </div>
                                                                <div class="form-group">
                                                                    <label for="judul">Judul</label>
                                                                    <input type="text" class="form-control" id="judul"
                                                                        value="<?= $row['judul']; ?>" name="judul" required>
                                                                </div>
                                                                <div class="form-group">
                                                                    <label for="isi_berita">Isi Berita</label>
                                                                    <textarea class="form-control" id="isi_berita"
                                                                        name="isi_berita"
                                                                        required><?= $row['isi_berita']; ?></textarea>
                                                                </div>

                                                                <div class="form-group">
                                                                    <label for="tanggal">Tanggal</label>
                                                                    <input type="date" class="form-control" id="tanggal"
                                                                        value="<?= $row['tanggal']; ?>" name="tanggal"
                                                                        required>
                                                                </div>
                                                                <div class="form-group">
                                                                    <label for="foto">Gambar</label>
                                                                    <img src="gambar/siswa/<?php echo $row['foto']; ?>"
                                                                        height="120" width="120">
                                                                    <input type="file" name="gambarnew"
                                                                        class="form-control-file">
                                                                    <small>Abaikan jika tidak merubah gambar.</small>

                                                                </div>
                                                                <div class="modal-footer">
                                                                    <button type="button" class="btn btn-secondary"
                                                                        data-bs-dismiss="modal">
                                                                        <i class="fas fa-times"></i> Tutup
                                                                    </button>
                                                                    <button type="submit" class="btn btn-primary"
                                                                        name="EditBerita" value="Submit">
                                                                        <i class="fas fa-check"></i> Submit
                                                                    </button>
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


            <!-- Modal tamabah data-->
            <div class="modal fade" id="tambah" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
                <div class="modal-dialog modal-lg">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" id="exampleModalLabel">Tambah Data Berita</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                        <div class="modal-body ">
                            <form action="#" method="post" enctype="multipart/form-data" id="formTambahData">
                                <div class="mb-3">
                                    <label for="judul" class="col-form-label">Judul Berita:</label>
                                    <input type="text" class="form-control" id="judul" name="judul">
                                </div>
                                <div class="mb-3">
                                    <label for="isi_berita" class="col-form-label">Isi Berita :</label>
                                    <textarea class="form-control" id="isi_berita" name="isi_berita"></textarea>
                                </div>
                                <div class="mb-3">
                                    <label for="tanggal" class="col-form-label">Tanggal</label>
                                    <input type="date" class="form-control" id="tanggal" name="tanggal">
                                </div>
                                <div class="mb-3">
                                    <label for="foto" class="col-form-label">Gambar</label>
                                    <input type="file" class="form-control" id="foto" name="foto">
                                </div>
                                <div class="modal-footer">
                                    <button type="button" class="btn btn-secondary"
                                        data-bs-dismiss="modal">Close</button>
                                    <button type="submit" class="btn btn-primary" name="TambahBerita" value="Submit"
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

    <script>
        function expandContent(element) {
            var expandedContent = element.nextElementSibling;
            if (expandedContent.style.display === "none" || expandedContent.style.display === "") {
                expandedContent.style.display = "block";
                element.innerHTML = element.nextElementSibling.innerHTML;
            } else {
                expandedContent.style.display = "none";
                element.innerHTML = element.innerHTML.slice(0, 100) + "...";
            }
        }
    </script>
</body>

</html>