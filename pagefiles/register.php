<?php

if(isset($_SESSION['pu_login'])) { header("Location: ../home/"); }

$logreturn = NULL;
    if(isset($_POST['userName']) && isset($_POST['emailAdrr']) && isset($_POST['password']) && isset($_POST['passwordAgain'])) {
        $username = $_POST['userName'];
        $email = $_POST['emailAdrr'];
        $password = $_POST['password'];
        $passwordag = $_POST['passwordAgain'];
        $time = time();
        
        if(!empty($username) && !empty($email) && !empty($password) && !empty($passwordag)) {
            if($password == $passwordag) {
                $stmt = $conn->prepare("SELECT * FROM pu_users WHERE email = :email OR username = :username");
                $stmt->bindParam(":email", $email);
                $stmt->bindParam(":username", $username);
                $stmt->execute();
                $checkc = $stmt->rowCount();
                if($checkc == 0) {
                    $accountpw = password_hash($password, PASSWORD_BCRYPT);
                    $stmt = $conn->prepare("INSERT INTO pu_users (username, displayname, email, password, registered_at) VALUES (:username, :displayname, :email, :pw, :curdate)");
                    $stmt->bindParam(":username", $username);
                    $stmt->bindParam(":displayname", $username);
                    $stmt->bindParam(":email", $email);
                    $stmt->bindParam(":pw", $accountpw);
                    $stmt->bindParam(":curdate", $time);
                    if($stmt->execute()) {
                        $logreturn = '<div class="alert alert-success alert-dismissible fade show" role="alert">
                                <strong>Erfolgreich!</strong> Dein neues Konto wurde erfolgreich Registriert!
                                <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                                </button>
                            </div>';
                    } else {
                        $logreturn = '<div class="alert alert-danger alert-dismissible fade show" role="alert">
                                            <strong>Fehler!</strong> Das System konnte kein Konto registrieren!
                                            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                            <span aria-hidden="true">&times;</span>
                                            </button>
                                        </div>';
                    }
                } else {
                    $logreturn = '<div class="alert alert-danger alert-dismissible fade show" role="alert">
                                    <strong>Fehler!</strong> Das System konnte kein Konto registrieren!
                                    <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                    <span aria-hidden="true">&times;</span>
                                    </button>
                                </div>';
                }
            } else {
                $logreturn = '<div class="alert alert-danger alert-dismissible fade show" role="alert">
                                    <strong>Fehler!</strong> Die angegebenen Passwörter stimmen nicht überein!
                                    <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                    <span aria-hidden="true">&times;</span>
                                    </button>
                                </div>';                
            }
        } else {
            $logreturn = '<div class="alert alert-danger alert-dismissible fade show" role="alert">
                            <strong>Fehler!</strong> Du musst alle Felder ausfüllen!
                            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                            </button>
                        </div>';
        }
    }
?>

<div class="row">
        <div class="col-12 col-md-8" style="margin-bottom: 25px;">
            <?= $logreturn ?>
            <form action="" method="post" style="max-width: 750px; margin: auto;">
                <div class="mb-3">
                    <label for="userName" class="form-label">Nutzername</label>
                    <input type="text" class="form-control" id="userName" name="userName" aria-describedby="usernameInfo">
                    <div id="usernameInfo" class="form-text">Diesen Namen kannst du später auch ändern. Deinen Anzeigenamen kannst du später Individuell anpassen!</div>
                </div>
                <div class="mb-3">
                    <label for="emailAdrr" class="form-label">Email-Adresse</label>
                    <input type="email" class="form-control" id="emailAdrr" name="emailAdrr">
                </div>
                <div class="mb-3">
                    <label for="password" class="form-label">Passwort</label>
                    <input type="password" class="form-control" id="password" name="password">
                </div>
                <div class="mb-3">
                    <label for="password2" class="form-label">Passwort wiederholen</label>
                    <input type="password" class="form-control" id="password2" name="passwordAgain">
                </div>
                <button type="submit" class="btn btn-dark float-center">Anmelden</button>
            </form>
        </div>
        <div class="col-md-4">
            <h5>Vorteile als Registrierter Nutzer</h5>
            <ul class="list-group list-group-flush">
                <li class="list-group-item">Du kannst dir Rezepte speichern</li>
                <li class="list-group-item">Du kannst eigene Rezepte einreichen</li>
                <li class="list-group-item">Wenn sich deine Freunde registrieren, kannst du auch ihre Rezepte sehen <br> <small>Hinweis: Die Rezepte müssen auf öffentlich sein!</small></li>
                <li class="list-group-item">Sammle Punkte & erhalte tolle Cosmetics für dein Profil!</li>
            </ul>
        </div>
</div>