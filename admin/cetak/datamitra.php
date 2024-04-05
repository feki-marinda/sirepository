<?php

require_once __DIR__ . '/../../vendor/autoload.php';

include '../conn.php';

$query = "SELECT * FROM mitra";
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
    <title>Data Siswa Praktik Kerja Lapangan</title>
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
<h2 style="text-align:center">Data Mitra Praktik Kerja Lapangan</h2>
<h3 style="text-align:center">SMK Al-Muhajirin</h3>
<table border="1" cellpadding="5" cellspacing="0">
<thead>
    <tr>
        <th>No.</th>
        <th>Nama Perusahaan</th>
        <th>Alamat</th>
        <th>Kontak</th>
        <th>Foto</th>
    </tr>
    </thead>';

$no = 1;
while ($row = mysqli_fetch_assoc($result)) {
    $html .= '<tr>
        <td>' . $no++ . '</td>
        <td>' . $row['nama'] . '</td>
        <td>' . $row['alamat'] . '</td>
        <td>' . $row['kontak'] . '</td>
        <td><img src="../../gambar/' . $row['foto'] . '" width="140" height="150"></td>
    </tr>';
}

$html .= '</tbody>
</table>
</body>
</html>';

$mpdf->WriteHTML($html);

// Output as a downloadable PDF file
$mpdf->Output('Data-mitra.pdf', 'I');
exit();
?>
