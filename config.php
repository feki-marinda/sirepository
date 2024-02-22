<?php

require 'vendor/autoload.php';

$client = new Google_Client();
$client->setClientId('1052042215210-vtfvc0b720rg09m4l7uhenjej17m8gop.apps.googleusercontent.com');
$client->setClientSecret('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');

$client->addScope(Google_Service_Drive::DRIVE);

$service = new Google_Service_Drive($client);

?>
