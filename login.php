<?php 
session_start();

include 'config/app.php';

// check apakah tombol login ditekan
if (isset($_POST['login'])) {
    $username = mysqli_real_escape_string($db, $_POST['username']);
    $password = mysqli_real_escape_string($db, $_POST['password']);

    // secret key YANG BENER
    $secret_key = "6LdqVXcrAAAAAMQrxZpEvEq53O0fbwRnE3H1no1C";

    // CEK DULU apakah user klik reCAPTCHA atau ngga
    if (empty($_POST['g-recaptcha-response'])) {
        $error_recaptcha = true;
    } else {
        $verifikasi = file_get_contents('https://www.google.com/recaptcha/api/siteverify?secret=' . $secret_key . '&response=' . $_POST['g-recaptcha-response']);
         
        $response = json_decode($verifikasi);

        // Kalo reCAPTCHA VALID, baru cek login
        if ($response->success) {
            // check username
            $result = mysqli_query($db, "SELECT * FROM akun WHERE username = '$username'");

            // jika ada usernya
            if (mysqli_num_rows($result) == 1) {
                // check passwordnya
                $hasil = mysqli_fetch_assoc($result);

                if (password_verify($password, $hasil['password']) || $password == $hasil['password']) {
                    // set session
                    $_SESSION['login']       = true;
                    $_SESSION['id_akun']     = $hasil['id_akun'];
                    $_SESSION['nama']        = $hasil['nama'];
                    $_SESSION['username']    = $hasil['username'];
                    $_SESSION['email']       = $hasil['email'];
                    $_SESSION['level']       = $hasil['level'];

                    // redirect berdasarkan level
                    if ($hasil['level'] == 1 || $hasil['level'] == 2) {
                        header("Location: index.php");
                        exit;
                    } elseif ($hasil['level'] == 3) {
                        header("Location: mahasiswa.php");
                        exit;
                    }
                } else {
                    $error = true;
                }
            } else {
                $error = true;
            }
        } else {
            // Kalo reCAPTCHA ga valid
            $error_recaptcha = true;
        }
    }
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>AdminLTE 3 | Log in</title>

  <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700&display=fallback">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/admin-lte/3.2.0/css/adminlte.min.css">
  
  <style>
    .login-page {
      background-color: #e4e6eb;
    }
    
    .login-box {
      width: 360px;
      margin: 7% auto;
    }
    
    .login-logo a {
      color: #495057;
      font-weight: 300;
      font-size: 2.1rem;
    }
    
    .login-logo b {
      font-weight: 400;
    }
    
    .login-logo img {
      width: 50px;
      height: 50px;
      margin-right: 5px;
      vertical-align: middle;
    }
    
    .card {
      box-shadow: 0 0 1px rgba(0,0,0,.125), 0 1px 3px rgba(0,0,0,.2);
      margin-bottom: 1rem;
      border-radius: 0.25rem;
    }
    
    .login-card-body {
      padding: 20px;
    }
    
    .login-box-msg {
      margin: 0;
      padding: 0 0 20px;
      text-align: center;
      color: #6c757d;
    }
    
    .input-group {
      position: relative;
      margin-bottom: 15px;
    }
    
    .input-group .form-control {
      padding-right: 35px;
      height: 34px;
      border: 1px solid #ced4da;
      background-color: #f8f9fa;
      font-size: 14px;
    }
    
    .input-group .form-control:focus {
      background-color: #fff;
      border-color: #80bdff;
    }
    
    .input-group-icon {
      position: absolute;
      right: 10px;
      top: 50%;
      transform: translateY(-50%);
      color: #6c757d;
      pointer-events: none;
      z-index: 10;
    }
    
    .btn-primary {
      background-color: #007bff;
      border-color: #007bff;
      padding: 5px 20px;
      font-size: 14px;
      font-weight: 400;
      height: auto;
      line-height: 1.5;
      width: auto;
      display: inline-block;
    }
    
    .btn-primary:hover {
      background-color: #0069d9;
      border-color: #0062cc;
    }
    
    .footer-text {
      text-align: center;
      margin-top: 15px;
      color: #6c757d;
      font-size: 0.875rem;
    }
    
    .footer-text a {
      color: #007bff;
      text-decoration: none;
    }
    
    .footer-text a:hover {
      text-decoration: underline;
    }
  </style>
</head>
<body class="hold-transition login-page">
<div class="login-box">
  <div class="login-logo">
    <img src="assets/img/bootstrap-logo.svg" alt="" width="72" height="57">
    <a href="#"><b>Admin</b>LTE</a>
  </div>
  
  <div class="card">
    <div class="card-body login-card-body">
      <p class="login-box-msg">Masukkan username dan password</p>

      <?php if (isset($error)) : ?>
        <div class="alert alert-danger alert-dismissible">
          <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
          <strong>Error!</strong> Username/Password SALAH
        </div>
      <?php endif; ?>

      <?php if (isset($error_recaptcha)) : ?>
        <div class="alert alert-danger text-center">
          <b>Recaptcha Tidak Valid</b>
        </div>
      <?php endif; ?>

      <form action="" method="post">
        <div class="input-group">
          <input type="text" name="username" class="form-control" placeholder="Username..." required>
          <span class="input-group-icon">
            <i class="fas fa-user"></i>
          </span>
        </div>
        
        <div class="input-group">
          <input type="password" name="password" class="form-control" placeholder="Password..." required>
          <span class="input-group-icon">
            <i class="fas fa-lock"></i>
          </span>
        </div>

        <div class="mb-3">
          <!-- SITE KEY YANG BENER -->
          <div class="g-recaptcha" data-sitekey="6LdqVXcrAAAAAN_mwQ2uzdFDqMR86HNoBG1MDnwO"></div>
        </div>

        <div class="row">
          <div class="col-8">
          </div>
       
          <div class="col-4">
            <button type="submit" name="login" class="btn btn-primary btn-block">Masuk</button>
          </div>
        </div>
      </form>

      <hr>
      <p class="footer-text">
        Developer © <a href="#">Muba Teknologi</a> 2022
      </p>
    </div>
  </div>
</div>

<script src="assets-template/plugins/jquery/jquery.min.js"></script>
<script src="assets-template/plugins/bootstrap/js/bootstrap.bundle.min.js"></script>
<script src="assets-template/dist/js/adminlte.min.js"></script>
<script src="https://www.google.com/recaptcha/api.js"></script>

</body>
</html>