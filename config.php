<?php

require 'vendor/autoload.php';

// Inisialisasi objek Google_Client
$client = new Google_Client();
$client->setClientId('1052042215210-vtfvc0b720rg09m4l7uhenjej17m8gop.apps.googleusercontent.com');
$client->setClientSecret('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');

// Tambahkan cakupan (scope) untuk Google Drive API
$client->addScope(Google_Service_Drive::DRIVE);

// Inisialisasi objek Google_Service_Drive menggunakan objek klien
$service = new Google_Service_Drive($client);

// Lanjutkan dengan operasi API Drive sesuai kebutuhan Anda
// ...

?>
