<?php

require_once __DIR__ . '/../../vendor/autoload.php';


include '../conn.php';
$nama = mysqli_real_escape_string($koneksi, $_POST['Nama_siswa']);
$query = "SELECT * FROM laporan_pkl JOIN siswa ON siswa.id_siswa=laporan_pkl.id_siswa";
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
    <title>Document</title>
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
<h2 style="text-align:center">Data Laporan PKL Siswa Praktik Kerja Lapangan</h2>
<h3 style="text-align:center">SMK Al-Muhajirin</h3>
<table border="1" cellpadding="5" cellspacing="0">
<thead>
<tr>
<th>No.</th>
<th>Nama Lengkap</th>
<th>Tanggal Pengupulan</th>
<th>status</th>
</tr>
    </thead>';

$no = 1;
while ($row = mysqli_fetch_assoc($result)) {
    $formattedDate = date('d/m/Y', strtotime($row['tanggal_kumpul']));
    $html .= '<tr>
            <td>' . $no++ . '</td>
            <td>' . $row['Nama_siswa'] . '</td>
            <td>' . $formattedDate . '</td>
            <td>' . $row['status'] . '</td>
        </tr>';
}


$html .= '</tbody>
</table>
</body>
</html>';


$mpdf->WriteHTML($html);
$mpdf->Output($nama . '-laporan.pdf', 'I');

