<?php
include 'conn.php';

function registrasi_guru($data){
    global $koneksi;

    $username = mysqli_real_escape_string($koneksi, stripcslashes($data["username"]));
    $password = mysqli_real_escape_string($koneksi, $data["password"]);
    $nama = mysqli_real_escape_string($koneksi, $data["nama"]);
    $nip = mysqli_real_escape_string($koneksi, $data["NIP"]);
    $email = mysqli_real_escape_string($koneksi, $data["Email"]);
    $alamat = mysqli_real_escape_string($koneksi, $data["Alamat"]);
    $no_telp = mysqli_real_escape_string($koneksi, $data["no_telp"]);
    $status = mysqli_real_escape_string($koneksi, $data["status"]);

    // Query untuk insert data ke tabel 'user'
    $query_user = "INSERT INTO user (username, password, status) VALUES ('$username', '$password','$status')";

    if (mysqli_query($koneksi, $query_user)) {
        $user_id = mysqli_insert_id($koneksi);

        // Query untuk insert data ke tabel 'guru_pamong'
        $query_guru = "INSERT INTO guru_pamong (id_user, nama, NIP, Email, Alamat, no_telp) 
        VALUES ('$user_id', '$nama', '$nip', '$email', '$alamat', '$no_telp')";

        if (mysqli_query($koneksi, $query_guru)) {
            return true;
        } else {
            echo "Pendaftaran gagal untuk guru. Error: " . mysqli_error($koneksi);
            return false;
        }
    } else {
        echo "Pendaftaran gagal untuk pengguna. Error: " . mysqli_error($koneksi);
        return false;
    }
}
?>
