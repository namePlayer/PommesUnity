<?php
if(!isset($_SESSION['pu_login'])) { header("Location: ".insertBase()."login/"); }
?>

<div class="row">
    <div class="col-md-3" style="margin-bottom: 25px;">
        <div class="list-group">
            <a href="<?php base() ?>myaccount/home/" class="list-group-item list-group-item-action active" aria-current="true">
                Mein Konto
            </a>
            <a href="<?php base() ?>myaccount/mydata/" class="list-group-item list-group-item-action"><i class="fas fa-info-circle"></i> Account Daten ändern</a>
            <a href="<?php base() ?>myaccount/profilepage/" class="list-group-item list-group-item-action"><i class="fas fa-eye"></i> Profil Seite anpassen</a>
            <a href="<?php base() ?>myaccount/password/" class="list-group-item list-group-item-action"><i class="fas fa-user-lock"></i> Passwort ändern</a>
            <a href="<?php base() ?>myaccount/privacy/" class="list-group-item list-group-item-action"><i class="fas fa-shield-alt"></i> Datenschutz Optionen</a>
            <a href="<?php base() ?>logout/" class="list-group-item list-group-item-action"><i class="fas fa-power-off"></i> Abmelden</a>
        </div>
    </div>
    <?php
    if($url[1] == "") {
        echo '<pre>';
        print_r(header("Location: ".insertBase()."myaccount/home/"));
        echo '</pre>';
    } else if($url[1] == "home") {?>
    <div class="col-md-9">
        <div class="row mb-3" style="margin-bottom: 25px;">
            <div class="row row-cols-1 row-cols-md-3">
                <div class="col">
                    <div class="card h-100">
                        <div class="card-body">
                            <h5 class="card-title text-center">Konto Punkte</h5>
                            <p class="card-text text-center"><?php echo getPoints(); ?></p>
                        </div>
                    </div>
                </div>
                <div class="col">
                    <div class="card h-100">
                        <div class="card-body">
                            <h5 class="card-title text-center">Konto Anzeigename</h5>
                            <p class="card-text text-center"><?php echo renderDisplayname(); ?></p>
                        </div>
                    </div>
                </div>
                <div class="col">
                    <div class="card h-100">
                        <div class="card-body">
                            <h5 class="card-title text-center">Konto Status</h5>
                            <p class="card-text text-center"><?php
                                $stmt = $conn->prepare("SELECT * FROM pu_warns WHERE userid = :usid");
                                $stmt->bindParam(":usid", $userid);
                                $stmt->execute();
                                $result = $stmt->rowCount();
                                if($result == 0) {
                                    echo '<span class="badge rounded-pill bg-success">Keine Verwarnungen</span>';
                                } else if($result <= 2) {
                                    echo '<span class="badge rounded-pill bg-warning"><i class="fas fa-exclamation-triangle"></i> '.$result.' Warnung(en)</span>';
                                } else if($result  > 2) {
                                    echo '<span class="badge rounded-pill bg-danger"><i class="fas fa-exclamation-circle"></i> '.$result.' Warnungen | <small>Account eingeschränkt</small></span>';
                                } else {
                                    echo '<span class="badge rounded-pill bg-info">Unbekannte Warnungen</span>';
                                }
                            ?>
                            </p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-sm-6" style="margin-bottom: 25px;">
                <h4>Deine Rezepte</h4>
                <table class="table">
                    <thead>
                        <tr>
                        <th scope="col">Name</th>
                        <th scope="col">Status</th>
                        <th scope="col">Aktionen</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                            $stmt = $conn->prepare("SELECT * FROM pu_recipes WHERE recipe_author = :usid ORDER BY recipe_posted DESC");
                            $stmt->bindParam(":usid", $userid);
                            $stmt->execute();
                            $result = $stmt->rowCount();
                            if($result > 0) {
                                while($row = $stmt->fetch()) {
                                    $recstatus = "";
                                    if($row['recipe_status'] == -2) {
                                        $recstatus = '<span class="badge rounded-pill bg-danger">Permanent Gesperrt</span>';
                                    } else if($row['recipe_status'] == -1) {
                                        $recstatus = '<span class="badge rounded-pill bg-danger">Temporär Gesperrt</span>';
                                    } else if($row['recipe_status'] == 0) {
                                        $recstatus = '<span class="badge rounded-pill bg-warning text-dark">Überprüfung</span>';
                                    } else if($row['recipe_status'] == 1) {
                                        $recstatus = '<span class="badge rounded-pill bg-secondary">Unsichtbar</span>';
                                    } else if($row['recipe_status'] == 2) {
                                        $recstatus = '<span class="badge rounded-pill bg-success">Öffentlich</span>';
                                    } else {
                                        $recstatus = '<span class="badge rounded-pill bg-info">Unbekannt</span>';
                                    }
                                    echo 
                                    '<tr>
                                    <td>'.$row['recipe_title'].'</td>
                                    <td class="text-warning">'.$recstatus.'</td>
                                    <td><a href="'.insertBase().'viewrecipe/'.$row['pu_recipeid'].'">Anzeigen</a></td>
                                    </tr>';
                                }
                            }
                        ?>
                    </tbody>
                </table>
            </div>
            <div class="col-sm-6" style="margin-bottom: 25px;">
                <h4>Letzte Follower</h4>
                <table class="table">
                    <thead>
                        <tr>
                        <th scope="col">Nutzername</th>
                        <th scope="col">Follower</th>
                        <th scope="col">Folgt seit</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                        <td><span class="badge bg-secondary">Pommes Män <i class="fas fa-infinity" title="Partner"></i></span></td>
                        <td>0</td>
                        <td>01.11.1980</td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
        <?php
    } else if($url[1] == "mydata") {
    ?>
        <div class="col-md-9">
            
        </div>
    <?php
    } else if($url[1] == "profilepage") {
    ?>
        <div class="col-md-9">
            
        </div>
    <?php
    } else if($url[1] == "password") {
    ?>
        <div class="col-md-9">
            
        </div>
    <?php
    } else if($url[1] == "privacy") {
    ?>
        <div class="col-md-9">
        <h4>Datenschutz Einstellungen</h4>
            <form action="" method="post">
                <fieldset>
                    <div class="row mb-3">
                    <legend class="col-form-label col-sm-3 pt-0">Wer kann mein Profil sehen?</legend>
                    <div class="col-sm-9">
                        <div class="form-check">
                        <input class="form-check-input" type="radio" name="whoSee" id="gridRadios1" value="seeProf1" checked>
                        <label class="form-check-label" for="gridRadios1">
                           Jeder
                        </label>
                        </div>
                        <div class="form-check">
                        <input class="form-check-input" type="radio" name="whoSee" id="gridRadios2" value="seeProf1">
                        <label class="form-check-label" for="gridRadios2">
                            Nur Follower
                        </label>
                        </div>
                        <div class="form-check">
                        <input class="form-check-input" type="radio" name="whoSee" id="gridRadios3" value="seeProf1">
                        <label class="form-check-label" for="gridRadios3">
                            Keiner
                        </label>
                        </div>
                    </div>
                    </div>
                </fieldset>
                <fieldset>
                    <div class="row mb-3">
                        <legend class="col-form-label col-sm-3 pt-0">Wer kann sehen, wem ich Folge?</legend>
                        <div class="col-sm-9">
                            <div class="form-check">
                                <input class="form-check-input" type="radio" name="whoFollows" id="whoFollows1" value="seeFol1" checked>
                                <label class="form-check-label" for="whoFollows">
                                    Jeder
                                </label>
                            </div>
                            <div class="form-check">
                                <input class="form-check-input" type="radio" name="whoFollows" id="whoFollows2" value="seeFol0">
                                <label class="form-check-label" for="whoFollows">
                                    Keiner
                                </label>
                            </div>
                        </div>
                    </div>
                </fieldset>
                <div class="row mb-3">
                    <div class="col-form-label col-sm-3 pt-0">Ist folgen aktiviert?</div>
                    <div class="col-sm-9">
                    <div class="form-check">
                        <input class="form-check-input" type="checkbox" id="gridCheck1">
                        <label class="form-check-label" for="gridCheck1">
                        Folgen aktivieren
                        </label>
                    </div>
                    </div>
                </div>    
            </form>
        </div>
    <?php
    } else {
        echo 'error';
    }
    ?>
</div>