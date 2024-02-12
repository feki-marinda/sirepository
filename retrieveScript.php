<?php
// retrieveScript.php

function getLoggedInUserName() {
    // Implementasikan cara Anda untuk mendapatkan nama siswa yang sedang login
    // Misalnya menggunakan session, cookie, atau otentikasi lainnya
    // Fungsi ini harus mengembalikan nama siswa atau NULL jika tidak ada siswa yang sedang login

    // Contoh menggunakan session (sesuaikan dengan cara implementasi otentikasi Anda):
    session_start();
    if (isset($_SESSION['username'])) {
        return $_SESSION['username'];
    } else {
        return NULL;
    }
}
?>
