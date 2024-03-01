<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
</head>
<body>
<div class="">

<div class="tab-content" id="v-pills-tabContent">
  <div class="tab-pane fade show active" id="v-pills-home" role="tabpanel"
    aria-labelledby="v-pills-home-tab">

    <div class="card-body">
      <h5 class="card-title">Informasi Data Siswa</h5>
      <table class="table table-sm">
        <?php
        while ($row = mysqli_fetch_assoc($result)) {
          $formattedDate = strftime('%d %B %Y', strtotime($row['tanggal_lahir']));
          echo "
                    <tr>
                        <td><strong class='text-primary'>Nama</strong></td>
                        <td>" . $row['Nama_siswa'] . "</td>
                    </tr>
                    <tr>
                    <td><strong class='text-primary'>Tempat/Tanggal Lahir</strong></td>
                    <td>" . $formattedDate . "</td>
                    </tr>
                    <tr>
                        <td><strong class='text-primary'>NIS</strong></td>
                        <td>" . $row['NIS'] . "</td>
                    </tr>
                    <tr>
                        <td><strong class='text-primary'>Kelas</strong></td>
                        <td>" . $row['kelas'] . "</td>
                    </tr>
                    <tr>
                        <td><strong class='text-primary'>Jenis Kelamin</strong></td>
                        <td>" . $row['jenis_kelamin'] . "</td>
                        </tr>
                        <tr>
                        <td><strong class='text-primary'>Alamat</strong></td>
                        <td>" . $row['alamat'] . "</td>
                        </tr>
                    <tr>
                        <td><strong class='text-primary'>No HP</strong></td>
                        <td>" . $row['no_hp'] . "</td>
                    <tr>
                        <td><strong class='text-primary'>Email</strong></td>
                        <td>" . $row['email'] . "</td>";
        }
        ?>
      </table>
    </div>

  </div>
                    </div>
    </div>
</body>
</html>