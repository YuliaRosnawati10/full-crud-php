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

$title = 'Ubah Mahasiswa';

include 'layout/header.php'; 

// Mengambil id mahasiswa dari URL
$id_mahasiswa = (int)$_GET['id_mahasiswa'];

// Query ambil data mahasiswa - CARA ALTERNATIF
$query = "SELECT * FROM mahasiswa WHERE id_mahasiswa = $id_mahasiswa";
$result = mysqli_query($db, $query);
$mahasiswa = mysqli_fetch_assoc($result);

// Atau jika fungsi select sudah diperbaiki:
// $mahasiswa = select("SELECT * FROM mahasiswa WHERE id_mahasiswa = $id_mahasiswa")[0];

// Check apakah tombol ubah ditekan
if (isset($_POST['ubah'])) {
    if (update_mahasiswa($_POST) > 0) {
        echo "<script>
                alert('Data Mahasiswa Berhasil Diubah');
                document.location.href = 'mahasiswa.php';
              </script>";
    } else {
        echo "<script>
                alert('Data Mahasiswa Gagal Diubah');
                document.location.href = 'mahasiswa.php';
              </script>";
    }
}

?>

<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <div class="content-header">
      <div class="container-fluid">
        <div class="row mb-2">
          <div class="col-sm-6">
            <h1 class="m-0"><i class="fas fa-edit"></i> <?= $title; ?></h1>
          </div>
          <div class="col-sm-6">
            <ol class="breadcrumb float-sm-right">
              <li class="breadcrumb-item"><a href="mahasiswa.php">Data Mahasiswa</a></li>
              <li class="breadcrumb-item active">Ubah Mahasiswa</li>
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
            <div class="card card-success">
              <div class="card-header">
                <h3 class="card-title"><i class="fas fa-user-edit"></i> Form Ubah Mahasiswa</h3>
              </div>
              <!-- /.card-header -->
              
              <!-- form start -->
              <form action="" method="post" enctype="multipart/form-data">
                <!-- Hidden input untuk ID dan foto lama -->
                <input type="hidden" name="id_mahasiswa" value="<?= $mahasiswa['id_mahasiswa']; ?>">
                <input type="hidden" name="foto_lama" value="<?= $mahasiswa['foto']; ?>">
                
                <div class="card-body">
                  <div class="form-group">
                    <label for="nama"><i class="fas fa-user"></i> Nama Mahasiswa</label>
                    <input type="text" class="form-control" id="nama" name="nama" placeholder="Masukkan nama mahasiswa..." required value="<?= $mahasiswa['nama']; ?>">
                  </div>

                  <div class="row">
                    <div class="col-md-6">
                      <div class="form-group">
                        <label for="prodi"><i class="fas fa-graduation-cap"></i> Program Studi</label>
                        <select name="prodi" id="prodi" class="form-control" required>
                          <?php $prodi = $mahasiswa['prodi']; ?>
                          <option value="Teknik Informatika" <?= $prodi == 'Teknik Informatika' ? 'selected' : '' ?>>Teknik Informatika</option>
                          <option value="Teknik Mesin" <?= $prodi == 'Teknik Mesin' ? 'selected' : '' ?>>Teknik Mesin</option>
                          <option value="Teknik Listrik" <?= $prodi == 'Teknik Listrik' ? 'selected' : '' ?>>Teknik Listrik</option>
                        </select>
                      </div>
                    </div>

                    <div class="col-md-6">
                      <div class="form-group">
                        <label for="jk"><i class="fas fa-venus-mars"></i> Jenis Kelamin</label>
                        <select name="jk" id="jk" class="form-control" required>
                          <?php $jk = $mahasiswa['jk']; ?>
                          <option value="Laki-laki" <?= $jk == 'Laki-laki' ? 'selected' : '' ?>>Laki-laki</option>
                          <option value="Perempuan" <?= $jk == 'Perempuan' ? 'selected' : '' ?>>Perempuan</option>
                        </select>
                      </div>
                    </div>
                  </div>

                  <div class="row">
                    <div class="col-md-6">
                      <div class="form-group">
                        <label for="telepon"><i class="fas fa-phone"></i> Telepon</label>
                        <input type="text" class="form-control" id="telepon" name="telepon" placeholder="Masukkan nomor telepon..." required value="<?= $mahasiswa['telepon']; ?>">
                      </div>
                    </div>

                    <div class="col-md-6">
                      <div class="form-group">
                        <label for="email"><i class="fas fa-envelope"></i> Email</label>
                        <input type="email" class="form-control" id="email" name="email" placeholder="Masukkan email..." required value="<?= $mahasiswa['email']; ?>">
                      </div>
                    </div>
                  </div>

                  <div class="form-group">
                    <label for="alamat"><i class="fas fa-map-marker-alt"></i> Alamat</label>
                    <textarea name="alamat" id="alamat" class="form-control" rows="3" placeholder="Masukkan alamat lengkap..."><?= $mahasiswa['alamat']; ?></textarea>
                  </div>

                  <div class="form-group">
                    <label for="foto"><i class="fas fa-camera"></i> Foto Mahasiswa <small class="text-muted">(Kosongkan jika tidak ingin mengubah)</small></label>
                    <input type="file" class="form-control" id="foto" name="foto" accept="image/*" onchange="previewImg()">
                    <small class="form-text text-muted">Format: JPG, PNG, JPEG (Max: 2MB)</small>
                    
                    <div class="mt-3">
                      <p><strong>Foto Sebelumnya:</strong></p>
                      <img src="assets/img/<?= $mahasiswa['foto']; ?>" alt="foto" width="200px" class="img-thumbnail img-current">
                      <img src="" alt="" class="img-thumbnail img-preview ml-2" width="200px" style="display: none;">
                    </div>
                  </div>
                </div>
                <!-- /.card-body -->

                <div class="card-footer">
                  <a href="mahasiswa.php" class="btn btn-secondary">
                    <i class="fas fa-arrow-left"></i> Kembali
                  </a>
                  <button type="submit" name="ubah" class="btn btn-success float-right">
                    <i class="fas fa-save"></i> Update Data
                  </button>
                </div>
              </form>
            </div>
            <!-- /.card -->
          </div>
        </div>
      </div>
    </section>
    <!-- /.content -->
</div>
<!-- /.content-wrapper -->

<!-- preview image -->
<script>
    function previewImg() {
        const foto = document.querySelector('#foto');
        const imgPreview = document.querySelector('.img-preview');

        if (foto.files && foto.files[0]) {
            const fileFoto = new FileReader();
            fileFoto.readAsDataURL(foto.files[0]);

            fileFoto.onload = function(e) {
                imgPreview.style.display = 'inline-block';
                imgPreview.src = e.target.result;
            }
        }
    }
</script>

<?php include 'layout/footer.php'; ?>