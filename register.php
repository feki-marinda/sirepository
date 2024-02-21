<?php
require 'login.php';

$error_message = $success_message = '';
if (isset($_POST['register'])) {

            if (registrasi($_POST)>0) {
                $success_message = "Registrasi Berhasil. Kembali Ke Halaman <a href='index.php'>Login</a>.";
            } else {
                $error_message = "Pendaftaran gagal !";
            }
    }
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="icon" href="gambar/repo.png" type="image/png">
    <title>Registrasi SiRepository</title>
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
    </style>
</head>

<body>

    <div class="container-fluid">
        <div class="row p-3">
            <div class="col-sm-6">
                <div class="text-center">
                    <img src="admin/gambar/R.png" alt="" class="mx-auto d-block">
                    <h3>Sistem Informasi Repository Laporan PKL</h3>
                </div><br>
                <div style="text-align: center;">
                    <h2>Selamat Datang</h2>
                    <p>Jika Belum Memiliki Akun Ayo Buat Akun Sekarang !</p>
                </div>
                <nav>
                    <div class="nav nav-tabs" id="nav-tab" role="tablist">
                        <button class="nav-link active" id="nav-home-tab" data-bs-toggle="tab"
                            data-bs-target="#nav-home" type="button" role="tab" aria-controls="nav-home"
                            aria-selected="true" onclick="showForm('siswa')">Siswa
                        </button>
                        <button class="nav-link" id="nav-profile-tab" data-bs-toggle="tab" data-bs-target="#nav-profile"
                            type="button" role="tab" aria-controls="nav-profile" aria-selected="false"
                            onclick="showForm('guru')">Guru
                        </button>
                    </div>
                </nav>
                <div class="tab-content" id="nav-tabContent">
                    <div class="tab-pane fade show active mt-3" id="nav-home" role="tabpanel"
                        aria-labelledby="nav-home-tab">
                        <div class="row justify-content-center">
                            <?php
                            if (!empty($error_message)) {
                                echo '<div class="alert alert-danger" role="alert">' . $error_message . '</div>';
                            }

                            if (!empty($success_message)) {
                                echo '<div class="alert alert-success" role="alert">' . $success_message . '</div>';
                            }
                            ?>
                            <form method="post" action="">
                                <div class="mb-3">
                                    <label for="username">Username</label>
                                    <input type="text" class="form-control" id="username" name="username"
                                        placeholder="Enter your username" required>
                                </div>
                                <div class="row">
                                    <div class="col-md-6 mb-3">
                                        <label for="password">Password</label>
                                        <input type="password" class="form-control" id="password" name="password"
                                            placeholder="Enter your password" required>
                                    </div>
                                    <div class="col-md-6 mb-3">
                                        <label for="confirm_password">Confirm Password</label>
                                        <input type="password" class="form-control" id="confirm_password"
                                            name="confirm_password" placeholder="Confirm your password" required>
                                    </div>
                                </div>

                                <div class="mb-3">
                                    <label for="full_name">Nama Lengkap</label>
                                    <input type="text" class="form-control" id="Nama_siswa" name="Nama_siswa"
                                        placeholder="Enter student name" required>
                                </div>
                                <div class="row">
                                    <div class="col-md-6 mb-3">
                                        <label for="full_name">NIS</label>
                                        <input type="text" class="form-control" id="NIS" name="NIS"
                                            placeholder="Enter student name" required>
                                    </div>
                                    <div class="col-md-6 mb-3">
                                        <label for="birth_info">Tempat/Tanggal Lahir</label>
                                        <input type="date" class="form-control" id="tanggal_lahir" name="tanggal_lahir"
                                            placeholder="Enter your birthplace and date" required>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-6 mb-3">
                                        <label for="gender">Jenis Kelamin</label>
                                        <select class="form-control" id="jenis_kelamin" name="jenis_kelamin" required>
                                            <option value="">Jenis Kelamin</option>
                                            <option value="Laki-laki">Laki-laki</option>
                                            <option value="Perempuan">Perempuan</option>
                                        </select>
                                    </div>
                                    <div class="col-md-6 mb-3">
                                        <label for="class">Kelas</label>
                                        <input type="text" class="form-control" id="kelas" name="kelas"
                                            placeholder="Enter class" required>
                                    </div>
                                </div>
                                <div class="mb-3">
                                    <label for="address">Alamat</label>
                                    <input type="text" class="form-control" id="alamat" name="alamat"
                                        placeholder="Enter class" required>
                                </div>
                                <button type="submit" class="btn btn-primary" name="register">Register</button>
                            </form>
                        </div>
                    </div>
                    <div class="tab-pane fade" id="nav-profile" role="tabpanel" aria-labelledby="nav-profile-tab">
                        <?php include 'register_guru.php' ?>
                    </div>
                </div>
            </div>
            <div class="col-sm-6" style="position: relative;">
                <div
                    style="position: absolute; top: 0; left: 0; width: 100%; height: 100%; background-color: white; opacity: 0.5;">
                </div>
                <img src="admin/assets/img/sekolah.jpg" alt="" style="height: 100vh; width: 100%; object-fit: cover;">
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.2/dist/umd/popper.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js"></script>

    <script>
        function showForm(formType) {
            // Sembunyikan semua formulir
            document.getElementById('nav-home').classList.add('show', 'active');
            document.getElementById('nav-profile').classList.remove('show', 'active');

            // Tampilkan formulir yang sesuai dengan tombol yang diklik
            if (formType === 'siswa') {
                document.getElementById('nav-home').classList.add('show', 'active');
                document.getElementById('nav-profile').classList.remove('show', 'active');
            } else if (formType === 'guru') {
                document.getElementById('nav-profile').classList.add('show', 'active');
                document.getElementById('nav-home').classList.remove('show', 'active');
            }
        }
    </script>

</body>

</html>