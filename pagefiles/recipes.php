<div class="row mb-3">
    <div class="col-6 themed-grid-col">
        <h4>Rezepte</h4>
        <small>Hier findest du alle Rezepte auf einen Blick!</small>
    </div>
    <div class="col-6 themed-grid-col">
        <?php
            if(isset($_SESSION['pu_login'])) {
                echo '<a class="btn btn-dark float-right" href="#" role="button" data-toggle="modal" data-target="#addReciepModal">Neues Rezept einreichen</a>';

                if(isset($_POST['recipeName']) && isset($_POST['recipeText']) && isset($_POST['recipeDesc'])) {
                    $usid = $_SESSION['pu_login'];
                    $title = $_POST['recipeName'];
                    $desc = $_POST['recipeDesc'];
                    $text = $_POST['recipeText'];
                    $curt = time();
                    if($_FILES['recipeImg']['size']>0) {
                        $checkimg = getimagesize($_FILES["recipeImg"]["tmp_name"]);
                        if($checkimg !== FALSE) {
                            $tmpfilen = explode('.', $_FILES['recipeImg']['name']);
                            $filename = 'recipe-' . generateRandomString(16) . '-' . time() .  '.' . end($tmpfilen);
                            if(move_uploaded_file($_FILES["recipeImg"]["tmp_name"], 'usercontent/recipeImg/' . $filename)) {
                                $stmt = $conn->prepare("INSERT INTO pu_recipes (recipe_author, recipe_title, recipe_description, recipe_text, recipe_posted, recipe_image) VALUES (:author, :title, :descript, :info, :curt, :imgloc)");
                                $stmt->bindParam(":author", $usid);
                                $stmt->bindParam(":title", $title);
                                $stmt->bindParam(":descript", $desc);
                                $stmt->bindParam(":info", $text);
                                $stmt->bindParam(":curt", $curt);
                                $stmt->bindParam(":imgloc", $filename);
                                if($stmt->execute()) {
                                    echo '<div class="alert alert-success" role="alert">
                                            Dein Rezept wurde eingereicht und wird in Kürze überprüft!
                                        </div>';
                                } else {
                                    echo '<div class="alert alert-danger" role="alert">
                                            Während des erstellen des Rezept-Beitrags ist ein Fehler aufgetreten!
                                        </div>';                                    
                                }
                            } else {
                                echo '<div class="alert alert-danger" role="alert">
                                        Während des erstellen des Rezept-Beitrags ist ein Fehler aufgetreten!
                                    </div>';                                    
                            }
                        }
                    } else {
                        $stmt = $conn->prepare("INSERT INTO pu_recipes (recipe_author, recipe_title, recipe_description, recipe_text, recipe_posted, recipe_image) VALUES (:author, :title, :descript, :info, :curt, NULL)");
                        $stmt->bindParam(":author", $usid);
                        $stmt->bindParam(":title", $title);
                        $stmt->bindParam(":descript", $desc);
                        $stmt->bindParam(":info", $text);
                        $stmt->bindParam(":curt", $curt);
                        if($stmt->execute()) {
                            echo '<div class="alert alert-success" role="alert">
                                    Dein Rezept wurde eingereicht und wird in Kürze überprüft!
                                </div>';
                        } else {
                            echo '<div class="alert alert-danger" role="alert">
                                    Während des erstellen des Rezept-Beitrags ist ein Fehler aufgetreten!
                                </div>';                                    
                        }
                    }
                }
            }
        ?>
        <!-- <form class="d-flex" method="post">
            <input class="form-control mr-2" type="search" placeholder="Rezept suchen" aria-label="Search" name="rsearch">
            <button class="btn btn-outline-success" type="submit">Suchen</button>
        </form> -->
    </div>
</div>
<?php
$stmt = $conn->prepare("SELECT * FROM pu_recipes WHERE recipe_status = 2 AND recipe_pinned = 1 ORDER BY recipe_posted DESC");
$stmt->execute();
$result = $stmt->rowCount();

if($result > 0) {
    echo '<div class="row row-cols-1 row-cols-md-3 g-4">';
    while($row = $stmt->fetch()) {
        ?>
        <div class="col">
            <div class="card h-100">
                <img src="<?php 
                    if(file_exists('usercontent/recipeImg/' . $row['recipe_image'])) {
                        echo base() . 'usercontent/recipeImg/' . convertChars($row['recipe_image']);
                    } else if($row['recipe_image'] === NULL) {
                        echo base() . 'img/noimg.png';
                    } else {
                        echo base() . 'img/noimg.png';
                    }
                ?>" class="card-img-top" alt="">
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
                    <small class="text-muted">Gepostet am <?php echo date("d.m.Y", $row['recipe_posted']) . ' um ' . date("H:i", $row['recipe_posted']); ?></small>
                </div>
            </div>
        </div>
        <?php
    }
    echo '</div>';

    $stmt = $conn->prepare("SELECT * FROM pu_recipes WHERE recipe_status = 2 AND recipe_pinned = 0 ORDER BY recipe_posted DESC");
    $stmt->execute();
    $result = $stmt->rowCount();

    if($result > 0) {
        echo '<div class="row row-cols-1 row-cols-md-3 g-4">';
        while($row = $stmt->fetch()) {
            ?>
            <div class="col">
                <div class="card h-100">
                    <img src="<?php 
                        if(file_exists('usercontent/recipeImg/' . $row['recipe_image'])) {
                            echo base() . 'usercontent/recipeImg/' . convertChars($row['recipe_image']);
                        } else if($row['recipe_image'] === NULL) {
                            echo base() . 'img/noimg.png';
                        } else {
                            echo base() . 'img/noimg.png';
                        }
                    ?>" class="card-img-top" alt="">
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
                        <small class="text-muted">Gepostet am <?php echo date("d.m.Y", $row['recipe_posted']) . ' um ' . date("H:i", $row['recipe_posted']); ?></small>
                    </div>
                </div>
            </div>
            <?php
        }
        echo '</div>';
    } else {
        echo '<div class="alert alert-danger" role="alert">
                Es wurden keine Rezepte in unserer Datenbank gefunden!
            </div>';
    }

} else {
    $stmt = $conn->prepare("SELECT * FROM pu_recipes WHERE recipe_status = 2 AND recipe_pinned = 0 ORDER BY recipe_posted DESC");
    $stmt->execute();
    $result = $stmt->rowCount();

    if($result > 0) {
        echo '<div class="row row-cols-1 row-cols-md-3 g-4">';
        while($row = $stmt->fetch()) {
            ?>
            <div class="col">
                <div class="card h-100">
                    <img src="<?php 
                        if(file_exists('usercontent/recipeImg/' . $row['recipe_image'])) {
                            echo base() . 'usercontent/recipeImg/' . convertChars($row['recipe_image']);
                        } else if($row['recipe_image'] === NULL) {
                            echo base() . 'img/noimg.png';
                        } else {
                            echo base() . 'img/noimg.png';
                        }
                    ?>" class="card-img-top" alt="">
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
                        <small class="text-muted">Gepostet am <?php echo date("d.m.Y", $row['recipe_posted']) . ' um ' . date("H:i", $row['recipe_posted']); ?></small>
                    </div>
                </div>
            </div>
            <?php
        }
        echo '</div>';
    } else {
        echo '<div class="alert alert-danger" role="alert">
                Es wurden keine Rezepte in unserer Datenbank gefunden!
            </div>';
    }
}

?>

<div class="modal fade" id="addReciepModal" tabindex="-1" aria-labelledby="addReciepModalTit" aria-hidden="true">
  <div class="modal-dialog modal-lg">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="addReciepModalTit">Neues Rezept einreichen</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        <form action="" method="post" enctype="multipart/form-data">
            <div class="form-file form-file-sm mb-3">
                <label for="recipeImg" class="form-label">Rezept Vorschaubild</label>
                <input type="file" name="recipeImg" id="recipeImg" class="btn btn-dark form-control">
            </div>
            <div class="mb-3">
                <label for="recipeName" class="form-label">Rezept Name</label>
                <input type="text" class="form-control" id="recipeName" name="recipeName" required>
            </div>
            <div class="mb-3">
                <label for="recipeDesc" class="form-label">Kurzbeschreibung</label>
                <input type="text" class="form-control" id="recipeDesc" name="recipeDesc" required>
            </div>
            <div class="mb-3">
                <label for="recipeText" class="form-label">Zutaten & Zubereitung</label>
                <textarea class="form-control" id="recipeText" name="recipeText">
                <p><strong>Zutaten: </strong></p>
                    <ul>
                    <li>Zutat 1</li>
                    <li>Zutat 2</li>
                    <li>Zutat 3</li>
                    </ul>
                    <p><strong>Zubreitung:</strong></p>
                    <p>&nbsp;</p>
                </textarea>
            </div>
            <button type="submit" class="btn btn-primary">Einreichen</button>
        </form>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary">Abbrechen</button>
      </div>
    </div>
  </div>
</div>