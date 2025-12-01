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

// membatasi halaman sebelum login
if ($_SESSION["level"] != 1 and $_SESSION["level"] != 3) {
    echo "<script>
             alert('Perhatian anda tidak punya hak akses');
             document.location.href ='crud-modal.php';
             </script>";
    exit;
}

$title = 'Tambah Mahasiswa';

include 'layout/header.php'; 

// check apakah tombol tambah ditekan
if (isset($_POST['tambah'])) {
    if (create_mahasiswa($_POST) > 0) {
        echo "<script>
                alert('Data mahasiswa Berhasil Ditambahkan');
                document.location.href = 'mahasiswa.php';
                </script>";
    } else {
        echo "<script>
                alert('Data mahasiswa Gagal Ditambahkan');
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
            <h1 class="m-0"><i class="fas fa-user-plus"></i> <?= $title; ?></h1>
          </div>
          <div class="col-sm-6">
            <ol class="breadcrumb float-sm-right">
              <li class="breadcrumb-item"><a href="mahasiswa.php">Data Mahasiswa</a></li>
              <li class="breadcrumb-item active">Tambah Mahasiswa</li>
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
            <div class="card card-primary">
              <div class="card-header">
                <h3 class="card-title"><i class="fas fa-user-graduate"></i> Form Tambah Mahasiswa</h3>
              </div>
              <!-- /.card-header -->
              
              <!-- form start -->
              <form action="" method="post" enctype="multipart/form-data">
                <div class="card-body">
                  <div class="form-group">
                    <label for="nama"><i class="fas fa-user"></i> Nama Mahasiswa</label>
                    <input type="text" class="form-control" id="nama" name="nama" placeholder="Masukkan nama mahasiswa..." required>
                  </div>

                  <div class="row">
                    <div class="col-md-6">
                      <div class="form-group">
                        <label for="prodi"><i class="fas fa-graduation-cap"></i> Program Studi</label>
                        <select name="prodi" id="prodi" class="form-control" required>
                          <option value="">-- Pilih Program Studi --</option>
                          <option value="Teknik Informatika">Teknik Informatika</option>
                          <option value="Teknik Mesin">Teknik Mesin</option>
                          <option value="Teknik Listrik">Teknik Listrik</option>
                        </select>
                      </div>
                    </div>

                    <div class="col-md-6">
                      <div class="form-group">
                        <label for="jk"><i class="fas fa-venus-mars"></i> Jenis Kelamin</label>
                        <select name="jk" id="jk" class="form-control" required>
                          <option value="">-- Pilih Jenis Kelamin --</option>
                          <option value="Laki-laki">Laki-laki</option>
                          <option value="Perempuan">Perempuan</option>
                        </select>
                      </div>
                    </div>
                  </div>

                  <div class="row">
                    <div class="col-md-6">
                      <div class="form-group">
                        <label for="telepon"><i class="fas fa-phone"></i> Telepon</label>
                        <input type="text" class="form-control" id="telepon" name="telepon" placeholder="Masukkan nomor telepon..." required>
                      </div>
                    </div>

                    <div class="col-md-6">
                      <div class="form-group">
                        <label for="email"><i class="fas fa-envelope"></i> Email</label>
                        <input type="email" class="form-control" id="email" name="email" placeholder="Masukkan email..." required>
                      </div>
                    </div>
                  </div>

                  <div class="form-group">
                    <label for="alamat"><i class="fas fa-map-marker-alt"></i> Alamat</label>
                    <textarea name="alamat" id="alamat" class="form-control" rows="3" placeholder="Masukkan alamat lengkap..."></textarea>
                  </div>

                  <div class="form-group">
                    <label for="foto"><i class="fas fa-camera"></i> Foto</label>
                    <input type="file" class="form-control" id="foto" name="foto" accept="image/*" onchange="previewImg()">
                    <small class="form-text text-muted">Format: JPG, PNG, JPEG (Max: 2MB)</small>
                    
                    <div class="mt-3">
                      <img src="" alt="" class="img-thumbnail img-preview" width="200px" style="display: none;">
                    </div>
                  </div>
                </div>
                <!-- /.card-body -->

                <div class="card-footer">
                  <a href="mahasiswa.php" class="btn btn-secondary">
                    <i class="fas fa-arrow-left"></i> Kembali
                  </a>
                  <button type="submit" name="tambah" class="btn btn-primary float-right">
                    <i class="fas fa-save"></i> Simpan Data
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
                imgPreview.style.display = 'block';
                imgPreview.src = e.target.result;
            }
        }
    }
</script>

<?php include 'layout/footer.php'; ?>