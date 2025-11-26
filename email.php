<?php
session_start();

// Import PHPMailer classes into the global namespace
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\Exception;

// Load Composer's autoloader (Pastikan path ini benar)
require 'vendor/autoload.php';

// Membatasi halaman sebelum login
if (!isset($_SESSION['login'])) {
    echo "<script>
            alert('login dulu');
            document.location.href = 'login.php';
          </script>";
    exit;
}

// Ganti Judul
$title = 'Kirim Email';
include 'layout/header.php'; // Asumsikan header.php ada


// --- Bagian Logika Pengiriman Email ---
// Tombol submit di form HTML menggunakan name="tambah". Kita ganti logika PHP untuk menangkapnya.
if (isset($_POST['tambah'])) {
    $mail = new PHPMailer(true); // Buat instance PHPMailer

    // Ambil data dari form (Sesuaikan dengan nama input di HTML)
    $penerima = $_POST['email_penerima'];
    $subjek = $_POST['subject'];
    $pesan = $_POST['pesan'];

    try {
        // Konfigurasi Server (SMTP) - HARAP GANTI DENGAN KREDENSIAL ANDA
        $mail->isSMTP();
        $mail->Host = 'smtp.gmail.com'; 
        $mail->SMTPAuth = true; 
        $mail->Username = 'noviasindi533@gmail.com'; 
        $mail->Password = 'aednnseneetdhmsk'; // tanpa spasi agar aman
        $mail->SMTPSecure = PHPMailer::ENCRYPTION_SMTPS; 
        $mail->Port = 465;

        // Penerima
        $mail->setFrom('noviasindi533@gmail.com', 'Admin Aplikasi');
        $mail->addAddress($penerima);


        // Konten
        $mail->isHTML(true); 
        $mail->Subject = $subjek;
        $mail->Body = nl2br($pesan); // Gunakan nl2br untuk format baris baru dari textarea
        $mail->AltBody = strip_tags($pesan);

        // Kirim Email
        $mail->send();
        
        echo "
        <script>
            alert('Email Berhasil Dikirim ke $penerima');
            document.location.href = 'email.php'; // Ganti ke halaman yang benar
        </script>
        ";

    } catch (Exception $e) {
        echo "
        <script>
            alert('Email Gagal Dikirim. Mailer Error: {$mail->ErrorInfo}');
            document.location.href = 'email.php'; // Ganti ke halaman yang benar
        </script>
        ";
    }
}
?>

<div class="content-wrapper">
    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1 class="m-0">Kirim Email PHPMailer</h1>
                </div>
            </div>
        </div>
    </div>
    <section class="content">
        <div class="container-fluid">
            <form action="" method="post">
                
                <div class="mb-3">
                    <label for="email_penerima" class="form-label">Email Penerima</label>
                    <input type="text" class="form-control" id="email_penerima" name="email_penerima" placeholder="Email Penerima..." required>
                </div>

                <div class="mb-3">
                    <label for="subject" class="form-label">Subject</label>
                    <input type="text" class="form-control" id="subject" name="subject" placeholder="Subject..." required>
                </div>
                
                <div class="mb-3">
                    <label for="pesan" class="form-label">Pesan</label>
                    <textarea class="form-control" name="pesan" id="pesan" cols="30" rows="10" placeholder="Tulis pesan email di sini..." required></textarea>
                </div>

                <button type="submit" name="tambah" class="btn btn-primary" style="float: right;">
                    Kirim</button>
            </form>
        </div>
    </section>
    </div>

<?php include 'layout/footer.php'; ?>