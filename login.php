<?php
include 'conn.php'; 

session_start();

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['login'])) {
    $username = $_POST['username'];
    $password = $_POST['password'];

    $query = "SELECT * FROM user WHERE username = '$username'";
    $result = mysqli_query($koneksi, $query);

    if ($result) {
        $user_data = mysqli_fetch_assoc($result);

        // Verifikasi password dengan password_verify
        if (password_verify($password, $user_data['password'])) {
            // Simpan informasi pengguna ke dalam sesi
            $_SESSION['id_user'] = $user_data['id'];
            $_SESSION['username'] = $user_data['username'];
            $_SESSION['nama'] = $user_data['nama'];

            header("Location: index.php");
            exit();
        } else {
            $error_message = "Login failed. Check your username and password.";
        }
    } else {
        $error_message = "Login failed. Check your username and password.";
    }
}
?>
