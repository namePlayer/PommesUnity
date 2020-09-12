<?php
session_start();
require('../inc/database.inc.php');
require('../inc/noxss/HTMLPurifier.auto.php');
require('../inc/accounteng.inc.php');
require('../inc/security.inc.php');

if(isset($_SESSION['pu_login'])) {
  if(isset($_SESSION['pu_control_login'])) {

  } else {
    header("Location: index.php");
  }
} else {
  header("Location: index.php");
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <meta http-equiv="x-ua-compatible" content="ie=edge">

  <title>PommesUnity CP | Nutzerverwaltung</title>
  <link rel="stylesheet" href="plugins/fontawesome-free/css/all.min.css">
  <link rel="stylesheet" href="plugins/overlayScrollbars/css/OverlayScrollbars.min.css">
  <link rel="stylesheet" href="dist/css/adminlte.min.css">
  <link href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700" rel="stylesheet">
</head>
<body class="hold-transition sidebar-mini layout-fixed layout-navbar-fixed layout-footer-fixed">
<div class="wrapper">
  <nav class="main-header navbar navbar-expand navbar-white navbar-light">
    <ul class="navbar-nav">
      <li class="nav-item">
        <a class="nav-link" data-widget="pushmenu" href="#" role="button"><i class="fas fa-bars"></i></a>
      </li>
      <li class="nav-item d-none d-sm-inline-block">
        <a href="dashboard.php" class="nav-link">Dashboard</a>
      </li>
    </ul>
    <ul class="navbar-nav ml-auto">
      <li class="nav-item d-none d-sm-inline-block">
        <a href="../logout" class="nav-link"><i class="fas fa-sign-out-alt"></i></a>
      </li>
    </ul>
  </nav>
  <!-- /.navbar -->

  <?php require_once('assets/sidebar.php'); ?>

  <!-- Content Wrapper. Contains page content -->
  <div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <div class="content-header">
      <div class="container-fluid">
        <div class="row mb-2">
          <div class="col-sm-6">
            <h1 class="m-0 text-dark">PommesUnity - Nutzerverwaltung</h1>
          </div>
          <div class="col-sm-6">
            <ol class="breadcrumb float-sm-right">
              <li class="breadcrumb-item"><a href="dashboard.php">Dashboard</a></li>
              <li class="breadcrumb-item active">Overview</li>
            </ol>
          </div>
        </div>
      </div>
    </div>

    <section class="content">
      <div class="row">
        <div class="col-12">
        <div class="card">
            <div class="card-header">
              <h3 class="card-title">Liste aller Registrierten Nutzer</h3>
            </div>
            <!-- /.card-header -->
            <div class="card-body">
              <div class="row">
              <div class="col-sm-12 col-md-6"></div><div class="col-sm-12 col-md-6"></div></div><div class="row"><div class="col-sm-12"><table id="example2" class="table table-bordered table-hover dataTable dtr-inline" role="grid" aria-describedby="example2_info">
                <thead>
                    <tr role="row"><th class="sorting_asc" rowspan="1" colspan="1">ID</th>
                        <th class="sorting" tabindex="0" rowspan="1" colspan="1">Rezeptname</th>
                        <th class="sorting" tabindex="0" rowspan="1" colspan="1">Kurzbeschreibung</th>
                        <th class="sorting" tabindex="0" rowspan="1" colspan="1">Erstellt am</th>
                        <th class="sorting" tabindex="0" rowspan="1" colspan="1">Ersteller</th>
                        <th class="sorting" tabindex="0" rowspan="1" colspan="1">Aktionen</th>
                    </tr>
                </thead>
                <tbody>
                <?php
                    $stmt = $conn->prepare("SELECT * FROM pu_recipes");
                    $stmt->execute();
                    while($row = $stmt->fetch()) {
                        echo '<tr role="row" class="odd">
                        <td tabindex="0" class="sorting_1">'.$row['pu_recipeid'].'</td>
                        <td>'.$row['recipe_title'].'</td>
                        <td>'.convertChars($row['recipe_title']).'</td>
                        <td>'.date("d.m.Y", $row['recipe_posted']).' - '.date("H:i", $row['recipe_posted']).'</td>
                        <td>'.renderDisplaynameOther($row['recipe_author']).'</td>
                        <td><a href="managerecipe.php?id='.$row['pu_recipeid'].'">Verwalten</a></td>
                      </tr>';
                    }
                ?>
                </tbody>
              </table></div></div><div class="row"><div class="col-sm-12 col-md-5"></div></div></div>
            </div>
          </div>
        </div>
      </div>
    </section>
  </div>
  <aside class="control-sidebar control-sidebar-dark">
  </aside>
  <footer class="main-footer">
    Copyright &copy; 2020 | Made by <strong>namePlayer</strong>
  </footer>
</div>
<script src="plugins/jquery/jquery.min.js"></script>
<script src="plugins/bootstrap/js/bootstrap.bundle.min.js"></script>
<script src="plugins/overlayScrollbars/js/jquery.overlayScrollbars.min.js"></script>
<script src="dist/js/adminlte.js"></script>
<script src="dist/js/demo.js"></script>
<script src="plugins/jquery-mousewheel/jquery.mousewheel.js"></script>
<script src="plugins/raphael/raphael.min.js"></script>
<script src="plugins/jquery-mapael/jquery.mapael.min.js"></script>
<script src="plugins/jquery-mapael/maps/usa_states.min.js"></script>
<script src="plugins/chart.js/Chart.min.js"></script>
<script src="dist/js/pages/dashboard2.js"></script>
</body>
</html>
