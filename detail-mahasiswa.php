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

<div class="container mt-5">
    <h1>Data <?= $mahasiswa['nama']; ?></h1>
    <hr>
    
    <table id class="table table-bordered table-striped mt-3">
        <tr>
            <td>Nama</td>
            <td>: <?= $mahasiswa['nama']; ?></td>
        </tr>
        <tr>
            <td>Program Studi</td>
            <td>: <?= $mahasiswa['prodi']; ?></td>
        </tr>
        <tr>
            <td>Jenis Kelamin</td>
            <td>: <?= $mahasiswa['jk']; ?></td>
        </tr>
        <tr>
            <td>Telepon</td>
            <td>: <?= $mahasiswa['telepon']; ?></td>
        </tr>
        <tr>
            <td>Alamat</td>
            <td>: <?= $mahasiswa['alamat']; ?></td>
        </tr>
        <tr>
            <td>Email</td>
            <td>: <?= $mahasiswa['email']; ?></td>
        </tr>
        <tr>
    <td width="50%">Foto</td>
    <td>
        <a href="assets/img/<?= $mahasiswa['foto']; ?>" target="_blank">
            <img src="assets/img/<?= $mahasiswa['foto']; ?>" alt="foto" width="150">
        </a>
    </td>
</tr>
    </table>

    <a href="mahasiswa.php" class="btn btn-secondary btn-sm" style="float: right ;">Kembali</a>
</div>

<?php include 'layout/footer.php'; ?>
```