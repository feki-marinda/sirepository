<?php
session_start();
include('conn.php');

$username = isset($_SESSION['username']) ? $_SESSION['username'] : '';

if (empty($username)) {
    header("Location: ../index.php");
    exit;
}
?>
<!DOCTYPE html>
<html lang="en">

<?php include 'head.html' ?>
<style>
    .profile img {
        height: 300px;
        max-width: 100%;
        border-radius: 5%;
    }

    @media (min-width: 768px) {

        .profile img {
            max-width: 300px;
        }
    }

    .profile-table th {
        width: 30%;
    }
</style>

<body class="sb-nav-fixed">
    <?php include 'header.php' ?>
    <div id="layoutSidenav_content">
        <main>
            <div class="container-fluid px-4">
                <h1 class="mt-4">Dashboard</h1>
                <ol class="breadcrumb mb-4">
                    <li class="breadcrumb-item active">Dashboard</li>
                </ol>
                <div class="row">
                    <div class="col-xl-3 col-md-6">
                        <div class="card bg-primary text-white mb-4">
                            <div class="card-body">Data Siswa</div>
                            <div class="card-footer d-flex align-items-center justify-content-between">
                                <a class="small text-white stretched-link" href="#">View Details</a>
                                <div class="small text-white"><i class="fas fa-angle-right"></i></div>
                            </div>
                        </div>
                    </div>
                    <div class="col-xl-3 col-md-6">
                        <div class="card bg-warning text-white mb-4">
                            <div class="card-body">Warning Card</div>
                            <div class="card-footer d-flex align-items-center justify-content-between">
                                <a class="small text-white stretched-link" href="#">View Details</a>
                                <div class="small text-white"><i class="fas fa-angle-right"></i></div>
                            </div>
                        </div>
                    </div>
                    <div class="col-xl-3 col-md-6">
                        <div class="card bg-success text-white mb-4">
                            <div class="card-body">Success Card</div>
                            <div class="card-footer d-flex align-items-center justify-content-between">
                                <a class="small text-white stretched-link" href="#">View Details</a>
                                <div class="small text-white"><i class="fas fa-angle-right"></i></div>
                            </div>
                        </div>
                    </div>
                    <div class="col-xl-3 col-md-6">
                        <div class="card bg-danger text-white mb-4">
                            <div class="card-body">Danger Card</div>
                            <div class="card-footer d-flex align-items-center justify-content-between">
                                <a class="small text-white stretched-link" href="#">View Details</a>
                                <div class="small text-white"><i class="fas fa-angle-right"></i></div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <h1 class="text-center">Data Guru Pamong</h1>
                    <div class="container mt-5">
                        <div class="row">
                            <div class="col-md-4 d-flex align-items-center justify-content-center mb-4 mb-md-0">
                                <div class="profile">
                                    <img src="assets/img/admin.jpeg" alt="Foto Profil">
                                </div>
                            </div>
                            <div class="col-md-8">
                                <table class="table profile-table table-striped">
                                    <tbody>
                                        <tr>
                                            <th>Nama</th>
                                            <td>John Doe</td>
                                        </tr>
                                        <tr>
                                            <th>Umur</th>
                                            <td>30 tahun</td>
                                        </tr>
                                        <tr>
                                            <th>Alamat</th>
                                            <td>Jl. Contoh No. 123</td>
                                        </tr>
                                        <tr>
                                            <th>Email</th>
                                            <td>john@example.com</td>
                                        </tr>
                                        <tr>
                                            <th>No. Telepon</th>
                                            <td>08123456789</td>
                                        </tr>
                                    </tbody>
                                </table>
                                <div class="d-grid">
                                    <button type="button" class="btn btn-primary btn-block">Edit Data</button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </main>
        <footer class="py-4 bg-light mt-auto">
            <div class="container-fluid px-4">
                <div class="d-flex align-items-center justify-content-between small">
                    <div class="text-muted">Copyright &copy; Your Website 2023</div>
                    <div>
                        <a href="#">Privacy Policy</a>
                        &middot;
                        <a href="#">Terms &amp; Conditions</a>
                    </div>
                </div>
            </div>
        </footer>
    </div>
    </div>
    <?php include 'footer.php';?>
</body>

</html>