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
if ($_SESSION["level"] != 1 and $_SESSION["level"] != 2) {
    echo "<script>
             alert('Perhatian anda tidak punya hak akses');
             document.location.href ='crud-modal.php';
             </script>";
    exit;
}

$title = 'Daftar Barang';

include 'layout/header.php'; 

if (isset($_POST['filter'])) {
  $tgl_awal = strip_tags($_POST['tgl_awal'] . " 00:00:00");
  $tgl_akhir = strip_tags($_POST['tgl_akhir'] . " 23:59:59");

  // query filter data 
  $data_barang = select("SELECT * FROM barang WHERE tanggal BETWEEN '$tgl_awal' AND '$tgl_akhir' ORDER BY id_barang DESC");
} else {
  // query tampil seluruh data 
  $data_barang = select("SELECT * FROM barang ORDER BY id_barang DESC");
}

?>

  <!-- Content Wrapper. Contains page content -->
  <div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <div class="content-header">
      <div class="container-fluid">
        <div class="row mb-2">
          <div class="col-sm-6">
            <h1 class="m-0"><i class="nav-icon fa fa-list"></i>Data Barang</h1>
          </div><!-- /.col -->
          <div class="col-sm-6">
            <ol class="breadcrumb float-sm-right">
              <li class="breadcrumb-item active">Data Barang</li>
            </ol>
          </div>
          <!-- /.col -->
        </div>
        <!-- /.row -->
      </div><!-- /.container-fluid -->
    </div>
    <!-- /.content-header -->

    <!-- Main content -->
    <section class="content">
      <div class="container-fluid">
        
        <!-- Card Statistik -->
        <div class="row mb-4">
          <div class="col-lg-3 col-6">
            <div class="small-box bg-info">
              <div class="inner">
                <h3><?= count($data_barang); ?></h3>
                <p>Data Barang</p>
              </div>
              <div class="icon">
                <i class="fas fa-list"></i>
              </div>
              <a href="index.php" class="small-box-footer">Lihat Detail <i class="fas fa-arrow-circle-right"></i></a>
            </div>
          </div>

          <div class="col-lg-3 col-6">
            <div class="small-box bg-success">
              <div class="inner">
                <h3>
                  <?php 
                    $query_mahasiswa = "SELECT COUNT(*) as total FROM mahasiswa";
                    $result = mysqli_query($db, $query_mahasiswa);
                    $data = mysqli_fetch_assoc($result);
                    echo $data['total'];
                  ?>
                </h3>
                <p>Data Mahasiswa</p>
              </div>
              <div class="icon">
                <i class="fas fa-users"></i>
              </div>
              <a href="mahasiswa.php" class="small-box-footer">Lihat Detail <i class="fas fa-arrow-circle-right"></i></a>
            </div>
          </div>

          <div class="col-lg-3 col-6">
            <div class="small-box bg-warning">
              <div class="inner">
                <h3>0</h3>
                <p>Data Pegawai</p>
              </div>
              <div class="icon">
                <i class="fas fa-user-tie"></i>
              </div>
              <a href="pegawai.php" class="small-box-footer">Lihat Detail <i class="fas fa-arrow-circle-right"></i></a>
            </div>
          </div>

          <div class="col-lg-3 col-6">
            <div class="small-box bg-danger">
              <div class="inner">
                <h3>
                  <?php 
                    $query_akun = "SELECT COUNT(*) as total FROM akun";
                    $result = mysqli_query($db, $query_akun);
                    $data = mysqli_fetch_assoc($result);
                    echo $data['total'];
                  ?>
                </h3>
                <p>Data Akun</p>
              </div>
              <div class="icon">
                <i class="fas fa-user-cog"></i>
              </div>
              <a href="crud-modal.php" class="small-box-footer">Lihat Detail <i class="fas fa-arrow-circle-right"></i></a>
            </div>
          </div>
        </div>
        
        <!-- Grafik Section -->
        <div class="row mb-4">
          <!-- Grafik Line Chart - Jumlah Barang -->
          <div class="col-lg-6">
            <div class="card">
              <div class="card-header bg-primary">
                <h3 class="card-title"><i class="fas fa-chart-line"></i> Tren Jumlah Barang (Line Chart)</h3>
              </div>
              <div class="card-body">
                <canvas id="lineChart" style="height: 300px;"></canvas>
              </div>
            </div>
          </div>

          <!-- Grafik Line Chart - Harga Barang -->
          <div class="col-lg-6">
            <div class="card">
              <div class="card-header bg-success">
                <h3 class="card-title"><i class="fas fa-chart-area"></i> Tren Harga Barang (Line Chart)</h3>
              </div>
              <div class="card-body">
                <canvas id="lineChartHarga" style="height: 300px;"></canvas>
              </div>
            </div>
          </div>
        </div>
        
        <!-- Tabel Data Barang -->
        <div class="row">
          <div class="col-12">
            <div class="card">
              <div class="card-header">
                <h3 class="card-title">Data Barang</h3>
              </div>
              <!-- /.card-header -->
              <div class="card-body">
                <a href="tambah-barang.php" class="btn btn-primary btn-sm mb-2"><i class="fas fa-plus"></i> Tambah Barang</a>

                   <button type="button" class="btn btn-success btn-sm mb-2" data-toggle="modal" data-target="#modalFilter">
                    <i class="fas fa-search">Filter Data</i>
                   </button>

                  <table id="example2" class="table table-bordered table-hover">
                    <thead>
                      <tr>
                          <th>No</th>
                          <th>Nama</th>
                          <th>Jumlah</th>
                          <th>Harga</th>
                          <th>Barcode</th>
                          <th>Tanggal</th>
                          <th style="min-width: 200px;">Aksi</th>
                      </tr>
                    </thead>
                    <tbody>
                      <?php $no = 1; ?>
                      <?php foreach ($data_barang as $barang) : ?>
                      <tr>
                          <td><?= $no++; ?></td>
                          <td><?= $barang['nama']; ?></td>
                          <td><?= $barang['jumlah']; ?></td>
                          <td>Rp. <?=  number_format($barang['harga'], 0, ',', '.'); ?></td>
                          <td class="text-center">
                              <img alt="barcode" src="barcode.php?codetype=Code128&size=15&text=<?= $barang['barcode']; ?>&print=true" />
                          </td>
                          <td><?= date("d/m/Y | H:i:s", strtotime($barang['tanggal'])); ?></td>
                          <td class="text-center" style="white-space: nowrap;">
                              <a href="ubah-barang.php?id_barang=<?= $barang['id_barang']; ?>" class="btn btn-success btn-sm">
                                <i class="fas fa-edit"></i> Ubah
                              </a>
                              <a href="hapus-barang.php?id_barang=<?= $barang['id_barang']; ?>" class="btn btn-danger btn-sm" onclick="return confirm('Yakin Data Barang Akan Dihapus?');">
                                <i class="fas fa-trash-alt"></i> Hapus
                              </a>
                          </td>
                      </tr>
                      <?php endforeach; ?>
                    </tbody>
                  </table>
                </div>
                
              </div>
              <!-- /.card-body -->
            </div>
            <!-- /.card -->
          </div>
        </div>
      </div><!-- /.container-fluid -->
    </section>
    <!-- /.content -->
  </div>

<!-- Modal Filter -->
<div class="modal fade" id="modalFilter" tabindex="-1" role="dialog">
  <div class="modal-dialog">
    <div class="modal-content">
      
      <div class="modal-header bg-success">
        <h5 class="modal-title"><i class="fas fa-search"></i> Filter Data</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>

      <div class="modal-body">
        <form action="" method="post">
          <div class="mb-3">
            <label for="tgl_awal">Tanggal Awal</label>
            <input type="date" name="tgl_awal" id="tgl_awal" class="form-control" required>
          </div>

          <div class="mb-3">
            <label for="tgl_akhir">Tanggal Akhir</label>
            <input type="date" name="tgl_akhir" id="tgl_akhir" class="form-control" required>
          </div>
      </div>

      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
        <button type="submit" name="filter" class="btn btn-primary">Save changes</button>
      </div>

      </form>
    </div>
  </div>
</div>

<!-- Chart.js Script -->
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
// Ambil data dari tabel barang
const tableData = <?php echo json_encode($data_barang); ?>;

// Proses data untuk grafik
const namaBarang = tableData.map(item => item.nama);
const jumlahBarang = tableData.map(item => parseInt(item.jumlah));
const hargaBarang = tableData.map(item => parseInt(item.harga));

// ===== LINE CHART 1: JUMLAH BARANG =====
const lineCtx = document.getElementById('lineChart').getContext('2d');
const lineChart = new Chart(lineCtx, {
  type: 'line',
  data: {
    labels: namaBarang,
    datasets: [{
      label: 'Jumlah Barang',
      data: jumlahBarang,
      borderColor: '#007bff',
      backgroundColor: 'rgba(0, 123, 255, 0.1)',
      borderWidth: 3,
      fill: true,
      tension: 0.4, // Membuat garis lebih smooth/melengkung
      pointRadius: 5,
      pointHoverRadius: 7,
      pointBackgroundColor: '#007bff',
      pointBorderColor: '#fff',
      pointBorderWidth: 2,
      pointHoverBackgroundColor: '#fff',
      pointHoverBorderColor: '#007bff'
    }]
  },
  options: {
    responsive: true,
    maintainAspectRatio: false,
    plugins: {
      legend: {
        display: true,
        position: 'top',
        labels: {
          font: {
            size: 13
          }
        }
      },
      tooltip: {
        backgroundColor: 'rgba(0, 0, 0, 0.8)',
        padding: 12,
        titleFont: {
          size: 14
        },
        bodyFont: {
          size: 13
        },
        callbacks: {
          label: function(context) {
            return 'Jumlah: ' + context.parsed.y + ' unit';
          }
        }
      }
    },
    scales: {
      y: {
        beginAtZero: true,
        ticks: {
          stepSize: 10,
          font: {
            size: 11
          }
        },
        title: {
          display: true,
          text: 'Jumlah (Unit)',
          font: {
            size: 12,
            weight: 'bold'
          }
        },
        grid: {
          color: 'rgba(0, 0, 0, 0.05)'
        }
      },
      x: {
        ticks: {
          font: {
            size: 10
          },
          maxRotation: 45,
          minRotation: 45
        },
        title: {
          display: true,
          text: 'Nama Barang',
          font: {
            size: 12,
            weight: 'bold'
          }
        },
        grid: {
          display: false
        }
      }
    },
    interaction: {
      intersect: false,
      mode: 'index'
    }
  }
});

// ===== LINE CHART 2: HARGA BARANG =====
const lineCtxHarga = document.getElementById('lineChartHarga').getContext('2d');
const lineChartHarga = new Chart(lineCtxHarga, {
  type: 'line',
  data: {
    labels: namaBarang,
    datasets: [{
      label: 'Harga Barang',
      data: hargaBarang,
      borderColor: '#28a745',
      backgroundColor: 'rgba(40, 167, 69, 0.1)',
      borderWidth: 3,
      fill: true,
      tension: 0.4,
      pointRadius: 5,
      pointHoverRadius: 7,
      pointBackgroundColor: '#28a745',
      pointBorderColor: '#fff',
      pointBorderWidth: 2,
      pointHoverBackgroundColor: '#fff',
      pointHoverBorderColor: '#28a745'
    }]
  },
  options: {
    responsive: true,
    maintainAspectRatio: false,
    plugins: {
      legend: {
        display: true,
        position: 'top',
        labels: {
          font: {
            size: 13
          }
        }
      },
      tooltip: {
        backgroundColor: 'rgba(0, 0, 0, 0.8)',
        padding: 12,
        titleFont: {
          size: 14
        },
        bodyFont: {
          size: 13
        },
        callbacks: {
          label: function(context) {
            return 'Harga: Rp ' + context.parsed.y.toLocaleString('id-ID');
          }
        }
      }
    },
    scales: {
      y: {
        beginAtZero: true,
        ticks: {
          font: {
            size: 11
          },
          callback: function(value) {
            return 'Rp ' + value.toLocaleString('id-ID');
          }
        },
        title: {
          display: true,
          text: 'Harga (Rupiah)',
          font: {
            size: 12,
            weight: 'bold'
          }
        },
        grid: {
          color: 'rgba(0, 0, 0, 0.05)'
        }
      },
      x: {
        ticks: {
          font: {
            size: 10
          },
          maxRotation: 45,
          minRotation: 45
        },
        title: {
          display: true,
          text: 'Nama Barang',
          font: {
            size: 12,
            weight: 'bold'
          }
        },
        grid: {
          display: false
        }
      }
    },
    interaction: {
      intersect: false,
      mode: 'index'
    }
  }
});
</script>

<?php include 'layout/footer.php'; ?>