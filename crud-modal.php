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

$title = 'Daftar Akun';

include 'layout/header.php'; 

$data_akun = select("SELECT * FROM akun");

// tampil data berdasarkan user login
$id_akun = $_SESSION['id_akun'];
$data_bylogin = select("SELECT * FROM akun WHERE id_akun = $id_akun");

// Hitung statistik untuk grafik
$jumlah_admin = count(array_filter($data_akun, function($akun) {
    return $akun['level'] == 1;
}));

$jumlah_operator_barang = count(array_filter($data_akun, function($akun) {
    return $akun['level'] == 2;
}));

$jumlah_operator_mahasiswa = count(array_filter($data_akun, function($akun) {
    return $akun['level'] == 3;
}));

$total_akun = count($data_akun);

// jika tombol tambah ditekan jalankan script berikut
if (isset($_POST['tambah'])) {
  if (create_akun($_POST) > 0) {
    echo "<script>
            alert('Data Akun Berhasil Ditambahkan');
            document.location.href = 'crud-modal.php';
            </script>";
  } else {
    echo "<script>
            alert('Data Akun Gagal Ditambahkan');
            document.location.href = 'crud-modal.php';
            </script>";
  }
}

// jika tombol ubah ditekan jalankan script berikut
if (isset($_POST['ubah'])) {
  if (update_akun($_POST) > 0) {
    echo "<script>
            alert('Data Akun Berhasil Diubah');
            document.location.href = 'crud-modal.php';
            </script>";
  } else {
    echo "<script>
            alert('Data Akun Gagal Diubah');
            document.location.href = 'crud-modal.php';
            </script>";
  }
}

// jika tombol hapus ditekan jalankan script berikut
if (isset($_POST['hapus'])) {
  if (delete_akun($_POST['id_akun']) > 0) {
    echo "<script>
            alert('Data Akun Berhasil Dihapus');
            document.location.href = 'crud-modal.php';
            </script>";
  } else {
    echo "<script>
            alert('Data Akun Gagal Dihapus');
            document.location.href = 'crud-modal.php';
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
            <h1 class="m-0"><i class="fas fa-user-cog"></i> Daftar Akun</h1>
          </div>
          <div class="col-sm-6">
            <ol class="breadcrumb float-sm-right">
              <li class="breadcrumb-item active"><?= $title; ?></li>
            </ol>
          </div>
        </div>
      </div>
    </div>
    
    <!-- Main content -->
    <section class="content">
      <div class="container-fluid">
        
        <!-- Grafik -->
        <?php if ($_SESSION['level'] == 1) : ?>
        <div class="row mb-3">
          <div class="col-lg-6">
            <div class="card">
              <div class="card-header">
                <h3 class="card-title">
                  <i class="fas fa-chart-pie mr-1"></i>
                  Distribusi Akun Berdasarkan Level
                </h3>
              </div>
              <div class="card-body">
                <canvas id="pieChart" style="min-height: 250px; height: 250px; max-height: 250px; max-width: 100%;"></canvas>
              </div>
            </div>
          </div>
          
          <div class="col-lg-6">
            <div class="card">
              <div class="card-header">
                <h3 class="card-title">
                  <i class="fas fa-chart-line mr-1"></i>
                  Jumlah Akun Per Level
                </h3>
              </div>
              <div class="card-body">
                <canvas id="lineChart" style="min-height: 250px; height: 250px; max-height: 250px; max-width: 100%;"></canvas>
              </div>
            </div>
          </div>
        </div>
        <?php endif; ?>

        <div class="row">
          <div class="col-12">
            <div class="card">
              <div class="card-header">
                <h3 class="card-title">Tabel Data Akun</h3>
              </div>
              <!-- /.card-header -->
              <div class="card-body">
                <?php if ($_SESSION['level'] == 1) : ?>
                <button type="button" class="btn btn-primary btn-sm mb-2" data-toggle="modal" data-target="#modalTambah">
                  <i class="fas fa-plus"></i> Tambah
                </button>
                <?php endif; ?>

                <div class="table-responsive">
                  <table id="example2" class="table table-bordered table-hover">
                      <thead>
                          <tr>
                              <th>No</th>
                              <th>Nama</th>
                              <th>Username</th>
                              <th>Email</th>
                              <th>Password</th>
                              <th style="min-width: 180px;">Aksi</th>
                          </tr>
                      </thead>
                      <tbody>
                          <?php $no = 1; ?>
                          <?php if ($_SESSION['level'] == 1) : ?>
                          <?php foreach ($data_akun as $akun) : ?>
                          <tr>
                              <td><?= $no++; ?></td>
                              <td><?= $akun['nama']; ?></td>
                              <td><?= $akun['username']; ?></td>
                              <td><?= $akun['email']; ?></td>
                              <td>Password Ter-enkripsi</td>
                              <td class="text-center" style="white-space: nowrap;">
                                  <button type="button" class="btn btn-success btn-sm" data-toggle="modal" data-target="#modalUbah<?= $akun['id_akun']; ?>">
                                    <i class="fas fa-edit"></i> Ubah
                                  </button>
                                  <button type="button" class="btn btn-danger btn-sm" data-toggle="modal" data-target="#modalHapus<?= $akun['id_akun']; ?>">
                                    <i class="fas fa-trash-alt"></i> Hapus
                                  </button>
                              </td>
                          </tr>
                          <?php endforeach; ?>
                          <?php else : ?>
                            <!-- tampil data berdasarkan user login -->
                            <?php foreach ($data_bylogin as $akun) : ?>
                          <tr>
                              <td><?= $no++; ?></td>
                              <td><?= $akun['nama']; ?></td>
                              <td><?= $akun['username']; ?></td>
                              <td><?= $akun['email']; ?></td>
                              <td>Password Ter-enkripsi</td>
                              <td class="text-center" style="white-space: nowrap;">
                                  <button type="button" class="btn btn-success btn-sm" data-toggle="modal" data-target="#modalUbah<?= $akun['id_akun']; ?>">
                                    <i class="fas fa-edit"></i> Ubah
                                  </button>
                              </td>
                          </tr>
                          <?php endforeach; ?>
                          <?php endif; ?>
                      </tbody>
                  </table>
                </div>
              </div>
              <!-- /.card-body -->
            </div>
            <!-- /.card -->
          </div>
        </div>
      </div>
    </section>
    <!-- /.content -->
</div>
<!-- /.content-wrapper -->

<!-- Chart.js Script -->
<?php if ($_SESSION['level'] == 1) : ?>
<script src="https://cdn.jsdelivr.net/npm/chart.js@3.9.1/dist/chart.min.js"></script>
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Pie Chart
    var pieChartCanvas = document.getElementById('pieChart');
    if (pieChartCanvas) {
        var pieChart = new Chart(pieChartCanvas, {
            type: 'pie',
            data: {
                labels: ['Admin', 'Operator Barang', 'Operator Mahasiswa'],
                datasets: [{
                    data: [<?= $jumlah_admin; ?>, <?= $jumlah_operator_barang; ?>, <?= $jumlah_operator_mahasiswa; ?>],
                    backgroundColor: ['#28a745', '#ffc107', '#dc3545'],
                }]
            },
            options: {
                maintainAspectRatio: false,
                responsive: true,
                plugins: {
                    legend: {
                        position: 'bottom',
                    }
                }
            }
        });
    }

    // Line Chart
    var lineChartCanvas = document.getElementById('lineChart');
    if (lineChartCanvas) {
        var lineChart = new Chart(lineChartCanvas, {
            type: 'line',
            data: {
                labels: ['Admin', 'Operator Barang', 'Operator Mahasiswa'],
                datasets: [{
                    label: 'Jumlah Akun',
                    data: [<?= $jumlah_admin; ?>, <?= $jumlah_operator_barang; ?>, <?= $jumlah_operator_mahasiswa; ?>],
                    borderColor: '#007bff',
                    backgroundColor: 'rgba(0, 123, 255, 0.1)',
                    borderWidth: 3,
                    fill: true,
                    tension: 0.4,
                    pointBackgroundColor: ['#28a745', '#ffc107', '#dc3545'],
                    pointBorderColor: '#fff',
                    pointBorderWidth: 2,
                    pointRadius: 6,
                    pointHoverRadius: 8
                }]
            },
            options: {
                maintainAspectRatio: false,
                responsive: true,
                scales: {
                    y: {
                        beginAtZero: true,
                        ticks: {
                            stepSize: 1
                        }
                    }
                },
                plugins: {
                    legend: {
                        display: true,
                        position: 'bottom'
                    }
                }
            }
        });
    }
});
</script>
<?php endif; ?>

<!-- Modal Tambah -->
<div class="modal fade" id="modalTambah" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header bg-primary text-white">
        <h5 class="modal-title" id="exampleModalLabel">Tambah Akun</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        <form action="" method="post">
          <div class="mb-3">
            <label for="nama">Nama</label>
            <input type="text" name="nama" id="nama" class="form-control" required>
          </div>

          <div class="mb-3">
            <label for="username">Username</label>
            <input type="text" name="username" id="username" class="form-control" required>
          </div>

          <div class="mb-3">
            <label for="email">Email</label>
            <input type="email" name="email" id="email" class="form-control" required>
          </div>

          <div class="mb-3">
            <label for="password">Password</label>
            <input type="password" name="password" id="password" class="form-control" required minlength="6">
          </div>

          <div class="mb-3">
            <label for="level">Level</label>
            <select name="level" id="level" class="form-control" required>
              <option value="">-- pilih level --</option>
              <option value="1">Admin</option>
              <option value="2">Operator Barang</option>
              <option value="3">Operator Mahasiswa</option>
            </select>
          </div>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Kembali</button>
        <button type="submit" name="tambah" class="btn btn-primary">Tambah</button>
      </div>
      </form>
    </div>
  </div>
</div>

<!-- Modal Ubah -->
<?php foreach ($data_akun as $akun) : ?>
<div class="modal fade" id="modalUbah<?= $akun['id_akun']; ?>" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header bg-success text-white">
        <h5 class="modal-title" id="exampleModalLabel">Ubah Akun</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        <form action="" method="post">
          <input type="hidden" name="id_akun" value="<?= $akun['id_akun']; ?>">
          
          <div class="mb-3">
            <label for="nama">Nama</label>
            <input type="text" name="nama" id="nama" class="form-control" value="<?= $akun['nama']; ?>" required>
          </div>

          <div class="mb-3">
            <label for="username">Username</label>
            <input type="text" name="username" id="username" class="form-control" value="<?= $akun['username']; ?>" required>
          </div>

          <div class="mb-3">
            <label for="email">Email</label>
            <input type="email" name="email" id="email" class="form-control" value="<?= $akun['email']; ?>" required>
          </div>

          <div class="mb-3">
            <label for="password">Password <small>(Masukkan password baru/lama)</small></label>
            <input type="password" name="password" id="password" class="form-control" minlength="6">
          </div>

          <?php if ($_SESSION['level'] == 1) : ?>
          <div class="mb-3">
            <label for="level">Level</label>
            <select name="level" id="level" class="form-control" required>
              <?php $lvl = $akun['level']; ?>
              <option value="1" <?= $lvl == '1' ? 'selected' : null ?>>Admin</option>
              <option value="2" <?= $lvl == '2' ? 'selected' : null ?>>Operator Barang</option>
              <option value="3" <?= $lvl == '3' ? 'selected' : null ?>>Operator mahasiswa</option>
            </select>
          </div>
          <?php else : ?>
            <input type="hidden" name="level" value="<?= $akun['level']; ?>">
            <?php endif; ?>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Kembali</button>
        <button type="submit" name="ubah" class="btn btn-success">Ubah</button>
      </div>
      </form>
    </div>
  </div>
</div>
<?php endforeach; ?>

<!-- Modal Hapus -->
<?php foreach ($data_akun as $akun) : ?>
<div class="modal fade" id="modalHapus<?= $akun['id_akun']; ?>" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header bg-danger text-white">
        <h5 class="modal-title" id="exampleModalLabel">Hapus Akun</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        <form action="" method="post">
          <input type="hidden" name="id_akun" value="<?= $akun['id_akun']; ?>">
          <p>Yakin Ingin Menghapus Data Akun : <strong><?= $akun['nama']; ?></strong> ?</p>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Kembali</button>
        <button type="submit" name="hapus" class="btn btn-danger">Hapus</button>
      </div>
      </form>
    </div>
  </div>
</div>
<?php endforeach; ?>

<?php include 'layout/footer.php'; ?>