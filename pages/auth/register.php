<?php
session_start();
include_once("../../config/conn.php");

if ($_SERVER["REQUEST_METHOD"] == "POST") {
  // Mendapatkan nilai dari form
  $nama = $_POST['nama'];
  $alamat = $_POST['alamat'];
  $no_ktp = $_POST['no_ktp'];
  $no_hp = $_POST['no_hp'];
  $password = $_POST['password'];

  // Generate no_rm in the format (tahun)(bulan)-(nomorurutanid)
  
  $tahun_bulan = "20" . date("ym");
  $no_rm_query = $pdo->prepare("SELECT MAX(RIGHT(no_rm, 3)) as max_id FROM pasien WHERE SUBSTRING(no_rm, 1, 4) = :tahun_bulan");
  $no_rm_query->bindParam(':tahun_bulan', $tahun_bulan, PDO::PARAM_STR);


  // Ambil ID terakhir yang ditambahkan
  $query_last_id = "SELECT MAX(id) as max_id FROM pasien";
  $result_last_id = mysqli_query($conn, $query_last_id);
  $row_last_id = mysqli_fetch_assoc($result_last_id);
  $last_inserted_id = $row_last_id['max_id'] ? $row_last_id['max_id'] : 0;

  $no_rm = $tahun_bulan . "-" . str_pad($last_inserted_id + 1, 3, '0', STR_PAD_LEFT);

  // Hash password sebelum menyimpan ke database (gunakan metode keamanan yang lebih baik di produksi)
  $hashed_password = password_hash($password, PASSWORD_DEFAULT);

  // Query untuk menambahkan data ke tabel pasien
  $query = "INSERT INTO pasien (nama, alamat, no_ktp, no_hp, no_rm, password) VALUES ('$nama', '$alamat', '$no_ktp', '$no_hp', '$no_rm', '$hashed_password')";

  // Eksekusi query
  if (mysqli_query($conn, $query)) {
    // Display alert with the generated no_rm
    echo "<script>alert('Data berhasil ditambahkan. No RM: $no_rm');</script>";
    // Redirect ke halaman lain
    header("Location: login-pasien.php");
    exit();
  } else {
    echo "Error: " . $query . "<br>" . mysqli_error($conn);
  }

  // Tutup koneksi database
  mysqli_close($conn);
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Poliklinik | Registration Page (v2)</title>

  <!-- Google Font: Source Sans Pro -->
  <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700&display=fallback">
  <!-- Font Awesome -->
  <link rel="stylesheet" href="../../plugins/fontawesome-free/css/all.min.css">
  <!-- icheck bootstrap -->
  <link rel="stylesheet" href="../../plugins/icheck-bootstrap/icheck-bootstrap.min.css">
  <!-- Theme style -->
  <link rel="stylesheet" href="../../dist/css/adminlte.min.css">
</head>
<body class="hold-transition register-page">
<div class="register-box">
  <div class="card card-outline card-primary">
    <div class="card-header text-center">
      <a href="../../index2.html" class="h1"><b>Poli</b>klinik</a>
    </div>
    <div class="card-body">
      <p class="login-box-msg">Register a new account</p>

      <form action="" method="post">
        <div class="input-group mb-3">
          <input type="text" class="form-control" placeholder="Full name" name="nama" >
          <div class="input-group-append">
            <div class="input-group-text">
              <span class="fas fa-user"></span>
            </div>
          </div>
        </div>
        <div class="input-group mb-3">
          <input type="text" class="form-control" placeholder="alamat" name="alamat" >
          <div class="input-group-append">
            <div class="input-group-text">
              <span class="fas fa-envelope"></span>
            </div>
          </div>
        </div>
        <!-- no ktp -->
        <div class="input-group mb-3">
          <input type="number" class="form-control" placeholder="No ktp" name="no_ktp" >
          <div class="input-group-append">
            <div class="input-group-text">
              <span class="fas fa-lock"></span>
            </div>
          </div>
        </div>
        <!-- no hp -->
        <div class="input-group mb-3">
          <input type="number" class="form-control" placeholder="NO HP" name="no_hp" >
          <div class="input-group-append">
            <div class="input-group-text">
              <span class="fas fa-lock"></span>
            </div>
          </div>
        </div>


        <!-- pass -->
        <div class="input-group mb-3">
          <input type="password" class="form-control" placeholder="Password" name="password" >
          <div class="input-group-append">
            <div class="input-group-text">
              <span class="fas fa-lock"></span>
            </div>
          </div>
        </div>
        
        <div class="row">
          <div class="col-8">
            <div class="icheck-primary">
              <input type="checkbox" id="agreeTerms" name="terms" value="agree">
              <label for="agreeTerms">
               I agree to the <a href="#">terms</a>
              </label>
            </div>
          </div>
          <!-- /.col -->
          <div class="col-4">
            <button type="submit" class="btn btn-primary btn-block">Register</button>
          </div>
          <!-- /.col -->
        </div>
      </form>

      <a href="login.php" class="text-center">I already have an account</a>
    </div>
    <!-- /.form-box -->
  </div><!-- /.card -->
</div>
<!-- /.register-box -->

<!-- jQuery -->
<script src="../../plugins/jquery/jquery.min.js"></script>
<!-- Bootstrap 4 -->
<script src="../../plugins/bootstrap/js/bootstrap.bundle.min.js"></script>
<!-- AdminLTE App -->
<script src="../../dist/js/adminlte.min.js"></script>

</body>
</html>
