<?php
include 'conn.php';

if (isset($_POST['Simpanindikator'])) {
    $indikator = $_POST['indikator'];
    mysqli_query($koneksi, "INSERT INTO indikator (indikator) VALUES ('$indikator')");
}

if (isset($_POST['Editindikator'])) {
    $id_indikator = $_POST['id_indikator'];
    $indikator = $_POST['indikator'];
    mysqli_query($koneksi, "UPDATE indikator SET indikator='$indikator' WHERE id_indikator='$id_indikator'");
}

if (isset($_GET['id_indikator'])) {
    $id_indikator = $_GET['id_indikator'];
    mysqli_query($koneksi, "DELETE FROM indikator WHERE id_indikator='$id_indikator'");
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Form Input Indikator</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
</head>

<body>

    <div class="container">

        <div class="card bg-light">
            <div class="card-body">
                <h2 class="text-center">Tambah Indikator Penilaian</h2>

                <form method="post" action="#">
                    <div class="form-group">
                        <label for="indikator">Indikator:</label>
                        <input type="text" class="form-control" id="indikator" name="indikator" required>
                    </div>
                    <button type="submit" class="btn btn-primary" name="Simpanindikator">Simpan</button>
                </form>
            </div>
        </div>

        <div class="">
            <h2 style="text-align: center;">Indikator Penilaian Praktik Kerja Lapangan</h2>
            <table class="table table-bordered table-striped mt-3">
                <thead>
                    <tr>
                        <th>No</th>
                        <th>Indikator</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    $query = "SELECT * FROM indikator";
                    $result = mysqli_query($koneksi, $query);
                    $no = 1;
                    while ($row = mysqli_fetch_assoc($result)) {
                        echo "<tr>";
                        echo "<td>" . $no++ . "</td>";
                        echo "<td>" . $row['indikator'] . "</td>";
                        echo '<td>                
                        <a href="" data-toggle="modal" data-target="#editindikator' . $row['id_indikator'] . '" class="btn btn-primary btn-sm"><i class="nav-icon fas fa-edit"></i> Edit</a>
                        <a href="" data-toggle="modal" data-target="#deleteindikator' . $row['id_indikator'] . '" class="btn btn-danger btn-sm"><i class="nav-icon fas fa-trash-alt"></i> Hapus</a>
                    </td>';
                        echo "</tr>";
                    }
                    ?>
                </tbody>
            </table>
        </div>

        <?php
        $query = "SELECT * FROM indikator";
        $result = mysqli_query($koneksi, $query);
        while ($row = mysqli_fetch_assoc($result)) {
            ?>
            <div class="modal fade" id="editindikator<?php echo $row['id_indikator']; ?>">
                <div class="modal-dialog">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h4 class="modal-title">Edit Data indikator</h4>
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                        <div class="modal-body">
                            <form method="post" action="">
                                <div class="card-body">
                                    <div class="form-group">
                                        <label for="ID indikator">ID</label>
                                        <input type="text" class="form-control" id="id_indikator"
                                            value="<?= $row['id_indikator']; ?>" name="id_indikator" readonly>
                                    </div>
                                    <div class="form-group">
                                        <label for="indikator">Indikator</label>
                                        <input type="text" class="form-control" id="indikator"
                                            value="<?= $row['indikator']; ?>" name="indikator" required>
                                    </div>
                                    <div class="modal-footer justify-content-between">
                                        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                                        <button type="submit" class="btn btn-primary" name="Editindikator">Simpan</button>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>

            <div class="modal fade" id="deleteindikator<?php echo $row['id_indikator']; ?>">
                <div class="modal-dialog">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h4 class="modal-title">Delete Data indikator</h4>
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                        <div class="modal-body">
                            <h4 align="center">Apakah anda yakin ingin menghapus indikator <strong>
                                    <?= $row['indikator']; ?>
                                </strong>?</h4>
                        </div>
                        <div class="modal-footer justify-content-between">
                            <button type="button" class="btn btn-danger" data-dismiss="modal">Cancel</button>
                            <a href="download.php?id_indikator=<?= $row['id_indikator']; ?>"
                                class="btn btn-primary">Delete</a>
                        </div>
                    </div>
                </div>
            </div>
        <?php } ?>

    </div>

    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.5.4/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>

</body>

</html>