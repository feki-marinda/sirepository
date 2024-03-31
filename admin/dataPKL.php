<?php
session_start();
include('conn.php');

$id_user = isset($_SESSION['id_user']) ? $_SESSION['id_user'] : '';

if (empty($id_user)) {
    header("Location: ../index.php");
    exit;
}

function escapeString($koneksi, $string)
{
    return mysqli_real_escape_string($koneksi, $string);
}

$query = "SELECT siswa.Nama_siswa, pkl.id_pkl, pkl.id_siswa, pkl.tgl_mulai, pkl.tgl_selesai, pkl.kelas, pkl.nama_perusahaan, pkl.tahun_pelajaran FROM pkl INNER JOIN siswa ON siswa.id_siswa = pkl.id_siswa";
$result = mysqli_query($koneksi, $query);

if (!$result) {
    die("Error in query: " . mysqli_error($koneksi));
}

if (isset($_POST['Tambahpkl'])) {
    $id_siswa = escapeString($koneksi, $_POST['id_siswa']);
    $tgl_mulai = escapeString($koneksi, $_POST['tgl_mulai']);
    $tgl_selesai = escapeString($koneksi, $_POST['tgl_selesai']);
    $kelas = escapeString($koneksi, $_POST['kelas']);
    $nama_perusahaan = escapeString($koneksi, $_POST['nama_perusahaan']);
    $tahun_pelajaran = escapeString($koneksi, $_POST['tahun_pelajaran']);

    $query = "INSERT INTO pkl (id_siswa, tgl_mulai, tgl_selesai, kelas, nama_perusahaan, tahun_pelajaran) 
              VALUES ('$id_siswa', '$tgl_mulai', '$tgl_selesai', '$kelas', '$nama_perusahaan', '$tahun_pelajaran')";

    // Eksekusi query dan cek hasilnya
    $result = $koneksi->query($query);

    if ($result) {
        $_SESSION['success_message'] = "Data PKL berhasil ditambahkan!";
        header("Location: dataPKL.php");
        exit();
    } else {
        $_SESSION['error_message'] = "Error: " . $koneksi->error;
        header("Location: dataPKL.php");
        exit();
    }
}

if (isset($_POST['Editpkl'])) {
    $id_pkl = escapeString($koneksi, $_POST['id_pkl']);
    $id_siswa = escapeString($koneksi, $_POST['id_siswa']);
    $tgl_mulai = escapeString($koneksi, $_POST['tgl_mulai']);
    $tgl_selesai = escapeString($koneksi, $_POST['tgl_selesai']);
    $kelas = escapeString($koneksi, $_POST['kelas']);
    $nama_perusahaan = escapeString($koneksi, $_POST['nama_perusahaan']);
    $tahun_pelajaran = escapeString($koneksi, $_POST['tahun_pelajaran']);

    mysqli_query($koneksi, "UPDATE pkl SET 
                         tgl_mulai='$tgl_mulai',
                         tgl_selesai='$tgl_selesai',
                         kelas='$kelas',
                         nama_perusahaan='$nama_perusahaan',
                         tahun_pelajaran='$tahun_pelajaran'                            
                         WHERE id_pkl='$id_pkl'");

    $id_pkl = $_GET['id_pkl'];

    if ($result) {
        $rows_affected = mysqli_affected_rows($koneksi);
        if ($rows_affected > 0) {
            $_SESSION['success_message'] = "Data PKL berhasil diubah!";
        } else {
            $_SESSION['error_message'] = "Tidak ada perubahan pada Data Pamong!";
        }
    } else {
        $_SESSION['error_message'] = "Error: " . $koneksi->error;
    }

    header("Location: dataPKL.php");
    exit();

}

if (isset($_GET['id_pkl'])) {
    $id_pkl = escapeString($koneksi, $_GET['id_pkl']);

    mysqli_query($koneksi, "DELETE FROM pkl WHERE id_pkl='$id_pkl'");
    if ($result) {
        $_SESSION['success_message'] = "Data PKL berhasil dihapus!";
        header("Location: dataPKL.php");
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
                    <h1 class="mt-4">Data PKL</h1>
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
                                    Tambah Data PKL</button>
                                    <button id="printButton">
                                    <a href="cetak/datapkl.php" style="text-decoration: none; color: inherit;" target="_blank">
                                        <i class="fas fa-print"></i> Cetak
                                    </a>
                                </button>
                            </div>
                        </div>
                    </div>

                    <div class="card mb-4">
                        <div class="card-header">
                            <i class="fas fa-table me-1"></i>
                            Data Praktik Kerja Lapangan Siswa
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
                                        <th>Tanggal Mulai</th>
                                        <th>Tanggal Selesai</th>
                                        <th>Kelas</th>
                                        <th>Nama Perusahaan</th>
                                        <th>Tahun Pelajaran</th>
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
                                        echo "<td>" . date('d-m-Y', strtotime($row['tgl_mulai'])) . "</td>";
                                        echo "<td>" . date('d-m-Y', strtotime($row['tgl_selesai'])) . "</td>";
                                        echo "<td>" . $row['kelas'] . "</td>";
                                        echo "<td>" . $row['nama_perusahaan'] . "</td>";
                                        echo "<td>" . $row['tahun_pelajaran'] . "</td>";
                                        echo "<td>";
                                        echo "<div class='d-flex'>";
                                        echo "<button type='button' class='btn btn-primary me-2' data-bs-toggle='modal' data-bs-target='#edit" . $row['id_pkl'] . "' data-bs-whatever='@mdo'>";
                                        echo "<i class='fas fa-pencil-alt'></i> Edit";
                                        echo "</button>";
                                        echo "<button type='button' class='btn btn-danger' data-bs-toggle='modal' data-bs-target='#hapus" . $row['id_pkl'] . "'>";
                                        echo "<i class='fas fa-trash'></i> Hapus";
                                        echo "</button>";
                                        echo "</div>";
                                        echo "</td>";
                                        echo "</tr>";

                                        ?>

                                        <!-- Modal hapus data -->
                                        <div class="modal fade" id='hapus<?= $row['id_pkl'] ?>' tabindex="-1" role="dialog"
                                            aria-labelledby="exampleModalLabel" aria-hidden="true">
                                            <div class="modal-dialog modal-lg">
                                                <div class="modal-content">
                                                    <div class="modal-header">
                                                        <h5 class="modal-title" id="exampleModalLabel">Hapus Data Praktek
                                                            Kerja Lapangan
                                                        </h5>
                                                        <button type="button" class="btn-close" data-bs-dismiss="modal"
                                                            aria-label="Close"></button>
                                                    </div>
                                                    <div class="modal-body">
                                                        Apakah anda yakin ingin menghapus Data Praktik Kerja Lapangan?
                                                    </div>
                                                    <div class="modal-footer">
                                                        <button type="button" class="btn btn-secondary"
                                                            data-bs-dismiss="modal">Tidak</button>
                                                        <a href="dataPKL.php?id_pkl=<?= $row['id_pkl'] ?>"
                                                            class="btn btn-danger">Delete</a>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>

                                        <!-- Modal edit data -->
                                        <div class='modal fade' id='edit<?= $row['id_pkl'] ?>' tabindex='-1'
                                            aria-labelledby='exampleModalLabel' aria-hidden='true'>
                                            <div class="modal-dialog modal-lg">
                                                <div class="modal-content">
                                                    <div class="modal-header">
                                                        <h5 class="modal-title" id="exampleModalLabel">Edit Data dokumen
                                                        </h5>
                                                        <button type="button" class="btn-close" data-bs-dismiss="modal"
                                                            aria-label="Close"></button>
                                                    </div>
                                                    <input type="hidden" name="id_pkl" value="<?= $row['id_pkl']; ?>">
                                                    <input type="hidden" name="id_siswa" value="<?= $row['id_siswa']; ?>">

                                                    <div class="modal-body">
                                                        <form method="post" action="#" enctype="multipart/form-data">
                                                            <div class="form-group">
                                                                <div class="form-group">
                                                                    <label for="id_pkl">ID</label>
                                                                    <input type="text" class="form-control" id="id_pkl"
                                                                        value="<?= $row['id_pkl']; ?>" name="id_pkl"
                                                                        readonly>
                                                                </div>
                                                                <div class="form-group">
                                                                    <label for="Nama_siswa">Nama siswa</label>
                                                                    <input type="text" class="form-control" id="Nama_siswa"
                                                                        value="<?= $row['Nama_siswa']; ?>" name="Nama_siswa"
                                                                        required>
                                                                </div>
                                                                <div class="form-group">
                                                                    <label for="tgl_mulai">Tanggal Mulai PKL :</label>
                                                                    <input type="date" class="form-control" id="tgl_mulai"
                                                                        value="<?= $row['tgl_mulai']; ?>" name="tgl_mulai"
                                                                        required>
                                                                </div>
                                                                <div class="form-group">
                                                                    <label for="tgl_selesai">Tanggal Selesai PKL :</label>
                                                                    <input type="date" class="form-control" id="tgl_selesai"
                                                                        value="<?= $row['tgl_selesai']; ?>"
                                                                        name="tgl_selesai" required>
                                                                </div>
                                                                <div class="form-group">
                                                                    <label for="kelas">Kelas</label>
                                                                    <select class="form-control" id="kelas" name="kelas"
                                                                        required>
                                                                        <option value="XI A" <?= ($row['kelas'] === 'XI A') ? 'selected' : ''; ?>>XI A</option>
                                                                        <option value="XI B" <?= ($row['kelas'] === 'XI B') ? 'selected' : ''; ?>>XI B</option>
                                                                    </select>
                                                                </div>
                                                                <div class="form-group">
                                                                    <label for="nama_perusahaan">Nama Perusahaan</label>
                                                                    <input type="text" class="form-control"
                                                                        id="nama_perusahaan"
                                                                        value="<?= $row['nama_perusahaan']; ?>"
                                                                        name="nama_perusahaan" required>
                                                                </div>
                                                                <div class="form-group">
                                                                    <label for="tahun_pelajaran">Tahun
                                                                        Pelajaran</label><input type="text"
                                                                        class="form-control" id="tahun_pelajaran"
                                                                        value="<?= $row['tahun_pelajaran']; ?>"
                                                                        name="tahun_pelajaran" required>
                                                                </div>
                                                            </div>
                                                            <div class="modal-footer">
                                                                <button type="button" class="btn btn-secondary"
                                                                    data-bs-dismiss="modal">Close</button>
                                                                <button type="submit" class="btn btn-primary" name="Editpkl"
                                                                    value="Submit">Submit</button>
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
                            <h5 class="modal-title" id="exampleModalLabel">Tambah Data PKL</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                        <div class="modal-body">
                            <form action="#" method="post" enctype="multipart/form-data" id="formTambahData">
                                <div class="form-group">
                                    <label for="id_siswa">Nama siswa</label>
                                    <select class="form-control" id="id_siswa" name="id_siswa" required>
                                        <?php
                                        $siswaQuery = "SELECT id_siswa, Nama_siswa FROM siswa";
                                        $siswaResult = mysqli_query($koneksi, $siswaQuery);

                                        while ($siswa = mysqli_fetch_assoc($siswaResult)) {
                                            echo "<option value='{$siswa['id_siswa']}'>{$siswa['Nama_siswa']}</option>";
                                        }
                                        ?>
                                    </select>
                                </div>
                                <div class="form-group">
                                    <label for="tgl_mulai">Tanggal Mulai PKL:</label>
                                    <input type="date" class="form-control" id="tgl_mulai" name="tgl_mulai" required>
                                </div>
                                <div class="form-group">
                                    <label for="tgl_selesai">Tanggal Selesai PKL:</label>
                                    <input type="date" class="form-control" id="tgl_selesai" name="tgl_selesai"
                                        required>
                                </div>
                                <div class="form-group">
                                    <label for="kelas">Kelas:</label>
                                    <select class="form-control" id="kelas" name="kelas" required>
                                        <option value="XI A">XI A</option>
                                        <option value="XI B">XI B</option>
                                    </select>
                                </div>
                                <div class="form-group">
                                    <label for="nama_perusahaan">Nama Perusahaan:</label>
                                    <input type="text" class="form-control" id="nama_perusahaan" name="nama_perusahaan"
                                        required>
                                </div>
                                <div class="form-group">
                                    <label for="tahun_pelajaran">Tahun Pelajaran:</label>
                                    <input type="number" class="form-control" id="tahun_pelajaran"
                                        name="tahun_pelajaran" required>
                                </div>
                                <div class="modal-footer">
                                    <button type="button" class="btn btn-secondary"
                                        data-bs-dismiss="modal">Close</button>
                                    <button type="submit" class="btn btn-primary" name="Tambahpkl" value="Submit"
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