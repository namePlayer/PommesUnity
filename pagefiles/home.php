<h3 class="text-center">Pommes-Unity</h3>
<p class="text-center text-muted">Kochen ist nur klein schneiden und zusammen kippen</p>
<hr>
<?php
$stmt = $conn->prepare("SELECT * FROM pu_recipes WHERE recipe_featured = 1 AND recipe_status = 2");
$stmt->execute();
$result = $stmt->rowCount();
if($result > 0) {
    echo '<h5>Unsere Rezept-Emfehlungen</h5>';
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
    echo '</div> <br>';
}

$stmt = $conn->prepare("SELECT * FROM pu_recipes WHERE recipe_status = 2 LIMIT 3");
$stmt->execute();
$result = $stmt->rowCount();
if($result > 0) {
    echo '<h5>Die 3 aktuellsten Rezepte</h5>';
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
    echo '</div> <br>';
}

?>
