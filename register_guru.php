<?php
include 'conn.php';
$error_message = $success_message = '';

// Cek jika form telah disubmit
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $id_guru = $_POST['id_guru'];
    $id_user = $_POST['id_user'];
    $username = $_POST['username'];
    $password = $_POST['password'];
    $status = $_POST['status'];
    $nama = $_POST['nama'];
    $nip = $_POST['NIP'];
    $email = $_POST['Email'];
    $alamat = $_POST['Alamat'];
    $no_telp = $_POST['no_telp'];

    // Query untuk insert data ke tabel 'user'
    $query_user = "INSERT INTO user (username, password, status) VALUES ('$username', '$password','$status')";

    if (mysqli_query($koneksi, $query_user)) {
        $user_id = mysqli_insert_id($koneksi);

        // Query untuk insert data ke tabel 'guru_pamong'
        $query_guru = "INSERT INTO guru_pamong (id_guru, id_user, nama, NIP, Email, Alamat, no_telp) VALUES ('$id_guru', '$user_id', '$nama', '$nip', '$email', '$alamat', '$no_telp')";

        if (mysqli_query($koneksi, $query_guru)) {
            $success_message = "Registrasi Berhasil. Kembali Ke Halaman <a href='index.php'>Login</a>.";
        } else {
            $error_message = "Pendaftaran gagal !";
        }
    } else {
        $error_message = "Error: " . mysqli_error($koneksi);
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
        <button class="btn btn-primary" type="submit" nama="Registrasi">Submit</button>
    </div>
</form>
