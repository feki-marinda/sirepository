<?php
session_start();
include('conn.php');

$id_user = isset($_SESSION['id_user']) ? $_SESSION['id_user'] : '';

if (empty($id_user)) {
    header("Location: index.php");
    exit;
}

$query = "SELECT * FROM siswa INNER JOIN user ON siswa.id_user = user.id_user WHERE user.id_user = '$id_user'";
$result = mysqli_query($koneksi, $query);
?>
<!DOCTYPE html>
<html lang="en">

<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet">
<link rel="stylesheet" href="assets/css/styl.css">
<?php include 'head.html'; ?>

<style>
  .warna{
    color: #012970;
  }
</style>

<body>
  <?php include 'header_siswa.php'; ?>
  <main id="main">

    <section class="breadcrumbs">
      <div class="container">
        <ol>
          <li><a href="index.php">Home</a></li>
          <li><a href="datadiri.php">Data Siswa</a></li>
        </ol>
        <h2>
          SMK Al-Muhajirin
        </h2>
      </div>
    </section>
    
    <style>
            table {
                font-family: Arial, sans-serif;
            }
           ul,li {
                font-family: Arial, sans-serif;
            }
        </style>
    
    <div class="container mt-3 card shadow">
            <div class="row">
            <div class="col-md-2 border-end rounded" style="background: #012970;">
                    <ul class="nav nav-pills flex-column" id="myTab" role="tablist" aria-orientation="vertical">
                        <li class="nav-item" role="presentation">
                            <button class="nav-link active btn-custom" id="home-tab" data-bs-toggle="tab"
                                data-bs-target="#home" type="button" role="tab" aria-controls="home"
                                aria-selected="true">Data Siswa</button>
                        </li>
                        <li class="nav-item" role="presentation">
                            <button class="nav-link btn-custom" id="profile-tab" data-bs-toggle="tab"
                                data-bs-target="#profile" type="button" role="tab" aria-controls="profile"
                                aria-selected="false">Data Nilai</button>
                        </li>
                        <li class="nav-item" role="presentation">
                            <button class="nav-link btn-custom" id="contact-tab" data-bs-toggle="tab"
                                data-bs-target="#contact" type="button" role="tab" aria-controls="contact"
                                aria-selected="false">Sertifikat</button>
                        </li>
                    </ul>
                </div>

                <div class="col-md-10">
                    <div class="tab-content" id="myTabContent">
                        <div class="tab-pane fade show active" id="home" role="tabpanel" aria-labelledby="home-tab">
                            <?php include 'datasiswa.php'; ?>
                        </div>
                        <div class="tab-pane fade" id="profile" role="tabpanel" aria-labelledby="profile-tab">
                            <?php include 'datanilai.php'; ?>
                        </div>
                        <div class="tab-pane fade" id="contact" role="tabpanel" aria-labelledby="contact-tab">
                            <?php include 'datasertifikat.php'; ?>
                        </div>
                    </div>
                </div>
            </div>
        </div>


  </main>

  <a href="#" class="back-to-top d-flex align-items-center justify-content-center"><i
      class="bi bi-arrow-up-short"></i></a>


  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js">
    <script src="assets/js/main.js"></script>

</body>

</html>