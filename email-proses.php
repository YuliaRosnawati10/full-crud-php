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
        $mail->Username = 'alamat_email_anda@gmail.com'; 
        $mail->Password = 'password_aplikasi_anda'; // Gunakan App Password jika pakai Gmail
        $mail->SMTPSecure = PHPMailer::ENCRYPTION_SMTPS; 
        $mail->Port = 465;

        // Penerima
        $mail->setFrom('alamat_email_anda@gmail.com', 'Admin Aplikasi'); 
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