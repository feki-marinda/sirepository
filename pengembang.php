<!DOCTYPE html>
<html lang="en">

<?php include 'head.html' ?>

<body>

  <?php include 'header_siswa.php' ?>

  <main id="main">

    <section class="breadcrumbs">
      <div class="container">

        <ol>
          <li><a href="index.php">Home</a></li>
          <li><a href="about.php">Logbook</a></li>
        </ol>
        <h2>
          <?php
          if (isset($_SESSION['username'])) {
            $nama_siswa = $_SESSION['username'];
            echo '<h2>Hallo ' . $nama_siswa . '</h2>';
          } else {
            echo '<h2>Hallo</h2>';
          }
          ?>
        </h2>

      </div>
    </section>
    <div class="container">
      <div class="row ms-3 pb-5 pt-5 ps-5 pe-5 rounded shadow d-flex">
        <div class="text-center">
          <h1 class="font-weight-bold text-left" style="font-size: 2.5rem; color: #333;">
            Data Pengembang
          </h1>
          <h3>Sistem Informasi Repository Laporan PKL Siswa<span style="color: #FFD700; font-weight: bold;">SMK Al-Muhajirin</span></h3>
          <hr>
        </div>
        <div class="container mt-4">
  <div class="row">
    <div class="col-md-3">
      <img src="gambar/foto-1.jpg" alt="Profile Picture" class="img-fluid">
    </div>
    <div class="col-md-9">
      <table class="table">
        <tbody>
          <tr>
            <td>Nama</td>
            <td>Feki Dui Marinda</td>
          </tr>
          <tr>
            <td>Program Studi</td>
            <td>Pendidikan Informatika / Universitas Trunojoyo Madura</td>
          </tr>
          <tr>
            <td>Alamat</td>
            <td>Ponorogo</td>
          </tr>
          <tr>
            <td>Email</td>
            <td>fekimarinda2901@gmail.com</td>
          </tr>
        </tbody>
      </table>
    </div>
  </div>
</div>


    <script src="assets/js/main.js"></script>

  </main>

</body>

</html>