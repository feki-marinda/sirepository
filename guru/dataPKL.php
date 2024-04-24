<?php
session_start();
include('conn.php');

$username = isset($_SESSION['username']) ? $_SESSION['username'] : '';

if (empty($username)) {
    header("Location: ../index.php");
    exit;
}

function escapeString($koneksi, $string)
{
    return mysqli_real_escape_string($koneksi, $string);
}

$query = "SELECT * FROM pkl INNER JOIN siswa ON siswa.id_siswa = pkl.id_siswa
INNER JOIN mitra ON mitra.id_mitra=pkl.id_mitra";
$result = mysqli_query($koneksi, $query);

if (!$result) {
    die("Error in query: " . mysqli_error($koneksi));
}



?>
<style>
    body,
    table {
        font-family: "Poppins", sans-serif;
    }

    .form,
    label,
    input {
        font-family: "Poppins", sans-serif;
    }
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
                    <h1 class="mt-4">Data PKL</h1>
                    <ol class="breadcrumb mb-4">
                        <li class="breadcrumb-item"><a href="index.php">Dashboard</a></li>
                        <li class="breadcrumb-item active">Tables</li>
                    </ol>

                    <div class="card mb-4">
                        <div class="button-container">
                            <div class="spacer"></div>
                            <div class="buttons-right">
                                <button id="printButton">
                                    <a href="../admin/cetak/datapkl.php" style="text-decoration: none; color: inherit;" target="_blank">
                                        <i class="fas fa-print"></i> Cetak
                                    </a>
                                </button>
                            </div>
                        </div>
                    </div>

                    <div class="card mb-4">
                        <div class="card-header">
                            <i class="fas fa-table me-1"></i>
                            Data Praktik Kerja Lapangan Siswa SMK AL-Muhajirin
                        </div>
                        <div class="card-body">
                            <table id="datatablesSimple" class="table table-striped table-hover">
                                <thead>
                                    <tr>

                                        <th>No.</th>
                                        <th>Nama Lengkap</th>
                                        <th>Tanggal Mulai</th>
                                        <th>Tanggal Selesai</th>
                                        <th>Nama Perusahaan</th>
                                        <th>Tahun Pelajaran</th>
                                        <th>No Hp</th>
                                        <th>Email</th>
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
                                        echo "<td>" . $row['nama'] . "</td>";
                                        echo "<td>" . $row['tahun_pelajaran'] . "</td>";
                                        echo "<td>" . $row['no_hp'] . "</td>";
                                        echo "<td>" . $row['email'] . "</td>";
                                        echo "<td>";
                                        // Tombol Detail
                                        echo "<div class='btn-group me-2'>";
                                        echo "<button type='button' class='btn btn-success' onclick=\"window.open('detail/pkl.php?id_siswa={$row['id_siswa']}', '_blank')\"><i class='fa-solid fa-eye'></i> Detail</button>";
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
                                                        <h5 class="modal-title" id="exampleModalLabel">Hapus Data dokumen
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
                                                        <h5 class="modal-title" id="exampleModalLabel">Edit Data Praktik Kerja Lapangan
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
                                                                    <label for="Nama_siswa">Nama siswa</label>
                                                                    <input type="text" class="form-control" id="Nama_siswa"
                                                                        value="<?= $row['Nama_siswa']; ?>" name="Nama_siswa"
                                                                        required>
                                                                </div> 
                                                                <div class="form-group">
                                                                    <input type="text" class="form-control" id="id_pkl"
                                                                        value="<?= $row['id_pkl']; ?>" name="id_pkl" hidden>
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
                                                                    <label for="id_mitra">Tempat PKL</label>
                                                                    <select class="form-control" id="id_mitra"
                                                                        name="id_mitra">
                                                                        <?php
                                                                        $siswaQuery = "SELECT id_mitra, nama FROM mitra";
                                                                        $siswaResult = mysqli_query($koneksi, $siswaQuery);

                                                                        while ($siswa = mysqli_fetch_assoc($siswaResult)) {
                                                                            echo "<option value='{$siswa['id_mitra']}'>{$siswa['nama']}</option>";
                                                                        }
                                                                        ?>
                                                                    </select>
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
                                        </div>                                 <?php
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
                <div class="modal-dialog">
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
                                    <label for="angkatan">Angkatan:</label>
                                    <input type="text" class="form-control" id="angkatan" name="angkatan" required>
                                </div>
                                <div class="form-group">
                                    <label for="nama_perusahaan">Nama Perusahaan:</label>
                                    <input type="text" class="form-control" id="nama_perusahaan" name="nama_perusahaan"
                                        required>
                                </div>
                                <div class="form-group">
                                    <label for="tahun_pelajaran">Tahun Pelajaran:</label>
                                    <input type="text" class="form-control" id="tahun_pelajaran" name="tahun_pelajaran"
                                        required>
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


            
        </div>
    </div>
    <?php include 'footer.php'; ?>
</body>

</html>