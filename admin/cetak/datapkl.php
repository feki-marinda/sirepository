<?php

require_once __DIR__ . '/../../vendor/autoload.php';


include '../conn.php';
$query = "SELECT siswa.Nama_siswa, pkl.id_pkl, pkl.id_siswa, pkl.tgl_mulai, pkl.tgl_selesai, pkl.kelas, pkl.nama_perusahaan, pkl.tahun_pelajaran FROM pkl INNER JOIN siswa ON siswa.id_siswa = pkl.id_siswa";
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
<h2 style="text-align:center">Data Praktik Kerja Lapangan</h2>
<h3 style="text-align:center">SMK Al-Muhajirin</h3>
<table border="1" cellpadding="10" cellspacing="0">
<thead>
    <tr>
        <th>No.</th>
        <th>Nama Lengkap</th>
        <th>Tanggal Mulai</th>
        <th>Tanggal Selesai</th>
        <th>Kelas</th>
        <th>Nama Perusahaan</th>
        <th>Tahun Pelajaran</th>
    </tr>
    </thead>';
    
    $no = 1;
    while ($row = mysqli_fetch_assoc($result)) {
        $html .= '<tr>
            <td>' . $no++ . '</td>
            <td>' . $row['Nama_siswa'] . '</td>
            <td>' . $row['tgl_mulai'] . '</td>
        <td>' . $row['tgl_selesai'] . '</td>
        <td>' . $row['kelas'] . '</td>
        <td>' . $row['nama_perusahaan'] . '</td>
        <td>' . $row['tahun_pelajaran'] . '</td>
        </tr>';
    }
    
    $html .= '</tbody>
</table>
</body>
</html>';

$mpdf->WriteHTML($html);
$mpdf->Output('Data-PKL.pdf', 'I');
