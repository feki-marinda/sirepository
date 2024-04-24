<?php
include('conn.php');

$id_user = isset($_SESSION['id_user']) ? $_SESSION['id_user'] : '';

if (empty($id_user)) {
  header("Location: index.php");
  exit;
}

$query = "SELECT sertifikat.file_sertifikat 
          FROM sertifikat 
          INNER JOIN siswa ON siswa.id_siswa = sertifikat.id_siswa 
          INNER JOIN user ON user.id_user = siswa.id_user 
          WHERE user.id_user = '$id_user'";
$result = mysqli_query($koneksi, $query);

?>

<div class="text-center">
  <h1>Sertifikat</h1>
  <h2>Praktik Kerja Lapangan</h2>
</div>
<?php
while ($row = mysqli_fetch_assoc($result)) { ?>
  <div class="ratio ratio-16x9 mt-3">
    <img src="admin/Sertifikat/<?php echo $row["file_sertifikat"]; ?>" width="150" height="130">
  </div>
  <div class="d-grid gap-2 d-md-flex justify-content-md-end mt-3 mb-3">
    <a href="admin/Sertifikat/<?php echo $row['file_sertifikat']; ?>" class="btn btn-primary me-md-2" download>
      Download <i class="fas fa-download"></i>
    </a>
  <?php } ?>
</div>