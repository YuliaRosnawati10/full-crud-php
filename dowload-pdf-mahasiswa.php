<?php
session_start();

// membatasi halaman login
if (!isset($_SESSION['login'])) {
    echo "<script>
            alert('Harap login terlebih dahulu');
            document.location.href = 'login.php';
          </script>";
    exit;
}

// membatasi halaman sesuai user login
if ($_SESSION['level'] != 1 and $_SESSION['level'] != 3) {
    echo "<script>
            alert('Anda tidak punya hak akses');
            document.location.href = 'crud-modal.php';
          </script>";
    exit;
}

require __DIR__ . '/vendor/autoload.php';
require 'config/app.php';

use Spipu\Html2Pdf\Html2Pdf;

// ambil data mahasiswa
$data_barang = select("SELECT * FROM mahasiswa");

// CSS untuk gambar
$content = '
<style type="text/css">
    .gambar {
        width: 50px;
        height: 50px;
        object-fit: cover;
    }
    table {
        border-collapse: collapse;
        width: 100%;
    }
    th, td {
        padding: 5px;
        text-align: center;
    }
</style>
';

// mulai halaman PDF
$content .= "
<page>
    <h3 align='center'>LAPORAN DATA MAHASISWA</h3>
    <table border='1' align='center'>
        <tr>
            <th>No</th>
            <th>Nama</th>
            <th>Program Studi</th>
            <th>Jenis Kelamin</th>
            <th>Telepon</th>
            <th>Email</th>
            <th>Foto</th>
        </tr>
";

$no = 1;
foreach ($data_barang as $barang) {
    // Cek apakah foto ada dan file exists
    $fotoPath = __DIR__ . '/assets/img/' . $barang['foto'];
    $fotoHTML = '';
    
    if (!empty($barang['foto']) && file_exists($fotoPath)) {
        $fotoHTML = '<img src="' . $fotoPath . '" class="gambar">';
    } else {
        $fotoHTML = 'No Image';
    }
    
    $content .= "
        <tr>
            <td>". $no++ ."</td>
            <td>". $barang['nama'] ."</td>
            <td>". $barang['prodi'] ."</td>
            <td>". $barang['jk'] ."</td>
            <td>". $barang['telepon'] ."</td>
            <td>". $barang['email'] ."</td>
            <td>". $fotoHTML ."</td>
        </tr>
    ";
}

$content .= "
    </table>
</page>
";

// proses PDF
$html2pdf = new Html2Pdf('P', 'A4', 'en');
$html2pdf->writeHTML($content);
$html2pdf->output('Laporan-mahasiswa.pdf');