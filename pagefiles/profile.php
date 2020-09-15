<?php
  if($url[1] === "" || !is_numeric($url[1])) {
    header("Location: ".insertBase()."home/");
  } else {
    $currentuserid = $url[1];
    $stmt = $conn->prepare("SELECT * FROM pu_users WHERE user_id = :usid");
    $stmt->bindParam(":usid", $currentuserid);
    if($stmt->execute()) {
      $result = $stmt->rowCount();
      $data = $stmt->fetch();
      if($result > 0) {
        if(isset($_POST['reportReason'])) {
          $reason = $_POST['reportReason'];
          if(!empty($reason)) {
            $stmt = $conn->prepare("INSERT INTO pu_reports (report_by, report_type, report_info, report_status, report_at, report_message) VALUES (:usid, 2, :req, 1, :curtime, :msg)");
            $stmt->bindParam(":usid", $userid);
            $stmt->bindParam(":req", $currentuserid);
            $stmt->bindParam(":curtime", $currenttime);
            $stmt->bindParam(":msg", $reason);
            if($stmt->execute()) {
              $return = '<div class="alert alert-success" role="alert">
              Vielen Dank für deine Meldung, das Team wird sich in kürze darum Kümmern!
            </div>';
            }
          }
        }
      } else {
        header("Location: home/");
      }
    } else {
      header("Location: home/");
    }
  }
?>

<?= $return; ?>

<div class="row">
  <div class="col-md-2" style="border-right: 1px solid white;">
      <img src="<?php 
        if($data['profileimg'] === NULL) {
          echo base() . 'img/nopimg.png';
        } else if(file_exists('usercontent/profileImg/' . $data['profileimg'])) {
          echo insertBase() . 'usercontent/profileImg/' . convertChars($data['profileimg']);
        } else {
          echo base() . 'img/nopimg.png';
        }
      ?>" alt="" class="sticky-top overflow-auto rounded shadow profile-img" style="margin-bottom: 15px;">
      <a href="" class="btn btn-outline-dark btn-block btn-mg mb-3">Folgen</a>
      <h5><?php echo $data['displayname']; ?></h5>
      <small>Anzeigename: <?php echo renderDisplaynameOther($currentuserid); ?></small>
      <p class="text-muted font-italic font-weight-light"><?php echo $data['biographie']; ?></p>
      <hr>
      <a href="#" data-toggle="modal" data-target="#reportUser"><i class="fas fa-exclamation-circle"></i> Konto Melden</a>
  </div>
    <div class="col-md-10">
      <?php
        $stmt = $conn->prepare("SELECT * FROM pu_recipes WHERE recipe_author = :userid AND recipe_status = 2");
        $stmt->bindParam(":userid", $currentuserid);
        if($stmt->execute()) {
          $result = $stmt->rowCount();
          if($result > 0) {
            echo '<div class="row row-cols-1 row-cols-md-3">';
            while($row = $stmt->fetch()) {
              ?>
              <div class="col">
                <div class="card h-100">
                  <img src="<?php 
                    if($row['recipe_image'] === NULL) {
                      echo base() . 'img/noimg.png';
                    } else if(file_exists('usercontent/recipeImg/' . $row['recipe_image'])) {
                      echo base() . 'usercontent/recipeImg/' . convertChars($row['recipe_image']);
                    } else {
                      echo base() . 'img/noimg.png';
                    }
                    ?>" class="card-img-top" alt="...">
                    <div class="card-body">
                      <h5 class="card-title"><?php
                      $rfp = "";
                      $rpp = "";
                      if($row['recipe_featured'] == 1) {
                        $rfp = '<i class="fas fa-crown" title="Featured" style="font-size: 13px; cursor: pointer;"></i> ';
                      }
                      if($row['recipe_pinned'] == 1) {
                        $rpp = '<i class="fas fa-map-pin" title="Pinned" style="font-size: 13px; cursor: pointer;"></i> ';
                      }
                      echo $rfp . $rpp . convertChars($row['recipe_title']);
                      ?></h5>
                      <p class="card-text"><?=convertChars($row['recipe_description']); ?></p>
                      <a class="btn btn-dark align-items-end" href="<?php base() ?>viewrecipe/<?php echo $row['pu_recipeid']; ?>" role="button">Anzeigen</a>
                  </div>
                  <div class="card-footer">
                      <small class="text-muted">Gepostet am <?php echo date("d.m.Y", $row['recipe_posted']) . ' um ' . date("G:i", $row['recipe_posted']); ?></small>
                  </div>
                </div>
              </div>
              <?php
            }
          } else {
            echo '<div class="alert alert-danger" role="alert">
            Dieser Nutzer hat keine Rezepte öffentlich geposted!
          </div>';
          }
        } 
      ?>
    <?php echo '</div>'; ?>
  </div>
</div>


<div class="modal fade" id="reportUser" tabindex="-1" aria-hidden="true">
      <div class="modal-dialog">
        <div class="modal-content">
          <div class="modal-header">
            <h5 class="modal-title" id="exampleModalLabel">Nutzer melden</h5>
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
              <span aria-hidden="true">&times;</span>
            </button>
          </div>
          <div class="modal-body">
            <form action="" method="post">
                <div class="mb-3">
                    <label for="disabledTextInput" class="form-label">Nutzer ID</label>
                    <input type="text" id="disabledTextInput" class="form-control" placeholder="<?= $data['user_id']; ?>" value="<?= $data['user_id']; ?>" required disabled>
                </div>
                <div class="mb-3">
                    <label for="reportReason" class="form-label">Grund in wenigen Worten</label>
                    <input type="text" id="reportReason" name="reportReason" class="form-control" required>
                </div>
                <button type="submit" class="btn btn-dark float-right">Jetzt melden</button>
            </form>
          </div>
          <div class="modal-footer">
            <button type="button" class="btn btn-secondary" data-dismiss="modal">Schließen</button>
          </div>
        </div>
      </div>
    </div>

<?php
  if(getTeamLevel($userid)) {
    ?>
    <div class="modal fade" id="userManager" tabindex="-1" aria-hidden="true">
      <div class="modal-dialog">
        <div class="modal-content">
          <div class="modal-header">
            <h5 class="modal-title" id="exampleModalLabel">Rezept verwalten</h5>
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
              <span aria-hidden="true">&times;</span>
            </button>
          </div>
          <div class="modal-body">
            <form action="" method="post">
                <div class="mb-3">
                    <label for="disabledTextInput" class="form-label">Nutzer ID</label>
                    <input type="text" id="disabledTextInput" class="form-control" placeholder="<?= $data['user_id']; ?>" value="<?= $data['user_id']; ?>" required disabled>
                </div>
                <div class="mb-3">
                    <label for="inputPassword5" class="form-label">Grund in wenigen Worten</label>
                    <input type="password" id="inputPassword5" class="form-control" required>
                </div>
                <button type="submit" class="btn btn-dark float-right">Jetzt melden</button>
            </form>
          </div>
          <div class="modal-footer">
            <button type="button" class="btn btn-secondary" data-dismiss="modal">Schließen</button>
          </div>
        </div>
      </div>
    </div>
    <?php
  }
?>