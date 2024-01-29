<?php
include 'conn.php'; 

session_start();

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['login'])) {
    $username = $_POST['username'];
    $password = $_POST['password'];

    $query = "SELECT * FROM user WHERE username = '$username' AND password = '$password'";
    $result = mysqli_query($koneksi, $query);

    if (mysqli_num_rows($result) == 1) {
        $user_data = mysqli_fetch_assoc($result);

        // Simpan informasi pengguna ke dalam sesi
        $_SESSION['user_id'] = $user_data['id'];
        $_SESSION['username'] = $user_data['username'];
        $_SESSION['nama'] = $user_data['nama'];

        header("Location: index.php");
        exit();
    } else {
        $error_message = "Login failed. Check your username and password.";
    }
}
?>
