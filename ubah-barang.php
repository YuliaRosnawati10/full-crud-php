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



$title = 'Ubah Barang';

include 'layout/header.php'; 

// mengambil id_barang dari url
$id_barang = (int)$_GET['id_barang'];

// Koneksi database
global $db;

// Query untuk mengambil data
$query = "SELECT * FROM barang WHERE id_barang = $id_barang";
$result = mysqli_query($db, $query);
$barang = mysqli_fetch_assoc($result);

// Cek apakah data ditemukan
if (!$barang) {
    echo "<script>
            alert('Data Barang Tidak Ditemukan');
            document.location.href = 'index.php';
          </script>";
    exit;
}

// check apakah tombol ubah ditekan
if (isset($_POST['ubah'])) {
    if (update_barang($_POST) > 0) {
        echo "<script>
                alert('Data Barang Berhasil Diubah');
                document.location.href = 'index.php';
              </script>";
    } else {
        echo "<script>
                alert('Data Barang Gagal Diubah');
                document.location.href = 'index.php';
              </script>";
    }
}
?>

<div class="container mt-5">
    <h1>Ubah Barang</h1>
    <hr>
    <form action="" method="post">
        <input type="hidden" name="id_barang" value="<?= $barang['id_barang']; ?>">
        <div class="mb-3">
            <label for="nama" class="form-label">Nama Barang</label>
            <input type="text" class="form-control" id="nama" name="nama" value="<?= htmlspecialchars($barang['nama']); ?>" placeholder="Nama barang..." required>
        </div>
        <div class="mb-3">
            <label for="jumlah" class="form-label">Jumlah</label>
            <input type="number" class="form-control" id="jumlah" name="jumlah" value="<?= htmlspecialchars($barang['jumlah']); ?>" placeholder="jumlah barang..." required>
        </div>
        <div class="mb-3">
            <label for="harga" class="form-label">Harga</label>
            <input type="number" class="form-control" id="harga" name="harga" value="<?= htmlspecialchars($barang['harga']); ?>" placeholder="Harga barang" required>
        </div>
        <button type="submit" name="ubah" class="btn btn-primary" style="float: right;">Ubah</button>
    </form>
</div>

<?php include 'layout/footer.php'; ?>