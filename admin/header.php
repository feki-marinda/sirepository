<?php
$current_page = basename($_SERVER['PHP_SELF']);
?>
<style>
.nav-link.active {
            background-color: #007BFF;
            color: #FFFFFF; 
            border-left: 3px solid #0056b3;
        }
</style>


<nav class="sb-topnav navbar navbar-expand-lg navbar-dark bg-dark">
    <a class="navbar-brand ps-3" href="index.php">
        <img src="../admin/assets/img/r.png" alt="" style="width: 200px; height: 40px;">
    </a>
    <!-- Sidebar Toggle-->
    <button class="btn btn-link btn-sm order-1 order-lg-0 me-2 me-lg-0" id="sidebarToggle" aria-label="Toggle sidebar">
        <i class="fas fa-bars"></i>
    </button>
    
    <ul class="navbar-nav ms-auto me-0">
        <li class="nav-item">
            <button type="button" class="btn btn-danger btn-sm me-3" data-bs-toggle="modal" data-bs-target="#exampleModal">
                <i class="fas fa-sign-out-alt"></i> Logout
            </button>
        </li>
    </ul>
</nav>



<div class="modal fade" id="exampleModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Logout</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <h3>Apakah Anda Yakin Ingin Keluar ?</h3>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary me-auto" data-bs-dismiss="modal">Close</button>
                <button type="button" class="btn btn-danger" onclick="logout()">Logout</button>

<script>
    function logout() {
        window.location.href = 'logout.php';
    }
</script>

            </div>
        </div>
    </div>
</div>

<div id="layoutSidenav">
    <div id="layoutSidenav_nav">
        <nav class="sb-sidenav accordion sb-sidenav-dark" id="sidenavAccordion">
            <div class="sb-sidenav-menu">
                <div class="nav">
                    <div class="sb-sidenav-menu-heading">Core</div>
                    <a class="nav-link <?php echo ($current_page === 'index.php') ? 'active' : ''; ?>" href="index.php">
                    <div class="sb-nav-link-icon"><i class="fa-solid fa-user"></i></div>
                    Hallo, Admin <br> Sirepository
                </a>
                    <div class="sb-sidenav-menu-heading">Menu</div>
                    <a class="nav-link <?php echo ($current_page === 'datauser.php') ? 'active' : ''; ?>" href="datauser.php">
                    <div class="sb-nav-link-icon"><i class="fas fa-table"></i></div>
                    Data User
                </a>
                <a class="nav-link <?php echo ($current_page === 'datasiswa.php') ? 'active' : ''; ?>" href="datasiswa.php">
                    <div class="sb-nav-link-icon"><i class="fas fa-table"></i></div>
                    Data Siswa
                </a>
                <a class="nav-link <?php echo ($current_page === 'dataGP.php') ? 'active' : ''; ?>" href="dataGP.php">
                    <div class="sb-nav-link-icon"><i class="fas fa-table"></i></div>
                    Data Guru Pamong
                </a>
                <a class="nav-link <?php echo ($current_page === 'dataPKL.php') ? 'active' : ''; ?>" href="dataPKL.php">
                    <div class="sb-nav-link-icon"><i class="fas fa-table"></i></div>
                    Data PKL
                </a>
                <a class="nav-link <?php echo ($current_page === 'datalaporan.php') ? 'active' : ''; ?>" href="datalaporan.php">
                    <div class="sb-nav-link-icon"><i class="fas fa-table"></i></div>
                    Data Laporan PKL
                </a>
                <a class="nav-link <?php echo ($current_page === 'databerita.php') ? 'active' : ''; ?>" href="databerita.php">
                    <div class="sb-nav-link-icon"><i class="fas fa-table"></i></div>
                    Data Berita
                </a>
                <a class="nav-link <?php echo ($current_page === 'datadokumen.php') ? 'active' : ''; ?>" href="datadokumen.php">
                    <div class="sb-nav-link-icon"><i class="fas fa-table"></i></div>
                    Data Dokumen
                </a>
                <a class="nav-link <?php echo ($current_page === 'datalogbook.php') ? 'active' : ''; ?>" href="datalogbook.php">
                    <div class="sb-nav-link-icon"><i class="fas fa-table"></i></div>
                    Data Logbook
                </a>
                <a class="nav-link <?php echo ($current_page === 'datamitra.php') ? 'active' : ''; ?>" href="datamitra.php">
                    <div class="sb-nav-link-icon"><i class="fas fa-table"></i></div>
                    Data Mitra
                </a>
                <a class="nav-link <?php echo ($current_page === 'indikator.php') ? 'active' : ''; ?>" href="indikator.php">
                    <div class="sb-nav-link-icon"><i class="fas fa-table"></i></div>
                    Data Indikator
                </a>
                <a class="nav-link <?php echo ($current_page === 'datanilai.php') ? 'active' : ''; ?>" href="datanilai.php">
                    <div class="sb-nav-link-icon"><i class="fas fa-table"></i></div>
                    Data Nilai
                </a>
                <a class="nav-link <?php echo ($current_page === 'datasertifikat.php') ? 'active' : ''; ?>" href="datasertifikat.php">
                    <div class="sb-nav-link-icon"><i class="fas fa-table"></i></div>
                    Data Sertifikat
                </a>
                </div>
            </div>
        </nav>        
    </div>