<?php

require __DIR__ . '/vendor/autoload.php';

use Google\Client;
use Google\Service\Drive;

function uploadToGoogleDrive($filePath, $fileName)
{
    try {
        $client = new Client();
        putenv('GOOGLE_APPLICATION_CREDENTIALS=./credentials.json');
        $client->useApplicationDefaultCredentials();
        $client->addScope(Drive::DRIVE);
        $driveService = new Drive($client);

        $fileMetadata = new Google_Service_Drive_DriveFile(array(
            'name' => $fileName,
            'parents' => ['1qfiPKFujNlkT0E0YuftIAJi14zIuI45A']
        ));

        $content = file_get_contents($filePath);
        $file = $driveService->files->create($fileMetadata, array(
            'data' => $content,
            'mimeType' => mime_content_type($filePath),
            'uploadType' => 'multipart',
            'fields' => 'id'
        ));

        return $file->id;
    } catch (Exception $e) {
        return "Error: " . $e->getMessage();
    }
}
