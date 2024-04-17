<?php
require_once __DIR__ . '/../../vendor/autoload.php';
session_start();
include '../conn.php';

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
$nama_siswa = $row_siswa['Nama_siswa'];
$tempat_pkl = $row_siswa['nama_perusahaan'];
$nis = $row_siswa['NIS'];

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

$html = '
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Form Penilaian Praktek Kerja Lapangan</title>
</head>
<style>
        @media print {
            table {
                width: 100%;
            }
        }
        th {
            background-color: #04AA6D;
            color: white;
          }

          .right {
            text-align: right;
          }

    </style>
<body>

<table>
<tr>
<td><img src="../assets/img/smk.png" alt="" style="width: 180px; height: 150px;"></td>
<td style="text-align: center;">
<h5>YAYASAN HIDAYATULLOH AL-MUHAJIRIN</h5>
        <h3>SMKS AL-MUHAJIRIN</h3>
        <p>NSS : 322052905001 | NPSN : 20555424 | Akreditasi : B</p>
        <p>Dsn. Paserean Bawah , Ds. Buduran, Kec. Arosbaya, Kab. Bangkalan.</p>
        <p>Kodepos : 69151 | Telp : 081 737 5464 / 0823 3508 1945</p>
        <p>G-mail : smksalmuhajirin.arosbaya@gmail.com | Website : www.smkalmuhajirin.sch.id</p>
</td>
</tr>
</table>
<hr>

<div style="text-align: center;">
    <h4 >Form Penilaian Praktek Kerja Lapangan</h4>
</div>

<table>
<tr>
<td><strong>Nama</strong> </td>
<td><strong>: ' . $nama_siswa . '</strong></td>
</tr>
<tr>
<td><strong>Tempat PKL</strong></td>
<td><strong>: ' . $tempat_pkl . '</strong></td>
</tr>
<tr>
<td><strong>NIS</strong></td>
<td><strong>: ' . $nis . '</strong></td>
</tr>
<tr>
<td><strong>Aspek Penilaian</strong></td>
<td><strong>: Non Teknis</strong></td>
</tr>
</table>

<br>
<table class="table table-striped" border="1" cellpadding="5" cellspacing="0">
    <thead class="bg-primary text-white">
        <tr>
            <th scope="col">No</th>
            <th scope="col">Indikator</th>
            <th scope="col">Nilai</th>
        </tr>
    </thead>
    <tbody>';

$no = 1;
while ($row = mysqli_fetch_assoc($result)) {
    $html .= '
        <tr>
            <td>' . $no++ . '</td>
            <td>' . $row['indikator'] . '</td>
            <td>' . $row['nilai'] . '</td>
        </tr>';
}

$html .= '
    </tbody>
</table>

<div>
    <strong>
        <div class="right">
            
            <p>Bangkalan, ' . $tanggal_sekarang . $tanggal_sekarang = date("d M Y") . '</p>
            <p>Guru Pamong</p>
        </div>
        
        <br><br>
    </strong>
</div>
</body>
</html>';





$mpdf = new \Mpdf\Mpdf();
$mpdf->WriteHTML($html);
$mpdf->Output($nama_siswa . '-logbook.pdf', 'I');
?>
