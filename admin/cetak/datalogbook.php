<?php

require_once __DIR__ . '/../../vendor/autoload.php';

include '../conn.php';

$nama = mysqli_real_escape_string($koneksi, $_POST['Nama_siswa']); 

$query = "SELECT logbook.*, pkl.*, siswa.*
FROM logbook
INNER JOIN pkl ON logbook.id_pkl = pkl.id_pkl
INNER JOIN siswa ON pkl.id_siswa = siswa.id_siswa
WHERE siswa.Nama_siswa = '$nama'";
$result = mysqli_query($koneksi, $query);

if (!$result) {
    die("Error in query: " . mysqli_error($koneksi));
}

// Ambil data siswa
$query_siswa = "SELECT * FROM siswa WHERE Nama_siswa = '$nama'";
$result_siswa = mysqli_query($koneksi, $query_siswa);
$row_siswa = mysqli_fetch_assoc($result_siswa);

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
<h2 style="text-align:center">Data Logbook Siswa Praktik Kerja Lapangan</h2>
<h3 style="text-align:center">SMK Al-Muhajirin</h3>

<table>
    <tr>
        <td>Nama</td>
        <td> : ' . $row_siswa['Nama_siswa'] .'</td>
    </tr>
    <tr>
        <td>NIS</td>
        <td> : '. $row_siswa['NIM'].'</td>
    </tr>
    <tr>
        <td>Kelas</td>
        <td>: '.$row_siswa['kelas'].'</td>
    </tr>
    <tr>
        <td>Tempat PKL</td>
        <td>: '.$row_siswa['tempat_magang'].'</td>
    </tr>
</table>

<table border="1" cellpadding="5" cellspacing="0">
<thead>
     <tr>
        <th>No.</th>
        <th>Nama</th>
        <th>Tanggal</th>
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
            <td>' . $row['aktivitas'] . '</td>
            <td><img src="../../Logbook/' . $row['dokumentasi'] . '" style="max-width: 20%; height: auto;" class="img-responsive"></td>
        </tr>';
    }
    

$html .= '</tbody>
</table>
</body>
</html>';


$mpdf->WriteHTML($html);
$mpdf->Output($nama.'-logbook.pdf', 'I');
