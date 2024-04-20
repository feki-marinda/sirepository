<?php
session_start(); 
require_once __DIR__ . '/../../vendor/autoload.php';
include '../conn.php';

$id_user = isset($_SESSION['id_user']) ? $_SESSION['id_user'] : '';

if (empty($id_user)) {
    header("Location: index.php");
    exit;
}

$username = isset($_SESSION['username']) ? $_SESSION['username'] : '';

$query = "SELECT
            siswa.Nama_siswa,
            siswa.NIS,
            siswa.kelas,
            pkl.nama_perusahaan,
            logbook.tanggal,
            logbook.aktivitas,
            logbook.dokumentasi,
            logbook.status_logbook
        FROM
            user
        JOIN siswa ON user.id_user = siswa.id_user
        JOIN pkl ON siswa.id_siswa = pkl.id_siswa
        JOIN logbook ON siswa.id_siswa = logbook.id_siswa
        WHERE user.username = '$username';";

$result = mysqli_query($koneksi, $query);

if (!$result) {
    die("Error in query: " . mysqli_error($koneksi));
}

// Generate PDF
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
<h3 style="text-align:center">SMK Al-Muhajirin</h3>';

// Informasi siswa
$row = mysqli_fetch_assoc($result); // Ambil baris pertama
$html .= '
<table>
    <tr>
        <td>Nama</td>
        <td> : ' . $row['Nama_siswa'] . '</td>
    </tr>
    <tr>
        <td>NIS</td>
        <td> : '. $row['NIS'].'</td>
    </tr>
    <tr>
        <td>Kelas</td>
        <td>: '.$row['kelas'].'</td>
    </tr>
    <tr>
        <td>Tempat PKL</td>
        <td>: '.$row['nama_perusahaan'].'</td>
    </tr>
    
</table>';

$html .= '
<table border="1" cellpadding="3" cellspacing="0">
<thead>
    <tr>
        <th>No.</th>
        <th>Tanggal <br>Kegiatan</th>
        <th>Aktivitas</th>
        <th>Dokumentasi</th>
    </tr>
    </thead>';

if (mysqli_num_rows($result) > 0) {
    $no = 1;
    do {
        $formattedDate = date('d/m/Y', strtotime($row['tanggal']));
        $html .= '<tr>
            <td>' . $no++ . '</td>
            <td>' . $formattedDate . '</td>
            <td style="text-align: justify;">' . $row['aktivitas'] . '</td>
            <td><img src="../../Logbook/' . $row['dokumentasi'] . '" style="max-width: 20%; height: auto;" class="img-responsive"></td>
        </tr>';
    } while ($row = mysqli_fetch_assoc($result));
} else {
    $html .= '<tr><td colspan="5">Tidak ada data logbook yang ditemukan.</td></tr>';
}

$html .= '</tbody>
</table>
</body>
</html>';

$mpdf->WriteHTML($html);
$mpdf->Output('siswalogbook.pdf', 'I');
?>
