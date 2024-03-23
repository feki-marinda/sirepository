<?php

require_once __DIR__ . '/../../vendor/autoload.php';


include '../conn.php';
$query = "SELECT * FROM siswa";
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
    <title>Print-Siswa</title>
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
<h2 style="text-align:center">Data Siswa Praktik Kerja Lapangan</h2>
<h3 style="text-align:center">SMK Al-Muhajirin</h3>
<table border="1" cellpadding="5" cellspacing="0">
<thead>
    <tr>
        <th>No.</th>
        <th>Nama Lengkap</th>
        <th>NIS</th>
        <th>Kelas</th>
        <th>Jenis Kelamin</th>
        <th>Alamat</th>
        <th>Tanggal Lahir</th>
        <th>No Hp</th>
        <th>Email</th>
    </tr>
    </thead>';
    
    $no = 1;
    while ($row = mysqli_fetch_assoc($result)) {
        $html .= '<tr>
            <td>' . $no++ . '</td>
            <td>' . $row['Nama_siswa'] . '</td>
            <td>' . $row['NIS'] . '</td>
        <td>' . $row['kelas'] . '</td>
        <td>' . $row['jenis_kelamin'] . '</td>
        <td>' . $row['alamat'] . '</td>
        <td>' . $row['tanggal_lahir'] . '</td>
        <td>' . $row['no_hp'] . '</td>
        <td>' . $row['email'] . '</td>
        </tr>';
    }
    
    $html .= '</tbody>
</table>
</body>
</html>';

$mpdf->WriteHTML($html);
$mpdf->Output('Daftar-PKL-Siswa.pdf', 'I');

