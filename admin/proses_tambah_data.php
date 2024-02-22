<?php
include 'conn.php';

$judul = $_POST['judul'];
$isi_berita = $_POST['isi_berita'];
$tanggal = $_POST['tanggal'];


$query = "INSERT INTO berita (judul, isi_berita, tanggal, gambar) 
          VALUES ('$judul', '$isi_berita', '$tanggal', '$gambar')";

if ($koneksi->query($query) === TRUE) {
    header('Location: tambahberita.php');
    exit;
} else {
    echo 'Error: ' . $koneksi->error;
}

$koneksi->close();
?>
