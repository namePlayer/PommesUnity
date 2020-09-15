<?php
session_start();
require('../inc/database.inc.php');
require('../inc/noxss/HTMLPurifier.auto.php');
require('../inc/accounteng.inc.php');
require('../inc/security.inc.php');

if(isset($_SESSION['pu_login'])) {
    $userid = $_SESSION['pu_login'];
}

$return = "";
$currenttime = time();

$config = HTMLPurifier_Config::createDefault();
$purifier = new HTMLPurifier($config);

if(isset($_SESSION['pu_login'])) {
  if(isset($_SESSION['pu_control_login'])) {
    if(isset($_GET['id'])) {
        $reqid = $_GET['id'];
        if(is_numeric($reqid) && $reqid > 0) {
            $stmt = $conn->prepare("SELECT * FROM pu_users WHERE user_id = :curid");
            $stmt->bindParam(":curid", $reqid);
            if($stmt->execute()) {
                $result = $stmt->rowCount();
                $data = $stmt->fetch();
                if($result == 0) {
                    header('Location: ' . $_SERVER['HTTP_REFERER']);
                }
                if(isset($_POST['username']) && isset($_POST['displayname']) && isset($_POST['bio'])) {
                    $username = $_POST['username'];
                    $displayname = $_POST['displayname'];
                    $bio = $_POST['bio'];
                    $bugb = 0;
                    $verb = 0;
                    $temb = 0;
                    $parb = 0;
                    if(isset($_POST['verBadge']) && $_POST['verBadge'] == 'tru') {
                        $verb = 1;
                    }
                    if(isset($_POST['partBadge']) && $_POST['partBadge'] == 'tru') {
                        $parb = 1;
                    }
                    if(isset($_POST['bugBadge']) && $_POST['bugBadge'] == 'tru') {
                        $bugb = 1;
                    }
                    if(isset($_POST['teamBadge']) && $_POST['teamBadge'] == 'tru') {
                        $temb = 1;
                    }

                    if(!empty($username) && !empty($displayname) && !empty($bio)) {
                        $updatestmt = $conn->prepare("UPDATE pu_users SET username=:uname,displayname=:dpname,biographie=:bio,partner_badge=:parb,verified_badge=:verb,team_badge=:temb,bug_badge=:bugb  WHERE user_id = :reqid");
                        $updatestmt->bindParam(":uname", $username);
                        $updatestmt->bindParam(":dpname", $displayname);
                        $updatestmt->bindParam(":bio", $bio);
                        $updatestmt->bindParam(":reqid", $reqid);
                        $updatestmt->bindParam(":parb", $parb);
                        $updatestmt->bindParam(":verb", $verb);
                        $updatestmt->bindParam(":temb", $temb);
                        $updatestmt->bindParam(":bugb", $bugb);
                        if($updatestmt->execute()) {
                            $return = '<div class="alert alert-success" role="alert">
                            Das Konto wurde erfolgreich geupdated!
                          </div>';
                        } else {
                            $return = '<div class="alert alert-danger" role="alert">
                            Das Konto konnte nicht geupdated werden!
                          </div>';
                        }
                    }
                }

                if(isset($_POST['warnReason'])) {
                    $reason = $_POST['warnReason'];
                    $warnstmt = $conn->prepare("INSERT INTO pu_warns (userid, createdat, reason, creator) VALUES (:usid, :creation, :reason, :creator)");
                    $warnstmt->bindParam(":usid", $reqid);
                    $warnstmt->bindParam(":creation", $currenttime);
                    $warnstmt->bindParam(":reason", $reason);
                    $warnstmt->bindParam(":creator", $userid);
                    if($warnstmt->execute()) {
                        $return = '<div class="alert alert-success" role="alert">
                        Der Nutzer wurde erfolgreich verwarnt!
                      </div>'; 
                    } else {
                        $return = '<div class="alert alert-danger" role="alert">
                        Der Nutzer konnte nicht verwarnt werden! 
                      </div>';
                      var_dump($warnstmt);
                    }
                }
            } else {
                header("Location: dashboard.php");
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
            <h1 class="m-0 text-dark">PommesUnity - Nutzerverwaltung</h1>
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
                    if(file_exists('../usercontent/profileImg/' . convertChars($data['profileImg']))) {
                        echo '../usercontent/profileImg/' . convertChars($data['profileImg']);
                    } else if($data['profileImg'] === NULL) {
                        echo'../img/nopimg.png';
                    } else {
                        echo '../img/nopimg.png';
                    }
                    ?>
                  " alt="">
                </div>

                

                <h3 class="profile-username text-center"><?= renderDisplaynameOther($reqid); ?> <br> <small class="text-muted text-center" style="font-size: 14px;"><?= convertChars($data['username']); ?></small></h3>
                <p class="text-muted text-center"><?= convertChars($data['biographie']); ?></p>

                <ul class="list-group list-group-unbordered mb-3">
                  <li class="list-group-item">
                    <b>Registriert </b> <a class="float-right"><?php echo date("d.m.Y", $data['registered_at']) . ' um ' . date("G:i", $data['registered_at']); ?></a>
                  </li>
                  <li class="list-group-item">
                    <b>Punkte </b> <a class="float-right"><?php echo $data['account_points']; ?></a>
                  </li>
                  <li class="list-group-item mb-3">
                    <b>Verwarnungen </b> <a class="float-right">
                    <?php
                        $warningsstmt = $conn->prepare("SELECT * FROM pu_warns WHERE userid = :usid");
                        $warningsstmt->bindParam(":usid", $reqid);
                        $warningsstmt->execute();
                        $warningsresult = $warningsstmt->rowCount();
                        if($warningsresult == 0) {
                            echo '<span class="badge rounded-pill bg-success">Keine Verwarnungen</span>';
                        } else if($warningsresult <= 2) {
                            echo '<span class="badge rounded-pill bg-warning"><i class="fas fa-exclamation-triangle"></i> '.$result.' Warnung(en)</span>';
                        } else if($warningsresult  > 2) {
                            echo '<span class="badge rounded-pill bg-danger"><i class="fas fa-exclamation-circle"></i> '.$result.' Warnungen | <small>Account eingeschränkt</small></span>';
                        } else {
                            echo '<span class="badge rounded-pill bg-info">Unbekannte Warnungen</span>';
                        }
                    ?>
                    </a>
                  </li>
                  <button class="btn btn-danger" data-toggle="modal" data-target="#warnUserModal">Nutzer verwarnen</button>
                </ul>
                
              </div>
            </div>
          </div>
          <div class="col-md-9">
            <div class="card">
              <div class="card-header p-2">
                <ul class="nav nav-pills">
                  <li class="nav-item"><a class="nav-link active" href="#recipes" data-toggle="tab">Rezepte</a></li>
                  <li class="nav-item"><a class="nav-link" href="#details" data-toggle="tab">Warnungen</a></li>
                  <li class="nav-item"><a class="nav-link" href="#reports" data-toggle="tab">Meldungen</a></li>
                  <li class="nav-item"><a class="nav-link" href="#manage" data-toggle="tab">Verwalten</a></li>
                </ul>
              </div>
              <div class="card-body">
                <div class="tab-content">
                  <div class="active tab-pane" id="recipes">
                    <?php 
                        $stmt = $conn->prepare("SELECT * FROM pu_recipes WHERE recipe_author = :reqid");
                        $stmt->bindParam(":reqid", $reqid);
                        $stmt->execute();
                        $result = $stmt->rowCount();
                        if($result > 0) {
                            echo '<table class="table">
                            <thead>
                              <tr>
                                <th scope="col">#</th>
                                <th scope="col">Titel</th>
                                <th scope="col">Beschreibung</th>
                                <th scope="col">Geposted am</th>
                                <th scope="col">Aktionen</th>
                              </tr>
                            </thead>
                            <tbody>';
                            while($row = $stmt->fetch()) {
                                ?>
                                <tr>
                                    <th scope="row"><?= $row['pu_recipeid']; ?></th>
                                    <td><?= $row['recipe_title'] ?></td>
                                    <td><?= convertChars($row['recipe_description']); ?></td>
                                    <td><?= date('d.m.Y', $row['recipe_posted']) . ' um ' . date('G:i', $row['recipe_posted'])?></td>
                                    <td><a href="managerecipe.php?id=<?= $row['pu_recipeid'] ?>">Ansehen</a></td>
                                </tr>
                                <?php
                            }
                            echo '</tbody></table>';
                        } else {
                            echo '<div class="alert alert-success" role="alert">Keine Verwarnungen vorhanden!</div>';
                        }
                    ?>
                  </div>
                  <div class="tab-pane" id="details">
                    <?php 
                        $stmt = $conn->prepare("SELECT * FROM pu_warns WHERE userid = :reqid");
                        $stmt->bindParam(":reqid", $reqid);
                        $stmt->execute();
                        $result = $stmt->rowCount();
                        echo '<a href="managerecipe.php?id='.$reqid.'&act=cla" class="btn btn-danger float-right mb-3">Nutzer verwarnen</a>';
                        if($result > 0) {
                            echo '<table class="table">
                            <thead>
                              <tr>
                                <th scope="col">#</th>
                                <th scope="col">Grund</th>
                                <th scope="col">Warnungsdatum</th>
                                <th scope="col">Aktionen</th>
                              </tr>
                            </thead>
                            <tbody>';
                            while($row = $stmt->fetch()) {
                                ?>
                                <tr>
                                    <th scope="row"><?= $row['id']; ?></th>
                                    <td><?= convertChars($row['reason']); ?></td>
                                    <td><?= date('d.m.Y', $row['createdat']) . ' um ' . date('G:i', $row['createdat'])?></td>
                                    <td><a href="#">Löschen</a></td>
                                </tr>
                                <?php
                            }
                            echo '</tbody></table>';
                        } else {
                            echo '<div class="alert alert-success" role="alert">Keine Verwarnungen vorhanden!</div>';
                        }
                    ?>
                  </div>
                  <div class="tab-pane" id="reports">
                    <?php
                        $stmt = $conn->prepare("SELECT * FROM pu_reports WHERE report_type = 2 AND report_info = :ident AND report_status = 1 ORDER BY report_at DESC");
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
                        <label for="username" class="form-label">Nutzername</label>
                        <input type="text" class="form-control" id="username" name="username" value="<?php echo $data['username'];?>" required>
                      </div>
                      <div class="mb-3">
                        <label for="displayname" class="form-label">Anzeigename</label>
                        <input type="text" class="form-control" id="displayname" name="displayname" value="<?php echo $data['displayname'];?>" required>
                      </div>
                      <div class="mb-3">
                        <label for="bio" class="form-label">Biografie</label>
                        <input type="text" class="form-control" id="bio" name="bio" value="<?php echo $data['biographie'];?>" required>
                      </div>
                      <div class="mb-3">
                        <div class="form-check form-check-inline">
                            <input class="form-check-input" type="checkbox" id="verBadge" name="verBadge" value="tru" <?php if($data['verified_badge'] == 1) { echo 'checked'; } ?> >
                            <label class="form-check-label" for="verBadge">Verifiziert-Badge</label>
                        </div>
                        <div class="form-check form-check-inline">
                            <input class="form-check-input" type="checkbox" id="partBadge" name="partBadge" value="tru" <?php if($data['partner_badge'] == 1) { echo 'checked'; } ?>>
                            <label class="form-check-label" for="partBadge">Partner-Badge</label>
                        </div>
                        <div class="form-check form-check-inline">
                            <input class="form-check-input" type="checkbox" id="bugBadge" name="bugBadge" value="tru" <?php if($data['bug_badge'] == 1) { echo 'checked'; } ?>>
                            <label class="form-check-label" for="bugBadge">Bughunter-Badge</label>
                        </div>
                        <div class="form-check form-check-inline">
                            <input class="form-check-input" type="checkbox" id="teamBadge" name="teamBadge" value="tru" <?php if($data['team_badge'] == 1) { echo 'checked'; } ?>>
                            <label class="form-check-label" for="teamBadge">Team-Badge</label>
                        </div>
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

<div class="modal fade" id="warnUserModal" tabindex="-1" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="exampleModalLabel">Nutzer verwarnen</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        <form action="" method="post">
            <div class="mb-3">
                <label for="userWarnID" class="form-label">Nutzer ID</label>
                <input type="email" class="form-control" id="userWarnID" disabled value="<?= $reqid ?>">
            </div>
            <div class="mb-3">
                <label for="warnReason" class="form-label">Grund</label>
                <input type="text" class="form-control" id="warnReason" name="warnReason">
            </div>
            <button type="submit" class="btn btn-dark">Verwarnen</button>
        </form>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Abbrechen</button>
      </div>
    </div>
  </div>
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
