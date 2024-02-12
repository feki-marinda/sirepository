<?php
require __DIR__ . '/vendor/autoload.php';
include 'conn.php';

use Google\Client;
use Google\Service\Drive;

function uploadToGoogleDriveAndDatabase($filePath, $folderId)
{
    global $koneksi;

    try {
        // Pastikan session sudah di-start
        session_start();

        $client = new Client();
        putenv('GOOGLE_APPLICATION_CREDENTIALS=./credentials.json');
        $client->useApplicationDefaultCredentials();
        $client->addScope(Drive::DRIVE);
        $driveService = new Drive($client);

        $fileName = basename($filePath);
        $mimeType = mime_content_type($filePath);

        $fileMetadata = new Drive\DriveFile(
            array(
                'name' => $fileName,
                'parents' => $folderId
            )
        );

        $content = file_get_contents($filePath);
        $file = $driveService->files->create($fileMetadata, array(
            'data' => $content,
            'mimeType' => $mimeType,
            'uploadType' => 'multipart',
            'fields' => 'id'
        ));

        $fileId = $file->id;

        // Periksa apakah $_SESSION['username'] sudah terdefinisi
        if (!isset($_SESSION['username'])) {
            throw new Exception("Error: Nama siswa tidak ditemukan di sesi.");
        }

        // Gunakan $_SESSION['username'] pada bind parameter
        $query_insert = "INSERT INTO laporan_pkl (nama_siswa, tanggal_kumpul, berkas, judul_laporan, google_drive_file_id) 
VALUES (?, CURRENT_DATE, ?, ?, ?)";
$stmt_insert = $koneksi->prepare($query_insert);

        if (!$stmt_insert) {
            throw new Exception("Prepare statement error: " . $koneksi->error);
        }

        $judul_laporan = $_POST['judul_laporan']; // Ambil judul laporan dari form

        $stmt_insert->bind_param("ssss", $_SESSION['username'], $fileId, $judul_laporan, $fileId);

        if ($stmt_insert->execute()) {
            echo "File berhasil diunggah dan informasi disimpan ke database.";
        } else {
            echo 'Error: ' . $stmt_insert->error;
        }

        $stmt_insert->close();
        $koneksi->close();

        return $fileId;
    } catch (Exception $e) {
        echo "Error Message: " . $e->getMessage();
    }
}

if (isset($_FILES['fileLaporan'])) {
    $uploadedFile = $_FILES['fileLaporan']['tmp_name'];

    // Pastikan folder_id disesuaikan dengan folder di Google Drive Anda
    $folderId = ['1WmABjy2PW424qM9bRZ4YOqJEdJo7_80z'];

    $fileId = uploadToGoogleDriveAndDatabase($uploadedFile, $folderId);
    echo "File ID: " . $fileId;
} else {
    echo "File tidak ditemukan.";
}
?>
