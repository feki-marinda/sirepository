<?php
include 'conn.php';
$error_message = $success_message = '';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
  $id_guru = $_POST['id_guru'];
  $username = $_POST['username'];
  $password = $_POST['password'];
  $status = $_POST['status'];
  $nama = $_POST['nama'];
  $nip = $_POST['NIP']; // Ubah 'NIP' menjadi huruf kecil sesuai dengan nama input pada form
  $email = $_POST['Email']; // Ubah 'Email' menjadi 'Email' sesuai dengan nama input pada form
  $alamat = $_POST['Alamat']; // Ubah 'Alamat' menjadi 'Alamat' sesuai dengan nama input pada form
  $no_telp = $_POST['no_telp'];

  // Buat query untuk memasukkan data ke dalam tabel 'user'
  $query_user = "INSERT INTO user (username, password, status) VALUES ('$username', '$password','$status')";

  // Jalankan query untuk memasukkan data ke dalam tabel 'user'
  if (mysqli_query($koneksi, $query_user)) {
    // Dapatkan ID user yang baru saja dimasukkan
    $user_id = mysqli_insert_id($koneksi);

    // Buat query untuk memasukkan data tambahan ke dalam tabel 'guru_pamong'
    $query_guru = "INSERT INTO guru_pamong (id_guru, nama, NIP, Email, Alamat, no_telp) VALUES ('$id_guru', '$nama', '$nip', '$email', '$alamat', '$no_telp')";

    // Jalankan query untuk memasukkan data tambahan ke dalam tabel 'guru_pamong'
    if (mysqli_query($koneksi, $query_guru)) {
      $success_message = "Registrasi Berhasil. Kembali Ke Halaman <a href='index.php'>Login</a>.";
    } else {
      $error_message = "Pendaftaran gagal !";
    }
  } else {
    echo "Error: " . $query_user . "<br>" . mysqli_error($koneksi);
  }
}
?>
<form class="mt-4" method="post">
  <?php
  if (!empty($error_message)) {
    echo '<div class="alert alert-danger" role="alert">' . $error_message . '</div>';
  }

  if (!empty($success_message)) {
    echo '<div class="alert alert-success" role="alert">' . $success_message . '</div>';
  }
  ?>
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
  <input type="hidden" name="status" value="Guru">
  <input type="hidden" name="id_guru">

  <div class="d-grid gap-2">
    <button class="btn btn-primary" type="submit" nama="Registrasi">Submit</button>
  </div>
</form>