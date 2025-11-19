<?php 

function select($query)
{
    global $db;

    $result = mysqli_query($db, $query);
    $rows = [];

    while ($row = mysqli_fetch_assoc($result)) {
        $rows[] = $row;
    }
    return $rows;
}

//fungsi menambahkan (create)
function create_barang($post)
{
    global $db;

    $nama = $post['nama'];
    $jumlah = $post['jumlah'];
    $harga = $post['harga'];
    $barcode = rand(100000, 999999);

    //query tambah data
    $query = "INSERT INTO barang VALUES(null, '$nama', '$jumlah', '$harga', '$barcode', CURRENT_TIMESTAMP())";
    $result = mysqli_query($db, $query);

    if(!$result) {
        die("Query gagal: " . mysqli_error($db));
    }

    return mysqli_affected_rows($db);
}

// fungsi mengubah data barang
function update_barang($post)
{
    global $db;

    $id_barang   = $post['id_barang'];
    $nama        = $post['nama'];
    $jumlah      = $post['jumlah'];
    $harga       = $post['harga'];

    // query ubah data
    $query = "UPDATE barang SET nama ='$nama', jumlah = '$jumlah', harga= '$harga' WHERE id_barang = $id_barang";

    mysqli_query($db, $query);

    return mysqli_affected_rows($db);
}

// fungsi hapus data barang
function delete_barang($id_barang)
{
    global $db;

    // query hapus data barang
    $query ="DELETE FROM barang WHERE id_barang = $id_barang";

    mysqli_query($db, $query);

    return mysqli_affected_rows($db);
}

//fungsi menambahkan (create)
function create_mahasiswa($post)
{
    global $db;

    $nama      = strip_tags($post['nama']);
    $prodi     = strip_tags($post['prodi']);
    $jk        = strip_tags($post['jk']);
    $telepon   = strip_tags($post['telepon']);
    $alamat    = $post['alamat'];
    $email     = strip_tags($post['email']);
    $foto      = upload_foto();

    // check upload file
    if (!$foto) {
        return false;
    }
    

    //query tambah data
    $query = "INSERT INTO mahasiswa VALUES(null, '$nama', '$prodi', '$jk', '$telepon', '$alamat', '$email', '$foto')";
    $result = mysqli_query($db, $query);

    if(!$result) {
        die("Query gagal: " . mysqli_error($db));
    }

    return mysqli_affected_rows($db);
}

function upload_foto()
{
    $namaFile   = $_FILES['foto']['name'];
    $ukuranFile = $_FILES['foto']['size'];
    $error      = $_FILES['foto']['error'];
    $tmpName    = $_FILES['foto']['tmp_name'];

    // cek jika tidak ada file yang diupload
    if ($error === 4) {
        echo "<script>
                alert('Pilih file gambar terlebih dahulu!');
                document.location.href = 'tambah-mahasiswa.php';
              </script>";
        die();
    }

    // ekstensi file yang diizinkan
    $extensifileValid = ['jpg', 'jpeg', 'png'];
    $extensifile = explode('.', $namaFile);
    $extensifile = strtolower(end($extensifile));

    if (!in_array($extensifile, $extensifileValid)) {
        echo "<script>
                alert('Format file tidak valid (hanya JPG, JPEG, PNG)');
                document.location.href = 'tambah-mahasiswa.php';
              </script>";
        die();
    }

    // cek ukuran file max 2MB
    if ($ukuranFile > 2048000) {
        echo "<script>
                alert('Ukuran file terlalu besar (maks 2MB)');
                document.location.href = 'tambah-mahasiswa.php';
              </script>";
        die();
    }

    // generate nama file baru dan simpan
    $namaFileBaru = uniqid();
    $namaFileBaru .= '.';
    $namaFileBaru .= $extensifile;

    move_uploaded_file($tmpName, 'assets/img/' . $namaFileBaru);

    return $namaFileBaru;
}

function delete_mahasiswa($id_mahasiswa)
{
    global $db;

    // ambil foto mahasiswa
    $queryFoto = mysqli_query($db, "SELECT foto FROM mahasiswa WHERE id_mahasiswa = $id_mahasiswa");
    $dataFoto = mysqli_fetch_assoc($queryFoto);

    // hapus file foto dari folder
    if ($dataFoto && file_exists("assets/img/" . $dataFoto['foto'])) {
        unlink("assets/img/" . $dataFoto['foto']);
    }

    // query hapus data mahasiswa dari database
    $query = "DELETE FROM mahasiswa WHERE id_mahasiswa = $id_mahasiswa";
    mysqli_query($db, $query);

    return mysqli_affected_rows($db);
}

function update_mahasiswa($post)
{
    global $db;

    $id_mahasiswa = strip_tags($post['id_mahasiswa']);
    $nama      = strip_tags($post['nama']);
    $prodi     = strip_tags($post['prodi']);
    $jk        = strip_tags($post['jk']);
    $telepon   = strip_tags($post['telepon']);
    $alamat    = $post['alamat'];
    $email     = strip_tags($post['email']);
    $fotoLama  = strip_tags($post['foto_lama']);  // ✅ Perbaiki dari 'fotoLama' ke 'foto_lama'

    // check upload foto baru atau tidak
    if ($_FILES['foto']['error'] == 4) {
        $foto = $fotoLama;
    } else {
        $foto = upload_foto();  // ✅ Panggil fungsi upload_foto() yang sudah ada
    }
    
    // ✅ Query UPDATE, bukan INSERT
    $query = "UPDATE mahasiswa SET nama = '$nama', prodi = '$prodi', jk = '$jk', telepon = '$telepon', alamat = '$alamat', email = '$email', foto = '$foto' WHERE id_mahasiswa = $id_mahasiswa";
    
    $result = mysqli_query($db, $query);

    if(!$result) {
        die("Query gagal: " . mysqli_error($db));
    }

    return mysqli_affected_rows($db);
}

// fungsi tambah akun
function create_akun($post)
{
    global $db;

    $nama          = strip_tags($post['nama']);
    $username      = strip_tags($post['username']);
    $email         = strip_tags($post['email']);
    $password      = strip_tags($post['password']);
    $level         = strip_tags($post['level']);

    // enkripsi password
    $password = password_hash($password, PASSWORD_DEFAULT);

    // query tambah data
    $query ="INSERT INTO akun VALUES(null, '$nama', '$username', '$email', '$password', '$level')";

    mysqli_query($db, $query);

    return mysqli_affected_rows($db);
}

function delete_akun($id_akun)
{
    global $db;

    // query hapus data mahasiswa dari database
    $query = "DELETE FROM akun WHERE id_akun = $id_akun";
    mysqli_query($db, $query);

    return mysqli_affected_rows($db);
}

// fungsi ubah akun
function update_akun($post)
{
    global $db;

    $id_akun       = strip_tags($post['id_akun']);
    $nama          = strip_tags($post['nama']);
    $username      = strip_tags($post['username']);
    $email         = strip_tags($post['email']);
    $password      = strip_tags($post['password']);
    $level         = strip_tags($post['level']);

    // cek apakah password diisi
    if (!empty($password)) {
        // jika password diisi, hash password baru
        $password = password_hash($password, PASSWORD_DEFAULT);
        $query = "UPDATE akun SET nama = '$nama', username = '$username', email = '$email', password = '$password', level = '$level' WHERE id_akun = $id_akun";
    } else {
        // jika password tidak diisi, update tanpa password
        $query = "UPDATE akun SET nama = '$nama', username = '$username', email = '$email', level = '$level' WHERE id_akun = $id_akun";
    }

    mysqli_query($db, $query);

    return mysqli_affected_rows($db);
}
