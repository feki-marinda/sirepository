<?php

require_once __DIR__ . '/../../vendor/autoload.php';


include '../conn.php';
$query = "SELECT
siswa.id_siswa,
nilai_pkl.id_nilai,
siswa.Nama_siswa,
nilai_pkl.nilai,
nilai_pkl.grade,
nilai_pkl.file,
pkl.nama_perusahaan
FROM
nilai_PKL
INNER JOIN siswa ON nilai_pkl.id_siswa = siswa.id_siswa
INNER JOIN pkl ON siswa.id_siswa = pkl.id_siswa;
";
$result = $koneksi->query($query);
if (!$result) {
    die("Error: " . $koneksi->error);
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
        <th>Tampat Magang</th>
        <th>Nilai</th>
        <th>Grade</th>
    </tr>
    </thead>';
    
    $no = 1;
    while ($row = mysqli_fetch_assoc($result)) {
        $html .= '<tr>
            <td>' . $no++ . '</td>
            <td>' . $row['Nama_siswa'] . '</td>
            <td>' . $row['nama_perusahaan'] . '</td>
            <td>' . $row['nilai'] . '</td>
            <td>' . $row['grade'] . '</td>
        </tr>';
    }
    

$html .= '</tbody>
</table>
</body>
</html>';


$mpdf->WriteHTML($html);
$mpdf->Output($nama.'-logbook.pdf', 'I');

