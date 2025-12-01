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

$title = 'Daftar Pegawai';

include 'layout/header.php';

// Ambil data pegawai untuk statistik
$data_pegawai = select("SELECT * FROM pegawai");

// Hitung jumlah pegawai per jabatan
$jabatan_counts = [];
foreach ($data_pegawai as $pegawai) {
    $jabatan = $pegawai['jabatan'];
    if (isset($jabatan_counts[$jabatan])) {
        $jabatan_counts[$jabatan]++;
    } else {
        $jabatan_counts[$jabatan] = 1;
    }
}

$total_pegawai = count($data_pegawai);

// Prepare data untuk Chart.js
$jabatan_labels = [];
$jabatan_data = [];
$jabatan_colors = ['#007bff', '#28a745', '#ffc107', '#dc3545', '#6f42c1', '#fd7e14', '#20c997', '#17a2b8'];

$color_index = 0;
$chart_colors = [];
foreach ($jabatan_counts as $jabatan => $count) {
    $jabatan_labels[] = $jabatan;
    $jabatan_data[] = $count;
    $chart_colors[] = $jabatan_colors[$color_index % count($jabatan_colors)];
    $color_index++;
}

?>

<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1 class="m-0"><i class="fas fa-users"></i> Data Pegawai</h1>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item active">Data Pegawai</li>
                    </ol>
                </div>
            </div>
        </div>
    </div>

    <!-- Main content -->
    <section class="content">
        <div class="container-fluid">
            
            <!-- Grafik Section -->
            <div class="row mb-3">
                <div class="col-lg-6">
                    <div class="card">
                        <div class="card-header">
                            <h3 class="card-title">
                                <i class="fas fa-chart-pie mr-1"></i>
                                Distribusi Pegawai Berdasarkan Jabatan
                            </h3>
                        </div>
                        <div class="card-body">
                            <canvas id="pieChartPegawai" style="min-height: 250px; height: 250px; max-height: 250px; max-width: 100%;"></canvas>
                        </div>
                    </div>
                </div>
                
                <div class="col-lg-6">
                    <div class="card">
                        <div class="card-header">
                            <h3 class="card-title">
                                <i class="fas fa-chart-line mr-1"></i>
                                Jumlah Pegawai Per Jabatan
                            </h3>
                        </div>
                        <div class="card-body">
                            <canvas id="lineChartPegawai" style="min-height: 250px; height: 250px; max-height: 250px; max-width: 100%;"></canvas>
                        </div>
                    </div>
                </div>
            </div>

            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Tabel Data Pegawai (Realtime)</h3>
                    <div class="card-tools">
                    </div>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-bordered table-hover">
                            <thead>
                                <tr>
                                    <th>No</th>
                                    <th>Name</th>
                                    <th>Jabatan</th>
                                    <th>Email</th>
                                    <th>Telepon</th>
                                    <th>Alamat</th>
                                </tr>
                            </thead>
                            <tbody id="live_data">
                                
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </section>
</div>

<!-- Chart.js Script -->
<script src="https://cdn.jsdelivr.net/npm/chart.js@3.9.1/dist/chart.min.js"></script>
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Pie Chart
    var pieChartCanvas = document.getElementById('pieChartPegawai');
    if (pieChartCanvas) {
        var pieChart = new Chart(pieChartCanvas, {
            type: 'pie',
            data: {
                labels: <?= json_encode($jabatan_labels); ?>,
                datasets: [{
                    data: <?= json_encode($jabatan_data); ?>,
                    backgroundColor: <?= json_encode($chart_colors); ?>,
                }]
            },
            options: {
                maintainAspectRatio: false,
                responsive: true,
                plugins: {
                    legend: {
                        position: 'bottom',
                    },
                    tooltip: {
                        callbacks: {
                            label: function(context) {
                                var label = context.label || '';
                                var value = context.parsed || 0;
                                var total = context.dataset.data.reduce((a, b) => a + b, 0);
                                var percentage = ((value / total) * 100).toFixed(1);
                                return label + ': ' + value + ' (' + percentage + '%)';
                            }
                        }
                    }
                }
            }
        });
    }

    // Line Chart
    var lineChartCanvas = document.getElementById('lineChartPegawai');
    if (lineChartCanvas) {
        var lineChart = new Chart(lineChartCanvas, {
            type: 'line',
            data: {
                labels: <?= json_encode($jabatan_labels); ?>,
                datasets: [{
                    label: 'Jumlah Pegawai',
                    data: <?= json_encode($jabatan_data); ?>,
                    borderColor: '#007bff',
                    backgroundColor: 'rgba(0, 123, 255, 0.1)',
                    borderWidth: 3,
                    fill: true,
                    tension: 0.4,
                    pointBackgroundColor: <?= json_encode($chart_colors); ?>,
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

// Realtime data update
$(document).ready(function() {
    // Load data immediately
    getPegawai();
    
    // Then update every 2 seconds
    setInterval(function() {
        getPegawai()
    }, 2000);
});

function getPegawai() {
    $.ajax({
        url: "realtime-pegawai.php",
        type: "GET",
        success: function(response) {
            $('#live_data').html(response)
        }
    });
}
</script>

<?php include 'layout/footer.php'; ?>