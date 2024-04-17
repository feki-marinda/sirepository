<?php

require_once __DIR__ . '/../../vendor/autoload.php';

include '../conn.php';
$nama = mysqli_real_escape_string($koneksi, $_POST['Nama_siswa']); 

$query = "SELECT sertifikat.file_sertifikat 
          FROM sertifikat 
          INNER JOIN siswa ON siswa.id_siswa = sertifikat.id_siswa 
          INNER JOIN user ON user.id_user = siswa.id_user 
          WHERE siswa.Nama_siswa = '$nama'";
$result = mysqli_query($koneksi, $query);

if (!$result) {
    die("Error in query: " . mysqli_error($koneksi));
}

$mpdf = new \Mpdf\Mpdf();
$html = '<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Print-Logbook</title>
</head>
<body>';

while ($row = mysqli_fetch_assoc($result)) {
    $html .= '<div class="ratio ratio-16x9 mt-3">
    <img src="admin/Sertifikat/' . $row["file_sertifikat"] . '" width="150" height="130">
  </div>';
}

$html .= '</body>
</html>';

$mpdf->WriteHTML($html);
$mpdf->Output($nama . '-sertifikat.pdf', 'I');


?>
