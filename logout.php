<?php
session_start();

// Hapus session sesuai dengan jenis pengguna
unset($_SESSION['admin']);
unset($_SESSION['siswa']);
unset($_SESSION['guru']);

// Redirect ke halaman login
header("Location: index.php");
exit;
?>
