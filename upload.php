<?php
require __DIR__.'/vendor/autoload.php';

use Google\Client;
use Google\Service\Drive;

function uploadBasic()
{
    try {
        $client = new Client();
        putenv('GOOGLE_APPLICATION_CREDENTIALS=./credentials.json');
        $client->useApplicationDefaultCredentials();
        $client->addScope(Drive::DRIVE);
        $driveService = new Drive($client);

        $file=getcwd().'/okee.png';
        $fileName = basename($file);
        $mimeType = mime_content_type($file);

        $fileMetadata = new Drive\DriveFile(array(
        'name' => $fileName,
        'parents' => ['1WmABjy2PW424qM9bRZ4YOqJEdJo7_80z']
    ));
        $content = file_get_contents($file);
        $file = $driveService->files->create($fileMetadata, array(
            'data' => $content,
            'mimeType' => $mimeType,
            'uploadType' => 'multipart',
            'fields' => 'id'));
        printf("File ID: %s\n", $file->id);
        return $file->id;
    } catch(Exception $e) {
        echo "Error Message: ".$e;
    } 

}
uploadBasic();