<?php
session_start();

// Check if the form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    include('conn.php');

    $username = mysqli_real_escape_string($koneksi, $_POST['username']);
    $password = mysqli_real_escape_string($koneksi, $_POST['password']);

    // Query to check if the provided username and password match
    $query = "SELECT * FROM user WHERE username='$username' AND password='$password'";
    $result = mysqli_query($koneksi, $query);

    if ($result) {
        // Check if any row is returned
        if (mysqli_num_rows($result) == 1) {
            // User is authenticated, store user information in session
            $user = mysqli_fetch_assoc($result);
            $_SESSION['id_user'] = $user['id_user'];
            $_SESSION['username'] = $user['username'];

            // Redirect to dashboard or any other page
            header('Location: siswa/index.php');
            exit;
        } else {
            // Invalid username or password
            $error_message = "Invalid username or password.";
        }
    } else {
        // Query execution failed
        $error_message = "Error executing the query.";
    }

    // Close the database connection
    mysqli_close($koneksi);
}
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
                        <input type="password" class="form-control"id="password" name="password" required
                            placeholder="Masukkan Password Anda">
                    </div>
                    <div class="d-grid gap-2">
                        <button class="btn btn-primary" type="submit">Login</button>
                        <div class="text-center">
                            <p>Belum punya akun? <a href="register.php" class="btn btn-link">Buat akun</a></p>
                        </div>

                    </div>
                </form>
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