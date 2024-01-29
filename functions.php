<?php
session_start();

// Fungsi untuk memeriksa apakah pengguna sudah login
function isUserLoggedIn() {
    return isset($_SESSION['username']);
}

// Fungsi untuk mendapatkan nama siswa yang login
function getLoggedInUserName() {
    return isset($_SESSION['username']) ? $_SESSION['username'] : null;
}

// Jika pengguna belum login, arahkan ke halaman login
if (!isUserLoggedIn()) {
    header('Location: login.php'); // Ganti 'login.php' dengan halaman login yang sebenarnya
    exit;
}
?>
