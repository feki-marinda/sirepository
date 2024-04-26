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
    <!-- Navbar Brand-->
    <a class="navbar-brand ps-3" href="index.php">
        <img src="../admin/assets/img/r.png" alt="" style="width: 200px; height: 40px;">
    </a>
    <!-- Sidebar Toggle-->
    <button class="btn btn-link btn-sm order-1 order-lg-0 me-2 me-lg-0" id="sidebarToggle" aria-label="Toggle sidebar">
        <i class="fas fa-bars"></i>
    </button>
    <!-- Navbar Search-->
    <!-- <form class="d-none d-lg-flex form-inline ms-auto me-3 my-2 my-lg-0">
        <div class="input-group">
            <input class="form-control" type="text" placeholder="Search for..." aria-label="Search for..." aria-describedby="btnNavbarSearch">
            <button class="btn btn-primary" id="btnNavbarSearch" type="button">
                <i class="fas fa-search"></i>
            </button>
        </div>
    </form> -->
    <!-- Navbar-->
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
    <button type="button" class="btn btn-danger" id="logoutBtn">Logout</button>
</div>

<script>
    document.getElementById('logoutBtn').addEventListener('click', function() {
        window.location.href = '../logout.php'; // Mengarahkan ke halaman logout.php
    });
</script>

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
                    <div class="sb-nav-link-icon"><i class="fas fa-tachometer-alt"></i></div>
                    Dashboard <br> Guru Pamong
                </a>
                    <div class="sb-sidenav-menu-heading">Menu</div>
                    <a class="nav-link <?php echo ($current_page === 'dataPKL.php') ? 'active' : ''; ?>" href="dataPKL.php">
                    <div class="sb-nav-link-icon"><i class="fas fa-table"></i></div>
                    Data PKL
                </a>
                <a class="nav-link <?php echo ($current_page === 'datalaporan.php') ? 'active' : ''; ?>" href="datalaporan.php">
                    <div class="sb-nav-link-icon"><i class="fas fa-table"></i></div>
                    Data Laporan PKL
                </a>
                <a class="nav-link <?php echo ($current_page === 'datalogbook.php') ? 'active' : ''; ?>" href="datalogbook.php">
                    <div class="sb-nav-link-icon"><i class="fas fa-table"></i></div>
                    Data Logbook
                </a>
                <a class="nav-link <?php echo ($current_page === 'datanilai.php') ? 'active' : ''; ?>" href="datanilai.php">
                    <div class="sb-nav-link-icon"><i class="fas fa-table"></i></div>
                    Data Nilai
                </a>
                <a class="nav-link <?php echo ($current_page === 'datasertifikat.php') ? 'active' : ''; ?>" href="datasertifikat.php">
                    <div class="sb-nav-link-icon"><i class="fas fa-table"></i></div>
                    Data Sertifikat
                </a>
                <a class="nav-link <?php echo ($current_page === 'datadokumen.php') ? 'active' : ''; ?>" href="datadokumen.php">
                    <div class="sb-nav-link-icon"><i class="fas fa-table"></i></div>
                    Data Dokumen
                </a>
                </div>
            </div>
        </nav>        
    </div>