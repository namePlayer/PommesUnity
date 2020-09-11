<?php
session_start();
require('../inc/database.inc.php');
require('../inc/noxss/HTMLPurifier.auto.php');

$msg = "";

if(isset($_SESSION['pu_login'])) {
  if(!isset($_SESSION['pu_control_login'])) {
    if(isset($_POST['email']) && isset($_POST['password'])) {
      $mail = $_POST['email'];
      $password = $_POST['password'];

      $stmt = $conn->prepare("SELECT * FROM pu_users WHERE email = :mail");
      $stmt->bindParam(":mail", $mail);
      $stmt->execute();
      $result = $stmt->rowCount();
      $data = $stmt->fetch();
      if($result == 1) {
        $dbpw = $data['password'];
        if(password_verify($password, $dbpw)) {
          $_SESSION['pu_control_login'] = $data['user_id'];
          header("Location: dashboard.php");
        } else {
          $msg = '<div class="alert alert-danger" role="alert">
                    Anmeldung fehlgeschlagen!
                  </div>';  
        }
      } else {
        $msg = '<div class="alert alert-danger" role="alert">
                  Anmeldung fehlgeschlagen!
                </div>';  
      }
    }
  } else {
    header("Location: dashboard.php");
  }
} else {
  header("Location: ../");
}
?>

<!DOCTYPE html>
<html>
<head>
  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <title>PommesUnity CP | Anmelden</title>
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <link rel="stylesheet" href="plugins/fontawesome-free/css/all.min.css">
  <link rel="stylesheet" href="plugins/icheck-bootstrap/icheck-bootstrap.min.css">
  <link rel="stylesheet" href="dist/css/adminlte.min.css">
  <link href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700" rel="stylesheet">
</head>
<body class="hold-transition login-page">
<div class="login-box">
  <div class="login-logo">
    <a href="../"><b>Pommes</b>Unity</a>
  </div>
  <div class="card">
    <div class="card-body login-card-body">
      <p class="login-box-msg">PommesUnity Control<b>Panel</b> Anmeldung</p>

      <?php echo $msg; ?>
      <form action="" method="post">
        <div class="input-group mb-3">
          <input type="email" class="form-control" placeholder="Email" name="email">
          <div class="input-group-append">
            <div class="input-group-text">
              <span class="fas fa-envelope"></span>
            </div>
          </div>
        </div>
        <div class="input-group mb-3">
          <input type="password" class="form-control" placeholder="Password" name="password">
          <div class="input-group-append">
            <div class="input-group-text">
              <span class="fas fa-lock"></span>
            </div>
          </div>
        </div>
        <div class="row">
          <div class="col-4">
            <button type="submit" class="btn btn-dark btn-block float-right">Anmelden</button>
          </div>
          <!-- /.col -->
        </div>
      </form>
    </div>
  </div>
</div>

<!-- jQuery -->
<script src="plugins/jquery/jquery.min.js"></script>
<script src="plugins/bootstrap/js/bootstrap.bundle.min.js"></script>
<script src="dist/js/adminlte.min.js"></script>

</body>
</html>
