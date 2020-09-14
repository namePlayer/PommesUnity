<?php
$urldata = $url[1];

if(is_numeric($urldata)) {
    $stmt = $conn->prepare("SELECT * FROM pu_recipes WHERE pu_recipeid = :id");
    $stmt->bindParam(":id", $urldata);
    $stmt->execute();
    $result = $stmt->rowCount();
    $data = $stmt->fetch();
    if($result == 0) {
      header("Location: ".insertBase()."recipes/");
    } else {
      if($data['recipe_status'] == 1 || $data['recipe_status'] == 2) {

      } else {
        header("Location: ".insertBase()."recipes/");
      }
    }
} else {
    header("Location: ".insertBase()."recipes/");
}

?>

<div class="row">
    <div class="col-md-3" style="margin-bottom: 35px;">
        <a class="btn btn-dark" style="margin-bottom: 15px;" href="<?php base() ?>recipes/" role="button"><i class="fas fa-chevron-left"></i> Zur Rezeptseite</a>
        <h4 class="text-center"><u>Rezept Infos</u></h4>
        <div id="carouselExampleSlidesOnly" class="carousel slide border border-dark" data-ride="carousel" style="margin-bottom: 15px;">
            <div class="carousel-inner">
                <div class="carousel-item active">
                <img src="<?php 
                    if(file_exists('usercontent/recipeImg/' . $data['recipe_image'])) {
                        echo insertBase() . 'usercontent/recipeImg/' . convertChars($data['recipe_image']);
                    } else if($data['recipe_image'] === NULL) {
                        echo insertBase() . 'img/noimg.png';
                    } else {
                        echo insertBase() . 'img/noimg.png';
                    }
                ?>" class="d-block w-100 shadow " alt="">
                </div>
            </div>
        </div>
        <p class="text-muted">
        Name: <?php echo convertChars($data['recipe_title']); ?> <br>
        Ersteller: <a href="<?php base() ?>profile/<?= $data['recipe_author']; ?>"><?php echo renderDisplaynameOther($data['recipe_author']); ?></a> <br>
        Gepostet am: <?php echo date("d.m.Y", $data['recipe_posted']) . ' um ' . date("G:H", $data['recipe_posted']);; ?>
        </p>
        <hr>
        <a href="#" data-toggle="modal" data-target="#reportModal"><i class="fas fa-exclamation-circle"></i> Rezept Melden</a>
    </div>
    <div class="shadow col-md-9" style="padding-top: 10px; margin-bottom: 35px;">
        <h3 class="text-center"><?php echo convertChars($data['recipe_title']); ?></h3>
        <p class="text-center"><small class="text-muted"><i><?php echo convertChars($data['recipe_description']); ?></i></small></p>
        <hr>
        <?php $clean_html = $purifier->purify($data['recipe_text']); echo $clean_html;?>
    </div>
</div>

<div class="modal fade" id="reportModal" tabindex="-1" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="exampleModalLabel">Rezept melden</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        <form action="" method="post">
            <div class="mb-3">
                <label for="disabledTextInput" class="form-label">Rezept Nummer</label>
                <input type="text" id="disabledTextInput" class="form-control" placeholder="<?= $data['pu_recipeid']; ?>" value="<?= $data['pu_recipeid']; ?>" required disabled>
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
  if(getTeamLevel($userid)) {
    ?>
    <div class="modal fade" id="recipeManager" tabindex="-1" aria-hidden="true">
      <div class="modal-dialog modal-lg">
        <div class="modal-content">
          <div class="modal-header">
            <h5 class="modal-title" id="exampleModalLabel">Rezept verwalten</h5>
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
              <span aria-hidden="true">&times;</span>
            </button>
          </div>
          <div class="modal-body">
            <form action="" method="post">
            <h5>Quick-Actions:</h5>
              <p>
                <a class="btn btn-warning" href="#" role="button"><i class="fas fa-star"></i> Featured aktivieren</a>
                <a class="btn btn-info" href="#" role="button"><i class="fas fa-thumbtack"></i> Rezept anpinnen</a>
                <a class="btn btn-danger" href="#" role="button"><i class="fas fa-trash-alt"></i> Löschen</a>
                <a class="btn btn-secondary" href="#" role="button"><i class="fas fa-tachometer-alt"></i> Im Control Panel anzeigen</a>
              </p>
            </form>
            <form action="" method="post">
            <h5>Rezept sperren</h5>
              <div class="mb-3">
                <label for="disabledTextInput" class="form-label">Begründung</label>
                <input type="text" id="disabledTextInput" class="form-control" placeholder="Sperrungs-Grund" required>
              </div>
              <button type="submit" class="btn btn-dark float-right" name="lockRec">Rezept sperren</button>
            </form>
            <br>
            <br>
            <hr>
            <form action="" method="post">
            <h5>Rezept sperren & Nutzer Warnen</h5>
              <div class="mb-3">
                <label for="disabledTextInput" class="form-label">Sperrungs Begründung</label>
                <input type="text" id="disabledTextInput" class="form-control" placeholder="Sperrungs-Grund" required>
              </div>
              <div class="mb-3">
                <label for="disabledTextInput" class="form-label">Warnungs Begründung</label>
                <input type="text" id="disabledTextInput" class="form-control" placeholder="Warnungs-Grund" required>
              </div>
              <button type="submit" class="btn btn-dark float-right" name="lockRec">Rezept sperren</button>
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