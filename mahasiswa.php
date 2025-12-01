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

$title = "Daftar Mahasiswa";

include 'layout/header.php';

// menampilkan data mahasiswa
$data_mahasiswa = select("SELECT * FROM mahasiswa ORDER BY id_mahasiswa DESC");

// Hitung statistik
$total_mahasiswa = count($data_mahasiswa);
$total_laki = 0;
$total_perempuan = 0;

// Hitung jumlah per jenis kelamin dengan pengecekan berbagai kemungkinan nama kolom
foreach ($data_mahasiswa as $mhs) {
    // Cek berbagai kemungkinan nama kolom jenis kelamin
    $jk = '';
    if (isset($mhs['jenis_kelamin'])) {
        $jk = $mhs['jenis_kelamin'];
    } elseif (isset($mhs['jk'])) {
        $jk = $mhs['jk'];
    } elseif (isset($mhs['kelamin'])) {
        $jk = $mhs['kelamin'];
    } elseif (isset($mhs['gender'])) {
        $jk = $mhs['gender'];
    }
    
    // Hitung berdasarkan jenis kelamin
    if (!empty($jk)) {
        if (stripos($jk, 'laki') !== false || strtolower($jk) == 'l' || strtolower($jk) == 'male') {
            $total_laki++;
        } elseif (stripos($jk, 'perempuan') !== false || strtolower($jk) == 'p' || strtolower($jk) == 'female') {
            $total_perempuan++;
        }
    }
}

// Hitung jumlah per prodi dengan pengecekan key
$prodi_count = [];
foreach ($data_mahasiswa as $mhs) {
    if (isset($mhs['prodi']) && !empty($mhs['prodi'])) {
        $prodi = $mhs['prodi'];
        if (isset($prodi_count[$prodi])) {
            $prodi_count[$prodi]++;
        } else {
            $prodi_count[$prodi] = 1;
        }
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
            <h1 class="m-0"><i class="fas fa-users"></i> <?= $title; ?></h1>
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
        
        <!-- Grafik Section -->
        <div class="row mb-4">
          <!-- Grafik Jenis Kelamin - Doughnut Chart -->
          <div class="col-lg-6">
            <div class="card">
              <div class="card-header bg-primary">
                <h3 class="card-title"><i class="fas fa-chart-pie"></i> Distribusi Jenis Kelamin</h3>
              </div>
              <div class="card-body">
                <canvas id="genderChart" style="height: 300px;"></canvas>
              </div>
            </div>
          </div>

          <!-- Grafik Program Studi - Line Chart -->
          <div class="col-lg-6">
            <div class="card">
              <div class="card-header bg-success">
                <h3 class="card-title"><i class="fas fa-chart-line"></i> Tren Mahasiswa per Program Studi</h3>
              </div>
              <div class="card-body">
                <canvas id="prodiChart" style="height: 300px;"></canvas>
              </div>
            </div>
          </div>
        </div>

        <!-- Tabel Data Mahasiswa -->
        <div class="row">
          <div class="col-12">
            <div class="card">
              <div class="card-header">
                <h3 class="card-title">Tabel Data Mahasiswa</h3>
              </div>
              <!-- /.card-header -->
              <div class="card-body">
                <div class="mb-3">
                  <a href="tambah-mahasiswa.php" class="btn btn-primary btn-sm">
                      <i class="fas fa-plus-circle"></i> Tambah
                  </a>
                  <a href="dowload-excel-mahasiswa.php" class="btn btn-success btn-sm">
                      <i class="fas fa-file-excel"></i> Download Excel
                  </a>
                  <a href="dowload-pdf-mahasiswa.php" class="btn btn-danger btn-sm" target="_blank">
                      <i class="fas fa-file-pdf"></i> Download PDF
                  </a>
                </div>

                <div class="table-responsive">
                  <table id="serverside" class="table table-bordered table-hover">
                      <thead>
                          <tr>
                              <th>No</th>
                              <th>Nama</th>
                              <th>Prodi</th>
                              <th>Jenis Kelamin</th>
                              <th>Telepon</th>
                              <th style="min-width: 250px;">Aksi</th>
                          </tr>
                      </thead>
                      <tbody>
                          
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
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
// Data mahasiswa dari PHP
const dataMahasiswa = <?php echo json_encode($data_mahasiswa); ?>;

// Data untuk Grafik Jenis Kelamin
const genderData = {
  labels: ['Laki-laki', 'Perempuan'],
  data: [<?= $total_laki; ?>, <?= $total_perempuan; ?>],
  colors: ['#007bff', '#dc3545']
};

// Grafik Doughnut Chart - Jenis Kelamin (lebih modern dari Pie)
const genderCtx = document.getElementById('genderChart').getContext('2d');
const genderChart = new Chart(genderCtx, {
  type: 'doughnut',
  data: {
    labels: genderData.labels,
    datasets: [{
      data: genderData.data,
      backgroundColor: genderData.colors,
      borderWidth: 3,
      borderColor: '#fff',
      hoverOffset: 10
    }]
  },
  options: {
    responsive: true,
    maintainAspectRatio: false,
    plugins: {
      legend: {
        position: 'bottom',
        labels: {
          padding: 20,
          font: {
            size: 13,
            weight: 'bold'
          },
          usePointStyle: true,
          pointStyle: 'circle'
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
            let label = context.label || '';
            if (label) {
              label += ': ';
            }
            label += context.parsed + ' mahasiswa';
            
            // Hitung persentase
            const total = context.dataset.data.reduce((a, b) => a + b, 0);
            const percentage = ((context.parsed / total) * 100).toFixed(1);
            label += ' (' + percentage + '%)';
            
            return label;
          }
        }
      }
    },
    cutout: '65%' // Membuat lubang di tengah (doughnut style) - lebih besar lubangnya
  }
});

// Data untuk Grafik Program Studi
const prodiCount = <?php echo json_encode($prodi_count); ?>;
const prodiLabels = Object.keys(prodiCount);
const prodiData = Object.values(prodiCount);

// Generate warna untuk setiap prodi (gradient colors)
const prodiColors = [
  '#28a745', '#ffc107', '#17a2b8', '#6f42c1', 
  '#e83e8c', '#fd7e14', '#20c997', '#6610f2'
];

// ===== LINE CHART - Program Studi =====
const prodiCtx = document.getElementById('prodiChart').getContext('2d');
const prodiChart = new Chart(prodiCtx, {
  type: 'line',
  data: {
    labels: prodiLabels,
    datasets: [{
      label: 'Jumlah Mahasiswa',
      data: prodiData,
      borderColor: '#28a745',
      backgroundColor: 'rgba(40, 167, 69, 0.1)',
      borderWidth: 3,
      fill: true,
      tension: 0.4, // Smooth curve
      pointRadius: 6,
      pointHoverRadius: 8,
      pointBackgroundColor: prodiColors.slice(0, prodiLabels.length),
      pointBorderColor: '#fff',
      pointBorderWidth: 2,
      pointHoverBackgroundColor: '#fff',
      pointHoverBorderColor: '#28a745',
      pointHoverBorderWidth: 3
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
            size: 13,
            weight: 'bold'
          },
          padding: 15,
          usePointStyle: true
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
            return 'Jumlah: ' + context.parsed.y + ' mahasiswa';
          }
        }
      }
    },
    scales: {
      y: {
        beginAtZero: true,
        ticks: {
          stepSize: 1,
          font: {
            size: 11
          }
        },
        title: {
          display: true,
          text: 'Jumlah Mahasiswa',
          font: {
            size: 12,
            weight: 'bold'
          }
        },
        grid: {
          color: 'rgba(0, 0, 0, 0.05)',
          drawBorder: false
        }
      },
      x: {
        ticks: {
          font: {
            size: 11
          },
          maxRotation: 45,
          minRotation: 0
        },
        title: {
          display: true,
          text: 'Program Studi',
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

<script>
$(document).ready(function() {
    $('#example').DataTable({
        "responsive": true,
        "lengthChange": true,
        "autoWidth": false,
    });
});
</script>

<?php include 'layout/footer.php'; ?>