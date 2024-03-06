<?php
session_start();
include('conn.php');

$id_user = isset($_SESSION['id_user']) ? $_SESSION['id_user'] : '';

if (empty($id_user)) {
    header("Location: index.php");
    exit;
}
if (isset($_SESSION['id_user'])) {
    $id_user = $_SESSION['id_user'];

    $query_siswa = "SELECT id_siswa FROM siswa WHERE id_user = '$id_user'";
    $result_siswa = mysqli_query($koneksi, $query_siswa);

    if ($result_siswa) {
        $row_siswa = mysqli_fetch_assoc($result_siswa);

        if ($row_siswa) {
            $_SESSION['id_siswa'] = $row_siswa['id_siswa'];

            $query = "SELECT siswa.Nama_siswa, siswa.id_siswa, pkl.*
                      FROM pkl
                      INNER JOIN siswa ON siswa.id_siswa = pkl.id_siswa
                      WHERE siswa.id_siswa = " . $_SESSION['id_siswa'];
            $result = mysqli_query($koneksi, $query);

        } else {
            echo "ID Siswa tidak ditemukan.";
        }
    } else {
        echo "Error: " . mysqli_error($koneksi);
    }
} else {
    echo "Tidak ada user yang login.";
}

if (isset($_POST['daftar'])) {
    $id_siswa = $_SESSION['id_siswa']; 
    $tgl_mulai = $_POST['tgl_mulai'];
    $tgl_selesai = $_POST['tgl_selesai'];
    $kelas = $_POST['kelas'];
    $nama_perusahaan = $_POST['nama_perusahaan'];
    $tahun_pelajaran = $_POST['tahun_pelajaran'];

    $query_daftar = "INSERT INTO pkl (id_siswa, tgl_mulai, tgl_selesai, nama_perusahaan, tahun_pelajaran, kelas)
                     VALUES ('$id_siswa', '$tgl_mulai', '$tgl_selesai', '$nama_perusahaan', '$tahun_pelajaran', '$kelas')";

    $result_daftar = mysqli_query($koneksi, $query_daftar);

    if ($result_daftar) {
        $success_message = "Pendaftaran Berhasil. Cek di bagian riwayat.";
    } else {
        $error_message = "Pendaftaran Gagal. Anda Sudah Mendaftar Praktik Kerja Lapangan, Cek Riwayat Pendaftar !";
    }
}

?>


<!DOCTYPE html>
<html lang="en">
<?php include 'head.html' ?>

<body>

    <!-- ======= Header ======= -->
    <?php include 'header_siswa.php' ?>
    <!-- End Header -->

    <main id="main">

        <!-- ======= Breadcrumbs ======= -->
        <section class="breadcrumbs">
            <div class="container">
                <ol>
                    <li><a href="index.php">Home</a></li>
                    <li><a href="about.php">Daftar PKL</a></li>
                </ol>
                <h2>SMK Al-Muhajirin</h2>
            </div>
        </section><!-- End Breadcrumbs -->

        <!-- ======= Blog Single Section ======= -->
        <section id="blog" class="blog">
            <div class="container">

                <div class="row">
                    <div class=" entries">
                        <article class="entry">
                            <h1 class="entry-title">
                                <a href="sejarah.php">Daftar Praktek Kerja Lapangan</a><br>
                            </h1>
                            <div class="entry-content" style="text-align:justify">
                            <?php
                            if (!empty($error_message)) {
                                echo '<div class="alert alert-danger" role="alert">' . $error_message . '</div>';
                            }

                            if (!empty($success_message)) {
                                echo '<div class="alert alert-success" role="alert">' . $success_message . '</div>';
                            }
                            ?>
                                <form method="post" action="">
                                    <div class="mb-3">
                                        <input type="text" class="form-control"
                                            value="<?php echo isset($_SESSION['id_siswa']) ? $_SESSION['id_siswa'] : ''; ?>"
                                            hidden>
                                    </div>
                                    <div class="mb-3">
                                        <label for="Nama_Siswa" class="form-label">Nama Siswa :</label>
                                        <input type="text" class="form-control">
                                    </div>
                                    <div class="mb-3">
                                        <label for="kelas" class="form-label">Kelas :</label>
                                        <input type="text" class="form-control" id="kelas" name="kelas">
                                    </div>
                                    <div class="row">
                                        <div class="col-md-6 mb-3">
                                            <label for="tgl_mulai" class="form-label">Tanggal Mulai :</label>
                                            <input type="date" class="form-control" id="tgl_mulai" name="tgl_mulai">
                                        </div>
                                        <div class="col-md-6 mb-3">
                                            <label for="tgl_selesai" class="form-label">Tanggal Selesai :</label>
                                            <input type="date" class="form-control" id="tgl_selesai" name="tgl_selesai">
                                        </div>
                                    </div>
                                    <div class="mb-3">
                                        <label for="nama_perusahaan" class="form-label">Tempat PKL :</label>
                                        <input type="text" class="form-control" id="nama_perusahaan"
                                            name="nama_perusahaan">
                                    </div>
                                    <div class="mb-3">
                                        <label for="tahun_pelajaran" class="form-label">Tahun Pelajaran :</label>
                                        <input type="text" class="form-control" id="tahun_pelajaran"
                                            name="tahun_pelajaran">
                                    </div>
                                    <button type="submit" name="daftar" class="btn btn-primary">Submit</button>
                                </form>
                            </div>
                        </article>
    </main>

    <div class="container">
        <div class="center text-center mb-3">
            <h3><strong>Riwayat Pendaftaran Praktek Kerja Lapangan</strong></h3>
        </div>
        <div class="div">
    <table class="table">
        <thead class="table-primary">
            <tr>
                <td>Nama</td>
                <td>Kelas</td>
                <td>Tanggal Mulai</td>
                <td>Tanggal Selesai</td>
                <td>Tempat Magang</td>
                <td>Tahun Pelajaran</td>
            </tr>
        </thead>
        <tbody>
            <?php
            $query = "SELECT siswa.Nama_siswa, siswa.id_siswa, pkl.*
            FROM pkl
            INNER JOIN siswa ON siswa.id_siswa = pkl.id_siswa
            WHERE siswa.id_siswa = " . $_SESSION['id_siswa'];
  $result = mysqli_query($koneksi, $query);
            while ($row = mysqli_fetch_assoc($result)) {
                echo "<tr>";
                echo "<td>" . $row['Nama_siswa'] . "</td>";
                echo "<td>" . $row['kelas'] . "</td>"; 
                echo "<td>" . $row['tgl_mulai'] . "</td>";
                echo "<td>" . $row['tgl_selesai'] . "</td>";
                echo "<td>" . $row['nama_perusahaan'] . "</td>";
                echo "<td>" . $row['tahun_pelajaran'] . "</td>";
                echo "</tr>";
            }
            ?>
        </tbody>
    </table>
</div>

    </div><br><br><br>

    <?php include 'footer.html' ?>

    <a href="#" class="back-to-top d-flex align-items-center justify-content-center"><i
            class="bi bi-arrow-up-short"></i></a>

    <script src="assets/js/main.js"></script>

</body>

</html>