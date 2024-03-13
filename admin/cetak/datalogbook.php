<?php

require_once __DIR__ . '/../../vendor/autoload.php';


include '../conn.php';
$nama = mysqli_real_escape_string($koneksi, $_POST['Nama_siswa']); // Prevent SQL injection

$query = "SELECT logbook.*, pkl.*, siswa.*
FROM logbook
INNER JOIN pkl ON logbook.id_pkl = pkl.id_pkl
INNER JOIN siswa ON pkl.id_siswa = siswa.id_siswa
WHERE siswa.Nama_siswa = '$nama'";
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
</head>
<body>
<h2 style="text-align:center">Data Logbook Siswa Praktik Kerja Lapangan</h2>
<h3 style="text-align:center">SMK Al-Muhajirin</h3>
<table border="1" cellpadding="10" cellspacing="0">
<thead>
    <tr>
        <th>No.</th>
        <th>Nama</th>
        <th>Tanggal Kegiatan</th>
        <th>Aktivitas</th>
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
        </tr>';
    }
    

$html .= '</tbody>
</table>
</body>
</html>';


$mpdf->WriteHTML($html);
$mpdf->Output($nama.'-logbook.pdf', 'I');

