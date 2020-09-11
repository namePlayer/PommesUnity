<?php
$userid = "";
if(isset($_SESSION['pu_login'])) {
    $userid = $_SESSION['pu_login'];
    $accountstmt = $conn->prepare("SELECT * FROM pu_users WHERE `user_id` = :usid");
    $accountstmt->bindParam(":usid", $userid);
    $accountstmt->execute();
    $accountdata = $accountstmt->fetch();
    $accountresultData = $accountstmt->rowCount();
    if($accountdata == 0) {
        session_destroy();
    } else {
        function getUsername() {
            global $accountdata;
            return $accountdata['username'];
        }

        function getDisplayname() {
            global $accountdata;
            return $accountdata['displayname'];
        }
        function getPoints() {
            global $accountdata;
            return $accountdata['account_points'];
        }
        function renderDisplayname() {
            global $accountdata;
            $partnerbadge = $accountdata['partner_badge'];
            $verifiedbadge = $accountdata['verified_badge'];
            $teambadge = $accountdata['team_badge'];
            $bugbadge = $accountdata['bug_badge'];

            $pbadge = "";
            $vbadge = "";
            $tbadge = "";
            $bbadge = "";

            if($partnerbadge == 1) {
                $pbadge = ' <i class="fas fa-infinity" title="Partner"></i> ';
            }
            if($verifiedbadge == 1) {
                $vbadge = ' <i class="fas fa-check" title="Verifiziert"></i> ';
            }
            if($teambadge == 1) {
                $tbadge = ' <i class="fas fa-hammer" title="Team-Mitglied"></i> ';
            }
            if($bugbadge == 1) {
                $bbadge = ' <i class="fas fa-bug" title="Bug-Hunter"></i> ';
            }

            if($partnerbadge == 1 || $verifiedbadge == 1 || $teambadge == 1 || $bugbadge == 1) {
                $badgeOut = '<span class="badge bg-secondary">' . $accountdata['displayname'] . $pbadge . $vbadge . $tbadge . $bbadge . '</span>';
            } else {
                $badgeOut = '<span class="badge bg-light text-dark">' . $accountdata['displayname'] . '</span>';
            }

            return $badgeOut;
        }
    }
}

function renderDisplaynameOther($userid) {
    global $conn;
    $getdisplaynamestmt = $conn->prepare("SELECT * FROM pu_users WHERE `user_id` = :userid");
    $getdisplaynamestmt->bindParam(":userid", $userid);
    $getdisplaynamestmt->execute();
    $getdisplaynameres = $getdisplaynamestmt->rowCount();
    $getdisplaynamedata = $getdisplaynamestmt->fetch();
    if($getdisplaynameres > 0) {
        $partnerbadge = $getdisplaynamedata['partner_badge'];
        $verifiedbadge = $getdisplaynamedata['verified_badge'];
        $teambadge = $getdisplaynamedata['team_badge'];
        $bugbadge = $getdisplaynamedata['bug_badge'];

        $pbadge = "";
        $vbadge = "";
        $tbadge = "";
        $bbadge = "";

        if($partnerbadge == 1) {
            $pbadge = ' <i class="fas fa-infinity" title="Partner"></i> ';
        }
        if($verifiedbadge == 1) {
            $vbadge = ' <i class="fas fa-check" title="Verifiziert"></i> ';
        }
        if($teambadge == 1) {
            $tbadge = ' <i class="fas fa-hammer" title="Team-Mitglied"></i> ';
        }
        if($bugbadge == 1) {
            $bbadge = ' <i class="fas fa-bug" title="Bug-Hunter"></i> ';
        }

        if($partnerbadge == 1 || $verifiedbadge == 1 || $teambadge == 1 || $bugbadge == 1) {
            $badgeOut = '<span class="badge bg-secondary">' . $getdisplaynamedata['displayname'] . $pbadge . $vbadge . $tbadge . $bbadge . '</span>';
        } else {
            $badgeOut = '<span class="badge bg-light text-dark">' . $getdisplaynamedata['displayname'] . '</span>';
        }

        return filter_var($badgeOut);
    } else {
        return '<span class="badge bg-info">Gelöschtes Konto</span>';
    }
}

function addAccountPoints($points, $userid) {
    global $conn;

    $addpointscheckstmt = $conn->prepare("SELECT user_id, account_points FROM pu_users WHERE user_id = :usid");
    $addpointscheckstmt->bindParam(":usid", $userid);
    $addpointscheckstmt->execute();
    $addpointscheckstmtreturn = $addpointscheckstmt->rowCount();
    $addpointscheckstmtdata = $addpointscheckstmt->fetch();
    if($addpointscheckstmtreturn > 0) {
        $acpoints = $addpointscheckstmtdata['account_points'];
        $acnewpoints = $acpoints + $points;
        
        $addpointsaddstmt = $conn->prepare("UPDATE pu_users SET account_points = :acpoints WHERE user_id = :usid");
        $addpointsaddstmt->bindParam(":acpoints", $acnewpoints);
        $addpointsaddstmt->bindParam(":usid", $userid);
        if($addpointsaddstmt->execute()) {
            return true;
        } else {
            return false;
        }
    } else {
        error_log("Couldn't fetch Account Points. Error while adding Points to useraccount " . $userid);
        return false;
    }
}

function getTeamLevel($userid) {
    global $conn;
    if(isset($_SESSION['pu_login'])) {
        $getuserteamlevelstmt = $conn->prepare("SELECT team_level FROM pu_users WHERE user_id = :userid");
        $getuserteamlevelstmt->bindParam(":userid", $userid);
        $getuserteamlevelstmt->execute();
        $getuserteamlevelresult = $getuserteamlevelstmt->rowCount();
        $getuserteamleveldata = $getuserteamlevelstmt->fetch();
        if($getuserteamlevelresult > 0) {
            if($getuserteamleveldata['team_level'] > 0) {
                return $getuserteamleveldata['team_level'];
            } else {
                return false;
            }
        } else {
            return false;
        }
    }
}