<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>SiRepository</title>
</head>

<body>
  <header id="header" class="header fixed-top">
    <div class="container-fluid container-xl d-flex align-items-center justify-content-between">

      <a href="index.php" class="logo d-flex align-items-center">
        <img src="assets/img/smk.png" alt="">
        <span>SiRepository</span>
      </a>

      <!-- .navbar -->
      <nav id="navbar" class="navbar">
        <ul>
          <li><a class="nav-link scrollto <?php echo isActivePage('home.php'); ?>" href="home.php">Home</a></li>
          <li class="dropdown"><a href="#"><span>Profil</span> <i class="bi bi-chevron-down"></i></a>
            <ul>
              <li><a href="sejarah.php" class="<?php echo isActivePage('sejarah.php'); ?>">Sejarah</a></li>
              <li><a href="visi.php" class="<?php echo isActivePage('visi.php'); ?>">Visi Misi Tujuan</a></li>
              <li><a href="Struktur.php" class="<?php echo isActivePage('Struktur.php'); ?>">Struktur Organisasi</a>
              </li>
            </ul>
          </li>
          <li class="dropdown"><a href="#"><span>Repository</span> <i class="bi bi-chevron-down"></i></a>
            <ul>
              <li><a href="repository.php" class="<?php echo isActivePage('repository.php'); ?>">Repo Laporan PKL <i
                    class="fa-solid fa-cloud-arrow-up"></i></a></li>
              <li><a href="unggahrepo.php" class="<?php echo isActivePage('unggahrepo.php'); ?>">Unggah Laporan <i
                    class="fa-solid fa-upload"></i></a></li>
              <li><a href="riwayatrepo.php" class="<?php echo isActivePage('riwayatrepo.php'); ?>">Riwayat<i
                    class="fa-solid fa-clock-rotate-left"></i></a></li>
            </ul>
          </li>
          <li><a class="nav-link scrollto <?php echo isActivePage('logbook.php'); ?>" href="logbook.php">Logbook</a>
          </li>
          <li><a class="nav-link scrollto <?php echo isActivePage('mitra.php'); ?>" href="mitra.php">Mitra</a></li>
          <li><a class="nav-link scrollto <?php echo isActivePage('dokumen.php'); ?>" href="dokumen.php">Dokumen</a>
          </li>
          <li class="dropdown">
            <a href="datadiri.php" class="nav-link">
              <span>Profil</span>
              <i class="fas fa-chevron-down"></i>
            </a>
            <ul>
              <li>
                <a href="datadiri.php" class="<?php echo isActivePage('datadiri.php'); ?>">
                  <i class="fas fa-user"></i>
                  Data Siswa
                </a>
              </li>
              <li>
                <a href="pengembang.php" class="<?php echo isActivePage('pengembang.php'); ?>">
                  <i class="fas fa-tools"></i>
                  Pengembang
                </a>
              </li>
              <li>
                <a href="logout.php" style="color: red;">
                  <i class="fas fa-sign-out-alt"></i>
                  Keluar
                </a>
              </li>

            </ul>
          </li>

        </ul>
        </li>

        <i class="bi bi-list mobile-nav-toggle"></i>
      </nav>
    </div>
  </header>

  <?php
  function isActivePage($page)
  {
    $currentPage = basename($_SERVER['PHP_SELF']);
    return ($currentPage == $page) ? 'active' : '';
  }
  ?>

</body>

</html>