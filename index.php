<?php
include 'conn.php';
session_start();

if (isset($_POST["login"])) {
    $username = mysqli_real_escape_string($koneksi, $_POST["username"]);
    $password = mysqli_real_escape_string($koneksi, $_POST["password"]);

    $query = "SELECT * FROM user WHERE username = '$username'";
    $result = mysqli_query($koneksi, $query) or die(mysqli_error($koneksi));

    if (mysqli_num_rows($result) == 1) {
        $row = mysqli_fetch_assoc($result);

        if ($password === $row["password"]) {
            $_SESSION['username'] = $username;
            $_SESSION['id_user'] = $row['id_user'];  
            header("Location: home.php");
            exit;
        } else {
            echo "Password tidak cocok.";
        }
    } else {
        echo "Username tidak ditemukan.";
    }
}
?>


<!DOCTYPE html>
<html lang="en">

<?php
include 'head.html';
?>
<style>
    body {
        font-family: 'Open Sans', sans-serif;
    }

    form,label{
        font-family: 'Arial', serif;
    }

    h3 {
        font-family: 'Libre Baskerville', serif;
    }
</style>

<body>

    <div class="container-fluid">
        <div class="row">
            <div class="col-sm-6">
                <div>
                    <img src="admin/assets/img/R.png" alt="" class="mx-auto d-block" style="margin-top: 15%;">
                    <h3 style="text-align: center;">Sistem Informasi Repository Laporan PKL</h3>
                </div><br>
                <form style="position: relative; margin: 50px;" action="" method="post">
                    <?php
                    if (isset($error)) {
                        echo '<div class="alert alert-danger" role="alert">' . "pesan eror" . '</div>';
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
                    <div class="d-grid gap-2">
                        <button class="btn btn-primary" type="submit" name="login">Login</button>
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