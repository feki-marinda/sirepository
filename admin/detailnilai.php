<?php
session_start();
include('conn.php');

$status = isset($_SESSION['status']) ? $_SESSION['status'] : '';

if (empty($status)) {
    header("Location: ../index.php");
    exit;
}

$id_siswa = isset($_GET['id_siswa']) ? $_GET['id_siswa'] : '';

$query_siswa = "SELECT siswa.Nama_siswa, pkl.id_mitra, siswa.NIS, mitra.nama FROM siswa 
INNER JOIN pkl ON siswa.id_siswa=pkl.id_siswa 
INNER JOIN mitra ON mitra.id_mitra = pkl.id_mitra WHERE siswa.id_siswa = '$id_siswa'";
$result_siswa = mysqli_query($koneksi, $query_siswa);

if (!$result_siswa) {
    die('Error: ' . mysqli_error($koneksi));
}

$row_siswa = mysqli_fetch_assoc($result_siswa);
$nama_siswa = $row_siswa['Nama_siswa'];
$tempat_pkl = $row_siswa['nama'];
$nis = $row_siswa['NIS'];


$query = "SELECT indikator.indikator, nilai_pkl.nilai, nilai_pkl.id_nilai
          FROM indikator 
          INNER JOIN nilai_pkl ON nilai_pkl.id_indikator = indikator.id_indikator
          WHERE nilai_pkl.id_siswa = '$id_siswa'";
$result = mysqli_query($koneksi, $query);

if (!$result) {
    die('Error: ' . mysqli_error($koneksi));
}

if (isset($_POST['EditNilai'])) {
    $id_nilai = $_POST['id_nilai'];
    $nilai = $_POST['nilai'];

    $query_update = "UPDATE nilai_pkl SET nilai = '$nilai' WHERE id_nilai='$id_nilai'";
    $result_update = mysqli_query($koneksi, $query_update);

    if ($result_update) {
        $rows_affected = mysqli_affected_rows($koneksi);
        if ($rows_affected > 0) {
            $admin_dtlnilai_success = "Berhasil Memperbarui Data Nilai!";
        } else {
            $admin_dtlnilai_error = "Tidak ada perubahan pada Data Nilai!";
        }
    } else {
        $admin_dtlnilai_error = "Tidak dapat Memperbarui Data Nilai!";
    }

    header("Location: detailnilai.php?id_siswa=$id_siswa");
    exit();
}

?>

<!DOCTYPE html>
<html lang="en">
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">

<style>
    @media print {
        .aksi {
            display: none;
        }
    }

    table {
        border-collapse: collapse;
        width: 100%;
    }

    th,
    td {
        border: 1px solid black;
        padding: 3px;
    }

    .text-center h5,
    .text-center h3,
    .text-center p {
        margin-bottom: 5px;
        margin-top: 0;
        padding: 0;
    }
</style>

<body>
    <div class="container">
        <div class="row">
                <div class="text-center">
                    <h5>YAYASAN HIDAYATULLOH AL-MUHAJIRIN</h5>
                    <h3>SMKS AL-MUHAJIRIN</h3>
                    <p>NSS : 322052905001 | NPSN : 20555424 | Akreditasi : B</p>
                    <p>Dsn. Paserean Bawah , Ds. Buduran, Kec. Arosbaya, Kab. Bangkalan.</p>
                    <p>Kodepos : 69151 | Telp : 081 737 5464 / 0823 3508 1945</p>
                    <p>G-mail : smksalmuhajirin.arosbaya@gmail.com | Website : www.smkalmuhajirin.sch.id</p>
                <hr>
            </div>

        </div>
        <div class="text-center">
            <h4>Form Penilaian </h4>
            <h4>Praktek Kerja Lapangan</h4>
        </div>
        <br>
        <div class="row">
            <div class="col-4"><strong>Nama</strong></div>
            <div class="col-8"><strong>:
                    <?php echo $nama_siswa ?>
                </strong></div>
        </div>
        <div class="row">
            <div class="col-4"><strong>Tempat PKL</strong></div>
            <div class="col-8"><strong>:
                    <?php echo $tempat_pkl ?>
                </strong></div>
        </div>
        <div class="row">
            <div class="col-4"><strong>NIS</strong></div>
            <div class="col-8"><strong>:
                    <?php echo $nis ?>
                </strong></div>
        </div>



        <table>
            <tr style="background-color: #ADD8E6;" class="text-center">
                <td>No</td>
                <td>Indikator</td>
                <td>Nilai</td>
                <td class="aksi">Keterangan</td>
            </tr>
            <?php
            $no = 1;
            while ($row = mysqli_fetch_assoc($result)) { ?>
                <tr>
                    <td class="text-center">
                        <?php echo $no++; ?>
                    </td>
                    <td>
                        <?php echo $row['indikator'] ?>
                    </td>
                    <td class="text-center">
                        <?php echo $row['nilai'] ?>
                    </td>
                    <td class="aksi">
                        <?php echo "<button type='button' class='btn btn-primary ms-3' data-bs-toggle='modal' data-bs-target='#edit" . $row['id_nilai'] . "' data-bs-whatever='@mdo'>";
                        echo "<i class='fas fa-pencil-alt'></i> Edit";
                        echo "</button>"; ?>
                    </td>
                </tr>

                <div class='modal fade' id='edit<?= $row['id_nilai'] ?>' tabindex='-1' aria-labelledby='exampleModalLabel'
                    aria-hidden='true'>
                    <div class="modal-dialog modal-lg">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h5 class="modal-title" id="exampleModalLabel">Edit Data Nilai
                                </h5>
                                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                            </div>
                            <div class="modal-body">
                                <form method="post" action="#" enctype="multipart/form-data">
                                    <div class="form-group">
                                        <div class="form-group">
                                            <input type="text" class="form-control" id="id_nilai"
                                                value="<?= $row['id_nilai']; ?>" name="id_nilai" hidden>
                                        </div>
                                        <div class="row">
                                            <div class="col-6">
                                                <div class="form-group">
                                                    <label for="indikator">indikator</label>
                                                    <input type="text" class="form-control" id="indikator"
                                                        value="<?= $row['indikator']; ?>" name="indikator" readonly>
                                                </div>
                                            </div>
                                            <div class="col-6">
                                                <div class="form-group">
                                                    <label for="nilai">Nilai</label>
                                                    <input type="text" class="form-control" id="nilai"
                                                        value="<?= $row['nilai']; ?>" name="nilai" required>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="modal-footer">
                                            <button type="button" class="btn btn-secondary"
                                                data-bs-dismiss="modal">Close</button>
                                            <button type="submit" class="btn btn-primary" name="EditNilai"
                                                value="Submit">Submit</button>
                                        </div>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>


            <?php } ?>
        </table>
        <br>
        <div>
            <div class="row">
                <div class="col-7">                    
                </div>
                <div class="col-5">
                    <strong>
                        <?php
                        $tanggal_sekarang = date("d M Y");
                        echo "<p>Bangkalan, $tanggal_sekarang</p>";
                        ?>
                        <p>Guru Pamong</p>
                        <br><br><br>
                        <hr>
                    </strong>

                    <div class="d-grid gap-2 mx-auto mt-5 ms-2">
                        <button class="btn btn-primary aksi" type="button" onclick="printPage()">Cetak</button>
                    </div>

                </div>
            </div>
        </div>

        <br><br>


        <script>
            function printPage() {
                window.print();
            }
        </script>

    </div>

    </div>




    <div class="col-2">
    </div>
    </div>
    </div>

    <!-- Bootstrap Bundle with Popper -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

    <?php include 'footer.php' ?>
</body>

</html>