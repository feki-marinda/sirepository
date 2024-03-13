<?php
include 'conn.php';

function registrasi($data){
    global $koneksi;

    $username = mysqli_real_escape_string($koneksi, stripcslashes($data["username"]));
    $password = mysqli_real_escape_string($koneksi, $data["password"]);
    $confirm_password = mysqli_real_escape_string($koneksi, $data["confirm_password"]);
    $nama = mysqli_real_escape_string($koneksi, $data["Nama_siswa"]);
    $NIS = mysqli_real_escape_string($koneksi, $data["NIS"]);
    $tanggal_lahir = mysqli_real_escape_string($koneksi, $data["tanggal_lahir"]);
    $jenis_kelamin = mysqli_real_escape_string($koneksi, $data["jenis_kelamin"]);
    $alamat = mysqli_real_escape_string($koneksi, $data["alamat"]);
    $kelas = mysqli_real_escape_string($koneksi, $data["kelas"]);
    $no_hp = mysqli_real_escape_string($koneksi, $data["no_hp"]);
    $email = mysqli_real_escape_string($koneksi, $data["email"]);


    $result = mysqli_query($koneksi, "SELECT * FROM user WHERE username = '$username'");
    if(mysqli_fetch_assoc($result)){
        echo "Username sudah terdaftar!";
        return false;
    }

    if ($password !== $confirm_password){
        echo "Konfirmasi password salah!";
        return false;
    }

    $result_user = mysqli_query($koneksi, "INSERT INTO user (username, password) VALUES ('$username', '$password')");

    if ($result_user) {
        $user_id = mysqli_insert_id($koneksi);

        $result_siswa = mysqli_query($koneksi, "INSERT INTO siswa (id_user, Nama_siswa, NIS, tanggal_lahir, jenis_kelamin, alamat, kelas, no_hp, email) VALUES ('$user_id', '$nama', '$NIS', '$tanggal_lahir', '$jenis_kelamin', '$alamat', '$kelas','$no_hp','$email')");

        if ($result_siswa) {
            return true;
        } else {
            echo "Pendaftaran gagal untuk siswa. Error: " . mysqli_error($koneksi);
            return false;
        }
    } else {
        echo "Pendaftaran gagal untuk pengguna. Error: " . mysqli_error($koneksi);
        return false;
    }
}
?>
