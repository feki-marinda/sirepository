<?php
session_start();

include('conn.php');

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = mysqli_real_escape_string($koneksi, $_POST['username']);
    $password = $_POST['password'];

    // Query untuk mendapatkan password dari database
    $query = "SELECT id_user, username, password FROM user WHERE username=? LIMIT 1";
    $stmt = mysqli_prepare($koneksi, $query);
    mysqli_stmt_bind_param($stmt, "s", $username);
    mysqli_stmt_execute($stmt);
    mysqli_stmt_bind_result($stmt, $id_user, $username, $db_password);
    mysqli_stmt_fetch($stmt);
    mysqli_stmt_close($stmt);

    // Memeriksa apakah password cocok
    if ($password == $db_password) {
        $_SESSION['id_user'] = $id_user;
        $_SESSION['username'] = $username;

        // Redirect ke dasbor atau halaman lainnya
        header('Location: home.php');
        exit;
    } else {
        // Password salah
        $error_message = "Invalid username or password.";
    }
}

mysqli_close($koneksi);
?>



<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="icon" href="gambar/repo.png" type="image/png">
    <title>Login SiRepository</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet">

    <!-- Gaya Font dari Google Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link
        href="https://fonts.googleapis.com/css2?family=Libre+Baskerville&family=PT+Serif&family=Poppins:wght@300;400;500;700&family=Ubuntu:wght@300&display=swap"
        rel="stylesheet">

    <style>
        body {
            font-family: 'Poppins', sans-serif;
        }

        h3 {
            font-family: 'Libre Baskerville', serif;
        }

        /* Styling untuk form dan elemen-elemen lainnya */
        /* Tambahkan sesuai kebutuhan Anda */
    </style>
</head>

<body>

    <div class="container-fluid">
        <div class="row">
            <div class="col-sm-6">
                <div>
                    <img src="admin/gambar/R.png" alt="" class="mx-auto d-block" style="margin-top: 15%;">
                    <h3 style="text-align: center;">Sistem Informasi Repository Laporan PKL</h3>
                </div><br>
                <form style="position: relative; margin: 50px;" action="" method="post">
                    <div class="mb-3">
                        <label for="exampleInputEmail1" class="form-label">Username</label>
                        <input type="text" class="form-control" id="username" name="username" required
                            placeholder="Masukkan Username Anda">
                    </div>
                    <div class="mb-3">
                        <label for="exampleInputPassword1" class="form-label">Password</label>
                        <input type="password" class="form-control" id="password" name="password" required
                            placeholder="Masukkan Password Anda">
                    </div>
                    <div class="d-grid gap-2">
                        <button class="btn btn-primary" type="submit">Login</button>
                        <div class="text-center">
                            <p>Belum punya akun? <a href="register.php" class="btn btn-link">Buat akun</a></p>
                        </div>

                    </div>
                </form>
                <?php
    if (!empty($error_message)) {
        echo '<div class="alert alert-danger" role="alert">' . $error_message . '</div>';
    }
    ?>
            </div>
            <div class="col-sm-6" style="position: relative;">
                <div
                    style="position: absolute; top: 0; left: 0; width: 100%; height: 100%; background-color: white; opacity: 0.5;">
                </div>
                <img src="admin/assets/img/sekolah.jpg" alt="" style="height: 100vh; width: 100%; object-fit: cover;">
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.2/dist/umd/popper.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.min.js"></script>

</body>

</html>