<?php
include('conn.php');

$id_user = isset($_SESSION['id_user']) ? $_SESSION['id_user'] : '';

if (empty($id_user)) {
    header("Location: index.php");
    exit;
}

if (!$koneksi) {
    die("Koneksi database gagal: " . mysqli_connect_error());
}

$query = "SELECT * FROM siswa INNER JOIN user ON siswa.id_user = user.id_user WHERE user.id_user = '$id_user'";
$result = mysqli_query($koneksi, $query);

if (!$result) {
    die("Query Error: " . mysqli_error($koneksi));
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Informasi Data Siswa</title>
    <style>
        table {
            font-family: Arial, sans-serif;
        }
        th, td {
            font-family: Arial, sans-serif;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="tab-content" id="v-pills-tabContent">
            <div class="tab-pane fade show active" id="v-pills-home" role="tabpanel" aria-labelledby="v-pills-home-tab">
                <div class="card-body table-responsive">
                    <h3 class="card-title text-center">Informasi Data Siswa</h3>
                    <table class="table table-sm">
                        <?php
                        while ($row = mysqli_fetch_assoc($result)) {
                            echo "
                                <tr>
                                    <td><strong class='text-primary'>Nama</strong></td>
                                    <td>" . $row['Nama_siswa'] . "</td>
                                </tr>
                                <tr>
                                    <td><strong class='text-primary'>Tempat/Tanggal Lahir</strong></td>
                                    <td>" . date('d F Y', strtotime($row['tanggal_lahir'])) . "</td>
                                </tr>
                                <tr>
                                    <td><strong class='text-primary'>NIS</strong></td>
                                    <td>" . $row['NIS'] . "</td>
                                </tr>
                                <tr>
                                    <td><strong class='text-primary'>Kelas</strong></td>
                                    <td>" . $row['kelas'] . "</td>
                                </tr>
                                <tr>
                                    <td><strong class='text-primary'>Jenis Kelamin</strong></td>
                                    <td>" . $row['jenis_kelamin'] . "</td>
                                </tr>
                                <tr>
                                    <td><strong class='text-primary'>Alamat</strong></td>
                                    <td>" . $row['alamat'] . "</td>
                                </tr>
                                <tr>
                                    <td><strong class='text-primary'>No HP</strong></td>
                                    <td>" . $row['no_hp'] . "</td>
                                </tr>
                                <tr>
                                    <td><strong class='text-primary'>Email</strong></td>
                                    <td>" . $row['email'] . "</td>
                                </tr>";
                        }
                        ?>
                    </table>
                </div>
            </div>
        </div>
    </div>
</body>
</html>
