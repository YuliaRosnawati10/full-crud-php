<?php 

$db = mysqli_connect('localhost', 'root', '', 'crud-php');
if (!$db) {
    die("Koneksi gagal: " . mysqli_connect_error());
}

// if (!$db) {
//     echo "gagal";
// }else {
//     echo "berhasil";
// }