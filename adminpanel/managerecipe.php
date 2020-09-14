<?php
session_start();
require('../inc/database.inc.php');
require('../inc/noxss/HTMLPurifier.auto.php');
require('../inc/accounteng.inc.php');
require('../inc/security.inc.php');

$return = "";

$config = HTMLPurifier_Config::createDefault();
$purifier = new HTMLPurifier($config);

if(isset($_SESSION['pu_login'])) {
  if(isset($_SESSION['pu_control_login'])) {
    if(isset($_GET['id'])) {
        $reqid = $_GET['id'];
        if(is_numeric($reqid) && $reqid > 0) {
            $stmt = $conn->prepare("SELECT * FROM pu_recipes WHERE pu_recipeid = :curid");
            $stmt->bindParam(":curid", $reqid);
            if($stmt->execute()) {
                $result = $stmt->rowCount();
                $data = $stmt->fetch();
                if($result == 0) {
                    header('Location: ' . $_SERVER['HTTP_REFERER']);
                }
            } else {
                header("Location: dashboard.php");
            }
            if(isset($_GET['act'])) {
                $action = $_GET['act'];
                if($action == "allow") {
                    $stmt = $conn->prepare("UPDATE pu_recipes SET recipe_status = 2 WHERE pu_recipeid = :ident");
                    $stmt->bindParam(":ident", $reqid);
                    if($stmt->execute()) {
                        $return = '<div class="alert alert-success" role="alert">
                                        Das Rezept wurde erfolgreich freigegeben!
                                    </div>';
                    } else {
                        $return = '<div class="alert alert-danger" role="alert">
                                        Das Rezept konnte nicht freigegeben werden!
                                    </div>';                        
                    }
                }
                if($action == "ltemp") {
                    $stmt = $conn->prepare("UPDATE pu_recipes SET recipe_status = -1 WHERE pu_recipeid = :ident");
                    $stmt->bindParam(":ident", $reqid);
                    if($stmt->execute()) {
                        $return = '<div class="alert alert-success" role="alert">
                                        Das Rezept wurde erfolgreich temporär gesperrt!
                                    </div>';
                    } else {
                        $return = '<div class="alert alert-danger" role="alert">
                                        Das Rezept konnte nicht gesperrt werden!
                                    </div>';                        
                    }
                }
                if($action == "lperm") {
                    $stmt = $conn->prepare("UPDATE pu_recipes SET recipe_status = -2 WHERE pu_recipeid = :ident");
                    $stmt->bindParam(":ident", $reqid);
                    if($stmt->execute()) {
                        $return = '<div class="alert alert-success" role="alert">
                                        Das Rezept wurde erfolgreich permanent gesperrt!
                                    </div>';
                    } else {
                        $return = '<div class="alert alert-danger" role="alert">
                                        Das Rezept konnte nicht gesperrt werden!
                                    </div>';                        
                    }
                }
                if($action == "lun") {
                    $stmt = $conn->prepare("UPDATE pu_recipes SET recipe_status = 0 WHERE pu_recipeid = :ident");
                    $stmt->bindParam(":ident", $reqid);
                    if($stmt->execute()) {
                        $return = '<div class="alert alert-success" role="alert">
                                        Das Rezept wurde erfolgreich entsperrt!
                                    </div>';
                    } else {
                        $return = '<div class="alert alert-danger" role="alert">
                                        Das Rezept konnte nicht entsperrt werden!
                                    </div>';                        
                    }
                }
                if($action == "cla") {
                  $stmt = $conn->prepare("UPDATE pu_reports SET report_status = 0 WHERE report_info = :ident AND report_type = 1");
                  $stmt->bindParam(":ident", $reqid);
                  if($stmt->execute()) {
                      $return = '<div class="alert alert-success" role="alert">
                                      Es wurden erfolgreich alle Meldungen geschlossen!
                                  </div>';
                  } else {
                      $return = '<div class="alert alert-danger" role="alert">
                                      Die Meldungen konnten nicht geschlossen werden!
                                  </div>';                        
                  }
              }
            }

            if(isset($_POST['recipeTitle']) && isset($_POST['recipeDesc']) && isset($_POST['recipetext'])) {
              $title = $_POST['recipeTitle'];
              $desc = $_POST['recipeDesc'];
              $text = $_POST['recipetext'];
              if(!empty($title) && !empty($desc) && !empty($text)) {
                $stmt = $conn->prepare("UPDATE pu_recipes SET recipe_title=:title, recipe_description=:descript, recipe_text=:descr WHERE pu_recipeid = :id");
                $stmt->bindParam(":title", $title);
                $stmt->bindParam(":descript", $desc);
                $stmt->bindParam(":descr", $text);
                $stmt->bindParam(":id", $reqid);
                if($stmt->execute()) {
                  $return = '<div class="alert alert-success" role="alert">
                  Das Rezept wurde erfolgreich geupdated!
              </div>';
                } else {
                  $return = '<div class="alert alert-danger" role="alert">
                  Das Rezept konnte nicht geupdated werden!
              </div>';
                }
              }
            }
        }
    }
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
  <script src="../js/tinymce/tinymce.min.js"></script>
  <script>
    tinymce.init({
        selector:'textarea',
        menubar: false,
        plugins: 'lists',
        toolbar: 'bold italic underline | styleselect | forecolor | alignleft aligncenter alignright alignjustify | outdent indent | numlist bullist | undo redo',
        setup: function (editor) {
            editor.on('change', function () {
                tinymce.triggerSave();
            });
        }
    });
    </script>
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
            <h1 class="m-0 text-dark">PommesUnity - Rezeptverwaltung</h1>
          </div>
          <div class="col-sm-6">
            <ol class="breadcrumb float-sm-right">
              <li class="breadcrumb-item"><a href="dashboard.php">Dashboard</a></li>
              <li class="breadcrumb-item active">Rezept</li>
            </ol>
          </div>
        </div>
      </div>
    </div>

    <section class="content">
      <div class="container-fluid">
        <?= $return ?>
        <div class="row">
          <div class="col-md-3">

            <!-- Profile Image -->
            <div class="card card-primary card-outline">
              <div class="card-body box-profile">
                <div class="text-center">
                  <img class="shadow-mg img-fluid" src="
                  <?php 
                    if(file_exists('../usercontent/recipeImg/' . convertChars($data['recipe_image']))) {
                        echo '../usercontent/recipeImg/' . convertChars($data['recipe_image']);
                    } else if($data['recipe_image'] === NULL) {
                        echo'../img/noimg.png';
                    } else {
                        echo '../img/noimg.png';
                    }
                    ?>
                  " alt="">
                </div>

                

                <h3 class="profile-username text-center"><?= convertChars($data['recipe_title']); ?></h3>

                <p class="text-muted text-center"><?= convertChars($data['recipe_description']); ?></p>

                <ul class="list-group list-group-unbordered mb-3">
                  <li class="list-group-item">
                    <b>Erstellt </b> <a class="float-right"><?php echo date("d.m.Y", $data['recipe_posted']) . ' um ' . date("G:i", $data['recipe_posted']); ?></a>
                  </li>
                  <li class="list-group-item">
                    <b>Ersteller </b> <a class="float-right"><a href="manageuser.php?id=<?php echo $data['recipe_author']; ?>"><?php echo renderDisplaynameOther($data['recipe_author']); ?></a></a>
                  </li>
                  <li class="list-group-item">
                    <b>Status </b> <a class="float-right">
                    <?php
                    if($data['recipe_status'] == -1) {
                        echo '<span class="badge bg-danger">Temp. Gesperrt</span>';
                    } else if($data['recipe_status'] == -2) {
                        echo '<span class="badge bg-danger">Perm. Gesperrt</span>';
                    } else if($data['recipe_status'] == 0) {
                        echo '<span class="badge bg-warning text-dark">Überprüfung ausstehend</span>';
                    } else 
                    if($data['recipe_status'] == 1) {
                        echo '<span class="badge bg-success">Freigeschaltet | Privat</span>';
                    } else if($data['recipe_status'] == 2) {
                        echo '<span class="badge bg-success">Freigeschaltet | Öffentlich</span>';
                    } else {
                        echo '<div class="badge bg-info" role="alert">
                        Unbekannt
                      </div>';
                    }
                ?></a>
                  </li>
                </ul>

                <?php
                    if($data['recipe_status'] == -1 || $data['recipe_status'] == -2) {
                        echo '<a class="btn btn-danger" href="managerecipe.php?id='.$reqid.'&act=lun" role="button">Rezept entsperren</a>';
                    } else if($data['recipe_status'] == 0) {
                        echo '<p><a class="btn btn-success btn-mg btn-block" href="managerecipe.php?id='.$reqid.'&act=allow" role="button">Rezept freigeben</a><a class="btn btn-danger btn-mg btn-block" href="managerecipe.php?id='.$reqid.'&act=lperm" role="button">Rezept perm. sperren</a><a class="btn btn-danger btn-mg btn-block" href="managerecipe.php?id='.$reqid.'&act=ltemp" role="button">Rezept temp. sperren</a></p>';
                    } else 
                    if($data['recipe_status'] == 1 || $data['recipe_status'] == 2) {
                        echo '<p><a class="btn btn-danger btn-mg btn-block" href="managerecipe.php?id='.$reqid.'&act=ltemp" role="button">Rezept temp. sperren</a> <a class="btn btn-danger btn-mg btn-block" href="managerecipe.php?id='.$reqid.'&act=lperm" role="button">Rezept perm. sperren</a></p>';
                    } else {
                        echo '<div class="alert alert-light" role="alert">
                        Unbekannt
                      </div>';
                    }
                ?>
              </div>
            </div>
          </div>
          <div class="col-md-9">
            <div class="card">
              <div class="card-header p-2">
                <ul class="nav nav-pills">
                  <li class="nav-item"><a class="nav-link active" href="#details" data-toggle="tab">Rezept Details</a></li>
                  <li class="nav-item"><a class="nav-link" href="#reports" data-toggle="tab">Meldungen</a></li>
                  <li class="nav-item"><a class="nav-link" href="#manage" data-toggle="tab">Verwalten</a></li>
                </ul>
              </div>
              <div class="card-body">
                <div class="tab-content">
                  <div class="active tab-pane" id="details">
                    <?php $clean_html = $purifier->purify($data['recipe_text']); echo $clean_html;?>
                  </div>
                  <div class="tab-pane" id="reports">
                    <?php
                        $stmt = $conn->prepare("SELECT * FROM pu_reports WHERE report_type = 1 AND report_info = :ident AND report_status = 1 ORDER BY report_at DESC");
                        $stmt->bindParam(":ident", $reqid);
                        if($stmt->execute()) {
                            $result = $stmt->rowCount();
                            if($result > 0) {
                                echo '<a href="managerecipe.php?id='.$reqid.'&act=cla" class="btn btn-success float-right mb-3">Alle Meldungen schließen</a>';
                                echo '<table class="table">
                                <thead>
                                  <tr>
                                    <th scope="col">#</th>
                                    <th scope="col">Nutzer</th>
                                    <th scope="col">Grund</th>
                                    <th scope="col">Meldungsdatum</th>
                                    <th scope="col">Aktionen</th>
                                  </tr>
                                </thead>
                                <tbody>';
                                while($row = $stmt->fetch()) {
                                    ?>
                                    <tr>
                                        <th scope="row"><?= $row['report_id']; ?></th>
                                        <td><?= renderDisplaynameOther($row['report_by']); ?></td>
                                        <td><?= convertChars($row['report_message']); ?></td>
                                        <td><?= date('d.m.Y', $row['report_at']) . ' um ' . date('G:i', $row['report_at'])?></td>
                                        <td><a href="#">Löschen</a></td>
                                    </tr>
                                    <?php
                                }
                                echo '</tbody></table>';
                            } else {
                                echo '<div class="alert alert-success" role="alert">Keine Meldungen vorhanden!</div>';
                            }
                        }
                    ?>
                  </div>
                  <div class="tab-pane" id="manage">
                    <form action="" method="post">
                      <div class="mb-3">
                        <label for="recipeTitle" class="form-label">Rezept Titel</label>
                        <input type="text" class="form-control" id="recipeTitle" name="recipeTitle" value="<?php echo $data['recipe_title'];?>" required>
                      </div>
                      <div class="mb-3">
                        <label for="recipeDesc" class="form-label">Rezept Beschreibung</label>
                        <input type="text" class="form-control" id="recipeDesc" name="recipeDesc" value="<?php echo $data['recipe_description'];?>" required>
                      </div>
                      <div class="mb-3">
                        <textarea name="recipetext" id="" cols="30" rows="10"><?php echo $data['recipe_text'];?>"</textarea>
                      </div>
                      <button type="submit" class="btn btn-dark">Speichern</button>
                    </form>
                  </div>
                </div>
              </div>
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
