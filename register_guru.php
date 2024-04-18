<?php
require 'guru.php';

$error_message = $success_message = '';
if (isset($_POST['registrasi'])) {

    if (registrasi_guru($_POST)) {
        $success_message = "Registrasi Berhasil. Kembali Ke Halaman <a href='index.php'>Login</a>.";
    } else {
        $error_message = "Pendaftaran gagal !";
    }
}
?>

<!-- Form Registrasi -->
<form class="mt-4" method="post">
    <!-- Sisipkan pesan kesalahan atau sukses di sini -->
    <?php
    if (!empty($error_message)) {
        echo '<div class="alert alert-danger" role="alert">' . $error_message . '</div>';
    }

    if (!empty($success_message)) {
        echo '<div class="alert alert-success" role="alert">' . $success_message . '</div>';
    }
    ?>

    <!-- Isian Form -->
    <div class="row">
        <div class="mb-3 col">
            <label for="exampleInputEmail1" class="form-label">Username</label>
            <input type="text" class="form-control" id="exampleInputEmail1" name="username" aria-describedby="emailHelp"
                placeholder="Masukkan Username">
        </div>
        <div class="mb-3 col">
            <label for="exampleInputPassword1" class="form-label">Password</label>
            <input type="password" class="form-control" id="exampleInputPassword1" name="password"
                placeholder="Masukkan Password">
        </div>
    </div>
    <div class="row">
        <div class="mb-3 col">
            <label for="exampleInputNama1" class="form-label">Nama</label>
            <input type="text" class="form-control" id="exampleInputNama1" name="nama" aria-describedby="emailHelp"
                placeholder="Masukkan Nama Anda">
        </div>
        <div class="mb-3 col">
            <label for="exampleInputEmail2" class="form-label">Email</label>
            <input type="text" class="form-control" id="exampleInputEmail2" name="Email" aria-describedby="emailHelp"
                placeholder="Masukkan Email">
        </div>
    </div>
    <div class="mb-3">
        <label for="exampleInputNIP1" class="form-label">NIP</label>
        <input type="text" class="form-control" id="exampleInputNIP1" name="NIP" aria-describedby="emailHelp"
            placeholder="Masukkan Nomor Induk Pendidikan">
    </div>
    <div class="row">
        <div class="mb-3 col">
            <label for="exampleInputAlamat1" class="form-label">Alamat</label>
            <input type="text" class="form-control" id="exampleInputAlamat1" name="Alamat" aria-describedby="emailHelp"
                placeholder="Masukkan Alamat">
        </div>
        <div class="mb-3 col">
            <label for="exampleInputTelp1" class="form-label">No Telp</label>
            <input type="text" class="form-control" id="exampleInputTelp1" name="no_telp" aria-describedby="emailHelp"
                placeholder="Masukkan Nomor Telp">
        </div>
    </div>
    <!-- Isian Tersembunyi untuk id_guru dan id_user -->
    <input type="hidden" name="status" value="Guru">
    <input type="hidden" name="id_guru" value="isi_disini"> <!-- Isi dengan nilai id_guru yang sesuai -->
    <input type="hidden" name="id_user" value="isi_disini"> <!-- Isi dengan nilai id_user yang baru saja dibuat -->

    <!-- Tombol Submit -->
    <div class="d-grid gap-2">
        <button class="btn btn-primary" type="submit" name="registrasi">Submit</button>
    </div>
</form>
