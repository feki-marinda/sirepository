<?php
include 'conn.php';

$id_user = isset($_SESSION['id_user']) ? $_SESSION['id_user'] : '';

if (empty($id_user)) {
    header("Location: index.php");
    exit;
}
$query_siswa = "SELECT siswa.Nama_siswa, pkl.nama_perusahaan, siswa.NIS FROM siswa 
INNER JOIN user ON user.id_user=siswa.id_user
INNER JOIN pkl ON siswa.id_siswa=pkl.id_siswa WHERE user.id_user = '$id_user'";
$result_siswa = mysqli_query($koneksi, $query_siswa);

if (!$result_siswa) {
    die('Error: ' . mysqli_error($koneksi));
}

$row_siswa = mysqli_fetch_assoc($result_siswa);

if ($row_siswa) {
    $nama_siswa = $row_siswa['Nama_siswa'];
    $tempat_pkl = $row_siswa['nama_perusahaan'];
    $nis = $row_siswa['NIS'];
} else {
    $nama_siswa = '';
    $tempat_pkl = '';
    $nis = '';
}


$nama = isset($_SESSION['username']) ? $_SESSION['username'] : '';

$query = "SELECT indikator.indikator, nilai_pkl.nilai
FROM indikator 
INNER JOIN nilai_pkl ON nilai_pkl.id_indikator = indikator.id_indikator
INNER JOIN siswa ON siswa.id_siswa = nilai_pkl.id_siswa
INNER JOIN user ON user.id_user = siswa.id_user
WHERE user.id_user = '$id_user'";
$result = mysqli_query($koneksi, $query);

if (!$result) {
    die('Error: ' . mysqli_error($koneksi));
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Form Penilaian Praktek Kerja Lapangan</title>
</head>

<body>
    <div class="row mt-3">
    <div class="col-md-2">
        <img src="assets/img/smk.png" alt="" class="ms-4" style="width:250px; height:220px">
    </div>
    <div class="col-md-10">
        <div class="text-center">
            <h5>YAYASAN HIDAYATULLOH AL-MUHAJIRIN</h5>
            <h3>SMKS AL-MUHAJIRIN</h3>
            <p>NSS : 322052905001 | NPSN : 20555424 | Akreditasi : B</p>
            <p>Dsn. Paserean Bawah , Ds. Buduran, Kec. Arosbaya, Kab. Bangkalan.</p>
            <p>Kodepos : 69151 | Telp : 081 737 5464 / 0823 3508 1945</p>
            <p>G-mail : smksalmuhajirin.arosbaya@gmail.com | Website : www.smkalmuhajirin.sch.id</p>
        </div>
    </div>
</div>

        <hr>

        <div class="text-center">
            <h4>Form Penilaian Praktek Kerja Lapangan</h4>
        </div>

        <div class="row">
            <div class="col-4"><strong>Nama</strong></div>
            <div class="col-8"><strong>: <?php echo $nama_siswa ?></strong></div>
        </div>
        <div class="row">
            <div class="col-4"><strong>Tempat PKL</strong></div>
            <div class="col-8"><strong>: <?php echo $tempat_pkl ?></strong></div>
        </div>
        <div class="row">
            <div class="col-4"><strong>NIS</strong></div>
            <div class="col-8"><strong>: <?php echo $nis ?></strong></div>
        </div>
        <div class="row">
            <div class="col-4"><strong>Aspek Penilaian</strong></div>
            <div class="col-8"><strong>: Non Teknis</strong></div>
        </div>
<br>
        <table class="table table-striped">
    <thead class="bg-primary text-white">
        <tr>
            <th scope="col">No</th>
            <th scope="col">Indikator</th>
            <th scope="col">Nilai</th>
        </tr>
    </thead>
    <tbody>
        <?php
        $no = 1;
        while ($row = mysqli_fetch_assoc($result)) {
        ?>
            <tr>
                <td><?php echo $no++; ?></td>
                <td><?php echo $row['indikator']; ?></td>
                <td><?php echo $row['nilai']; ?></td>
            </tr>
        <?php } ?>
    </tbody>
</table>
<br><br>

<div class="d-grid gap-2">
  <button class="btn btn-primary" type="button" onclick="printPage()">Cetak</button>
</div>

<script>
    function printPage() {
        window.location.href = 'admin/cetak/datanilai.php'; // Mengarahkan ke file yang tepat
    }
</script>
<br><br><br>
        </body>

</html>
