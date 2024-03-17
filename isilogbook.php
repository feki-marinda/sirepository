<?php
session_start();
include('conn.php');

$id_user = isset($_SESSION['id_user']) ? $_SESSION['id_user'] : '';

if (empty($id_user)) {
    header("Location: index.php");
    exit;
}

$username = isset($_SESSION['username']) ? $_SESSION['username'] : '';

$query_get_id = "SELECT siswa.id_siswa, pkl.id_pkl FROM siswa 
    INNER JOIN user ON siswa.id_user = user.id_user
    INNER JOIN pkl ON siswa.id_siswa = pkl.id_siswa
    WHERE user.username = ? LIMIT 1;"; // Menggunakan placeholder pada query

$id_result = $koneksi->prepare($query_get_id);
$id_result->bind_param("s", $username); // Mengikat variabel untuk keamanan
$id_result->execute();
$id_result->store_result();

if ($id_result->num_rows > 0) {
    $id_result->bind_result($id_siswa, $id_pkl);
    $id_result->fetch();

    $_SESSION['id_siswa'] = $id_siswa;
    $_SESSION['id_pkl'] = $id_pkl;
} else {
    $id_siswa = -1; // atau null, tergantung kebutuhan
}

$Nama_siswa = '';
$id_siswa = isset($_SESSION['id_siswa']) ? $_SESSION['id_siswa'] : '';
$id_pkl = isset($_SESSION['id_pkl']) ? $_SESSION['id_pkl'] : '';

$query_get_nama = $koneksi->prepare("SELECT siswa.Nama_siswa FROM siswa WHERE siswa.id_siswa = ?");
$query_get_nama->bind_param("s", $id_siswa);

if ($query_get_nama->execute()) {
    $result_nama = $query_get_nama->get_result();

    if ($result_nama->num_rows > 0) {
        $row_nama = $result_nama->fetch_assoc();
        $Nama_siswa = $row_nama['Nama_siswa'];
    } else {
        $Nama_siswa = 'Nama Siswa Tidak Ditemukan';
    }
} else {
    echo "Error executing query: " . $query_get_nama->error;
    exit();
}

$show_modal = false;

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $tanggal = $_POST['tanggal'];
    $aktivitas = $_POST['aktivitas'];

    // Query untuk memeriksa logbook pada tanggal tertentu
    $check_query = $koneksi->prepare("SELECT * FROM logbook WHERE tanggal = ? AND id_siswa = ? AND id_pkl = ?");
    $check_query->bind_param("sss", $tanggal, $id_siswa, $id_pkl);
    $check_query->execute();
    $result = $check_query->get_result();


    // Periksa hasil query
    if ($result !== false) {
        if ($result->num_rows > 0) {
            $show_modal = true;
        } else {
            $insert_query = $koneksi->prepare("INSERT INTO logbook (tanggal, aktivitas, id_siswa, id_pkl) VALUES (?, ?, ?, ?)");
            $insert_query->bind_param("ssss", $tanggal, $aktivitas, $id_siswa, $id_pkl);

            // Eksekusi query INSERT
            if ($insert_query->execute()) {
                header("Location: logbook.php");
                exit();
            } else {
                echo "Error: Anda Belum Daftar PKL ! Daftar Terlebih Dahulu ";
                exit();
            }
        }
    } else {
        echo "Error executing query: " . $koneksi->error;
        exit();
    }
}
?>



<!DOCTYPE html>
<html lang="en">

<?php include 'head.html' ?>

<body>

    <?php include 'header_siswa.php' ?>

    <main id="main">

        <section class="breadcrumbs">
            <div class="container">

                <ol>
                    <li><a href="index.php">Home</a></li>
                    <li><a href="logbook.php">Logbook</a></li>
                    <li>Ungah Logbook</li>
                </ol>
                <h2>
                    <?php
                    // Periksa session nama sudah ada
                    if (isset($_SESSION['username'])) {
                        $Nama_siswa = $_SESSION['username'];
                        echo '<h2>Hallo ' . $Nama_siswa . '</h2>';
                    } else {
                        echo '<h2>Hallo</h2>';
                    }
                    ?>
                </h2>

            </div>
        </section>

        <style>
            form {
                font-family: Arial, sans-serif;
            }
        </style>

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
                    <input type="hidden" name="id_pkl" value="<?php echo $_SESSION['id_pkl']; ?>">
                    <div class="col-3 mb-3">
                        <label for="exampleFormControlInput1" class="form-label fw-bold">Tanggal Kegiatan</label>
                        <input type="date" class="form-control" id="exampleFormControlInput1" name="tanggal" required>
                    </div>
                    <div class="mb-3">
                        <label for="exampleFormControlTextarea1" class="form-label fw-bold">Deskripsikan
                            Aktivitasmu</label>
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

        <script src="assets/js/main.js"></script>

    </main>

</body>

</html>