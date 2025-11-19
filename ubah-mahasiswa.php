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

$title = 'Ubah Mahasiswa';

include 'layout/header.php'; 

// Mengambil id mahasiswa dari URL
$id_mahasiswa = (int)$_GET['id_mahasiswa'];

// Query ambil data mahasiswa - CARA ALTERNATIF
$query = "SELECT * FROM mahasiswa WHERE id_mahasiswa = $id_mahasiswa";
$result = mysqli_query($db, $query);
$mahasiswa = mysqli_fetch_assoc($result);

// Atau jika fungsi select sudah diperbaiki:
// $mahasiswa = select("SELECT * FROM mahasiswa WHERE id_mahasiswa = $id_mahasiswa")[0];

// Check apakah tombol ubah ditekan
if (isset($_POST['ubah'])) {
    if (update_mahasiswa($_POST) > 0) {
        echo "<script>
                alert('Data Mahasiswa Berhasil Diubah');
                document.location.href = 'mahasiswa.php';
              </script>";
    } else {
        echo "<script>
                alert('Data Mahasiswa Gagal Diubah');
                document.location.href = 'mahasiswa.php';
              </script>";
    }
}

?>

<div class="container mt-5">
    <h1>Ubah Mahasiswa</h1>
    <hr>
    <form action="" method="post" enctype="multipart/form-data">
        <!-- Hidden input untuk ID dan foto lama -->
        <input type="hidden" name="id_mahasiswa" value="<?= $mahasiswa['id_mahasiswa']; ?>">
        <input type="hidden" name="foto_lama" value="<?= $mahasiswa['foto']; ?>">

        <div class="mb-3">
            <label for="nama" class="form-label">Nama Mahasiswa</label>
            <input type="text" class="form-control" id="nama" name="nama" placeholder="Nama mahasiswa..." required value="<?= $mahasiswa['nama']; ?>">
        </div>

        <div class="row">
            <div class="mb-3 col-6">
                <label for="prodi" class="form-label">Program Studi</label>
                <select name="prodi" id="prodi" class="form-control" required>
                    <?php $prodi = $mahasiswa['prodi']; ?>
                    <option value="Teknik Informatika" <?= $prodi == 'Teknik Informatika' ? 'selected' : '' ?>>Teknik Informatika</option>
                    <option value="Teknik Mesin" <?= $prodi == 'Teknik Mesin' ? 'selected' : '' ?>>Teknik Mesin</option>
                    <option value="Teknik Listrik" <?= $prodi == 'Teknik Listrik' ? 'selected' : '' ?>>Teknik Listrik</option>
                </select>
            </div>

            <div class="mb-3 col-6">
                <label for="jk" class="form-label">Jenis Kelamin</label>
                <select name="jk" id="jk" class="form-control" required>
                    <?php $jk = $mahasiswa['jk']; ?>
                    <option value="Laki-laki" <?= $jk == 'Laki-laki' ? 'selected' : '' ?>>Laki-laki</option>
                    <option value="Perempuan" <?= $jk == 'Perempuan' ? 'selected' : '' ?>>Perempuan</option>
                </select>
            </div>
        </div>

        <div class="mb-3">
            <label for="telepon" class="form-label">Telepon</label>
            <input type="text" class="form-control" id="telepon" name="telepon" placeholder="Telepon..." required value="<?= $mahasiswa['telepon']; ?>">
        </div>

        <div class="mb-3">
            <label for="alamat" class="form-label">Alamat</label>
            <textarea name="alamat" id="alamat"><?= $mahasiswa['alamat']; ?></textarea>
        </div>

        <div class="mb-3">
            <label for="email" class="form-label">Email</label>
            <input type="email" class="form-control" id="email" name="email" placeholder="Email..." required value="<?= $mahasiswa['email']; ?>">
        </div>

        <div class="mb-3">
            <label for="foto" class="form-label">Foto <small class="text-muted">(Kosongkan jika tidak ingin mengubah)</small></label>
            <input type="file" class="form-control" id="foto" name="foto">
            <p class="mt-2">
                <small>Foto Sebelumnya:</small>
            </p>
            <img src="assets/img/<?= $mahasiswa['foto']; ?>" alt="foto" width="150px" class="img-thumbnail">
        </div>
        
        <button type="submit" name="ubah" class="btn btn-primary" style="float: right;">
             Ubah
        </button>
    </form>
</div>

<?php include 'layout/footer.php'; ?>