<?php
require __DIR__ . '/vendor/autoload.php';

use Google\Client;
use Google\Service\Drive;

function editGoogleDriveFile($googleDriveFileId, $berkas)
{
    try {
        // Buat koneksi ke Google Drive API
        $client = new Client();
        putenv('GOOGLE_APPLICATION_CREDENTIALS=./credentials.json');
        $client->useApplicationDefaultCredentials();
        $client->addScope(Drive::DRIVE);
        $driveService = new Drive($client);

        $fileMetadata = new Google_Service_Drive_DriveFile(array(
            'name' => $berkas,
            'parents' => ['1t4miHgS9QTm8YA5wjOvmIpCPhauE-YPw']
        ));
        // Baca isi file lokal
        $content = file_get_contents($googleDriveFileId);
        $file = $driveService->files->create($fileMetadata, array(
            'data' => $content,
            'mimeType' => mime_content_type($googleDriveFileId),
            'uploadType' => 'multipart',
            'fields' => 'id'
        ));

        return $file->id;
    } catch (Exception $e) {
        return "Error: " . $e->getMessage();
    }
}
?>