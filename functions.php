<?php
session_start();

function isUserLoggedIn() {
    return isset($_SESSION['username']);
}

function getLoggedInUserName() {
    return isset($_SESSION['username']) ? $_SESSION['username'] : null;
}

if (!isUserLoggedIn()) {
    header('Location: login.php'); 
    exit;
}
?>
