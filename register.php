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

        /* Styling untuk form dan elemen-elemen lainnya */
        /* Tambahkan sesuai kebutuhan Anda */
    </style>
</head>

<body>

    <div class="container-fluid">
        <div class="row p-3">
            <div class="col-sm-6">
                <div>
                    <img src="admin/gambar/R.png" alt="" class="mx-auto d-block" style="margin-top: 5%;">
                    <h3 style="text-align: center;">Sistem Informasi Repository Laporan PKL</h3>
                </div><br>
                <div style="text-align: center;">
                    <h2>Selamat Datang</h2>
                    <p>Jika Belum Memiliki Akun Ayo Buat Akun Sekarang !</p>
                </div>
                <nav>
                    <div class="nav nav-tabs" id="nav-tab" role="tablist">
                        <button class="nav-link active" id="nav-home-tab" data-bs-toggle="tab"
                            data-bs-target="#nav-home" type="button" role="tab" aria-controls="nav-home"
                            aria-selected="true">Siswa</button>
                        <button class="nav-link" id="nav-profile-tab" data-bs-toggle="tab" data-bs-target="#nav-profile"
                            type="button" role="tab" aria-controls="nav-profile" aria-selected="false">Guru</button>

                    </div>
                </nav>
                <div class="tab-content" id="nav-tabContent">
                    <div class="tab-pane fade show active" id="nav-home" role="tabpanel" aria-labelledby="nav-home-tab">
                        <form class="mt-4">
                            <div class="mb-3">
                                <label for="exampleInputEmail1" class="form-label">NIS</label>
                                <input type="int" class="form-control" id="exampleInputEmail1"
                                    aria-describedby="emailHelp" placeholder="Masukkan Nomor Induk Siswa">
                            </div>
                            <div class="mb-3">
                                <label for="exampleInputEmail1" class="form-label">Username</label>
                                <input type="text" class="form-control" id="exampleInputEmail1"
                                    aria-describedby="emailHelp" placeholder="Masukkan Username">
                            </div>
                            <div class="mb-3">
                                <label for="exampleInputPassword1" class="form-label">Password</label>
                                <input type="password" class="form-control" id="exampleInputPassword1" placeholder="Masukkan Password">
                            </div><br>
                            <div class="d-grid gap-2">
                                <button class="btn btn-primary" type="submit" class="btn btn-primary">Submit</button>
                            </div>
                        </form>
                    </div>
                    <div class="tab-pane fade" id="nav-profile" role="tabpanel" aria-labelledby="nav-profile-tab">
                        <?php include 'register_guru.php' ?>
                    </div>
                </div>
                <div class="tab-pane fade" id="nav-contact" role="tabpanel" aria-labelledby="nav-contact-tab">...
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


    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.2/dist/umd/popper.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.min.js"></script>

    <script>
        function showForm(formType) {
            // Sembunyikan semua formulir
            document.querySelectorAll('.formguru').forEach(form => {
                form.classList.add('hide');
            });

            // Tampilkan formulir yang sesuai dengan tombol yang diklik
            document.getElementById('form' + formType).classList.remove('hide');
        }
    </script>

</body>

</html>