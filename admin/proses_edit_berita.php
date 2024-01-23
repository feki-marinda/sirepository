<?php
include 'conn.php';

// Pastikan form telah dikirimkan
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Ambil data dari form
    $id_berita = $_POST['id_berita'];
    $judul = $_POST['judul'];
    $isi_berita = $_POST['isi_berita'];
    $tanggal = $_POST['tanggal'];
    $gambar_lama = $_POST['gambar_lama'];

    // Proses upload gambar baru jika ada
    if ($_FILES['gambar']['name']) {
        $nama_file = $_FILES['gambar']['name'];
        $ukuran_file = $_FILES['gambar']['size'];
        $tmp_file = $_FILES['gambar']['tmp_name'];

        // Set folder tempat menyimpan gambar
        $folder = "uploads/";

        // Pindahkan gambar ke folder uploads
        move_uploaded_file($tmp_file, $folder . $nama_file);

        // Hapus gambar lama jika berhasil upload gambar baru
        unlink($folder . $gambar_lama);

        // Update data berita dengan gambar baru
        $query = "UPDATE berita SET judul='$judul', isi_berita='$isi_berita', tanggal='$tanggal', gambar='$nama_file' WHERE id_berita='$id_berita'";
    } else {
        // Update data berita tanpa mengubah gambar
        $query = "UPDATE berita SET judul='$judul', isi_berita='$isi_berita', tanggal='$tanggal' WHERE id_berita='$id_berita'";
    }

    // Eksekusi query
    $result = $koneksi->query($query);

    if ($result) {
        // Jika berhasil, kembalikan status success
        echo "success";
    } else {
        // Jika gagal, kembalikan pesan error
        echo "Error: " . $koneksi->error;
    }
}
?>
