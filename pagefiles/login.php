<?php
$logreturn = "";
if(isset($_SESSION['pu_login'])) { header("Location: ".insertBase()."home/"); }

if(isset($_POST['emailAdrr']) && isset($_POST['password'])) {
    $email = $_POST['emailAdrr'];
    $password = $_POST['password'];
    if(!empty($email) && !empty($password)) {
        $stmt = $conn->prepare("SELECT * FROM pu_users WHERE email = :email");
        $stmt->bindParam(":email", $email);
        $stmt->execute();
        $data = $stmt->fetch();
        $return = $stmt->rowCount();
        if($return == 1) {
            $dbpw = $data['password'];
            if(password_verify($password, $dbpw)) {
                $_SESSION['pu_login'] = $data['user_id'];
                $logreturn = '<div class="alert alert-success alert-dismissible fade show" role="alert">
                                <strong>Angemeldet!</strong> Du wurdest erfolgreich Angemeldet!
                                <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                                </button>
                            </div>';
                $llog = $data['last_login'];
                if(checkForDaily($llog) === TRUE) {
                    $userid = $data['user_id'];
                    $stmt = $conn->prepare("UPDATE pu_users SET last_login = :curtime WHERE user_id = :usid");
                    $stmt->bindParam(":curtime", $currenttime);
                    $stmt->bindParam(":usid", $userid);
                    $stmt->execute();
                    addAccountPoints(1, $userid);
                }
                header("Location: ".insertBase()."myaccount/home/");
            } else {
                $logreturn = '<div class="alert alert-danger alert-dismissible fade show" role="alert">
                                <strong>Fehler!</strong> Es ist ein Fehler aufgetreten!
                                <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                                </button>
                            </div>';  
            }
        } else {
            $logreturn = '<div class="alert alert-danger alert-dismissible fade show" role="alert">
                            <strong>Fehler!</strong> Es ist ein Fehler aufgetreten!
                            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                            </button>
                        </div>';
        }
    }
}
?>

<div class="row">
    <div class="col-12 col-md-8" style="margin-bottom: 25px;">
        <?= $logreturn ?>
        <form action="" method="post" style="max-width: 750px; margin: auto;">
            <div class="mb-3">
                <label for="emailAdrr" class="form-label">Email-Adresse</label>
                <input type="email" class="form-control" id="emailAdrr" name="emailAdrr">
            </div>
            <div class="mb-3">
                <label for="password" class="form-label">Passwort</label>
                <input type="password" class="form-control" id="password" name="password">
            </div>
            <button type="submit" class="btn btn-dark float-center">Anmelden</button>
        </form>
    </div>
    <div class="col-md-4">
        <h5>Vorteile als Registrierter Nutzer</h5>
        <ul class="list-group list-group-flush">
            <li class="list-group-item">Noch kein Konto? <a href="<?php base() ?>register/">Jetzt Registrieren!</a></li>
            <li class="list-group-item">Für tägliche Logins erhälst du Punkte!</li>
        </ul>
    </div>
</div>