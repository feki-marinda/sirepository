<?php
include 'conn.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $id_berita = $_POST['id_berita'];
    $judul = $_POST['judul'];
    $isi_berita = $_POST['isi_berita'];
    $tanggal = $_POST['tanggal'];
    $gambar_lama = $_POST['gambar_lama'];

    if ($_FILES['gambar']['name']) {
        $nama_file = $_FILES['gambar']['name'];
        $ukuran_file = $_FILES['gambar']['size'];
        $tmp_file = $_FILES['gambar']['tmp_name'];

        $folder = "uploads/";

        move_uploaded_file($tmp_file, $folder . $nama_file);

        unlink($folder . $gambar_lama);

        $query = "UPDATE berita SET judul='$judul', isi_berita='$isi_berita', tanggal='$tanggal', gambar='$nama_file' WHERE id_berita='$id_berita'";
    } else {
        $query = "UPDATE berita SET judul='$judul', isi_berita='$isi_berita', tanggal='$tanggal' WHERE id_berita='$id_berita'";
    }

    $result = $koneksi->query($query);

    if ($result) {
        echo "success";
    } else {
        echo "Error: " . $koneksi->error;
    }
}
?>
