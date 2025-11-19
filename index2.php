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

$data_barang = select("SELECT * FROM barang ORDER BY id_barang DESC"); 
?>

    <div class="container mt-5">
        <h1>Data Barang</h1>
        <hr>
        <a href="tambah-barang.php" class="btn btn-primary mb-1"><i class="fas fa-plus-circle"></i>Tambah</a>
        <table id="example" class="table table-bordered table-striped">
           
        </table>
    </div>

<?php include 'layout/footer.php'; ?>