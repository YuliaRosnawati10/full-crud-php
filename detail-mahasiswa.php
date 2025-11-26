<?php 

session_start();

// membatasi halaman sebelum login
if (!isset($_SESSION["login"])) {
    echo "<script>
             alert('login dulu');
             document.location.href ='login.php';
             </script>";
    exit;
}

$title = 'Detail Mahasiswa';

include 'layout/header.php';

// mengambil id mahasiswa dari url
$id_mahasiswa = (int)$_GET['id_mahasiswa'];

// menampilkan data mahasiswa (asumsi $db adalah koneksi database)
$query = "SELECT * FROM mahasiswa WHERE id_mahasiswa = $id_mahasiswa";
$result = mysqli_query($db, $query);
$mahasiswa = mysqli_fetch_assoc($result);

?>

<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <div class="content-header">
      <div class="container-fluid">
        <div class="row mb-2">
          <div class="col-sm-6">
            <h1 class="m-0"><i class="fas fa-user-circle"></i> Detail Mahasiswa</h1>
          </div>
          <div class="col-sm-6">
            <ol class="breadcrumb float-sm-right">
              <li class="breadcrumb-item"><a href="mahasiswa.php">Data Mahasiswa</a></li>
              <li class="breadcrumb-item active">Detail</li>
            </ol>
          </div>
        </div>
      </div>
    </div>
    
    <!-- Main content -->
    <section class="content">
      <div class="container-fluid">
        <div class="row">
          <div class="col-md-12">
            <div class="card card-info">
              <div class="card-header">
                <h3 class="card-title"><i class="fas fa-info-circle"></i> Data <?= $mahasiswa['nama']; ?></h3>
              </div>
              <!-- /.card-header -->
              
              <div class="card-body">
                <table class="table table-bordered table-striped">
                  <tr>
                    <td width="30%"><i class="fas fa-user"></i> Nama</td>
                    <td>: <?= $mahasiswa['nama']; ?></td>
                  </tr>
                  <tr>
                    <td><i class="fas fa-graduation-cap"></i> Program Studi</td>
                    <td>: <?= $mahasiswa['prodi']; ?></td>
                  </tr>
                  <tr>
                    <td><i class="fas fa-venus-mars"></i> Jenis Kelamin</td>
                    <td>: <?= $mahasiswa['jk']; ?></td>
                  </tr>
                  <tr>
                    <td><i class="fas fa-phone"></i> Telepon</td>
                    <td>: <?= $mahasiswa['telepon']; ?></td>
                  </tr>
                  <tr>
                    <td><i class="fas fa-envelope"></i> Email</td>
                    <td>: <?= $mahasiswa['email']; ?></td>
                  </tr>
                  <tr>
                    <td><i class="fas fa-map-marker-alt"></i> Alamat</td>
                    <td>: <?= !empty($mahasiswa['alamat']) ? $mahasiswa['alamat'] : '-'; ?></td>
                  </tr>
                  <tr>
                    <td><i class="fas fa-camera"></i> Foto</td>
                    <td>
                      <a href="assets/img/<?= $mahasiswa['foto']; ?>" target="_blank">
                        <img src="assets/img/<?= $mahasiswa['foto']; ?>" alt="foto" width="200" class="img-thumbnail">
                      </a>
                    </td>
                  </tr>
                </table>
              </div>
              <!-- /.card-body -->

              <div class="card-footer">
                <a href="mahasiswa.php" class="btn btn-secondary">
                  <i class="fas fa-arrow-left"></i> Kembali
                </a>
                <a href="ubah-mahasiswa.php?id_mahasiswa=<?= $mahasiswa['id_mahasiswa']; ?>" class="btn btn-success float-right">
                  <i class="fas fa-edit"></i> Edit Data
                </a>
              </div>
            </div>
            <!-- /.card -->
          </div>
        </div>
      </div>
    </section>
    <!-- /.content -->
</div>

<?php include 'layout/footer.php'; ?>