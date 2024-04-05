<?php
session_start(); // Pastikan session telah dimulai sebelum menggunakan $_SESSION

require_once __DIR__ . '/../../vendor/autoload.php';
include '../conn.php';

$id_user = isset($_SESSION['id_user']) ? $_SESSION['id_user'] : '';

// Ambil nama siswa berdasarkan id_user
$query_nama_siswa = "SELECT Nama_siswa FROM siswa WHERE id_user = '$id_user'";
$result_nama_siswa = mysqli_query($koneksi, $query_nama_siswa);
if (!$result_nama_siswa) {
    die("Error in query: " . mysqli_error($koneksi));
}
$row_nama_siswa = mysqli_fetch_assoc($result_nama_siswa);
$nama_siswa = $row_nama_siswa['Nama_siswa'];

$query = "SELECT logbook.*, pkl.*, siswa.*
FROM logbook
INNER JOIN pkl ON logbook.id_pkl = pkl.id_pkl
INNER JOIN siswa ON pkl.id_siswa = siswa.id_siswa
WHERE siswa.Nama_siswa = '$nama_siswa'";

$result = mysqli_query($koneksi, $query);

if (!$result) {
    die("Error in query: " . mysqli_error($koneksi));
}

$mpdf = new \Mpdf\Mpdf();
$html = '
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Print-Logbook</title>
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
    </style>
</head>
<body>
<h2 style="text-align:center">Logbook Siswa Praktik Kerja Lapangan</h2>
<h3 style="text-align:center">SMK Al-Muhajirin</h3>
<table border="1" cellpadding="5" cellspacing="0">
<thead>
    <tr>
        <th>No.</th>
        <th>Nama</th>
        <th>Tanggal <br>Kegiatan</th>
        <th>Aktivitas</th>
        <th>Dokumentasi</th>
    </tr>
    </thead>';
    
    $no = 1;
    while ($row = mysqli_fetch_assoc($result)) {
        $formattedDate = date('d/m/Y', strtotime($row['tanggal']));
        $html .= '<tr>
            <td>' . $no++ . '</td>
            <td>' . $row['Nama_siswa'] . '</td>
            <td>' . $formattedDate . '</td>
            <td style="text-align: justify;">' . $row['aktivitas'] . '</td>
            <td><img src="../../Logbook/' . $row['dokumentasi'] . '" style="max-width: 20%; height: auto;" class="img-responsive"></td>
        </tr>';
    }
    

$html .= '</tbody>
</table>
</body>
</html>';

$mpdf->WriteHTML($html);
$mpdf->Output($nama_siswa.'-siswalogbook.pdf', 'I');
?>
