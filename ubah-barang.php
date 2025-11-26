<?php

session_start();
if (!isset($_SESSION["login"])) {
    echo "<script>
            alert('Login Dulu!!');
            document.location.href='login.php';
        </script>";
    exit;
}


$title = 'Ubah Barang';

include 'layout/header.php';

$id_barang = (int) $_GET['id_barang'];

$barang = mysqli_query($db, "SELECT * FROM barang WHERE id_barang=$id_barang");

while ($barang_data = mysqli_fetch_array($barang)) {
    $id_barang = $barang_data['id_barang'];
    $nama = $barang_data['nama'];
    $jumlah = $barang_data['jumlah'];
    $harga = $barang_data['harga'];

}

if (isset($_POST['ubah'])) {
    if (update_barang($_POST) > 0) {
        echo "<script>
                        alert('Data Barang Berhasil Di Ubah');
                        document.location.href='index.php';
                    </script>";
    } else {
        echo "<script>
                        alert('Data Barang Gagal Di Ubah');
                        document.location.href='index.php';
                    </script>";
    }

}
?>

<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <div class="content-header">
      <div class="container-fluid">
        <div class="row mb-2">
          <div class="col-sm-6">
            <h1 class="m-0"><i class="fas fa-edit"></i> Ubah Barang</h1>
          </div>
          <div class="col-sm-6">
            <ol class="breadcrumb float-sm-right">
              <li class="breadcrumb-item"><a href="index.php">Data Barang</a></li>
              <li class="breadcrumb-item active">Ubah Barang</li>
            </ol>
          </div>
        </div>
      </div>
    </div>
    <!-- /.content-header -->

    <!-- Main content -->
    <section class="content">
      <div class="container-fluid">
        <div class="row">
          <div class="col-md-12">
            <div class="card card-success">
              <div class="card-header">
                <h3 class="card-title"><i class="fas fa-edit"></i> Form Ubah Barang</h3>
              </div>
              <!-- /.card-header -->
              
              <!-- form start -->
              <form action="" method="POST">
                <input type="hidden" name="id_barang" value="<?= $id_barang ?>">
                
                <div class="card-body">
                  <div class="form-group">
                    <label for="nama"><i class="fas fa-box"></i> Nama Barang</label>
                    <input type="text" class="form-control" id="nama" name="nama" value="<?= $nama ?>" placeholder="Masukkan nama barang..." required>
                  </div>
                  
                  <div class="row">
                    <div class="col-md-6">
                      <div class="form-group">
                        <label for="jumlah"><i class="fas fa-sort-numeric-up"></i> Jumlah</label>
                        <input type="number" class="form-control" id="jumlah" name="jumlah" value="<?= $jumlah ?>" placeholder="Masukkan jumlah..." min="1" required>
                      </div>
                    </div>
                    
                    <div class="col-md-6">
                      <div class="form-group">
                        <label for="harga"><i class="fas fa-money-bill-wave"></i> Harga Barang</label>
                        <input type="number" class="form-control" id="harga" name="harga" value="<?= $harga ?>" placeholder="Masukkan harga..." min="0" required>
                      </div>
                    </div>
                  </div>
                </div>
                <!-- /.card-body -->

                <div class="card-footer">
                  <a href="index.php" class="btn btn-secondary">
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

<?php include 'layout/footer.php'; ?>