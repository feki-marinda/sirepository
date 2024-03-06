<?php
session_start();
include 'conn.php';

$query = "SELECT * FROM user";
$result = mysqli_query($koneksi, $query);

if (!$result) {
    die("Error in query: " . mysqli_error($koneksi));
}

$error_message = $success_message = '';

if (isset($_POST['tambahuser'])) {
    $id_user = $_POST['id_user'];
    $username = $_POST['username'];
    $password = $_POST['password'];
    $status = $_POST['status'];

    $insert_query = "INSERT INTO user (id_user, username, password, status) VALUES ('$id_user','$username', '$password','$status')";

    if ($koneksi->query($insert_query) === TRUE) {
        $_SESSION['success_message'] = "Berhasil Menambah Data User!";
        header("Location: datauser.php");
        exit();
    } else {
        $_SESSION['error_message'] = "Error: " . $koneksi->error;
        header("Location: datauser.php");
        exit();
    }
}

if (isset($_POST['edituser'])) {
    $id_user = $_POST['id_user'];
    $username = $_POST['username'];
    $password = $_POST['password'];
    $status = $_POST['status'];

    $update_query = "UPDATE user SET username = '$username', password = '$password', status = '$status' WHERE id_user = '$id_user'";

    if ($koneksi->query($update_query) === TRUE) {
        $rows_affected = $koneksi->affected_rows;

        if ($rows_affected > 0) {
            $_SESSION['success_message'] = "Berhasil Memperbarui Data User!";
            header("Location: datauser.php");
            exit();
        } else {
            $_SESSION['error_message'] = "Tidak ada perubahan pada Data User!";
            header("Location: datauser.php");
            exit();
        }
    } else {
        $_SESSION['error_message'] = "Error: " . $koneksi->error;
        header("Location: datauser.php");
        exit();
    }
}

if (isset($_GET['id_user'])) {
    $id_user = $_GET['id_user'];
    $delete_query = "DELETE FROM user WHERE id_user='$id_user'";

    if (mysqli_query($koneksi, $delete_query)) {
        $_SESSION['success_message'] = "Berhasil Menghapus Data User!";
        header("Location: datauser.php");
        exit();
    } else {
        $_SESSION['error_message'] = "Error: " . mysqli_error($koneksi);
        header("Location: datauser.php");
        exit();
    }
}

$koneksi->close();
?>


<!DOCTYPE html>
<html lang="en">

<?php include 'head.html' ?>

<body class="sb-nav-fixed">
    <?php include 'header.php' ?>
    <div id="layoutSidenav" style="width: 100%">

        <div id="layoutSidenav_content">
            <main>
                <div class="container-fluid px-4">
                    <h1 class="mt-4">Data User</h1>
                    <ol class="breadcrumb mb-4">
                        <li class="breadcrumb-item"><a href="index.php">Dashboard</a></li>
                        <li class="breadcrumb-item active">Tables</li>
                    </ol>

                    <div class="card mb-4">
                        <div class="button-container">
                            <div class="spacer"></div>
                            <div class="buttons-right">
                                <button type="button" class="btn btn-primary" data-bs-toggle="modal"
                                    data-bs-target="#tambah" data-bs-whatever="@mdo"> <i class="fas fa-plus"></i>
                                    Tambah Data User</button>
                                <button id="printButton">
                                    <i class="fas fa-print"></i> Cetak
                                </button>
                            </div>
                        </div>
                    </div>
                    <?php
                    if (isset($_SESSION['error_message']) && !empty($_SESSION['error_message'])) {
                        echo '<div class="alert alert-primary" role="alert">' . $_SESSION['error_message'] . '</div>';
                        unset($_SESSION['error_message']);
                    }
                    
                    if (isset($_SESSION['success_message']) && !empty($_SESSION['success_message'])) {
                        echo '<div class="alert alert-success" role="alert">' . $_SESSION['success_message'] . '</div>';
                        unset($_SESSION['success_message']);
                    }
                    ?>
                    <div class="card mb-4">
                        <div class="card-header">
                            <i class="fas fa-table me-1"></i>
                            Data User
                        </div>
                        <div class="card-body">

                            <table id="datatablesSimple" class="table table-striped table-hover">
                                <thead>
                                    <tr>
                                        <th>No.</th>
                                        <th>Username</th>
                                        <th>Password</th>
                                        <th>Status</th>
                                        <th>Keterangan</th>
                                    </tr>
                                </thead>

                                <tbody>
                                    <?php
                                    $no = 1;
                                    while ($row = mysqli_fetch_assoc($result)) {
                                        echo "<tr>";
                                        echo "<td>" . $no++ . "</td>";
                                        echo "<td>" . $row['username'] . "</td>";
                                        echo "<td>" . $row['password'] . "</td>";
                                        echo "<td>" . $row['status'] . "</td>";
                                        echo "<td>";
                                        echo "<div class='btn-group'>";
                                        echo "<button type='button' class='btn btn-primary' data-bs-toggle='modal' data-bs-target='#edit" . $row['id_user'] . "' data-bs-whatever='@mdo'><i class='nav-icon fas fa-edit'></i> Edit</button>";
                                        echo "<button type='button' class='btn btn-danger' data-bs-toggle='modal' data-bs-target='#hapus" . $row['id_user'] . "'><i class='nav-icon fas fa-trash-alt'></i> Hapus</button>";
                                        echo "</div>";
                                        echo "</td>";
                                        echo "</tr>";
                                        ?>
                                        <div class="modal fade" id='hapus<?= $row['id_user'] ?>' tabindex="-1" role="dialog"
                                            aria-labelledby="exampleModalLabel" aria-hidden="true">
                                            <div class="modal-dialog">
                                                <div class="modal-content">
                                                    <div class="modal-header">
                                                        <h5 class="modal-title" id="exampleModalLabel">Hapus Data user
                                                        </h5>
                                                        <button type="button" class="btn-close" data-bs-dismiss="modal"
                                                            aria-label="Close"></button>
                                                    </div>
                                                    <div class="modal-body">
                                                        Apakah anda yakin ingin menghapus data user?
                                                    </div>
                                                    <div class="modal-footer">
                                                        <button type="button" class="btn btn-secondary"
                                                            data-bs-dismiss="modal">Tidak</button>
                                                        <a href="datauser.php?id_user=<?= $row['id_user'] ?>"
                                                            class="btn btn-danger">Delete</a>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>

                                        <div class='modal fade' id='edit<?= $row['id_user'] ?>' tabindex='-1'
                                            aria-labelledby='exampleModalLabel' aria-hidden='true'>
                                            <div class="modal-dialog">
                                                <div class="modal-content">
                                                    <div class="modal-header">
                                                        <h5 class="modal-title" id="exampleModalLabel">Edit Data User</h5>
                                                        <button type="button" class="btn-close" data-bs-dismiss="modal"
                                                            aria-label="Close"></button>
                                                    </div>
                                                    <div class="modal-body">
                                                        <div class="modal-body">
                                                            <form method="POST" action="#" enctype="multipart/form-data">
                                                                <div class="form-group">
                                                                    <label for="id_user">ID</label>
                                                                    <input type="text" class="form-control" id="id_user"
                                                                        value="<?= $row['id_user']; ?>" name="id_user"
                                                                        readonly>
                                                                </div>
                                                                <div class="form-group">
                                                                    <label for="username">username</label>
                                                                    <input type="text" class="form-control" id="username"
                                                                        value="<?= $row['username']; ?>" name="username"
                                                                        required>
                                                                </div>
                                                                <div class="form-group">
                                                                    <label for="password">Password</label>
                                                                    <input type="int" class="form-control" id="password"
                                                                        value="<?= $row['password']; ?>" name="password"
                                                                        required>
                                                                </div>
                                                                <div class="form-group">
                                                                    <label for="status">Status</label>
                                                                    <select class="form-select" id="status" name="status"
                                                                        required>
                                                                        <option value="admin" <?= ($row['status'] == 'Admin') ? 'selected' : ''; ?>>Admin</option>
                                                                        <option value="guru" <?= ($row['status'] == 'Guru') ? 'selected' : ''; ?>>Guru</option>
                                                                        <option value="siswa" <?= ($row['status'] == 'Siswa') ? 'selected' : ''; ?>>Siswa</option>
                                                                    </select>
                                                                </div>

                                                                <div class="modal-footer">
                                                                    <button type="button" class="btn btn-secondary"
                                                                        data-bs-dismiss="modal">Close</button>
                                                                    <button type="submit" class="btn btn-primary"
                                                                        name="edituser" value="Submit">Submit</button>
                                                                </div>
                                                            </form>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>


                                            <?php
                                    }
                                    ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </main>

            <div class="modal fade" id="tambah" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
                <div class="modal-dialog">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" id="exampleModalLabel">Tambah Data User</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                        <div class="modal-body">
                            <form action="#" method="post" id="formTambahData">
                                <div class="mb-3">
                                    <input type="number" class="form-control" id="id_user" name="id_user" hidden>
                                </div>
                                <div class="mb-3">
                                    <label for="username" class="col-form-label">Username:</label>
                                    <input type="text" class="form-control" id="username" name="username" required>
                                </div>
                                <div class="mb-3">
                                    <label for="password" class="col-form-label">Password:</label>
                                    <input type="text" class="form-control" id="password" name="password" required>
                                </div>
                                <div class="mb-3">
                                    <label for="status" class="col-form-label">Status:</label>
                                    <select class="form-select" id="status" name="status" required>
                                        <option value="admin">Admin</option>
                                        <option value="guru">Guru</option>
                                        <option value="siswa">Siswa</option>
                                    </select>
                                </div>
                                <div class="modal-footer">
                                    <button type="button" class="btn btn-secondary"
                                        data-bs-dismiss="modal">Close</button>
                                    <button type="submit" class="btn btn-primary" name="tambahuser" value="Submit"
                                        id="submit">Submit</button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>


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
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/js/bootstrap.bundle.min.js"
        crossorigin="anonymous"></script>
    <script src="js/scripts.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/2.8.0/Chart.min.js" crossorigin="anonymous"></script>
    <script src="assets/demo/chart-area-demo.js"></script>
    <script src="assets/demo/chart-bar-demo.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/simple-datatables@7.1.2/dist/umd/simple-datatables.min.js"
        crossorigin="anonymous"></script>
    <script src="js/datatables-simple-demo.js"></script>
    <script src="https://code.jquery.com/jquery-3.6.4.min.js"></script>

</body>

</html>