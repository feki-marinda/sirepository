<?php
session_start();
include('conn.php');

$id_user = isset($_SESSION['id_user']) ? $_SESSION['id_user'] : '';

if (empty($id_user)) {
    header("Location: index.php");
    exit;
}

$query_get_id = "SELECT siswa.id_siswa, pkl.id_pkl FROM siswa 
    INNER JOIN user ON siswa.id_user = user.id_user
    INNER JOIN pkl ON siswa.id_siswa = pkl.id_siswa
    WHERE user.id_user = ? LIMIT 1;"; 

$id_result = $koneksi->prepare($query_get_id);
$id_result->bind_param("s", $id_user); 
$id_result->execute();
$id_result->store_result();

if ($id_result->num_rows > 0) {
    $id_result->bind_result($id_siswa, $id_pkl);
    $id_result->fetch();

    $_SESSION['id_siswa'] = $id_siswa;
    $_SESSION['id_pkl'] = $id_pkl;
} else {
    $id_siswa = -1; 
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

$query = "SELECT * FROM logbook";
$result = mysqli_query($koneksi, $query);
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $tanggal = $_POST['tanggal'];
    $aktivitas = $_POST['aktivitas'];
    $dokumentasi = $_FILES['dokumentasi']['name'];
    $status = $_POST['status_logbook'];

    $rand = rand();
    $ekstensi = array('png', 'jpg', 'jpeg', 'gif', 'webp');
    $ukuran = $_FILES['dokumentasi']['size'];
    $ext = pathinfo($dokumentasi, PATHINFO_EXTENSION);

    $check_query = $koneksi->prepare("SELECT * FROM logbook WHERE tanggal = ? AND id_siswa = ? AND id_pkl = ? AND dokumentasi = ?");
    $check_query->bind_param("ssss", $tanggal, $id_siswa, $id_pkl, $dokumentasi);
    $check_query->execute();
    $result = $check_query->get_result();

    if ($result !== false) {
        if ($result->num_rows > 0) {
            $show_modal = true;
        } else {
            if (!in_array($ext, $ekstensi)) {
                echo "Error: Jenis file tidak didukung.";
            } else {
                if ($ukuran < 208815000) {
                    $xx = $rand . '_' . $dokumentasi;
                    move_uploaded_file($_FILES['dokumentasi']['tmp_name'], 'Logbook/' . $xx);
        
                    $insert_query = $koneksi->prepare("INSERT INTO logbook (tanggal, aktivitas, dokumentasi, id_siswa, id_pkl, status_logbook) VALUES (?, ?, ?, ?, ?, ?)");
                    $insert_query->bind_param("ssssss", $tanggal, $aktivitas, $xx, $id_siswa, $id_pkl, $status);

                    if ($insert_query->execute()) {
                        header("Location: logbook.php");
                        exit();
                    } else {
                        echo "Error: Gagal menambahkan entri logbook.";
                        exit();
                    }
                } else {
                    echo "Error: Ukuran file terlalu besar.";
                    exit();
                }
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
                    <li><a href="home.php">Home</a></li>
                    <li><a href="logbook.php">Logbook</a></li>
                    <li>Unggah Logbook</li>
                </ol>
                <h2>
                    SMK Al-Muhajirin
                </h2>

            </div>
        </section>

        <style>
            form {
                font-family: Arial, sans-serif;
            }
        </style>

        <div class="container">
            <div class="row  ms-3 mb-3 pb-5 pt-5 ps-5 pe-5 rounded shadow d-flex" style="background-color: #F0F8FF;">
                <div class="col-md-9">

                    <h1 class="font-weight-bold text-left" style="font-size: 2.5rem; color: #333;">
                        Bagaimana <span style="color: #FFD700;">Kegiatanmu</span> Hari ini ?
                    </h1><br>
                </div>
                </div>
                
            <div class="row ms-3 pb-5 pt-5 ps-5 pe-5 rounded shadow d-flex">
            <form method="post" action="#" enctype="multipart/form-data">
                    <input type="hidden" name="id_siswa" value="<?php echo $_SESSION['id_siswa']; ?>">
                    <input type="hidden" name="id_pkl" value="<?php echo $_SESSION['id_pkl']; ?>">
                    <div class="d-flex">
                        <div class="col-6 mb-3 me-3">
                            <label for="tanggal" class="form-label fw-bold">Tanggal Kegiatan</label>
                            <input type="date" class="form-control" id="tanggal" name="tanggal" required>
                        </div>
                        <div class="col-6 mb-3 ">
                            <label for="dokumentasi" class="form-label fw-bold">Dokumentasi</label>
                            <input type="file" class="form-control" id="dokumentasi" name="dokumentasi" required>
                        </div>
                    </div>
                    <div class="mb-3">
                        <label for="aktivitas" class="form-label fw-bold">Deskripsikan
                            Aktivitasmu</label>
                        <textarea class="form-control" id="aktivitas" rows="3" placeholder="Deskripsikan Kegiatanmu"
                            name="aktivitas" required></textarea>
                    </div>
                    <div class="mb-3">
                        <input id="status_logbook" name="status_logbook" Value="terkirim" hidden>
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
