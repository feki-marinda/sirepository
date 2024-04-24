<?php
include 'conn.php';
session_start();

if (isset($_POST["login"])) {
    $username = mysqli_real_escape_string($koneksi, $_POST["username"]);
    $password = mysqli_real_escape_string($koneksi, $_POST["password"]);
    $status = mysqli_real_escape_string($koneksi, $_POST["status"]);

    $query = "SELECT * FROM user WHERE username = '$username' AND status = '$status'";
    $result = mysqli_query($koneksi, $query) or die(mysqli_error($koneksi));

    if (mysqli_num_rows($result) == 1) {
        $row = mysqli_fetch_assoc($result);

        if ($password === $row["password"]) {
            $_SESSION['username'] = $username;
            $_SESSION['id_user'] = $row['id_user'];  
            $_SESSION['status'] = $status; 

            if ($status == 'admin') {
                header("Location: admin/index.php");
            } elseif ($status == 'siswa') {
                header("Location: home.php");
            } elseif ($status == 'guru') {
                header("Location: guru/index.php");
            }
            exit;
        } else {
            $error = "Password tidak cocok.";
        }
    } else {
        $error = "Username atau peran tidak ditemukan.";
    }
}
?>



<!DOCTYPE html>
<html lang="en">
<?php
include 'head.html';
?>
<head>
    
    <style>
        body {
            font-family: 'Open Sans', sans-serif;
        }

        form,
        label {
            font-family: 'Arial', serif;
        }

        h3 {
            font-family: 'Libre Baskerville', serif;
        }
    </style>
</head>

<body>

    <div class="container-fluid">
        <div class="row">
            <div class="col-md-6">
                <div class="text-center">
                <img src="admin/assets/img/R.png" alt="" class="img-fluid d-block mx-auto" style="margin-top: 15%;">
                    <h3>Sistem Informasi Repository Laporan PKL</h3>
                </div><br>
                <form style="position: relative; margin: 50px;" action="" method="post">
                    <?php
                    if (isset($error)) {
                        echo '<div class="alert alert-danger" status="alert">' . $error . '</div>';
                    }
                    ?>
                    <div class="mb-3">
                        <label for="username" class="form-label">Username</label>
                        <input type="text" class="form-control" id="username" name="username" required
                            placeholder="Masukkan Username Anda">
                    </div>
                    <div class="mb-3">
                        <label for="password" class="form-label">Password</label>
                        <input type="password" class="form-control" id="password" name="password" required
                            placeholder="Masukkan Password Anda">
                    </div>
                    <div class="mb-3">
                        <label for="status" class="form-label">Login Sebagai</label>
                        <select class="form-select" id="status" name="status" required>
                            <option value="" disabled selected>Pilih Login Sebagai</option>
                            <option value="admin">Admin</option>
                            <option value="guru">Guru</option>
                            <option value="siswa">Siswa</option>
                        </select>
                    </div>
                    <div class="d-grid gap-2">
                        <button class="btn btn-primary" type="submit" name="login">Login</button>
                        <div class="text-center">
                            <p>Belum punya akun? <a href="register.php" class="btn btn-link">Buat akun</a></p>
                        </div>
                    </div>

                </form>
            </div>
            <div class="col-md-6">
    <div style="position: relative; height: 100vh;">
        <div style="position: absolute; top: 0; left: 0; width: 100%; height: 100%; background-color: white; opacity: 0.5;"></div>
        <img src="admin/assets/img/sekolah.jpg" alt="" class="img-fluid" style="height: 100%; width: 100%; object-fit: cover;">
    </div>
</div>

        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>
