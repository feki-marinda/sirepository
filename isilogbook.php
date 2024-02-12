<?php
include 'conn.php';
session_start();

// Pastikan session 'username' berisi username dari tabel pengguna
$username = isset($_SESSION['username']) ? $_SESSION['username'] : '';

// Query untuk mendapatkan ID siswa berdasarkan username dari tabel pengguna
$query_get_id = "SELECT siswa.id_siswa FROM siswa
                INNER JOIN user ON siswa.id_user = user.id_user
                WHERE user.username = '$username' LIMIT 1";

$result_id = $koneksi->query($query_get_id);

if ($result_id !== false && $result_id->num_rows > 0) {
    // Ambil data ID siswa
    $row_id = $result_id->fetch_assoc();
    $id_siswa = $row_id['id_siswa'];

    // Simpan ID siswa ke dalam session
    $_SESSION['id_siswa'] = $id_siswa;
} else {
    // Handle jika data tidak ditemukan atau query gagal
    $id_siswa = 'ID Siswa Tidak Ditemukan';
}

$Nama_siswa = '';
$id_siswa = isset($_SESSION['id_siswa']) ? $_SESSION['id_siswa'] : '';

// Query untuk mendapatkan nama siswa berdasarkan ID siswa dengan JOIN
$query_get_nama = "SELECT siswa.Nama_siswa FROM logbook
                    INNER JOIN siswa ON logbook.id_siswa = siswa.id_siswa
                    WHERE logbook.id_siswa = '$id_siswa' LIMIT 1";
$result_nama = $koneksi->query($query_get_nama);

if ($result_nama !== false && $result_nama->num_rows > 0) {
    // Ambil data nama siswa
    $row_nama = $result_nama->fetch_assoc();
    $Nama_siswa = $row_nama['Nama_siswa'];
} else {
    // Handle jika data tidak ditemukan atau query gagal
    $Nama_siswa = 'Nama Siswa Tidak Ditemukan';
}

// Inisialisasi variabel $show_modal
$show_modal = false;

// Periksa apakah form telah disubmit
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Ambil data dari formulir
    $tanggal = $_POST['tanggal'];
    $aktivitas = $_POST['aktivitas'];
    $id_siswa = $_POST['id_siswa'];

    // Periksa apakah data sudah ada untuk tanggal tertentu dan pengguna tertentu
    $check_query = "SELECT * FROM logbook WHERE tanggal = '$tanggal' AND id_siswa = '$id_siswa'";
    $result = $koneksi->query($check_query);

    if ($result !== false) {
        if ($result->num_rows > 0) {
            // Jika data sudah ada, set $show_modal menjadi true
            $show_modal = true;
        } else {
            // Jika data belum ada, simpan data ke dalam database
            $insert_query = "INSERT INTO logbook (tanggal, aktivitas, id_siswa) VALUES ('$tanggal', '$aktivitas', '$id_siswa')";

            if ($koneksi->query($insert_query) === TRUE) {
                header("Location: logbook.php");
                exit();
            } else {
                echo "Error: " . $insert_query . "<br>" . $koneksi->error;
            }
        }
    } else {
        // Handle query execution failure
        echo "Error executing query: " . $koneksi->error;
    }
}

$koneksi->close();
?>

<!-- Bagian HTML dan lainnya tetap sama -->

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
                    <li><a href="logbook.php">Logbook</a></li>
                    <li>Ungah Logbook</li>
                </ol>
                <h2>
                    <?php
                    // Periksa apakah session nama sudah ada
                    if (isset($_SESSION['username'])) {
                        $Nama_siswa = $_SESSION['username'];
                        echo '<h2>Hallo ' . $Nama_siswa . '</h2>';
                    } else {
                        echo '<h2>Hallo</h2>';
                    }
                    ?>
                </h2>

            </div>
        </section><!-- End Breadcrumbs -->

        <div class="container">
            <div class="row ms-3 pb-5 pt-5 ps-5 pe-5 rounded shadow d-flex" style="background-color: #F0F8FF;">
                <div class="col-md-9">
                    <h3>Hallo
                        <?php echo $Nama_siswa; ?>
                    </h3>
                    <h1 class="font-weight-bold text-left" style="font-size: 2.5rem; color: #333;">
                        Bagaimana <span style="color: #FFD700;">Kegiatanmu</span> Hari ini ?
                    </h1><br>
                </div>
                <div class="row">
                    <div class="col-md-4">
                        <div class="form-check form-check-inline dfre">
                            <input class="form-check-input" type="radio" name="inlineRadioOptions" id="inlineRadio1"
                                value="option1">
                            <label class="form-check-label" for="inlineRadio1"
                                style="font-size: 1.5rem; font-weight: bold; color: #FF4500;">
                                Tidak Senang
                                <i class="far fa-thumbs-down"></i>
                            </label>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="form-check form-check-inline">
                            <input class="form-check-input" type="radio" name="inlineRadioOptions" id="inlineRadio2"
                                value="option2">
                            <label class="form-check-label" for="inlineRadio2"
                                style="font-size: 1.5rem; font-weight: bold; color: #FF4500;">
                                Biasa
                                <i class="far fa-meh"></i>
                            </label>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="form-check form-check-inline">
                            <input class="form-check-input" type="radio" name="inlineRadioOptions" id="inlineRadio3"
                                value="option3">
                            <label class="form-check-label" for="inlineRadio3"
                                style="font-size: 1.5rem; font-weight: bold; color: #FF4500;">
                                Senang
                                <i class="far fa-thumbs-up"></i>
                            </label>
                        </div>
                    </div>
                </div>
            </div><br>

            <div class="row ms-3 pb-5 pt-5 ps-5 pe-5 rounded shadow d-flex">
                <form action="#" method="post" class="form-container">
                    <input type="hidden" name="id_siswa" value="<?php echo $_SESSION['id_siswa']; ?>">

                    <div class="mb-3">
                        <label for="exampleFormControlInput1" class="form-label fw-bold">Tanggal Kegiatan</label>
                        <input type="date" class="form-control" id="exampleFormControlInput1" name="tanggal" required>
                    </div>
                    <div class="mb-3">
                        <label for="exampleFormControlTextarea1" class="form-label fw-bold">Deskripsikan Aktivitasmu</label>
                        <textarea class="form-control" id="exampleFormControlTextarea1" rows="3"
                            placeholder="Deskripsikan Kegiatanmu" name="aktivitas" required></textarea>
                    </div>
                    <button type="submit" class="btn btn-primary">Submit</button>
                </form>
            </div>

            <div class="modal fade" id="myModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
                aria-hidden="true">
                <div class="modal-dialog">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title">PERINGATAN !</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                        <div class="modal-body">
                            <p>Anda sudah mengisi logbook untuk tanggal ini.</p>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                        </div>
                    </div>
                </div>
            </div>

        </div>

        <!-- jQuery -->
        <script src="https://code.jquery.com/jquery-3.6.4.min.js"></script>

        <!-- Bootstrap JS -->
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.min.js"></script>

        <script>
            $(document).ready(function () {
                <?php if ($show_modal): ?>
                    $("#myModal").modal("show");
                <?php endif; ?>

                // Menampilkan modal saat tombol submit diklik
                $("form").submit(function () {
                    $("#myModal").modal("show");
                });
            });
        </script>

        <!-- Vendor JS Files -->
        <script src="assets/vendor/purecounter/purecounter_vanilla.js"></script>
        <script src="assets/vendor/aos/aos.js"></script>
        <script src="assets/vendor/bootstrap/js/bootstrap.bundle.min.js"></script>
        <script src="assets/vendor/glightbox/js/glightbox.min.js"></script>
        <script src="assets/vendor/isotope-layout/isotope.pkgd.min.js"></script>
        <script src="assets/vendor/swiper/swiper-bundle.min.js"></script>
        <script src="assets/vendor/php-email-form/validate.js"></script>

        <!-- Template Main JS File -->
        <script src="assets/js/main.js"></script>

    </main>

</body>

</html>
