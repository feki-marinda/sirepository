<?php

require_once __DIR__ . '/../../vendor/autoload.php';


include '../conn.php';
$query = "SELECT * FROM guru_pamong";
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
    <title>Print-Guru</title>
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
<h3 style="text-align:center">Data Guru Pamong Praktik Kerja Lapangan</h3>
<h4 style="text-align:center">SMK Al-Muhajirin</h4>
<table border="1" cellpadding="5" cellspacing="0">
<thead>
    <tr>
    <th>No.</th>
    <th>Foto</th>
    <th>Nama Lengkap</th>
    <th>NIP</th>
    <th>Email</th>
    <th>Alamat</th>
    <th>No Hp</th>
    </tr>
    </thead>';
    
    $no = 1;
    while ($row = mysqli_fetch_assoc($result)) {
        $html .= '<tr>
    <td>' . $no++ . '</td>
    <td><img src="../gambar/' . $row['Foto'] . '" width="120" height="150"></td>
    <td>' . $row['nama'] . '</td>
    <td>' . $row['NIP'] . '</td>
    <td>' . $row['Email'] . '</td>
    <td>' . $row['Alamat'] . '</td>
    <td>' . $row['no_telp'] . '</td>
</tr>';

    }
    
    $html .= '</tbody>
</table>
</body>
</html>';

$mpdf->WriteHTML($html);
$mpdf->Output('Data-GuruPamong.pdf', 'I');

